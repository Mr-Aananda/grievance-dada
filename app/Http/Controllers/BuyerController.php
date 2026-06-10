<?php

namespace App\Http\Controllers;

use App\Exports\BuyersExport;
use App\Http\Requests\BuyerRequest;
use App\Imports\BuyersImport;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buyers = Buyer::query();

        // Search functionality
        if (request()->search) {
            if (request('company_name')) {
                $buyers->where('company_name', 'like', '%' . request('company_name') . '%');
            }
            if (request('code')) {
                $buyers->where('code', 'like', '%' . request('code') . '%');
            }
            if (request('country')) {
                $buyers->where('country', 'like', '%' . request('country') . '%');
            }
            if (request('email')) {
                $buyers->where('email', 'like', '%' . request('email') . '%');
            }
            if (request('phone')) {
                $buyers->where('phone', 'like', '%' . request('phone') . '%');
            }
            if (request()->has('status') && request('status') !== '') {
                $buyers->where('status', request('status'));
            }
        }

        $buyers = $buyers->with(['creator', 'updater'])
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pages.buyer.index', compact('buyers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.buyer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BuyerRequest $request)
    {
        $data = $request->validated();

        // Add current user as creator
        $data['user_id'] = Auth::id();

        Buyer::create($data);
        return redirect()->back()->withSuccess('Buyer created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buyer = Buyer::with(['creator', 'updater'])->findOrFail($id);
        return view('pages.buyer.show', compact('buyer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $buyer = Buyer::findOrFail($id);
        return view('pages.buyer.edit', compact('buyer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BuyerRequest $request, string $id)
    {
        $data = $request->validated();
        $buyer = Buyer::findOrFail($id);

        // Add current user as updater
        $data['updated_id'] = Auth::id();

        $buyer->update($data);
        return redirect()->route('buyer.index')->withSuccess('Buyer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buyer = Buyer::query()->findOrFail($id);

        $buyer->delete();

        return redirect()->route('buyer.index')->withSuccess('Buyer deleted successfully.');
    }

    /**
     * Display a listing of the trashes.
     */
    public function trash()
    {
        $buyers = Buyer::latest()
            ->onlyTrashed()
            ->with(['creator', 'updater'])
            ->paginate(30)
            ->withQueryString();

        return view('pages.buyer.trash', compact('buyers'));
    }

    /**
     * restore deleted member
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        // restore by id
        Buyer::withTrashed()->find($id)->restore();

        // view
        return redirect()->back()->withSuccess('Buyer restored successfully.');
    }

    /**
     * permanently deleted data
     * @param $id
     * @return mixed
     */
    public function permanentDelete($id): mixed
    {
        $buyer = Buyer::withTrashed()->findOrFail($id);
        $buyer->forceDelete();

        return redirect()->back()->withSuccess('Buyer deleted permanently.');
    }

    /**
     * Toggle buyer status
     */
    public function toggleStatus($id)
    {
        $buyer = Buyer::findOrFail($id);
        $buyer->status = !$buyer->status;
        $buyer->updated_id = Auth::id();
        $buyer->save();

        $status = $buyer->status ? 'activated' : 'deactivated';
        return redirect()->back()->withSuccess("Buyer {$status} successfully.");
    }



    /**
     * Show import form page
     */
    public function importPage()
    {
        return view('pages.buyer.import');
    }


    /**
     * Import buyers from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new BuyersImport, $request->file('file'));
            return redirect()->back()->withSuccess('Buyers imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->withErrors($failures);
        }
    }

    /**
     * Export buyers to Excel.
     */
    public function export()
    {
        return Excel::download(new BuyersExport, 'buyers.xlsx');
    }
}
