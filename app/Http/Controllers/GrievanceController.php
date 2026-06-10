<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrievanceRequest;
use App\Models\Grievance;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class GrievanceController extends Controller
{
    /**
     * GET /grievance
     * – Browser request  → blade view (initial page load)
     * – AJAX / JSON request → paginated JSON for Vue table
     */
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->value();
        $filterStatus = $request->string('status')->trim()->value();

        $query = Grievance::with(['category', 'department'])
            ->when($search, fn($q) => $q->where('ticket_number', 'like', "%{$search}%"))
            ->when($filterStatus, fn($q) => $q->where('status', $filterStatus))
            ->latest();

        $statusCounts = Grievance::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Ensure all status keys exist with a default of 0
        $statusCounts = array_merge(
            ['submitted' => 0, 'under_review' => 0, 'in_resolution' => 0, 'resolved' => 0],
            $statusCounts,
        );

        // ── JSON response for Vue (AJAX table fetch / filter) ──
        if ($request->expectsJson()) {
            $grievances = $query->paginate(10);

            return response()->json([
                'data' => $grievances->map(fn($g) => [
                    'id' => $g->id,
                    'ticket_number' => $g->ticket_number,
                    'category' => $g->category ? ['name' => $g->category->name] : null,
                    'department' => $g->department ? ['name' => $g->department->name] : null,
                    'status' => $g->status,
                    'status_label' => $g->status_label,
                    'created_at' => $g->created_at->toISOString(),
                ]),
                'current_page' => $grievances->currentPage(),
                'last_page' => $grievances->lastPage(),
                'total' => $grievances->total(),
                'status_counts' => $statusCounts,
            ]);
        }

        // ── Blade response (initial page load) ────────────────
        $categories = Category::where('status', true)->orderBy('name')->get();
        $departments = Department::where('status', true)->orderBy('name')->get();

        return view('worker-dashboard', compact(
            'categories',
            'departments',
            'statusCounts',
        ));
    }

    /**
     * POST /grievance
     * Accepts both regular form submit and AJAX (Vue SPA).
     */
    public function store(GrievanceRequest $request): JsonResponse
    {
        $grievance = Grievance::create([
            'ticket_number' => Grievance::generateTicketNumber(),
            'category_id' => $request->category_id,
            'department_id' => $request->department_id,
            'employee_id' => $request->employee_id,
            'description' => $request->description,
            'status' => 'submitted',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $mime = $file->getMimeType();
                $collection = $this->resolveCollection($mime);

                $grievance
                    ->addMedia($file)
                    ->usingName(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    ->toMediaCollection($collection, 'grievance_media');
            }
        }

        return response()->json([
            'success' => true,
            'ticket_number' => $grievance->ticket_number,
            'message' => 'Grievance submitted successfully.',
        ]);
    }

    /**
     * GET /grievance/{grievance}
     * Always JSON — called by Vue modal.
     */
    public function show(Grievance $grievance): JsonResponse
    {
        $grievance->load(['category', 'department']);

        return response()->json([
            'ticket_number' => $grievance->ticket_number,
            'category' => $grievance->category?->name ?? null,
            'department' => $grievance->department?->name ?? null,
            'employee_id' => $grievance->employee_id,
            'description' => $grievance->description,
            'status' => $grievance->status,
            'status_label' => $grievance->status_label,
            'status_badge' => $grievance->status_badge,
            'admin_remarks' => $grievance->admin_remarks,
            'submitted_at' => $grievance->created_at->format('d M Y, h:i A'),
            'resolved_at' => $grievance->resolved_at?->format('d M Y, h:i A'),
            'media' => $grievance->all_media_data,
        ]);
    }

    // ── Private helpers ────────────────────────────────────────

    private function resolveCollection(string $mime): string
    {
        if (str_starts_with($mime, 'image/'))
            return 'grievance_images';
        if (str_starts_with($mime, 'video/'))
            return 'grievance_videos';
        return 'grievance_documents';
    }
}
