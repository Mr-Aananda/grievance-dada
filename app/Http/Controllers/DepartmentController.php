<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::query();
        if (request()->search) {
            if (request('name')) {
                $departments->where('name', 'like', '%' . request('name') . '%');
            }
        }
        $departments = $departments->with('sections')->latest()
        ->withCount('sections')
            ->paginate(25)
            ->withQueryString();
        return view('pages.department.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        $data = $request->validated();

        Department::create($data);
        return redirect()->back()->withSuccess('Department create successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::findOrFail($id);
        return view('pages.department.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        return view('pages.department.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, string $id)
    {
        $data = $request->validated();
        $department = Department::findOrFail($id);

        $department->update($data);
        return redirect()->route('department.index')->withSuccess('Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::query()->findOrFail($id);
        if ($department->users()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete section. There are users assigned to this department.']);
        }
        if ($department->sections()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete section. There are section assigned to this department.']);
        }

        $department->delete();

        return redirect()->route('department.index')->withSuccess('Department deleted successfully.');
    }

    /**
     * Display a listing of the trashes.
     */
    public function trash()
    {
        $departments = Department::latest()
            ->onlyTrashed()
            ->paginate(30)
            ->withQueryString();

        return view('pages.department.trash', compact('departments'));
    }

    /**
     * restore deleted member
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        // restore by id
        Department::withTrashed()->find($id)->restore();

        // view
        return redirect()->back()->withSuccess('Department restore successfully.');
    }

    /**
     * permanently deleted data
     * @param $id
     * @return mixed
     */
    public function permanentDelete($id): mixed
    {
        $department = Department::withTrashed()->findOrFail($id);
        $department->forceDelete();

        return redirect()->back()->withSuccess('Department deleted permanently.');
    }

    // /**
    //  * Get sections by department
    //  */
    // public function getSections($departmentId)
    // {
    //     try {
    //         $sections = Section::where('department_id', $departmentId)
    //             ->where('status', true)
    //             ->orderBy('name')
    //             ->select('id', 'name')
    //             ->get();

    //         return response()->json($sections);
    //     } catch (\Exception $e) {
    //         \Log::error('Error loading sections: ' . $e->getMessage());
    //         return response()->json([], 500);
    //     }
    // }
}
