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
        Schema::table('lecturers', function (Blueprint $table) {
            //
            $table->boolean('locked_out')->default(false);
            $table->timestamp('date_locked_out')->default(Carbon::now());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            //
            $table->boolean('locked_out')->default(false);
            $table->date('date_locked_out')->default(Carbon::now());
        });
    }
};
