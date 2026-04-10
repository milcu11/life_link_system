<?php

namespace App\Http\Controllers;

use App\Mail\DonorAcceptedMatch;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Matching;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DonorController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        return view('donor.profile', compact('donor'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'emergency_contact' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'verification_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $data = $request->only([
            'blood_type', 'phone', 'address', 'date_of_birth', 
            'gender', 'latitude', 'longitude', 'emergency_contact', 
            'medical_conditions'
        ]);

        // Handle file upload
        if ($request->hasFile('verification_document')) {
            $file = $request->file('verification_document');
            // Store file in the storage/app/private/verification_documents directory
            $path = $file->storeAs(
                'verification_documents',
                $user->id . '_' . time() . '.' . $file->getClientOriginalExtension(),
                'private'
            );
            $data['verification_document_path'] = $path;
            $data['is_verified'] = false; // Mark as unverified when new document is uploaded
        }

        $donor = $user->donor()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('donor.profile')->with('success', 'Profile updated successfully!');
    }

    public function history()
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        if (!$donor) {
            return redirect()->route('donor.profile')->with('info', 'Please complete your profile first.');
        }

        $donations = $donor->donations()->with(['request.hospital'])->latest()->paginate(10);
        
        return view('donor.history', compact('donor', 'donations'));
    }

    public function updateAvailability(Request $request)
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        if (!$donor) {
            return redirect()->route('donor.profile')->with('error', 'Please complete your profile first.');
        }

        $donor->update([
            'is_available' => $request->boolean('is_available')
        ]);

        $status = $request->boolean('is_available') ? 'available' : 'unavailable';
        return redirect()->back()->with('success', "You are now marked as {$status}.");
    }

    public function viewRequests()
    {
        $user = Auth::user();
        $donor = $user->donor;
        
        if (!$donor) {
            return redirect()->route('donor.profile')->with('info', 'Please complete your profile first.');
        }

        $matches = $donor->matchings()
            ->with('request.hospital')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
        
        return view('donor.requests', compact('matches'));
    }

    public function respondToMatch(Request $request, Matching $match)
    {
        $request->validate([
            'response' => 'required|in:accepted,rejected'
        ]);

        $isAccepted = $request->response === 'accepted';

        $match->update([
            'status' => $request->response,
            'responded_at' => now()
        ]);

        if ($isAccepted) {
            // Create a donation record
            Donation::create([
                'donor_id' => $match->donor_id,
                'request_id' => $match->request_id,
                'quantity' => $match->request->quantity,
                'status' => 'scheduled',
                'donation_date' => now(), // Can be updated by hospital later
            ]);

            // Create notification for hospital
            $bloodRequest = $match->request;
            $hospital = $bloodRequest->hospital;
            
            Notification::create([
                'user_id' => $hospital->id,
                'type' => 'in_app',
                'title' => "Donor Accepted: {$match->donor->blood_type}",
                'message' => "{$match->donor->user->name} has accepted your blood request for {$bloodRequest->patient_name}.",
                'request_id' => $bloodRequest->id,
            ]);

            // Send email to hospital immediately
            try {
                Mail::send(new DonorAcceptedMatch($match));
            } catch (\Exception $e) {
                \Log::error('Failed to send donor accepted email', [
                    'match_id' => $match->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $message = 'You have accepted this blood donation request. The hospital will contact you soon!';
        } else {
            $message = 'You have declined this blood donation request.';
        }

        return redirect()->back()->with('success', $message);
    }
}