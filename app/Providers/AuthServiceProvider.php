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
    }
}
