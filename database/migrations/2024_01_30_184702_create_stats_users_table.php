<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('haircuts')->default(0);
            $table->unsignedInteger('points')->default(0);
            $table->foreignId('user_id')->constrained('custom_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stats_users');
    }
}
