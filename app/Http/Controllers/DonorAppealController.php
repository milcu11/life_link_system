<?php

namespace App\Http\Controllers;

use App\Mail\AppealReceived;
use App\Models\Donor;
use App\Models\DonorAppeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DonorAppealController extends Controller
{
    public function denied()
    {
        $user = Auth::user();
        $donor = $user->donor ?? null;

        return view('donor.denied', compact('donor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();
        $donor = $user->donor;

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('appeals');
        }

        $appeal = DonorAppeal::create([
            'donor_id' => $donor->id,
            'message' => $request->input('message'),
            'attachment_path' => $path,
            'status' => 'pending',
        ]);

        // send acknowledgement email to donor if mailer configured
        try {
            Mail::to($user->email)->send(new AppealReceived($appeal));
        } catch (\Exception $e) {
            // swallow – mail may not be configured in local env
        }

        return redirect()->route('donor.denied')->with('success', 'Your appeal has been submitted. We will review it within 3 business days.');
    }
}
