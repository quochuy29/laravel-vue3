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
        Schema::create('config_requests', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('type')->nullable();
            $table->string('request_type_code');
            $table->string('request_type_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_requests');
    }
};
