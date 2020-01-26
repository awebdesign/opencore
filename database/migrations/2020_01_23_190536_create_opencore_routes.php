<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpencoreRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opencore_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method', 10);
            $table->string('uri', 191);
            $table->string('name', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->unique(['method', 'uri', 'name'], 'method_uri_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opencore_routes');
    }
}
