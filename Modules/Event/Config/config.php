<?php

return [
    'name'        => 'Event',
    'categories'  => [
        ['name' => 'show'],
        ['name' => 'festa'],
    ],
    'permissions' => [
        \Modules\Event\Models\Permission::MASTER    => [
            [
                'name'        => 'Organizador',
                'value'       => \Modules\Event\Models\Permission::ORGANIZER,
                'description' => 'descrição',
            ],
            [
                'name'        => 'Check-in',
                'value'       => \Modules\Event\Models\Permission::CHECKIN,
                'description' => 'descrição',
            ],
            [
                'name'        => 'Ponto de venda',
                'value'       => \Modules\Event\Models\Permission::PDV,
                'description' => 'descrição',
            ],
        ],
        \Modules\Event\Models\Permission::ORGANIZER => [
            [
                'name'        => 'Check-in',
                'value'       => \Modules\Event\Models\Permission::CHECKIN,
                'description' => 'descrição',
            ],
            [
                'name'        => 'Ponto de venda',
                'value'       => \Modules\Event\Models\Permission::PDV,
                'description' => 'descrição',
            ],
        ],
    ],
];
