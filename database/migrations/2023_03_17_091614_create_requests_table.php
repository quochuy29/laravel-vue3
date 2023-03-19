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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code')->unique()->index();
            $table->string('calendar_code')->unique()->index();
            $table->string('request_type_code')->index();
            $table->string('request_type_name')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('duration', 200);
            $table->longText('reason');
            $table->string('approve_status');
            $table->string('user_create_name')->index();
            $table->string('user_create_code')->index();
            $table->string('user_approve_name')->index();
            $table->string('user_approve_code')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
