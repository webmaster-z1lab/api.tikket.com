<?php

use App\Mail\Organizer\EventPublishedMail;

Route::view('/', 'cover');

Route::get('test', static function () {
    $event = \Modules\Event\Models\Event::find('5d71128b805c1e08400026c2');

    $params = [
        'action'  => config('app.main_site_url')."/evento/{$event->id}",
        'text'    => "O seu evento {$event->name} acaba de ser publicado e está pronto para venda de ingressos.",
        'title'   => 'Evento publicado com sucesso',
        'icon'    => 'far fa-calendar-check',
        'color'   => 'info',
        'sent_at' => now(),
    ];

    return new EventPublishedMail($event, $params);
});
