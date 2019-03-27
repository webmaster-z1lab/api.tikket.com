<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Services\SummaryService;

class SummaryController extends Controller
{
    /**
     * @var \Modules\Report\Services\SummaryService
     */
    private $service;

    /**
     * SummaryController constructor.
     *
     * @param \Modules\Report\Services\SummaryService $service
     */
    public function __construct(SummaryService $service)
    {
        $this->service = $service;
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function basic(string $event)
    {
        return $this->makeResource($this->service->basic($event));
    }

    /**
     * @param $obj
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    private function makeResource($obj)
    {
        return api_resource('Summary')->make($obj);
    }
}
