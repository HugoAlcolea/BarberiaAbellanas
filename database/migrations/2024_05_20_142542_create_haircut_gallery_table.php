<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHaircutGalleryTable extends Migration
{
    public function up()
    {
        Schema::create('haircut_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('photo_name');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('barber_id');
            $table->unsignedBigInteger('hairstyle_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('custom_users')->onDelete('cascade');
            $table->foreign('barber_id')->references('id')->on('barberos')->onDelete('cascade');
            $table->foreign('hairstyle_id')->references('id')->on('estilos_de_cortes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('haircut_gallery');
    }
}

