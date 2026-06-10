<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplainRequest;
use App\Models\Buyer;
use App\Models\Category;
use App\Models\Complain;
use App\Models\ComplainType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ComplainController extends Controller
{
    public function index()
    {
        $query = Complain::with(['user', 'complainType', 'category', 'buyer']);

        // Search filters
        if (request()->search == '1') {
            // Full-text search on complain field
            if (request()->filled('complain_text')) {
                $searchTerm = request()->complain_text;
                $query->whereRaw("MATCH(complain) AGAINST(? IN BOOLEAN MODE)", [$searchTerm]);
            }
            if (request()->filled('complain_type_id')) {
                $query->where('complain_type_id', request()->complain_type_id);
            }
            if (request()->filled('category_id')) {
                $query->where('category_id', request()->category_id);
            }
            if (request()->filled('subject')) {
                $query->where('subject', 'like', '%' . request()->subject . '%');
            }
            if (request()->filled('ps')) {
                $query->where('ps', 'like', '%' . request()->ps . '%');
            }
            if (request()->filled('buyer_id')) {
                $query->where('buyer_id', request()->buyer_id);
            }
            if (request()->filled('type')) {
                $query->where('type', request()->type);
            }
            if (request()->filled('status')) {
                $query->where('status', request()->status);
            }
            if (request()->filled('ps')) {
                $query->where('ps', 'like', '%' . request()->ps . '%');
            }
            if (request()->filled('po')) {
                $query->where('po', 'like', '%' . request()->po . '%');
            }
            if (request()->filled('cap')) {
                $query->where('cap', 'like', '%' . request()->cap . '%');
            }
            if (request()->filled('date_from')) {
                $query->whereDate('date', '>=', request()->date_from);
            }
            if (request()->filled('date_to')) {
                $query->whereDate('date', '<=', request()->date_to);
            }
        }

        $complains = $query->latest()->paginate(30)->withQueryString();
        $complainTypes = ComplainType::where('status', true)->orderBy('name')->get();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $buyers = Buyer::where('status', true)->orderBy('company_name')->get();
        $statuses = config('complains.statuses', []);

        return view('pages.complain.index', compact(
            'complains',
            'complainTypes',
            'categories',
            'buyers',
            'statuses'
        ));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'complain');

        // Only get complain types where type = 'complain'
        $complainTypes = ComplainType::where('status', true)
            ->where('type', 'complain')  // Only complain types
            ->get();

        return view('pages.complain.create', [
            'complainTypes' => $complainTypes,
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'buyers' => Buyer::where('status', true)->orderBy('company_name')->get(),
            'statuses' => config('complains.statuses', []),
            'type' => $type,
            'pageTitle' => 'Add New Complain',
            'formType' => 'complain'
        ]);
    }

    public function manual(Request $request)
    {
        $type = $request->get('type', 'manual');

        // Only get complain types where type = 'manual'
        $complainTypes = ComplainType::where('status', true)
            ->where('type', 'manual')  // Only manual types
            ->get();

        return view('pages.complain.create', [
            'complainTypes' => $complainTypes,
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'buyers' => Buyer::where('status', true)->orderBy('company_name')->get(),
            'statuses' => config('complains.statuses', []),
            'type' => $type,
            'pageTitle' => 'Add New Manual',
            'formType' => 'manual'
        ]);
    }

    public function store(ComplainRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            // Create the complaint
            $complain = Complain::create([
                'type' => $request->type,
                'complain_type_id' => $request->complain_type_id,
                'category_id' => $request->category_id,
                'subject' => $request->subject,
                'buyer_id' => $request->buyer_id,
                'manual_category' => $request->manual_category,
                'date' => $request->date,
                'name' => $request->name,
                'ps' => $request->ps,
                'po' => $request->po,
                'cap' => $request->cap,
                'style_order' => $request->style_order,
                'quantity' => $request->quantity,
                'amount' => $request->amount,
                'line_floor' => $request->line_floor,
                'complain' => $request->complain,
                'note' => $request->note,
                'status' => $request->status ?? 'pending',
                'user_id' => Auth::id(),
                'video_count' => 0,
                'has_videos' => false,
            ]);

            // Handle Files (Images + Documents)
            if ($request->hasFile('new_files')) {
                Log::info('New files found: ' . count($request->file('new_files')));

                foreach ($request->file('new_files') as $index => $file) {
                    Log::info('Processing file ' . $index . ': ' . $file->getClientOriginalName());

                    if (!$file->isValid()) {
                        Log::error('Invalid file: ' . $file->getClientOriginalName());
                        continue;
                    }

                    try {
                        // Check if it's an image
                        $isImage = str_starts_with($file->getMimeType(), 'image/');

                        if ($isImage) {
                            // Add to images collection
                            $media = $complain->addMedia($file)
                                ->usingName($file->getClientOriginalName())
                                ->toMediaCollection('images');
                        } else {
                            // Add to documents collection
                            $media = $complain->addMedia($file)
                                ->usingName($file->getClientOriginalName())
                                ->toMediaCollection('documents');
                        }

                        Log::info('File added successfully. Media ID: ' . $media->id .
                            ' | Type: ' . ($isImage ? 'Image' : 'Document'));
                    } catch (\Exception $e) {
                        Log::error('Error adding file: ' . $e->getMessage());
                    }
                }
            } else {
                Log::info('No new files in request');
            }

            // Handle Videos
            $videoCount = 0;
            if ($request->hasFile('videos')) {
                Log::info('Video files found: ' . count($request->file('videos')));

                foreach ($request->file('videos') as $videoFile) {
                    $validator = Validator::make(
                        ['video' => $videoFile],
                        ['video' => 'required|file|mimes:mp4,avi,mov,wmv,mkv,webm,flv,3gp,mpeg,quicktime|max:524288000']
                    );

                    if ($validator->fails()) {
                        throw new \Exception('Invalid video file: ' . $validator->errors()->first());
                    }

                    try {
                        $media = $complain->addMedia($videoFile)
                            ->usingName($videoFile->getClientOriginalName())
                            ->toMediaCollection('videos');

                        $videoCount++;
                        Log::info('Video added successfully: ' . $videoFile->getClientOriginalName());
                    } catch (\Exception $e) {
                        Log::error('Error adding video: ' . $e->getMessage());
                        throw $e;
                    }
                }
            } else {
                Log::info('No video files in request');
            }

            // Update video count
            $complain->update([
                'video_count' => $videoCount,
                'has_videos' => $videoCount > 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'complain_id' => $complain->id,
                'redirect' => route('complain.index'),
                'message' => $request->type === 'complain' ? 'Complain saved successfully!' : 'Manual entry saved successfully!'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Complain store error: ' . $e->getMessage());
            Log::error('Complain store trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $complain = Complain::with([
            'user',
            'updatedBy',
            'complainType',
            'category',
            'buyer'
        ])->findOrFail($id);

        return view('pages.complain.show', compact('complain'));
    }

    public function edit($id)
    {
        $complain = Complain::with(['user', 'complainType', 'category', 'buyer'])->findOrFail($id);

        $allFiles = $complain->files_data;
        $videos = $complain->videos_data;

        return view('pages.complain.edit', [
            'complain' => $complain,
            'complainTypes' => ComplainType::where('status', true)->orderBy('name')->get(),
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'buyers' => Buyer::where('status', true)->orderBy('company_name')->get(),
            'statuses' => config('complains.statuses', []),
            'existingFiles' => $allFiles,
            'existingVideos' => $videos,
        ]);
    }

    public function update(ComplainRequest $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $complain = Complain::findOrFail($id);

            // Handle deleted files (images + documents)
            $deletedFileIds = $request->input('deleted_files', []);
            if (!empty($deletedFileIds)) {
                Log::info('Deleting files: ' . json_encode($deletedFileIds));

                // Delete from both collections
                $complain->media()
                    ->whereIn('id', $deletedFileIds)
                    ->whereIn('collection_name', ['images', 'documents'])
                    ->each(function ($media) {
                        Log::info('Deleting file: ' . $media->id . ' - ' . $media->name);
                        $media->delete();
                    });
            }

            // Handle deleted videos
            $deletedVideoIds = $request->input('deleted_videos', []);
            if (!empty($deletedVideoIds)) {
                Log::info('Deleting videos: ' . json_encode($deletedVideoIds));

                $complain->media()
                    ->where('collection_name', 'videos')
                    ->whereIn('id', $deletedVideoIds)
                    ->each(function ($media) {
                        Log::info('Deleting video: ' . $media->id . ' - ' . $media->name);
                        $media->delete();
                    });
            }

            // Update complaint data
            $complain->update([
                'type' => $request->type,
                'complain_type_id' => $request->complain_type_id,
                'category_id' => $request->category_id,
                'subject' => $request->subject,
                'buyer_id' => $request->buyer_id,
                'manual_category' => $request->manual_category,
                'date' => $request->date,
                'name' => $request->name,
                'ps' => $request->ps,
                'po' => $request->po,
                'cap' => $request->cap,
                'style_order' => $request->style_order,
                'quantity' => $request->quantity,
                'amount' => $request->amount,
                'line_floor' => $request->line_floor,
                'complain' => $request->complain,
                'note' => $request->note,
                'status' => $request->status ?? 'pending',
                'updated_id' => Auth::id()
            ]);

            // Handle new files (images + documents)
            if ($request->hasFile('new_files')) {
                Log::info('New files found in update: ' . count($request->file('new_files')));

                foreach ($request->file('new_files') as $index => $file) {
                    try {
                        // Check if it's an image
                        $isImage = str_starts_with($file->getMimeType(), 'image/');

                        if ($isImage) {
                            // Add to images collection
                            $media = $complain->addMedia($file)
                                ->usingName($file->getClientOriginalName())
                                ->toMediaCollection('images');

                            Log::info('Added new image: ' . $file->getClientOriginalName());
                        } else {
                            // Add to documents collection
                            $media = $complain->addMedia($file)
                                ->usingName($file->getClientOriginalName())
                                ->toMediaCollection('documents');

                            Log::info('Added new document: ' . $file->getClientOriginalName());
                        }
                    } catch (\Exception $e) {
                        Log::error('Error adding new file: ' . $e->getMessage());
                    }
                }
            }

            // Handle new videos
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $videoFile) {
                    $validator = Validator::make(
                        ['video' => $videoFile],
                        ['video' => 'required|file|mimes:mp4,avi,mov,wmv,mkv,webm,flv,3gp,mpeg,quicktime|max:524288000']
                    );

                    if ($validator->fails()) {
                        throw new \Exception('Invalid video file: ' . $validator->errors()->first());
                    }

                    $complain->addMedia($videoFile)
                        ->usingName($videoFile->getClientOriginalName())
                        ->toMediaCollection('videos');

                    Log::info('Added new video: ' . $videoFile->getClientOriginalName());
                }
            }

            // Update video count
            $videoCount = $complain->getMedia('videos')->count();
            $complain->update([
                'video_count' => $videoCount,
                'has_videos' => $videoCount > 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('complain.index'),
                'message' => $request->type === 'complain' ? 'Complaint updated successfully!' : 'Manual entry updated successfully!'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Complain update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $complain = Complain::findOrFail($id);

            // Set deleted_by before soft delete
            $complain->deleted_by = auth()->id();
            $complain->save();

            // Soft delete
            $complain->delete();

            return redirect()->route('complain.index')
                ->with('success', 'Entry moved to trash successfully');

        } catch (\Throwable $e) {
            Log::error('Complain delete error: ' . $e->getMessage());

            return redirect()->route('complain.index')
                ->with('error', 'Failed to delete entry: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in_progress,resolved,closed',
            'note' => 'required|string|max:1000'
        ]);

        try {
            $complain = Complain::findOrFail($id);

            DB::beginTransaction();

            // Update complain status
            $complain->update([
                'status' => $request->status,
                'status_note' => $request->note,
                'updated_id' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('complain.index')
                ->with('success', 'Status updated successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Status update error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Stream image file
     */
    public function streamImage($complainId, $imageId)
    {
        try {
            $complain = Complain::findOrFail($complainId);

            $image = $complain->media()
                ->where('collection_name', 'images')
                ->findOrFail($imageId);

            $filePath = $image->getPath();

            if (!file_exists($filePath)) {
                // Try alternative path
                $alternativePath = 'D:/qms_media_storage/' . $image->id . '/' . $image->file_name;
                if (file_exists($alternativePath)) {
                    $filePath = $alternativePath;
                } else {
                    return $this->returnPlaceholderImage();
                }
            }

            return response()->stream(function () use ($filePath) {
                readfile($filePath);
            }, 200, [
                'Content-Type' => $image->mime_type ?? 'image/jpeg',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } catch (\Exception $e) {
            Log::error('Image stream error: ' . $e->getMessage());
            return $this->returnPlaceholderImage();
        }
    }


    /**
     * Download file (images, documents, videos)
     */
    public function downloadFile($complainId, $fileId)
    {
        try {
            $complain = Complain::findOrFail($complainId);

            // Try to find in all collections (images, documents, videos)
            $file = $complain->media()
                ->whereIn('collection_name', ['images', 'documents', 'videos'])
                ->findOrFail($fileId);

            // Check permissions if needed
            // if (!auth()->user()->can('view', $complain)) {
            //     abort(403, 'Unauthorized access');
            // }

            $filePath = $file->getPath();

            // Log for debugging
            Log::info('Attempting to download file:', [
                'file_id' => $fileId,
                'complain_id' => $complainId,
                'file_path' => $filePath,
                'file_exists' => file_exists($filePath)
            ]);

            // Alternative path for production (if your files are stored in D drive)
            if (!file_exists($filePath)) {
                // Try to find the file in your D drive location based on your media library configuration
                $alternativePath = 'D:/qms_media_storage/' . $file->id . '/' . $file->file_name;

                if (file_exists($alternativePath)) {
                    $filePath = $alternativePath;
                    Log::info('Using alternative path: ' . $alternativePath);
                } else {
                    // Try another common pattern
                    $alternativePath2 = 'D:/qms_media_storage/' . $file->getKey() . '/' . $file->file_name;
                    if (file_exists($alternativePath2)) {
                        $filePath = $alternativePath2;
                        Log::info('Using alternative path 2: ' . $alternativePath2);
                    }
                }
            }

            if (!file_exists($filePath)) {
                Log::error('File not found at any location for ID: ' . $fileId);
                abort(404, 'File not found');
            }

            // Get the original file name
            $fileName = $file->file_name;

            // Determine mime type
            $mimeType = $file->mime_type;
            if (!$mimeType) {
                $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
            }

            return response()->download(
                $filePath,
                $fileName,
                [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    'Content-Length' => filesize($filePath),
                    'Cache-Control' => 'no-cache, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]
            );

        } catch (\Exception $e) {
            Log::error('File download error: ' . $e->getMessage());
            Log::error('File download trace: ' . $e->getTraceAsString());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to download file: ' . $e->getMessage()
                ], 500);
            }

            abort(500, 'Unable to download file');
        }
    }


    /**
     * Stream video file
     */
    public function streamVideo($complainId, $videoId)
    {
        try {
            $complain = Complain::findOrFail($complainId);

            $video = $complain->media()
                ->where('collection_name', 'videos')
                ->findOrFail($videoId);

            $filePath = $video->getPath();

            if (!file_exists($filePath)) {
                // Try alternative path
                $alternativePath = 'D:/qms_media_storage/' . $video->id . '/' . $video->file_name;
                if (file_exists($alternativePath)) {
                    $filePath = $alternativePath;
                } else {
                    abort(404, 'Video not found');
                }
            }

            return response()->file($filePath, [
                'Content-Type' => $video->mime_type,
                'Content-Disposition' => 'inline; filename="' . $video->file_name . '"',
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'private, max-age=0, no-store',
            ]);
        } catch (\Exception $e) {
            Log::error('Video stream error: ' . $e->getMessage());
            abort(404, 'Video not found');
        }
    }

    /**
     * Download all attachments as ZIP
     */
    public function downloadAllAttachments($id)
    {
        try {
            $complain = Complain::findOrFail($id);

            // Check permissions
            if (!auth()->user()->can('view', $complain)) {
                abort(403, 'Unauthorized access');
            }

            $filename = 'complain_' . $complain->id . '_attachments_' . date('Y-m-d') . '.zip';
            $zipPath = storage_path('app/temp/' . $filename);

            // Ensure temp directory exists
            if (!is_dir(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0777, true);
            }

            // Create ZipArchive
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

                // Add images and documents
                $fileCount = 0;
                foreach ($complain->getMedia('images') as $media) {
                    $fileContent = file_get_contents($media->getPath());
                    if ($fileContent) {
                        $ext = pathinfo($media->file_name, PATHINFO_EXTENSION) ?: 'jpg';
                        $fileName = 'files/' . ($media->name ?? 'file_' . (++$fileCount) . '.' . $ext);
                        $zip->addFromString($fileName, $fileContent);
                    }
                }

                foreach ($complain->getMedia('documents') as $media) {
                    $fileContent = file_get_contents($media->getPath());
                    if ($fileContent) {
                        $ext = pathinfo($media->file_name, PATHINFO_EXTENSION) ?: 'bin';
                        $fileName = 'files/' . ($media->name ?? 'file_' . (++$fileCount) . '.' . $ext);
                        $zip->addFromString($fileName, $fileContent);
                    }
                }

                // Add videos
                $videoCount = 0;
                foreach ($complain->getMedia('videos') as $video) {
                    $videoContent = file_get_contents($video->getPath());
                    if ($videoContent) {
                        $videoName = 'videos/' . $video->name;
                        $zip->addFromString($videoName, $videoContent);
                        $videoCount++;
                    }
                }

                $zip->close();

                // Download the zip file
                return response()->download($zipPath, $filename)->deleteFileAfterSend(true);
            }

            return redirect()->back()->with('error', 'Failed to create download package.');

        } catch (\Exception $e) {
            Log::error('Download all attachments error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to download attachments.');
        }
    }

    public function trash()
    {
        $complains = Complain::onlyTrashed()->with(['complainType', 'category', 'user'])->latest()->paginate(25);
        return view('pages.complain.trash', compact('complains'));
    }

    public function restore($id)
    {
        try {
            $complain = Complain::onlyTrashed()->findOrFail($id);
            $complain->restore();

            return redirect()->route('complain.index')
                ->with('success', 'Entry restored successfully');

        } catch (\Throwable $e) {
            Log::error('Complain restore error: ' . $e->getMessage());

            return redirect()->route('complain.trash')
                ->with('error', 'Failed to restore entry: ' . $e->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        DB::beginTransaction();
        try {
            $complain = Complain::onlyTrashed()->findOrFail($id);

            // Force delete the complain (this will also delete all media files)
            $complain->forceDelete();

            DB::commit();

            return redirect()->route('complain.trash')
                ->with('success', 'Entry permanently deleted');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Complain permanent delete error: ' . $e->getMessage());

            return redirect()->route('complain.trash')
                ->with('error', 'Failed to permanently delete entry: ' . $e->getMessage());
        }
    }

    private function returnPlaceholderImage()
    {
        $placeholderPath = public_path('images/placeholder.jpg');

        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath, [
                'Content-Type' => 'image/jpeg',
                'Cache-Control' => 'public, max-age=3600',
            ]);
        }

        // Create dynamic placeholder
        $img = imagecreatetruecolor(800, 600);
        $bgColor = imagecolorallocate($img, 240, 240, 240);
        $textColor = imagecolorallocate($img, 150, 150, 150);
        imagefill($img, 0, 0, $bgColor);
        imagestring($img, 5, 300, 280, 'Image Loading...', $textColor);

        ob_start();
        imagejpeg($img);
        $imageData = ob_get_clean();
        imagedestroy($img);

        return response($imageData, 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
