<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Label::class);

        $labels = Label::all();
        return view('labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Label::class);
        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Label::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:labels,name',
            'description' => 'nullable|string',
        ]);

        Label::create($request->all());

        return redirect()->route('labels.index')->with('success', 'Etiket/aturan pakai berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
        $this->authorize('view', $label);
        return view('labels.show', compact('label'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        $this->authorize('update', $label);
        return view('labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {
        $this->authorize('update', $label);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('labels', 'name')->ignore($label->id)],
            'description' => 'nullable|string',
        ]);

        $label->update($request->all());

        return redirect()->route('labels.index')->with('success', 'Etiket/aturan pakai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);
        $label->delete();

        return redirect()->route('labels.index')->with('success', 'Etiket/aturan pakai berhasil dihapus.');
    }
}
