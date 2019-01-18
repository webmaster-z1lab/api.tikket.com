<?php

namespace App\Listeners;

use App\Events\ImageDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteImage implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param \App\Events\ImageDeleted $event
     */
    public function handle(ImageDeleted $event)
    {
        \Storage::delete($event->getPath());
    }

    /**
     * Handle a job failure.
     *
     * @param  ImageDeleted $event
     * @param  \Exception   $exception
     * @return void
     */
    public function failed(ImageDeleted $event, $exception)
    {
        //
    }
}
