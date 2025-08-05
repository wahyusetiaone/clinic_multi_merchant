<?php

namespace App\Http\Controllers;

use App\Models\DrugGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DrugGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', DrugGroup::class);

        $drugGroups = DrugGroup::all();
        return view('drug_groups.index', compact('drugGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', DrugGroup::class);
        return view('drug_groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', DrugGroup::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:drug_groups,name',
        ]);

        DrugGroup::create($request->all());

        return redirect()->route('drug_groups.index')->with('success', 'Golongan obat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DrugGroup $drugGroup)
    {
        $this->authorize('view', $drugGroup);
        return view('drug_groups.show', compact('drugGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DrugGroup $drugGroup)
    {
        $this->authorize('update', $drugGroup);
        return view('drug_groups.edit', compact('drugGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DrugGroup $drugGroup)
    {
        $this->authorize('update', $drugGroup);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('drug_groups', 'name')->ignore($drugGroup->id)],
        ]);

        $drugGroup->update($request->all());

        return redirect()->route('drug_groups.index')->with('success', 'Golongan obat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DrugGroup $drugGroup)
    {
        $this->authorize('delete', $drugGroup);
        $drugGroup->delete();

        return redirect()->route('drug_groups.index')->with('success', 'Golongan obat berhasil dihapus.');
    }
}
