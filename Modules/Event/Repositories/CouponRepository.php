<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 22/02/2019
 * Time: 11:39
 */

namespace Modules\Event\Repositories;

use Carbon\Carbon;
use Modules\Event\Models\Coupon;
use Z1lab\JsonApi\Repositories\ApiRepository;

class CouponRepository extends ApiRepository
{
    /**
     * CouponRepository constructor.
     *
     * @param \Modules\Event\Models\Coupon $model
     */
    public function __construct(Coupon $model)
    {
        parent::__construct($model, 'coupon');
    }

    /**
     * @param array $data
     *
     * @return \Modules\Event\Models\Coupon
     */
    public function create(array $data)
    {
        $data['valid_until'] = Carbon::createFromFormat('Y-m-d', $data['valid_until']);
        $data['is_percentage'] = $data['is_percentage'] === 'false' ? false : (bool) $data['is_percentage'];
        if (!$data['is_percentage']) $data['discount'] = (int) ($data['discount'] * 100);

        /** @var \Modules\Event\Models\Coupon $coupon */
        $coupon = $this->model->create($data);

        $coupon->entrance()->associate($data['entrance_id']);

        $coupon->save();

        $coupon->event()->associate($coupon->entrance->event);

        $coupon->save();

        $this->setCacheKey($coupon->id);
        $this->remember($coupon);

        return $coupon;
    }

    /**
     * @param array  $data
     * @param string $id
     *
     * @return \Modules\Event\Models\Coupon
     */
    public function update(array $data, string $id)
    {
        /** @var \Modules\Event\Models\Coupon $coupon */
        $coupon = $this->find($id);

        $data['valid_until'] = Carbon::createFromFormat('Y-m-d', $data['valid_until']);
        $data['is_percentage'] = $data['is_percentage'] === 'false' ? false : (bool) $data['is_percentage'];
        if (!$data['is_percentage']) $data['discount'] = (int) ($data['discount'] * 100);

        $coupon->update($data);

        $coupon->entrance()->associate($data['entrance_id']);

        $coupon->save();

        $coupon->event()->associate($coupon->entrance->event);

        $coupon->save();

        $this->setCacheKey($id);
        $this->flush()->remember($coupon);

        return $coupon->fresh();
    }

    /**
     * @param string $event
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|\Illuminate\Support\Collection
     */
    public function getByEvent(string $event)
    {
        return $this->model->where('event_id', $event)->get();
    }
}
