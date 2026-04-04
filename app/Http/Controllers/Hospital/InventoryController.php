<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hospital = $request->user();

        $inventory = BloodInventory::where('hospital_id', $hospital->id)
            ->orderBy('blood_type')
            ->orderBy('expiration_date')
            ->get();

        $lowStock = BloodInventory::where('hospital_id', $hospital->id)->lowStock()->get();
        $expiringSoon = BloodInventory::where('hospital_id', $hospital->id)->expiringSoon()->get();

        return view('hospital.inventory.index', compact('inventory', 'lowStock', 'expiringSoon'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('hospital.inventory.create', compact('bloodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date|after:today',
        ]);

        $hospital = $request->user();

        BloodInventory::create([
            'hospital_id' => $hospital->id,
            'blood_type' => $request->blood_type,
            'quantity' => $request->quantity,
            'expiration_date' => $request->expiration_date,
        ]);

        return redirect()->route('hospital.inventory.index')->with('success', 'Blood inventory added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloodInventory $inventory)
    {
        $this->authorize('update', $inventory);

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        return view('hospital.inventory.edit', compact('inventory', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BloodInventory $inventory)
    {
        $this->authorize('update', $inventory);

        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $inventory->update($request->only(['blood_type', 'quantity', 'expiration_date']));

        return redirect()->route('hospital.inventory.index')->with('success', 'Blood inventory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloodInventory $inventory)
    {
        $this->authorize('delete', $inventory);

        $inventory->delete();

        return redirect()->route('hospital.inventory.index')->with('success', 'Blood inventory removed successfully.');
    }
}
