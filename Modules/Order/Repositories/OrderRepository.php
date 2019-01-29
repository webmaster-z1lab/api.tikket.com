<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 29/01/2019
 * Time: 18:42
 */

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;
use Z1lab\JsonApi\Repositories\ApiRepository;

class OrderRepository extends ApiRepository
{
    /**
     * OrderRepository constructor.
     *
     * @param \Modules\Order\Models\Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model, 'order');
    }
}
