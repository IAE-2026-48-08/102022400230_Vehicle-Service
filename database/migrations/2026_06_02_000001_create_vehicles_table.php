<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_code')->unique();
            $table->string('plate_number')->unique();
            $table->string('brand');
            $table->string('model');
            $table->string('vehicle_type');
            $table->unsignedInteger('capacity');
            $table->string('fuel_type');
            $table->enum('status', ['Available', 'In-Use', 'Maintenance'])->default('Available');
            $table->date('last_service_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
