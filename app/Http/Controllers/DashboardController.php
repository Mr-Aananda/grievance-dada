<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use App\Models\ComplainType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get today's date
        $today = now()->toDateString();

        // Get statistics
        $stats = [
            'today_total' => Complain::whereDate('date', $today)->count(),
            'pending' => Complain::where('status', 'pending')->count(),
            'in_progress' => Complain::where('status', 'in_progress')->count(),
            'resolved' => Complain::where('status', 'resolved')->count(),
            'total' => Complain::count(),
        ];

        // Get yesterday's total for growth calculation
        $yesterday = Carbon::yesterday()->toDateString();
        $yesterdayTotal = Complain::whereDate('date', $yesterday)->count();
        $todayGrowth = $yesterdayTotal > 0 ?
            round((($stats['today_total'] - $yesterdayTotal) / $yesterdayTotal) * 100, 2) :
            ($stats['today_total'] > 0 ? 100 : 0);

        // Get today's resolved complains
        $todayResolved = Complain::whereDate('date', $today)
            ->where('status', 'resolved')
            ->count();

        // Get ALL complain types (active ones)
        $complainTypes = ComplainType::where('status', true)->get();

        // Separate types by their 'type' field
        $complainTypeItems = $complainTypes->where('type', 'complain');
        $manualTypeItems = $complainTypes->where('type', 'manual');

        // Get current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentMonthName = date('F');

        // Get MONTHLY data for current month with quantity and amount
        $monthlyComplainStats = [];
        $monthlyManualStats = [];
        $monthlyComplainTotal = 0;
        $monthlyManualTotal = 0;
        $monthlyComplainAmount = 0;
        $monthlyManualAmount = 0;


        // Process COMPLAIN types for MONTHLY
        foreach ($complainTypeItems as $type) {
            $typeName = strtolower(str_replace(' ', '_', $type->name));

            $complains = Complain::where('complain_type_id', $type->id)
                ->where('type', 'complain')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->get();

            $complainCount = $complains->count();
            $totalQuantity = $complains->sum('quantity');
            $totalAmount = $complains->sum(function ($complain) {
                return $complain->quantity * $complain->amount;
            });

            $monthlyComplainStats[$typeName] = [
                'id' => $type->id,
                'name' => $type->name,
                'count' => $complainCount,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'type' => 'complain'
            ];

            $monthlyComplainTotal += $complainCount;
            $monthlyComplainAmount += $totalAmount;

            // dd($complains);
        }

        // Process MANUAL types for MONTHLY
        foreach ($manualTypeItems as $type) {
            $typeName = strtolower(str_replace(' ', '_', $type->name));

            $manuals = Complain::where('complain_type_id', $type->id)
                ->where('type', 'manual')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->get();

            $manualCount = $manuals->count();
            $totalQuantity = $manuals->sum('quantity');
            $totalAmount = $manuals->sum(function ($manual) {
                return $manual->quantity * $manual->amount;
            });

            $monthlyManualStats[$typeName] = [
                'id' => $type->id,
                'name' => $type->name,
                'count' => $manualCount,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'type' => 'manual'
            ];

            $monthlyManualTotal += $manualCount;
            $monthlyManualAmount += $totalAmount;
        }

        // Get YEARLY data for current year with quantity and amount
        $yearlyComplainStats = [];
        $yearlyManualStats = [];
        $yearlyComplainTotal = 0;
        $yearlyManualTotal = 0;
        $yearlyComplainAmount = 0;
        $yearlyManualAmount = 0;

        // Process COMPLAIN types for YEARLY
        foreach ($complainTypeItems as $type) {
            $typeName = strtolower(str_replace(' ', '_', $type->name));

            $complains = Complain::where('complain_type_id', $type->id)
                ->where('type', 'complain')
                ->whereYear('date', $currentYear)
                ->get();

            $complainCount = $complains->count();
            $totalQuantity = $complains->sum('quantity');
            $totalAmount = $complains->sum(function ($complain) {
                return $complain->quantity * $complain->amount;
            });

            $yearlyComplainStats[$typeName] = [
                'id' => $type->id,
                'name' => $type->name,
                'count' => $complainCount,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'type' => 'complain'
            ];

            $yearlyComplainTotal += $complainCount;
            $yearlyComplainAmount += $totalAmount;
        }

        // Process MANUAL types for YEARLY
        foreach ($manualTypeItems as $type) {
            $typeName = strtolower(str_replace(' ', '_', $type->name));

            $manuals = Complain::where('complain_type_id', $type->id)
                ->where('type', 'manual')
                ->whereYear('date', $currentYear)
                ->get();

            $manualCount = $manuals->count();
            $totalQuantity = $manuals->sum('quantity');
            $totalAmount = $manuals->sum(function ($manual) {
                return $manual->quantity * $manual->amount;
            });

            $yearlyManualStats[$typeName] = [
                'id' => $type->id,
                'name' => $type->name,
                'count' => $manualCount,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'type' => 'manual'
            ];

            $yearlyManualTotal += $manualCount;
            $yearlyManualAmount += $totalAmount;
        }

        // Get overall totals for percentage calculation
        $overallComplainTotal = Complain::where('type', 'complain')->count();
        $overallManualTotal = Complain::where('type', 'manual')->count();

        // Get status distribution
        $statusStats = Complain::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact(
            'stats',
            'monthlyComplainStats',
            'monthlyManualStats',
            'monthlyComplainTotal',
            'monthlyManualTotal',
            'monthlyComplainAmount',
            'monthlyManualAmount',
            'yearlyComplainStats',
            'yearlyManualStats',
            'yearlyComplainTotal',
            'yearlyManualTotal',
            'yearlyComplainAmount',
            'yearlyManualAmount',
            'overallComplainTotal',
            'overallManualTotal',
            'statusStats',
            'todayResolved',
            'todayGrowth',
            'complainTypes',
            'complainTypeItems',
            'manualTypeItems',
            'currentYear',
            'currentMonthName'
        ));
    }

    /**
     * Get monthly/yearly complain statistics by type with quantity and amount
     */
    public function getMonthlyComplains(Request $request)
    {
        $request->validate([
            'year' => 'nullable|date_format:Y',
            'month' => 'nullable|date_format:Y-m'
        ]);

        $filterType = '';
        $filterDisplay = '';

        // Check if month is provided
        if ($request->filled('month')) {
            $month = $request->input('month');
            $year = date('Y', strtotime($month));
            $monthNum = date('m', strtotime($month));

            $queryCondition = function ($query) use ($year, $monthNum) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $monthNum);
            };
            $filterType = 'monthly';
            $filterDisplay = date('F Y', strtotime($month));

        } elseif ($request->filled('year')) {
            $year = $request->input('year');

            $queryCondition = function ($query) use ($year) {
                $query->whereYear('date', $year);
            };
            $filterType = 'yearly';
            $filterDisplay = $year;

        } else {
            // Default to current month
            $year = date('Y');
            $monthNum = date('m');

            $queryCondition = function ($query) use ($year, $monthNum) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $monthNum);
            };
            $filterType = 'monthly';
            $filterDisplay = date('F Y');
        }

        // Get all complain types
        $complainTypes = ComplainType::where('status', true)->get();

        // Separate by type
        $complainTypeItems = $complainTypes->where('type', 'complain');
        $manualTypeItems = $complainTypes->where('type', 'manual');

        $complainStats = [];
        $manualStats = [];
        $complainTotal = 0;
        $manualTotal = 0;
        $complainAmount = 0;
        $manualAmount = 0;

        // Process COMPLAIN types
        foreach ($complainTypeItems as $type) {
            $typeName = strtolower(str_replace(' ', '_', $type->name));

            $complains = Complain::where('complain_type_id', $type->id)
                ->where('type', 'complain')
                ->where($queryCondition)
                ->get();

            $complainCount = $complains->count();
            $totalQuantity = $complains->sum('quantity');
            $totalAmount = $complains->sum(function ($complain) {
                return $complain->quantity * $complain->amount;
            });

            $complainStats[$typeName] = [
                'id' => $type->id,
                'name' => $type->name,
                'count' => $complainCount,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'type' => 'complain'
            ];

            $complainTotal += $complainCount;
            $complainAmount += $totalAmount;
        }

        // Process MANUAL types
        foreach ($manualTypeItems as $type) {
            $typeName = strtolower(str_replace(' ', '_', $type->name));

            $manuals = Complain::where('complain_type_id', $type->id)
                ->where('type', 'manual')
                ->where($queryCondition)
                ->get();

            $manualCount = $manuals->count();
            $totalQuantity = $manuals->sum('quantity');
            $totalAmount = $manuals->sum(function ($manual) {
                return $manual->quantity * $manual->amount;
            });

            $manualStats[$typeName] = [
                'id' => $type->id,
                'name' => $type->name,
                'count' => $manualCount,
                'total_quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'type' => 'manual'
            ];

            $manualTotal += $manualCount;
            $manualAmount += $totalAmount;
        }

        return response()->json([
            'success' => true,
            'filter_type' => $filterType,
            'filter_display' => $filterDisplay,
            'data' => [
                'complain' => $complainStats,
                'manual' => $manualStats,
                'totals' => [
                    'complain' => $complainTotal,
                    'manual' => $manualTotal,
                    'overall' => $complainTotal + $manualTotal,
                    'complain_amount' => $complainAmount,
                    'manual_amount' => $manualAmount,
                    'overall_amount' => $complainAmount + $manualAmount
                ]
            ]
        ]);
    }
}
