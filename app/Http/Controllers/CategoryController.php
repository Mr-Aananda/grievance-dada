<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query();
        if (request()->search) {
            if (request('name')) {
                $categories->where('name', 'like', '%' . request('name') . '%');
            }
        }
        $categories = $categories->latest()
            ->paginate(25)
            ->withQueryString();
        return view('pages.category.index', compact('categories'));
    }

    public function create()
    {
        return view('pages.category.create');
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->back()->withSuccess('Category create successfully');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('pages.category.edit', compact('category'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return redirect()->route('category.index')->withSuccess('Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->grievances()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete category. There are grievances assigned to this category.']);
        }
        $category->delete();
        return redirect()->route('category.index')->withSuccess('Category deleted successfully.');
    }

    public function trash()
    {
        $categories = Category::latest()
            ->onlyTrashed()
            ->paginate(30)
            ->withQueryString();

        return view('pages.category.trash', compact('categories'));
    }

    public function restore($id)
    {
        Category::withTrashed()->find($id)->restore();
        return redirect()->back()->withSuccess('Category restore successfully.');
    }

    public function permanentDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->forceDelete();
        return redirect()->back()->withSuccess('Category deleted permanently.');
    }
}
