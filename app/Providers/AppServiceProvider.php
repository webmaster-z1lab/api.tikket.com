<?php

namespace App\Providers;

use App\Validator\Validator;
use Carbon\Carbon;
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
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        Carbon::setLocale('pt_BR');

        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
            $messages += [
                'cell_phone'  => 'O campo :attribute não é um possui o formato válido de celular com DDD',
                'cnpj'        => 'O campo :attribute não é um CNPJ válido',
                'cpf'         => 'O campo :attribute não é um CPF válido',
                'bool_custom' => 'O campo :attribute deve ser verdadeiro ou falso',
                'full_name'   => 'O campo :attribute deve ser um nome completo.',
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
