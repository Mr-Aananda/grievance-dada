<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SectionRequest;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $section_query = Section::query()->with('department')->withCount('department');

        // Search filters
        if (request()->name) {
            $section_query->where('name', 'LIKE', '%' . request()->name . '%');
        }
        if (request()->department_id) {
            $section_query->where('department_id', request()->department_id);
        }
        if (request()->status === '1' || request()->status === '0') {
            $section_query->where('status', request()->status);
        }

        $sections = $section_query->latest()
        ->withCount('users')
        ->paginate(25)
        ->withQueryString();
        $departments = Department::where('status', true)->get();

        return view('pages.section.index', compact('sections', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('status', true)->get();
        return view('pages.section.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                Section::create([
                    'name' => $request->name,
                    'code' => $request->code,
                    'department_id' => $request->department_id,
                    'note' => $request->note,
                    'status' => $request->status ?? true,
                ]);
            });

            return redirect()->route('section.index')->withSuccess('Section created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create section: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $section = Section::with(['department', 'users'])->withCount('users')->findOrFail($id);
        return view('pages.section.show', compact('section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $section = Section::findOrFail($id);
        $departments = Department::where('status', true)->get();
        return view('pages.section.edit', compact('section', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, Section $section)
    {
        try {
            DB::transaction(function () use ($request, $section) {
                $section->update([
                    'name' => $request->name,
                    'code' => $request->code,
                    'department_id' => $request->department_id,
                    'note' => $request->note,
                    'status' => $request->status ?? $section->status,
                ]);
            });

            return redirect()->route('section.index')->withSuccess('Section updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update section: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $section = Section::findOrFail($id);

        // Check if section has users
        if ($section->users()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete section. There are users assigned to this section.']);
        }

        try {
            DB::transaction(function () use ($section) {
                $section->delete();
            });

            return redirect()->route('section.index')->withSuccess('Section deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete section: ' . $e->getMessage()]);
        }
    }

    /**
     * Show trashed sections
     */
    public function trash()
    {
        $sections = Section::onlyTrashed()->with('department')->latest()->paginate(25);
        return view('pages.section.trash', compact('sections'));
    }

    /**
     * Restore trashed section
     */
    public function restore(string $id)
    {
        $section = Section::onlyTrashed()->findOrFail($id);

        try {
            DB::transaction(function () use ($section) {
                $section->restore();
            });

            return redirect()->route('section.trash')->withSuccess('Section restored successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to restore section: ' . $e->getMessage()]);
        }
    }

    /**
     * Permanent delete
     */
    public function permanentDelete(string $id)
    {
        $section = Section::onlyTrashed()->findOrFail($id);

        try {
            DB::transaction(function () use ($section) {
                $section->forceDelete();
            });

            return redirect()->route('section.trash')->withSuccess('Section permanently deleted');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete section: ' . $e->getMessage()]);
        }
    }
}
