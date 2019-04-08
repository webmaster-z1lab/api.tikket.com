<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Event\Repositories\SearchRepository;

class SearchController extends Controller
{
    public const RESOURCE = 'Event';

    /**
     * @var \Modules\Event\Repositories\SearchRepository
     */
    private $repository;

    /**
     * SearchController constructor.
     *
     * @param  \Modules\Event\Repositories\SearchRepository  $repository
     */
    public function __construct(SearchRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function index()
    {
        return $this->collectResource($this->repository->search());
    }

    /**
     * @param $obj
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    private function collectResource($obj)
    {
        return api_resource(self::RESOURCE)->collection($obj);
    }
}
