<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    // Display a listing of the consultations.
    public function index()
    {
        $consultations = Consultation::all();
        return view('backend.pages.consultations.index', compact('consultations'));
    }

    // Show the form for creating a new consultation.
    public function create()
    {
        return view('backend.pages.consultations.create');
    }

    // Store a newly created consultation in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'price' => 'required|numeric|min:0',
            'time' => 'required|integer|min:1',
        ]);

        Consultation::create($validated);

        return redirect()->route('backend.consultations.index')->with('success', 'Consultation created successfully.');
    }

    // Display the specified consultation.
    public function show(Consultation $consultation)
    {
        return view('backend.pages.consultations.show', compact('consultation'));
    }

    // Show the form for editing the specified consultation.
    public function edit(Consultation $consultation)
    {
        return view('backend.pages.consultations.edit', compact('consultation'));
    }

    // Update the specified consultation in storage.
    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'price' => 'required|numeric|min:0',
            'time' => 'required|integer|min:1',
            'show_on_consultation_page' => 'boolean',
        ]);

        // Handle checkbox - if not present, set to false
        $validated['show_on_consultation_page'] = $request->has('show_on_consultation_page') ? true : false;

        $consultation->update($validated);

        return redirect()->route('backend.consultations.index')->with('success', 'Consultation updated successfully.');
    }

    // Remove the specified consultation from storage.
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        return redirect()->route('backend.consultations.index')->with('success', 'Consultation deleted successfully.');
    }
}
