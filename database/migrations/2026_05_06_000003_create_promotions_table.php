<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $row) {
            $row->id();
            $row->string('title');
            $row->string('subtitle')->nullable();
            $row->text('description')->nullable();
            $row->string('button_text')->default('Jelajahi Sekarang');
            $row->string('button_link')->default('/katalog');
            $row->boolean('is_active')->default(true);
            $row->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
};
