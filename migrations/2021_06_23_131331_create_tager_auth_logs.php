<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagerAuthLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tager_auth_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->bigInteger('model_id')->unsigned()->nullable();
            $table->timestamps();
            $table->text('email')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('grant_type')->nullable();
            $table->boolean('auth_success')->default(false);
            $table->string('uuid')->nullable();
            $table->string('provider')->nullable();

            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tager_auth_logs');
    }
}
