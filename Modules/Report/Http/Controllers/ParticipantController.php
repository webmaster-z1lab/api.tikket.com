<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Report\Services\ParticipantService;

class ParticipantController extends Controller
{
    /**
     * @var \Modules\Report\Services\ParticipantService
     */
    private $service;

    /**
     * ParticipantController constructor.
     *
     * @param \Modules\Report\Services\ParticipantService $service
     */
    public function __construct(ParticipantService $service)
    {
        $this->service = $service;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $event
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function get(Request $request, string $event)
    {
        return $this->collectResource($this->service->search($request->only(['name', 'email', 'order', 'code']), $event));
    }

    /**
     * @param $obj
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    private function collectResource($obj)
    {
        return api_resource('Participant')->collection($obj);
    }
}
