<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('hospital.profile');
    }

    public function update(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = auth()->user();

        if (!$user || $user->role !== 'hospital') {
            abort(403);
        }

        $user->update($request->only(['location', 'latitude', 'longitude']));

        return redirect()->route('dashboard')->with('success', 'Hospital profile updated successfully.');
    }
}
