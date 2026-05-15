<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CropController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Auto-update crops that reached their harvest date
        Crop::where('farm_id', $user->farm_id)
            ->where('status', 'growing')
            ->where('expected_harvest_date', '<=', now())
            ->update(['status' => 'harvested']);

        // Buyers cannot access crop management
        if ($user->role === 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        if ($user->role === 'farmer') {
            // Farmers see only their own crops
            $crops = Crop::where('farm_id', $user->farm_id)
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            // Admin sees all farm crops (but not farmer-owned ones)
            $crops = Crop::where('farm_id', $user->farm_id)
                ->whereNull('user_id')
                ->latest()
                ->paginate(10);
        }

        return view('crops.index', compact('crops'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role === 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        return view('crops.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }

        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'variety'                => 'nullable|string|max:255',
            'planting_date'          => 'required|date',
            'expected_harvest_date'  => 'required|date|after:planting_date',
            'status'                 => 'required|in:growing,harvested,failed',
            'price'                  => 'nullable|numeric|min:0',
            'yield_kg'               => 'nullable|numeric|min:0',
        ]);

        $validated['farm_id'] = $user->farm_id;

        // Farmers own their own crops; admin crops have no user_id
        if ($user->role === 'farmer') {
            $validated['user_id'] = $user->id;
        } else {
            $validated['user_id'] = null;
        }

        Crop::create($validated);

        return redirect()->route('dashboard')->with('success', 'Crop added successfully.');
    }

    public function edit(Crop $crop)
    {
        $user = Auth::user();
        if ($user->role === 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        // Farmer can only edit their own crops
        if ($user->role === 'farmer' && $crop->user_id !== $user->id) {
            abort(403);
        }
        return view('crops.edit', compact('crop'));
    }

    public function update(Request $request, Crop $crop)
    {
        $user = Auth::user();
        if ($user->role === 'buyer') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        if ($user->role === 'farmer' && $crop->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'variety'                => 'nullable|string|max:255',
            'planting_date'          => 'required|date',
            'expected_harvest_date'  => 'required|date|after:planting_date',
            'status'                 => 'required|in:growing,harvested,failed',
            'price'                  => 'nullable|numeric|min:0',
            'yield_kg'               => 'nullable|numeric|min:0',
        ]);

        $crop->update($validated);
        return redirect()->route('dashboard')->with('success', 'Crop updated.');
    }

    public function destroy(Crop $crop)
    {
        $user = Auth::user();
        if ($user->role === 'farmer' && $crop->user_id !== $user->id) {
            abort(403);
        }
        $crop->delete();
        return redirect()->route('dashboard')->with('success', 'Crop deleted.');
    }
}
