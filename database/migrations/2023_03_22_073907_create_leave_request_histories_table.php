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
        Schema::create('leave_request_histories', function (Blueprint $table) {
            $table->id();
            $table->string('user_code')->index();
            $table->string('user_name')->index();
            $table->dateTime('transaction_time');
            $table->string('active')->default('Cộng phép năm');
            $table->float('amount');
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_request_histories');
    }
};
