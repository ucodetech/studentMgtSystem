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
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('admin_uniqueid', 15);
            $table->string('admin_fullname');
            $table->string('admin_email')->unique();
            $table->string('admin_tel', 15)->unique();
            $table->tinyInteger('email_verified')->default(0);
            $table->string('password');
            $table->string('admin_photo')->default('default.png');
            $table->enum('status', ['active','inactive'])->default('inactive');
            $table->timestamp('admin_last_login')->default(Carbon::now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
