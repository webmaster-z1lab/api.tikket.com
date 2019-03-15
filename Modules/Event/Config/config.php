<?php

$levels = [
    \Modules\Event\Models\Permission::ORGANIZER => [
        'name'        => 'Organizador',
        'value'       => \Modules\Event\Models\Permission::ORGANIZER,
        'description' => 'descrição',
    ],
    \Modules\Event\Models\Permission::CHECKIN   => [
        'name'        => 'Check-in',
        'value'       => \Modules\Event\Models\Permission::CHECKIN,
        'description' => 'descrição',
    ],
    \Modules\Event\Models\Permission::PDV       => [
        'name'        => 'Ponto de venda',
        'value'       => \Modules\Event\Models\Permission::PDV,
        'description' => 'descrição',
    ],
];

return [
    'name'        => 'Event',
    'categories'  => [
        ['name' => 'show'],
        ['name' => 'festa'],
    ],
    'levels'      => $levels,
    'permissions' => [
        \Modules\Event\Models\Permission::MASTER    => array_values($levels),
        \Modules\Event\Models\Permission::ORGANIZER => \Illuminate\Support\Arr::except($levels, \Modules\Event\Models\Permission::ORGANIZER),
    ],
];
