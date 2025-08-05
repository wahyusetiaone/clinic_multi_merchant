<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Unit::class);

        $units = Unit::all();
        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Unit::class);
        return view('units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Unit::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'Satuan obat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $this->authorize('view', $unit);
        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        $this->authorize('update', $unit);
        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $this->authorize('update', $unit);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('units', 'name')->ignore($unit->id)],
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Satuan obat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $this->authorize('delete', $unit);
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Satuan obat berhasil dihapus.');
    }
}
