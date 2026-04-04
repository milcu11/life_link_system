<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Matching;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of conversations with matched donors.
     */
    public function index(Request $request)
    {
        $hospital = $request->user();

        // Get all donors the hospital has matched with
        $matchedDonors = User::whereHas('donor.matchings', function($query) use ($hospital) {
            $query->whereHas('request', function($q) use ($hospital) {
                $q->where('hospital_id', $hospital->id);
            });
        })->with(['donor', 'donor.matchings' => function($query) use ($hospital) {
            $query->whereHas('request', function($q) use ($hospital) {
                $q->where('hospital_id', $hospital->id);
            })->with('request');
        }])->get();

        // Get conversations with unread counts
        $conversations = [];
        foreach ($matchedDonors as $donor) {
            $latestMessage = Message::where(function($q) use ($hospital, $donor) {
                $q->where('sender_id', $hospital->id)->where('recipient_id', $donor->id)
                  ->orWhere('sender_id', $donor->id)->where('recipient_id', $hospital->id);
            })->latest()->first();

            $unreadCount = Message::where('sender_id', $donor->id)
                ->where('recipient_id', $hospital->id)
                ->unread()
                ->count();

            $conversations[] = [
                'donor' => $donor,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
                'matchings' => $donor->donor->matchings
            ];
        }

        return view('hospital.messages.index', compact('conversations'));
    }

    /**
     * Show the conversation with a specific donor.
     */
    public function show(Request $request, User $donor)
    {
        $hospital = $request->user();

        // Verify they have a matching
        $hasMatching = Matching::whereHas('request', function($q) use ($hospital) {
            $q->where('hospital_id', $hospital->id);
        })->where('donor_id', $donor->donor->id)->exists();

        if (!$hasMatching) {
            abort(403, 'You can only message matched donors.');
        }

        // Get all messages between hospital and donor
        $messages = Message::where(function($q) use ($hospital, $donor) {
            $q->where('sender_id', $hospital->id)->where('recipient_id', $donor->id)
              ->orWhere('sender_id', $donor->id)->where('recipient_id', $hospital->id);
        })->with(['sender', 'recipient', 'request'])->orderBy('created_at')->get();

        // Mark messages from donor as read
        Message::where('sender_id', $donor->id)
            ->where('recipient_id', $hospital->id)
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

        return view('hospital.messages.show', compact('donor', 'messages', 'relatedRequests'));
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, User $donor)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'request_id' => 'nullable|exists:blood_requests,id',
            'type' => 'required|in:message,reminder,coordination,urgent'
        ]);

        $hospital = $request->user();

        // Verify they have a matching
        $hasMatching = Matching::whereHas('request', function($q) use ($hospital) {
            $q->where('hospital_id', $hospital->id);
        })->where('donor_id', $donor->donor->id)->exists();

        if (!$hasMatching) {
            abort(403, 'You can only message matched donors.');
        }

        Message::create([
            'sender_id' => $hospital->id,
            'recipient_id' => $donor->id,
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
