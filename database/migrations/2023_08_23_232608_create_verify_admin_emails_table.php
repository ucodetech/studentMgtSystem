<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('verify_admin_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('admin_uniqueid')->unique();
            $table->string('token', 125);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verify_admin_emails');
    }
};
