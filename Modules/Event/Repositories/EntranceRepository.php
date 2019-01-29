<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 16:20
 */

namespace Modules\Event\Repositories;

use Modules\Event\Models\Entrance;
use Z1lab\JsonApi\Repositories\ApiRepository;

class EntranceRepository extends ApiRepository
{
    /**
     * EntranceRepository constructor.
     *
     * @param \Modules\Event\Models\Entrance $model
     */
    public function __construct(Entrance $model)
    {
        parent::__construct($model, 'event');
    }

    /**
     * @param array $data
     *
     * @return \Modules\Event\Models\Entrance
     */
    public function create(array $data)
    {
        $entrance = $this->model->create($data);

        $entrance->lots()->createMany($data['lots']);

        $this->setCacheKey($entrance->id);
        $this->remember($entrance);

        return $entrance;
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Event\Models\Entrance
     */
    public function update(array $data, string $id)
    {
        $item = $this->find($id);
        $item->update($data);

        $item->lots()->delete();
        $item->lots()->createMany($data['lots']);

        $this->setCacheKey($id);
        $this->flush()->remember($item);

        return $item->fresh();
    }
}
