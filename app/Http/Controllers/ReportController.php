<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use App\Models\ComplainType;
use App\Models\Category;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ComplainReportExport;

class ReportController extends Controller
{
    public function overallReport()
    {
        $complainTypes = ComplainType::where('status', true)->orderBy('name')->get();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $buyers = Buyer::where('status', true)->orderBy('company_name')->get();

        $complains = collect();
        $summaryStats = [];

        // Default: Current month report (সবসময় চলতি মাস দেখাবে)
        $defaultStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $defaultEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        if (request()->has('search')) {
            $complains = $this->getFilteredComplains(request());
            $summaryStats = $this->getSummaryStats($complains);
        } else {
            // Automatically show current month report
            $complains = Complain::with(['user', 'complainType', 'category', 'buyer'])
                ->whereDate('date', '>=', $defaultStartDate)
                ->whereDate('date', '<=', $defaultEndDate)
                ->orderBy('date', 'desc')
                ->get();

            $summaryStats = $this->getSummaryStats($complains);
        }

        return view('pages.reports.overall-report', compact(
            'complainTypes',
            'categories',
            'buyers',
            'complains',
            'summaryStats',
            'defaultStartDate',
            'defaultEndDate'
        ));
    }

    public function exportReport(Request $request)
    {
        $request->validate([
            'export_format' => 'required|in:excel'
        ]);

        $complains = $this->getFilteredComplains($request);

        if ($complains->isEmpty()) {
            return back()->with('error', 'No data found to export.');
        }

        $filename = 'complains-report-' . date('Y-m-d-H-i-s');

        return Excel::download(new ComplainReportExport($complains), $filename . '.xlsx');
    }

    private function getFilteredComplains($request)
    {
        $query = Complain::with(['user', 'complainType', 'category', 'buyer']);

        // Filter by Year
        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        // Filter by Month
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        // Filter by Quarter
        if ($request->filled('quarter')) {
            $quarter = $request->quarter;
            $months = $this->getQuarterMonths($quarter);
            $query->whereIn(\DB::raw('MONTH(date)'), $months);
            if ($request->filled('year')) {
                $query->whereYear('date', $request->year);
            }
        }

        // Filter by filter_type (quick filters)
        if ($request->filled('filter_type')) {
            $this->applyFilterType($query, $request->filter_type);
        }

        // Date range (overrides other date filters)
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Type (complain/manual)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // PO
        if ($request->filled('po')) {
            $query->where('po', 'like', '%' . $request->po . '%');
        }

        // PS
        if ($request->filled('ps')) {
            $query->where('ps', 'like', '%' . $request->ps . '%');
        }

        // CAP
        if ($request->filled('cap')) {
            $query->where('cap', 'like', '%' . $request->cap . '%');
        }

        // Buyer
        if ($request->filled('buyer_id')) {
            $query->where('buyer_id', $request->buyer_id);
        }

        // Complain Type
        if ($request->filled('complain_type_id')) {
            $query->where('complain_type_id', $request->complain_type_id);
        }

        // Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    private function applyFilterType($query, $filterType)
    {
        switch ($filterType) {
            case 'today':
                $query->whereDate('date', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('date', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('date', Carbon::now()->subMonth()->month)
                    ->whereYear('date', Carbon::now()->subMonth()->year);
                break;
            case 'this_quarter':
                $query->whereBetween('date', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]);
                break;
            case 'last_quarter':
                $query->whereBetween('date', [Carbon::now()->subQuarter()->startOfQuarter(), Carbon::now()->subQuarter()->endOfQuarter()]);
                break;
            case 'this_year':
                $query->whereYear('date', Carbon::now()->year);
                break;
            case 'last_year':
                $query->whereYear('date', Carbon::now()->subYear()->year);
                break;
        }
    }

    private function getQuarterMonths($quarter)
    {
        $quarters = [
            1 => [1, 2, 3],  // Jan-Mar
            2 => [4, 5, 6],  // Apr-Jun
            3 => [7, 8, 9],  // Jul-Sep
            4 => [10, 11, 12] // Oct-Dec
        ];

        return $quarters[$quarter] ?? [1, 2, 3];
    }

    private function getSummaryStats($complains)
    {
        $total = $complains->count();

        // Type wise count (Complain/Manual)
        $complainsCount = $complains->where('type', 'complain')->count();
        $manualsCount = $complains->where('type', 'manual')->count();

        // Status wise count
        $pending = $complains->where('status', 'pending')->count();
        $inProgress = $complains->where('status', 'in_progress')->count();
        $resolved = $complains->where('status', 'resolved')->count();
        $closed = $complains->where('status', 'closed')->count();

        // Files and videos count
        $totalFiles = 0;
        $totalVideos = 0;

        foreach ($complains as $complain) {
            $files = $complain->files_data ?? [];
            $totalFiles += count($files);
            $totalVideos += $complain->videos_count ?? 0;
        }

        // Quantity and amount calculations
        $totalQuantity = $complains->sum('quantity');
        $totalAmount = $complains->sum('amount');

        // Calculate total amount (quantity * amount)
        $totalCalculatedAmount = $complains->sum(function ($complain) {
            return ($complain->quantity ?? 0) * ($complain->amount ?? 0);
        });

        // Averages
        $averageQuantity = $total > 0 ? $totalQuantity / $total : 0;
        $averageAmount = $total > 0 ? $totalAmount / $total : 0;

        // Percentages
        $pendingPercentage = $total > 0 ? round(($pending / $total) * 100, 2) : 0;
        $inProgressPercentage = $total > 0 ? round(($inProgress / $total) * 100, 2) : 0;
        $resolvedPercentage = $total > 0 ? round(($resolved / $total) * 100, 2) : 0;
        $closedPercentage = $total > 0 ? round(($closed / $total) * 100, 2) : 0;

        // Complain type distribution
        $typeDistribution = [];
        foreach ($complains->groupBy('complain_type_id') as $typeId => $typeComplains) {
            $typeName = $typeComplains->first()->complainType->name ?? 'Unknown';
            $typeDistribution[] = [
                'name' => $typeName,
                'count' => $typeComplains->count(),
                'percentage' => $total > 0 ? round(($typeComplains->count() / $total) * 100, 2) : 0
            ];
        }

        // Category distribution
        $categoryDistribution = [];
        foreach ($complains->groupBy('category_id') as $catId => $catComplains) {
            $catName = $catComplains->first()->category->name ?? 'Unknown';
            $categoryDistribution[] = [
                'name' => $catName,
                'count' => $catComplains->count(),
                'percentage' => $total > 0 ? round(($catComplains->count() / $total) * 100, 2) : 0
            ];
        }

        // Monthly trend for charts
        $monthlyTrend = [];
        $groupedByMonth = $complains->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y-m');
        })->sortKeys();

        foreach ($groupedByMonth as $month => $monthComplains) {
            $monthlyTrend[] = [
                'month' => Carbon::parse($month)->format('M Y'),
                'count' => $monthComplains->count(),
                'pending' => $monthComplains->where('status', 'pending')->count(),
                'resolved' => $monthComplains->where('status', 'resolved')->count()
            ];
        }

        return [
            'total' => $total,
            'complains_count' => $complainsCount,
            'manuals_count' => $manualsCount,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'closed' => $closed,
            'total_files' => $totalFiles,
            'total_videos' => $totalVideos,
            'total_quantity' => $totalQuantity,
            'total_amount' => $totalAmount,
            'total_calculated_amount' => $totalCalculatedAmount,
            'avg_quantity' => round($averageQuantity, 2),
            'avg_amount' => round($averageAmount, 2),
            'pending_percentage' => $pendingPercentage,
            'in_progress_percentage' => $inProgressPercentage,
            'resolved_percentage' => $resolvedPercentage,
            'closed_percentage' => $closedPercentage,
            'type_distribution' => $typeDistribution,
            'category_distribution' => $categoryDistribution,
            'monthly_trend' => $monthlyTrend,
        ];
    }
}
