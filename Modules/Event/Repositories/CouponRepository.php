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

        /** @var \Modules\Event\Models\Coupon $coupon */
        $coupon = $this->model->create($data);

        $coupon->entrance()->associate($data['entrance_id']);

        $coupon->save();

        $coupon = $coupon->fresh();

        $this->setCacheKey($coupon->id);
        $this->remember($coupon);

        return $coupon;
    }
}
