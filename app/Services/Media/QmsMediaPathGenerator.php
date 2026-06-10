<?php
// app/Services/Media/QmsMediaPathGenerator.php

namespace App\Services\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class QmsMediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $year = date('Y', strtotime($media->created_at));
        $collectionName = $media->collection_name;
        $modelType = class_basename($media->model_type);

        return "{$collectionName}/{$year}/{$modelType}/{$media->model_id}/";
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
