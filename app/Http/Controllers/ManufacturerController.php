<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ManufacturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Manufacturer::class);

        $manufacturers = Manufacturer::all();
        return view('manufacturers.index', compact('manufacturers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Manufacturer::class);
        return view('manufacturers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Manufacturer::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:manufacturers,name',
        ]);

        Manufacturer::create($request->all());

        return redirect()->route('manufacturers.index')->with('success', 'Pabrik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Manufacturer $manufacturer)
    {
        $this->authorize('view', $manufacturer);
        return view('manufacturers.show', compact('manufacturer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manufacturer $manufacturer)
    {
        $this->authorize('update', $manufacturer);
        return view('manufacturers.edit', compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        $this->authorize('update', $manufacturer);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('manufacturers', 'name')->ignore($manufacturer->id)],
        ]);

        $manufacturer->update($request->all());

        return redirect()->route('manufacturers.index')->with('success', 'Pabrik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manufacturer $manufacturer)
    {
        $this->authorize('delete', $manufacturer);
        $manufacturer->delete();

        return redirect()->route('manufacturers.index')->with('success', 'Pabrik berhasil dihapus.');
    }
}
