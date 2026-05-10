<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        return redirect()->route('profile.edit');
    }

    public function team()
    {
        // Only admin can access team settings
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $teamMembers = User::where('farm_id', Auth::user()->farm_id)->get();
        return view('settings.team', compact('teamMembers'));
    }

    public function farm()
    {
        $farm = Auth::user()->farm;
        return view('settings.farm', compact('farm'));
    }

    public function notifications()
    {
        return view('settings.notifications');
    }

    public function security()
    {
        return view('settings.security');
    }

    public function updateFarm(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        Auth::user()->farm->update($validated);

        return back()->with('success', 'Farm details updated successfully.');
    }

    public function updateNotifications(Request $request)
    {
        // For now just simulate success as we don't have a notifications_settings table yet
        // In a real app, you'd save these to a JSON column or a separate table
        return back()->with('success', 'Notification preferences saved.');
    }

    public function updateSecurity(Request $request)
    {
        // Handle security updates (e.g., sessions)
        return back()->with('success', 'Security settings updated.');
    }
}
