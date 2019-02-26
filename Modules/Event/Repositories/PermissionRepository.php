<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 26/02/2019
 * Time: 11:37
 */

namespace Modules\Event\Repositories;

use Modules\Event\Models\Permission;
use Z1lab\JsonApi\Repositories\ApiRepository;

class PermissionRepository extends ApiRepository
{
    /**
     * PermissionRepository constructor.
     *
     * @param \Modules\Event\Models\Permission $model
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model, 'permission');
    }

    /**
     * @param array $data
     *
     * @return \Modules\Event\Models\Permission
     */
    public function create(array $data)
    {
        $data['parent_id'] = \Auth::id();

        /** @var \Modules\Event\Models\Permission $permission */
        $permission = $this->model->create(array_except($data, ['event_id']));

        $permission->event()->associate($data['event_id']);

        $permission->save();

        $this->setCacheKey($permission->id);
        $this->remember($permission);

        return $permission;
    }

    /**
     * @param string $event_id
     *
     * @return \Illuminate\Support\Collection
     */
    public function getByEvent(string $event_id)
    {
        return $this->model->where('event_id', $event_id)->get();
    }
}
