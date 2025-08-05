<?php

namespace App\Http\Controllers;

use App\Models\DrugCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DrugCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', DrugCategory::class);

        $drugCategories = DrugCategory::all();
        return view('drug_categories.index', compact('drugCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', DrugCategory::class);
        return view('drug_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', DrugCategory::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:drug_categories,name',
        ]);

        DrugCategory::create($request->all());

        return redirect()->route('drug_categories.index')->with('success', 'Kategori obat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DrugCategory $drugCategory)
    {
        $this->authorize('view', $drugCategory);
        return view('drug_categories.show', compact('drugCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DrugCategory $drugCategory)
    {
        $this->authorize('update', $drugCategory);
        return view('drug_categories.edit', compact('drugCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DrugCategory $drugCategory)
    {
        $this->authorize('update', $drugCategory);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('drug_categories', 'name')->ignore($drugCategory->id)],
        ]);

        $drugCategory->update($request->all());

        return redirect()->route('drug_categories.index')->with('success', 'Kategori obat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DrugCategory $drugCategory)
    {
        $this->authorize('delete', $drugCategory);
        $drugCategory->delete();

        return redirect()->route('drug_categories.index')->with('success', 'Kategori obat berhasil dihapus.');
    }
}
