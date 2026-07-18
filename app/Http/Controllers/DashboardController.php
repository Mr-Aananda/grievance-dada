<?php

namespace App\Http\Controllers;

use App\Models\Grievance;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        // Get grievance statistics
        $stats = [
            'today_total' => Grievance::whereDate('created_at', $today)->count(),
            'submitted' => Grievance::where('status', 'submitted')->count(),
            'under_review' => Grievance::where('status', 'under_review')->count(),
            'in_resolution' => Grievance::where('status', 'in_resolution')->count(),
            'resolved' => Grievance::where('status', 'resolved')->count(),
            'total' => Grievance::count(),
        ];

        // Get yesterday's total for growth calculation
        $yesterday = Carbon::yesterday()->toDateString();
        $yesterdayTotal = Grievance::whereDate('created_at', $yesterday)->count();
        $todayGrowth = $yesterdayTotal > 0 ?
            round((($stats['today_total'] - $yesterdayTotal) / $yesterdayTotal) * 100, 2) :
            ($stats['today_total'] > 0 ? 100 : 0);

        // Get today's resolved grievances
        $todayResolved = Grievance::whereDate('created_at', $today)
            ->where('status', 'resolved')
            ->count();

        // Get grievance count grouped by categories
        $categoryStats = Category::where('status', true)
            ->withCount('grievances')
            ->get()
            ->map(fn($cat) => [
                'name' => $cat->name,
                'count' => $cat->grievances_count,
            ])
            ->toArray();

        // Get grievance count grouped by departments
        $departmentStats = Department::where('status', true)
            ->withCount('grievances')
            ->get()
            ->map(fn($dept) => [
                'name' => $dept->name,
                'count' => $dept->grievances_count,
            ])
            ->toArray();

        // Sort statistics descending by count
        usort($categoryStats, fn($a, $b) => $b['count'] <=> $a['count']);
        usort($departmentStats, fn($a, $b) => $b['count'] <=> $a['count']);

        // Get grievance trend for last 7 days
        $trendLabels = [];
        $trendCreated = [];
        $trendResolved = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $label = Carbon::now()->subDays($i)->format('d M');
            $trendLabels[] = $label;
            
            $trendCreated[] = Grievance::whereDate('created_at', $date)->count();
            $trendResolved[] = Grievance::whereDate('created_at', $date)
                ->where('status', 'resolved')
                ->count();
        }

        $recentGrievances = Grievance::with(['category', 'department'])->latest()->take(6)->get();

        return view('dashboard', compact(
            'stats',
            'todayResolved',
            'todayGrowth',
            'categoryStats',
            'departmentStats',
            'recentGrievances',
            'trendLabels',
            'trendCreated',
            'trendResolved'
        ));
    }
}
