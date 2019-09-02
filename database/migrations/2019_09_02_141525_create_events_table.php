<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('events')) Schema::create('events');

        Schema::table('events', function (Blueprint $collection) {
            $collection->index(
                [
                    'name'                => 'text',
                    'description'         => 'text',
                    'category'            => 'text',
                    'address.city'        => 'text',
                    'address.state'       => 'text',
                    'address.postal_code' => 'text',
                ],
                'full-text-events'
                , NULL,
                [
                    'weights' => [
                        'name'                => 32,
                        'description'         => 8,
                        'category'            => 4,
                        'address.city'        => 4,
                        'address.state'       => 4,
                        'address.postal_code' => 4,
                    ],
                    'name'    => 'full-text-events',
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $collection) {
            $collection->dropIndex('full-text-users');
        });
    }
}
