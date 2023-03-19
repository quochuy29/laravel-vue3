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
            $table->string('user_code')->index();
            $table->string('user_name')->index();
            $table->date('date');
            $table->time('checkin', 0);
            $table->time('checkout', 0);
            $table->string('unpaid_leave');
            $table->string('paid_leave');
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
