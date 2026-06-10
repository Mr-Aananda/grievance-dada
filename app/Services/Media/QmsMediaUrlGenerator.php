<?php

namespace App\Services\Media;

use Illuminate\Support\Facades\URL;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class QmsMediaUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $media = $this->media;

        if ($media->model_type === 'App\Models\Complain') {

            // ✅ IMAGE (signed + short expiry)
            if ($media->collection_name === 'images') {
                return URL::temporarySignedRoute(
                    'complain.image.stream',
                    now()->addMinutes(10),
                    [
                        'complain' => $media->model_id,
                        'image' => $media->id,
                    ]
                );
            }

            // ✅ DOCUMENT
            if ($media->collection_name === 'documents') {
                return URL::temporarySignedRoute(
                    'complain.file.download',
                    now()->addMinutes(20),
                    [
                        'complain' => $media->model_id,
                        'file' => $media->id,
                    ]
                );
            }

            // ✅ VIDEO
            if ($media->collection_name === 'videos') {
                return URL::temporarySignedRoute(
                    'complain.videos.stream',
                    now()->addMinutes(15),
                    [
                        'complain' => $media->model_id,
                        'video' => $media->id,
                    ]
                );
            }
        }

        return parent::getUrl();
    }
}
