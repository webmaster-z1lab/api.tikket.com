<?php

$levels = [
    \Modules\Event\Models\Permission::MASTER    => [
        'name'        => 'Master',
        'value'       => \Modules\Event\Models\Permission::MASTER,
        'description' => 'descrição',
    ],
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
        [
            'value' => 'congress',
            'name'  => 'Congresso',
        ],
        [
            'value' => 'class',
            'name'  => 'Curso',
        ],
        [
            'value' => 'party',
            'name'  => 'Festa',
        ],
        [
            'value' => 'networking',
            'name'  => 'Networking',
        ],
        [
            'value' => 'show',
            'name'  => 'Show',
        ],
        [
            'value' => 'workshop',
            'name'  => 'Workshop',
        ],
    ],
    'levels'      => $levels,
    'permissions' => [
        \Modules\Event\Models\Permission::MASTER    => array_values(\Illuminate\Support\Arr::except($levels, \Modules\Event\Models\Permission::MASTER)),
        \Modules\Event\Models\Permission::ORGANIZER => array_values(\Illuminate\Support\Arr::except($levels,
            [\Modules\Event\Models\Permission::MASTER, \Modules\Event\Models\Permission::ORGANIZER])),
    ],
];
