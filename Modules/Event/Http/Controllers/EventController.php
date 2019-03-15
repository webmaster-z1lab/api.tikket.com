<?php

namespace Modules\Event\Http\Controllers;

use Modules\Event\Http\Requests\AddressRequest;
use Modules\Event\Http\Requests\BasicInformationRequest;
use Modules\Event\Http\Requests\FeeRequest;
use Modules\Event\Repositories\EventRepository;
use Z1lab\JsonApi\Http\Controllers\ApiController;

class EventController extends ApiController
{
    /**
     * EventController constructor.
     *
     * @param \Modules\Event\Repositories\EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        parent::__construct($repository, 'Event');
    }

    /**
     * @param \Modules\Event\Http\Requests\BasicInformationRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function store(BasicInformationRequest $request)
    {
        $data = $request->validated();
        $data['cover'] = $request->file('cover')->store('events');

        return $this->makeResource($this->repository->create($data));
    }

    /**
     * @param \Modules\Event\Http\Requests\BasicInformationRequest $request
     * @param string                                               $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function update(BasicInformationRequest $request, string $id)
    {
        $data = $request->validated();
        $data['cover'] = $request->file('cover')->store('events');

        return $this->makeResource($this->repository->update($data, $id));
    }

    /**
     * @param string $url
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function findByUrl(string $url)
    {
        return $this->makeResource($this->repository->findByUrl($url));
    }

    /**
     * @param \Modules\Event\Http\Requests\AddressRequest $request
     * @param string                                      $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function address(AddressRequest $request, string $id)
    {
        return $this->makeResource($this->repository->setAddress($request->validated(), $id));
    }

//    /**
//     * @param \Modules\Event\Http\Requests\FeeRequest $request
//     * @param string                                  $id
//     *
//     * @return \Illuminate\Http\Resources\Json\Resource
//     */
//    public function fee(FeeRequest $request, string $id)
//    {
//        return $this->makeResource($this->repository->setFeeIsHidden($request->validated(), $id));
//    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function finilize(string $id)
    {
        return $this->makeResource($this->repository->finalize($id));
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function publish(string $id)
    {
        return $this->makeResource($this->repository->publish($id));
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function getByUser()
    {
        return $this->collectResource($this->repository->getByUser());
    }
}
