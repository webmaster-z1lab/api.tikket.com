<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 28/02/2019
 * Time: 15:08
 */

namespace App\Traits;

use Modules\Event\Models\Coupon;

trait AvailableCoupons
{
    /**
     * @param \Modules\Event\Models\Coupon $coupon
     */
    public function incrementUsed(Coupon $coupon)
    {
        if ($coupon->used === $coupon->quantity) abort(400, 'Coupon unavailable.');

        $coupon->increment('used', 1, ['is_locked' => TRUE]);
    }

    /**
     * @param \Modules\Event\Models\Coupon $coupon
     */
    public function decrementUsed(Coupon $coupon)
    {
        if ($coupon->used === 0) return;

        if ($coupon->used === 1)
            $coupon->decrement('used', 1, ['is_locked' => FALSE]);

        $coupon->decrement('used');
    }
}
