<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('lecturer_uniqueid', 15);
            $table->string('lecturer_fullname');
            $table->string('lecturer_email')->unique();
            $table->string('lecturer_tel', 15)->unique();
            $table->tinyInteger('email_verified')->default(0);
            $table->string('lecturer_department');
            $table->string('password');
            $table->string('lecturer_photo')->default('default.png');
            $table->enum('status', ['active','inactive'])->default('inactive');
            $table->timestamp('lecturer_last_login')->default(Carbon::now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
