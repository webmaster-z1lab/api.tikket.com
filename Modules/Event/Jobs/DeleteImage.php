<?php

namespace Modules\Event\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var string
     */
    private $path;

    /**
     * DeleteImage constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Storage::delete($this->path);
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function failed(\Exception $e)
    {
        \Log::error($e->getMessage());
    }
}
