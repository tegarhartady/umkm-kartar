<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_bank_accounts', function (Blueprint $col) {
            $col->id();
            $col->string('bank_name');
            $col->string('account_number');
            $col->string('account_holder');
            $col->boolean('is_active')->default(true);
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_bank_accounts');
    }
};
