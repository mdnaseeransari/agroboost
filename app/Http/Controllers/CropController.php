<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CropController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'farmer') {
            return redirect()->route('dashboard')->with('error', 'Access denied to Crops section.');
        }
        $crops = Crop::where('farm_id', Auth::user()->farm_id)->latest()->paginate(10);
        return view('crops.index', compact('crops'));
    }

    public function create()
    {
        return view('crops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'required|date',
            'expected_harvest_date' => 'required|date|after:planting_date',
            'status' => 'required|in:growing,harvested,failed',
        ]);

        $validated['farm_id'] = Auth::user()->farm_id;
        Crop::create($validated);

        return redirect()->route('crops.index')->with('success', 'Crop created successfully.');
    }

    public function edit(Crop $crop)
    {
        $this->authorize('update', $crop);
        return view('crops.edit', compact('crop'));
    }

    public function update(Request $request, Crop $crop)
    {
        $this->authorize('update', $crop);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'variety' => 'nullable|string|max:255',
            'planting_date' => 'required|date',
            'expected_harvest_date' => 'required|date|after:planting_date',
            'status' => 'required|in:growing,harvested,failed',
        ]);
        $crop->update($validated);
        return redirect()->route('crops.index')->with('success', 'Crop updated.');
    }

    public function destroy(Crop $crop)
    {
        $this->authorize('delete', $crop);
        $crop->delete();
        return redirect()->route('crops.index')->with('success', 'Crop deleted.');
    }
}
