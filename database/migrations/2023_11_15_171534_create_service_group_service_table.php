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
        Schema::create('service_service_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_group_id');
            $table->unsignedBigInteger('service_id');

            $table->foreign('service_group_id')->references('id')->on('service_groups')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_group_service');
    }
};
