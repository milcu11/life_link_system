<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BloodDrive;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $donor = $request->user();

        $availableDrives = BloodDrive::where('status', 'scheduled')
            ->where('start_time', '>=', now())
            ->withCount(['appointments as confirmed_appointments_count' => function ($q) {
                $q->where('status', 'confirmed');
            }])
            ->get();

        $myAppointments = $donor->appointments()->with('bloodDrive')->orderBy('slot_time')->get();

        return view('donor.drives.index', compact('availableDrives', 'myAppointments'));
    }

    public function book(Request $request, BloodDrive $drive)
    {
        $donor = $request->user();

        if ($drive->status !== 'scheduled') {
            return back()->with('error', 'This drive is no longer open for booking.');
        }

        $request->validate([
            'slot_time' => 'required|date|after_or_equal:' . $drive->start_time->format('Y-m-d H:i:s') . '|before_or_equal:' . $drive->end_time->format('Y-m-d H:i:s'),
            'notes' => 'nullable|string|max:1500',
        ]);

        if ($drive->confirmed_appointments_count >= $drive->capacity) {
            return back()->with('error', 'This drive is fully booked.');
        }

        Appointment::updateOrCreate([
            'donor_id' => $donor->id,
            'blood_drive_id' => $drive->id,
        ], [
            'slot_time' => $request->slot_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('donor.drives.index')->with('success', 'Appointment requested. The hospital will confirm soon.');
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->donor_id !== auth()->id()) {
            abort(403);
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('donor.drives.index')->with('success', 'Appointment cancelled.');
    }
}
