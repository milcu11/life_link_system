<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BloodDrive;
use App\Models\User;
use Illuminate\Http\Request;

class BloodDriveController extends Controller
{
    public function __construct()
    {
        $this->middleware('hospital.profile.complete');
    }

    public function index(Request $request)
    {
        $hospital = $request->user();
        $drives = $hospital->bloodDrives()->withCount(['appointments as confirmed_appointments_count' => function($q) {
            $q->where('status', 'confirmed');
        }])->orderBy('start_time', 'desc')->get();

        return view('hospital.drives.index', compact('drives'));
    }

    public function create()
    {
        return view('hospital.drives.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string|max:2000',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'capacity' => 'required|integer|min:1',
        ]);

        $data['hospital_id'] = $request->user()->id;
        BloodDrive::create($data);

        return redirect()->route('hospital.drives.index')->with('success', 'Blood drive scheduled successfully.');
    }

    public function show(BloodDrive $drive)
    {
        $this->authorizeDrive($drive);
        $drive->load(['appointments.donor']);

        return view('hospital.drives.show', compact('drive'));
    }

    public function edit(BloodDrive $drive)
    {
        $this->authorizeDrive($drive);
        return view('hospital.drives.edit', compact('drive'));
    }

    public function update(Request $request, BloodDrive $drive)
    {
        $this->authorizeDrive($drive);

        $data = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string|max:2000',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $drive->update($data);

        return redirect()->route('hospital.drives.index')->with('success', 'Blood drive updated successfully.');
    }

    public function destroy(BloodDrive $drive)
    {
        $this->authorizeDrive($drive);
        $drive->delete();
        return redirect()->route('hospital.drives.index')->with('success', 'Blood drive removed successfully.');
    }

    public function cancel(BloodDrive $drive)
    {
        $this->authorizeDrive($drive);
        $drive->update(['status' => 'cancelled']);

        return redirect()->route('hospital.drives.show', $drive)->with('success', 'Blood drive cancelled.');
    }

    protected function authorizeDrive(BloodDrive $drive)
    {
        if ($drive->hospital_id !== auth()->id()) {
            abort(403);
        }
    }
}
