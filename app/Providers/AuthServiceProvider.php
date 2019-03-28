<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Gate::define('master', 'Modules\Event\Policies\EventPolicy@master');

        \Gate::define('organizer', 'Modules\Event\Policies\EventPolicy@organizer');

        \Gate::define('checkin', 'Modules\Event\Policies\EventPolicy@checkin');

        \Gate::define('pdv', 'Modules\Event\Policies\EventPolicy@pdv');

        \Gate::define('sell', 'Modules\Event\Policies\EventPolicy@sell');

        \Gate::define('admin', 'Modules\Event\Policies\EventPolicy@admin');

        \Gate::define('cart_owner', '\Modules\Cart\Policies\CartPolicy@owner');

        \Gate::define('order_owner', 'Modules\Order\Policies\OrderPolicy@owner');

        \Gate::define('ticket_owner', 'Modules\Ticket\Policies\TicketPolicy@owner');

        \Gate::define('ticket_receiver', 'Modules\Ticket\Policies\TicketPolicy@receiver');
    }
}
