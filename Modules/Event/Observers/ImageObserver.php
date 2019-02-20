<?php

namespace Modules\Event\Observers;

use Modules\Event\Jobs\DeleteImage;
use Modules\Event\Jobs\ProcessImage;
use Modules\Event\Models\Image;

class ImageObserver
{
    private $types = [
        'cover',
        'square',
        'thumbnail',
        'facebook_cover',
    ];

    /**
     * Handle the image "created" event.
     *
     * @param  \Modules\Event\Models\Image $image
     *
     * @return void
     */
    public function created(Image $image)
    {
        ProcessImage::dispatch($image);
    }

    /**
     * Handle the image "updated" event.
     *
     * @param  \Modules\Event\Models\Image $image
     *
     * @return void
     */
    public function updated(Image $image)
    {
        if ($image->isDirty('original') && $image->original !== NULL) ProcessImage::dispatch($image);

        foreach ($this->types as $type) {
            if ($image->isDirty($type) && isset($image->getOriginal()[$type])) DeleteImage::dispatch($image->getOriginal()[$type]);
        }
    }
}
