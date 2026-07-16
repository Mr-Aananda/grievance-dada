<?php
// app/Services/Media/QmsMediaPathGenerator.php

namespace App\Services\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class QmsMediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $year = $media->created_at ? date('Y', strtotime($media->created_at)) : date('Y');
        
        $ticketFolder = $media->model_id; // fallback
        
        if ($media->model) {
            if ($media->model instanceof \App\Models\Grievance) {
                $ticketFolder = $media->model->ticket_number;
            } elseif (isset($media->model->ticket_number)) {
                $ticketFolder = $media->model->ticket_number;
            }
        }
        
        // Clean ticket folder just in case it contains characters not allowed in path
        $ticketFolder = str_replace(['/', '\\'], '-', $ticketFolder);

        $newPath = "{$year}/{$ticketFolder}/";

        // Fallback checks for existing files uploaded under old path structures
        $diskPath = config('filesystems.disks.grievance_media.root', 'D:/grievance_media_storage');
        $fullNewPath = rtrim($diskPath, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $newPath) . $media->file_name;
        
        if (file_exists($fullNewPath)) {
            return $newPath;
        }
        
        // Fallback 1: Old custom path (from Complain model days)
        $collectionName = $media->collection_name;
        $modelType = class_basename($media->model_type);
        $oldCustomPath = "{$collectionName}/{$year}/{$modelType}/{$media->model_id}/";
        $fullOldCustomPath = rtrim($diskPath, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $oldCustomPath) . $media->file_name;
        if (file_exists($fullOldCustomPath)) {
            return $oldCustomPath;
        }

        // Fallback 2: Default Spatie incremental generator path (e.g. "id/")
        $defaultPath = "{$media->id}/";
        $fullDefaultPath = rtrim($diskPath, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $defaultPath) . $media->file_name;
        if (file_exists($fullDefaultPath)) {
            return $defaultPath;
        }

        return $newPath;
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive-images/';
    }

    
}
