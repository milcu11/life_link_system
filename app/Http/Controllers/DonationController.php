<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Mark a donation as completed
     */
    public function complete(Request $request, Donation $donation)
    {
        // Verify the hospital owns this request
        if ($donation->request->hospital_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $donation->update([
            'status' => 'completed',
        ]);

        // Add to inventory
        \App\Models\BloodInventory::create([
            'hospital_id' => Auth::id(),
            'blood_type' => $donation->request->blood_type,
            'quantity' => $donation->quantity,
            'expiration_date' => now()->addDays(42), // Standard blood shelf life
        ]);

        return redirect()->back()->with('success', 'Donation marked as completed and added to inventory!');
    }

    /**
     * Mark a donation as cancelled
     */
    public function cancel(Request $request, Donation $donation)
    {
        // Verify the hospital owns this request
        if ($donation->request->hospital_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500'
        ]);

        $donation->update([
            'status' => 'cancelled',
            'notes' => $request->input('cancellation_reason'),
        ]);

        return redirect()->back()->with('success', 'Donation cancelled.');
    }
}
