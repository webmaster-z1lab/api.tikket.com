<?php

namespace App\Providers;

use App\Validator\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
            $messages += [
                'cell_phone'  => 'O campo :attribute não é um possui o formato válido de celular com DDD',
                'cnpj'        => 'O campo :attribute não é um CNPJ válido',
                'cpf'         => 'O campo :attribute não é um CPF válido',
                'bool_custom' => 'O campo :attribute deve ser verdadeiro ou falso',
            ];

            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
