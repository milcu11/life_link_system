<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function index()
    {
        $requests = BloodRequest::where('hospital_id', Auth::id())
            ->with('matchings')
            ->latest()
            ->paginate(10);
        
        return view('hospital.requests.index', compact('requests'));
    }

    public function create()
    {
        return view('hospital.requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|integer|min:1',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'patient_name' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'needed_by' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['hospital_id'] = Auth::id();
        $validated['status'] = 'pending';

        $bloodRequest = BloodRequest::create($validated);

        return redirect()->route('hospital.requests.show', $bloodRequest)
            ->with('success', 'Blood request created successfully!');
    }

    public function show(BloodRequest $request)
    {
        if ($request->hospital_id !== Auth::id()) {
            abort(403);
        }

        $request->load('matchings.donor.user');
        
        return view('hospital.requests.show', compact('request'));
    }

    public function update(Request $request, BloodRequest $bloodRequest)
    {
        if ($bloodRequest->hospital_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,matched,fulfilled,cancelled',
            'notes' => 'nullable|string',
        ]);

        $bloodRequest->update($validated);

        return redirect()->back()->with('success', 'Request updated successfully!');
    }

    public function destroy(BloodRequest $request)
    {
        if ($request->hospital_id !== Auth::id()) {
            abort(403);
        }

        $request->delete();

        return redirect()->route('hospital.requests.index')
            ->with('success', 'Request deleted successfully!');
    }
}