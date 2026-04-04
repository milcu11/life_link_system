<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Matching;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of conversations with matched hospitals.
     */
    public function index(Request $request)
    {
        $donor = $request->user();

        // Get all hospitals the donor has matched with
        $matchedHospitals = User::whereHas('bloodRequests.matchings', function($query) use ($donor) {
            $query->where('donor_id', $donor->donor->id);
        })->with(['bloodRequests' => function($query) use ($donor) {
            $query->whereHas('matchings', function($q) use ($donor) {
                $q->where('donor_id', $donor->donor->id);
            })->with(['matchings' => function($m) use ($donor) {
                $m->where('donor_id', $donor->donor->id);
            }]);
        }])->get();

        // Get conversations with unread counts
        $conversations = [];
        foreach ($matchedHospitals as $hospital) {
            $latestMessage = Message::where(function($q) use ($donor, $hospital) {
                $q->where('sender_id', $donor->id)->where('recipient_id', $hospital->id)
                  ->orWhere('sender_id', $hospital->id)->where('recipient_id', $donor->id);
            })->latest()->first();

            $unreadCount = Message::where('sender_id', $hospital->id)
                ->where('recipient_id', $donor->id)
                ->unread()
                ->count();

            $conversations[] = [
                'hospital' => $hospital,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
                'requests' => $hospital->bloodRequests
            ];
        }

        return view('donor.messages.index', compact('conversations'));
    }

    /**
     * Show the conversation with a specific hospital.
     */
    public function show(Request $request, User $hospital)
    {
        $donor = $request->user();

        // Verify they have a matching
        $hasMatching = Matching::where('donor_id', $donor->donor->id)
            ->whereHas('request', function($q) use ($hospital) {
                $q->where('hospital_id', $hospital->id);
            })->exists();

        if (!$hasMatching) {
            abort(403, 'You can only message matched hospitals.');
        }

        // Get all messages between donor and hospital
        $messages = Message::where(function($q) use ($donor, $hospital) {
            $q->where('sender_id', $donor->id)->where('recipient_id', $hospital->id)
              ->orWhere('sender_id', $hospital->id)->where('recipient_id', $donor->id);
        })->with(['sender', 'recipient', 'request'])->orderBy('created_at')->get();

        // Mark messages from hospital as read
        Message::where('sender_id', $hospital->id)
            ->where('recipient_id', $donor->id)
            ->unread()
            ->update(['read_at' => now()]);

        // Get related requests for context
        $relatedRequests = $donor->donor->matchings()
            ->whereHas('request', function($q) use ($hospital) {
                $q->where('hospital_id', $hospital->id);
            })
            ->with('request')
            ->get()
            ->pluck('request');

        return view('donor.messages.show', compact('hospital', 'messages', 'relatedRequests'));
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, User $hospital)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'request_id' => 'nullable|exists:blood_requests,id',
            'type' => 'required|in:message,reminder,coordination,urgent'
        ]);

        $donor = $request->user();

        // Verify they have a matching
        $hasMatching = Matching::where('donor_id', $donor->donor->id)
            ->whereHas('request', function($q) use ($hospital) {
                $q->where('hospital_id', $hospital->id);
            })->exists();

        if (!$hasMatching) {
            abort(403, 'You can only message matched hospitals.');
        }

        Message::create([
            'sender_id' => $donor->id,
            'recipient_id' => $hospital->id,
            'request_id' => $request->request_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'type' => $request->type,
        ]);

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
