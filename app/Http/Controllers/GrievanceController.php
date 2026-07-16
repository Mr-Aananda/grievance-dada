<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrievanceRequest;
use App\Models\Grievance;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

        // ── JSON response for Vue (AJAX table fetch) ──
        if ($request->expectsJson()) {
            if (empty($search)) {
                return response()->json([
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => 0,
                    'status_counts' => ['submitted' => 0, 'under_review' => 0, 'in_resolution' => 0, 'resolved' => 0],
                ]);
            }

            // Strict exact match to secure public tracking page
            $grievances = Grievance::with(['category', 'department'])
                ->where('ticket_number', $search)
                ->paginate(10);

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
                'status_counts' => ['submitted' => 0, 'under_review' => 0, 'in_resolution' => 0, 'resolved' => 0],
            ]);
        }

        // ── Blade response (initial page load) ────────────────
        $categories = Category::where('status', true)->orderBy('name')->get();
        $departments = Department::where('status', true)->orderBy('name')->get();
        $statusCounts = ['submitted' => 0, 'under_review' => 0, 'in_resolution' => 0, 'resolved' => 0];

        return view('grievance-portal', compact(
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

    /**
     * GET /grievance-media/{media}
     *
     * Streams a media file from the configured disk (grievance_media)
     * directly to the browser — image, video, or document.
     *
     * Images / videos → inline (browser can display / play)
     * PDFs            → inline (browser renders in built-in PDF viewer)
     * Other documents → forced download (Content-Disposition: attachment)
     */
    public function stream(Request $request, Media $media): \Symfony\Component\HttpFoundation\Response
    {
        $path = $media->getPath();

        abort_unless(file_exists($path), 404, 'File not found.');

        $mime = $media->mime_type ?: (mime_content_type($path) ?: 'application/octet-stream');
        $fileName = $media->file_name;
        $size = filesize($path);

        $view = $request->has('view');
        $download = $request->has('download');

        // On production environments, redirect office documents to Google Docs Web Viewer for inline reading
        if ($view && (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet') || str_contains($mime, 'word') || str_contains($mime, 'officedocument') || str_contains($mime, 'powerpoint') || str_contains($mime, 'presentation'))) {
            $appUrl = config('app.url');
            if (!preg_match('/(localhost|127\.0\.0\.1|\.test)/i', $appUrl)) {
                $publicUrl = route('grievance.media.stream', ['media' => $media->id, 'download' => 1]);
                return redirect('https://docs.google.com/viewer?url=' . urlencode($publicUrl) . '&embedded=true');
            }
        }

        if ($download) {
            $isInline = false;
        } elseif ($view) {
            $isInline = true;
        } else {
            $isInline = $this->shouldBeInline($mime);
        }

        $disposition = $isInline
            ? 'inline; filename="' . addslashes($fileName) . '"'
            : 'attachment; filename="' . addslashes($fileName) . '"';

        // Support byte-range requests so <video> elements can seek
        $start = 0;
        $end = $size - 1;
        $status = 200;

        $rangeHeader = $request->header('Range');
        if ($rangeHeader && preg_match('/bytes=(\d*)-(\d*)/i', $rangeHeader, $m)) {
            $start = $m[1] !== '' ? (int) $m[1] : 0;
            $end = $m[2] !== '' ? (int) $m[2] : $size - 1;
            $end = min($end, $size - 1);
            $status = 206;
        }

        $length = $end - $start + 1;

        $headers = [
            'Content-Type' => $mime,
            'Content-Length' => $length,
            'Content-Disposition' => $disposition,
            'Content-Range' => "bytes {$start}-{$end}/{$size}",
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ];

        return response()->stream(function () use ($path, $start, $length) {
            $handle = fopen($path, 'rb');

            if ($start > 0) {
                fseek($handle, $start);
            }

            $remaining = $length;
            $chunkSize = 8192;

            while (!feof($handle) && $remaining > 0) {
                $read = min($chunkSize, $remaining);
                $chunk = fread($handle, $read);
                echo $chunk;
                $remaining -= strlen($chunk);
                ob_flush();
                flush();
            }

            fclose($handle);
        }, $status, $headers);
    }

    // ── Private helpers ────────────────────────────────────────

    /**
     * Images, videos, and PDFs are rendered inline by the browser.
     * Everything else triggers a download.
     */
    private function shouldBeInline(string $mime): bool
    {
        $mime = strtolower($mime);
        return str_starts_with($mime, 'image/')
            || str_starts_with($mime, 'video/')
            || $mime === 'application/pdf'
            || str_contains($mime, 'pdf');
    }

    private function resolveCollection(string $mime): string
    {
        if (str_starts_with($mime, 'image/'))
            return 'grievance_images';
        if (str_starts_with($mime, 'video/'))
            return 'grievance_videos';
        return 'grievance_documents';
    }

    /**
     * Admin list view for tracking grievances.
     */
    public function adminIndex(Request $request): View
    {
        $query = Grievance::with(['category', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $grievances = $query->latest()->paginate(30)->withQueryString();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $departments = Department::where('status', true)->orderBy('name')->get();

        return view('pages.grievance.index', compact('grievances', 'categories', 'departments'));
    }

    /**
     * Admin detail view.
     */
    public function adminShow($id): View
    {
        $grievance = Grievance::with(['category', 'department'])->findOrFail($id);
        return view('pages.grievance.show', compact('grievance'));
    }

    /**
     * Admin status update.
     */
    public function adminUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:submitted,under_review,in_resolution,resolved',
            'admin_remarks' => 'nullable|string|max:5000',
        ]);

        $grievance = Grievance::findOrFail($id);
        $grievance->status = $request->status;
        $grievance->admin_remarks = $request->admin_remarks;

        if ($request->status === 'resolved') {
            $grievance->resolved_at = now();
        } else {
            $grievance->resolved_at = null;
        }

        $grievance->save();

        return redirect()->route('admin.grievance.show', $id)->with('success', 'Grievance status updated successfully.');
    }

    /**
     * Admin delete.
     */
    public function adminDestroy($id)
    {
        $grievance = Grievance::findOrFail($id);
        $grievance->delete();

        return redirect()->route('admin.grievance.index')->with('success', 'Grievance deleted successfully.');
    }
}
