<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CropListing;
use App\Models\Crop;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = CropListing::with(['crop', 'farmer'])->where('is_active', true);

        // Search by crop name
        if ($request->has('search')) {
            $query->whereHas('crop', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by farmer
        if ($request->has('farmer_id')) {
            $query->where('farmer_id', $request->farmer_id);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price_per_unit', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price_per_unit', '<=', $request->max_price);
        }

        $listings = $query->latest()->paginate(12);
        
        // For farmers, get their harvested crops that can be listed
        $myCrops = collect();
        if (Auth::user()->isFarmer()) {
            $myCrops = Crop::where('user_id', Auth::id())
                ->where('status', 'harvested')
                ->get();
        }

        return view('marketplace.index', compact('listings', 'myCrops'));
    }

    public function storeListing(Request $request)
    {
        $request->validate([
            'crop_id' => 'required|exists:crops,id',
            'quantity_available' => 'required|numeric|min:0.01',
            'price_per_unit' => 'required|numeric|min:0.01',
        ]);

        $crop = Crop::findOrFail($request->crop_id);
        
        // Ensure farmer owns the crop
        if ($crop->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        // Ensure they aren't listing more than they have harvested (yield_kg)
        if ($request->quantity_available > $crop->yield_kg) {
            return back()->with('error', 'You only have ' . $crop->yield_kg . 'kg available in your harvest yield. Please update your crop yield first.');
        }

        CropListing::create([
            'crop_id' => $request->crop_id,
            'farmer_id' => Auth::id(),
            'quantity_available' => $request->quantity_available,
            'price_per_unit' => $request->price_per_unit,
            'is_active' => true,
        ]);

        return back()->with('success', 'Crop listed successfully in the marketplace.');
    }

    public function updateListing(Request $request, CropListing $listing)
    {
        // Ensure farmer owns the listing
        if ($listing->farmer_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $request->validate([
            'quantity_available' => 'numeric|min:0',
            'price_per_unit' => 'numeric|min:0.01',
            'is_active' => 'boolean',
        ]);

        $listing->update($request->only(['quantity_available', 'price_per_unit', 'is_active']));

        return back()->with('success', 'Listing updated successfully.');
    }
}
