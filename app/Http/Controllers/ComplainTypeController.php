<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplainTypeRequest;
use App\Models\ComplainType;
use Illuminate\Http\Request;

class ComplainTypeController extends Controller
{
    public function index()
    {
        $complainTypes = ComplainType::query();

        if (request()->search) {
            if (request('name')) {
                $complainTypes->where('name', 'like', '%' . request('name') . '%');
            }
            if (request('type')) {
                $complainTypes->where('type', request('type'));
            }
        }

        $complainTypes = $complainTypes->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pages.complain-type.index', compact('complainTypes'));
    }

    public function create()
    {
        return view('pages.complain-type.create');
    }

    public function store(ComplainTypeRequest $request)
    {
        ComplainType::create($request->validated());
        return redirect()->back()->withSuccess('Complain type created successfully');
    }

    public function show($id)
    {
        $complainType = ComplainType::findOrFail($id);
        return view('pages.complain-type.show', compact('complainType'));
    }

    public function edit($id)
    {
        $complainType = ComplainType::findOrFail($id);
        return view('pages.complain-type.edit', compact('complainType'));
    }

    public function update(ComplainTypeRequest $request, $id)
    {
        $complainType = ComplainType::findOrFail($id);
        $complainType->update($request->validated());
        return redirect()->route('complain-type.index')->withSuccess('Complain type updated successfully');
    }

    public function destroy($id)
    {
        $complainType = ComplainType::findOrFail($id);
        if ($complainType->complains()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete complain type. There are complains assigned to this type.']);
        }

        $complainType->delete();
        return redirect()->route('complain-type.index')->withSuccess('Complain type deleted successfully.');
    }

    public function trash()
    {
        $complainTypes = ComplainType::latest()
            ->onlyTrashed()
            ->paginate(30)
            ->withQueryString();

        return view('pages.complain-type.trash', compact('complainTypes'));
    }

    public function restore($id)
    {
        ComplainType::withTrashed()->find($id)->restore();
        return redirect()->back()->withSuccess('Complain type restored successfully.');
    }

    public function permanentDelete($id)
    {
        $complainType = ComplainType::withTrashed()->findOrFail($id);
        $complainType->forceDelete();
        return redirect()->back()->withSuccess('Complain type deleted permanently.');
    }
}
