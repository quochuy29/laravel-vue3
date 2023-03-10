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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->date('date');
            $table->time('start_time', 1);
            $table->time('end_time', 1);
            $table->string('duration', 200);
            $table->string('request_type_code', 200)->index();
            $table->string('request_type_name', 200)->index();
            $table->string('reason', 200);
            $table->string('user_create_name')->index();
            $table->string('user_create_code')->index();
            $table->string('user_approve_name')->index();
            $table->string('user_approve_code')->index();
            $table->string('status_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
