<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 16/03/2019
 * Time: 10:14
 */

namespace App\Traits;

use Modules\Event\Models\Event;

trait EventValidator
{
    /**
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    public function is_valid(Event $event) : bool
    {
        $validator = \Validator::make($event->toArray(), [
            'name' => 'bail|required|string',
            'user_id' => 'bail|required|string',
            'url' => 'bail|required|string',
            'description' => 'bail|required|string',
            'body' => 'bail|required|string',
            'category'  => 'bail|required|string',
            'referer' => 'bail|required|url',

            'address' => 'bail|required|array',
            'address.name' => 'bail|required|string',
            'address.street' => 'bail|required|string',
            'address.number' => 'bail|required|integer|min:1',
            'address.district' => 'bail|required|string',
            'address.complement' => 'bail|nullable|string',
            'address.city' => 'bail|required|string',
            'address.state' => 'bail|required|string|size:2',
            'address.postal_code' => 'bail|required|digits:8',
            'address.formatted' => 'bail|required|string',
            'address.maps_url' => 'bail|required|url',
            'address.coordinate' => 'bail|required|array',
        ]);

        $validator->after(function ($validator) use ($event) {
            if ($event->starts_at->lte(today()))
                $validator->errors()->add('starts_at', '');
            if ($event->finishes_at->lte(today()) || $event->finishes_at->lte($event->starts_at))
                $validator->errors()->add('finishes_at', '');
            if (!$event->producer()->exists())
                $validator->errors()->add('producer', '');
            if (!$event->image()->exists())
                $validator->errors()->add('image', '');
            if (!$event->entrances()->exists())
                $validator->errors()->add('entrances', '');

            /** @var \Modules\Event\Models\Entrance $entrance */
            foreach ($event->entrances as $entrance) {
                $prev = today();
                /** @var \Modules\Event\Models\Lot $lot */
                foreach ($entrance->lots as $lot) {
                    if ($lot->finishes_at->lt($prev))
                        $validator->errors()->add('entrances', '');
                    $prev = $lot->finishes_at;
                }
            }
        });

        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return FALSE;
        }

        return TRUE;
    }
}
