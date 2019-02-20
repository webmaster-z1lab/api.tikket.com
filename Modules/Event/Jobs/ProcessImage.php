<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Event\Models\Image;
use Modules\Event\Services\ImageProcessor;


class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Modules\Event\Models\Image
     */
    protected $image;

    private $types = [
        'cover',
        'square',
        'thumbnail',
        'facebook_cover',
    ];

    /**
     * ProcessImage constructor.
     *
     * @param \Modules\Event\Models\Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image->fresh();
    }

    /**
     * @param \Modules\Event\Services\ImageProcessor $processor
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(ImageProcessor $processor)
    {
        $path = 'events/' . $this->image->event_id;

        $processor->setPath($path)->setSource($this->image->original);

        foreach ($this->types as $type) {
            $method = camel_case($type);

            if (method_exists($processor, $method)) $this->image->$type = $processor->$method();
        }

        $processor->destroyOriginal();
        $this->image->original = NULL;

        $this->image->save();
    }
}
