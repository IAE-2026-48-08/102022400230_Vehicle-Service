<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('trip_reference')->unique();
            $table->string('requester_name');
            $table->string('destination');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('dispatch_status')->default('Dispatched');
            $table->foreignId('approved_by_sso_user_id')->nullable()->constrained('sso_users')->nullOnDelete();
            $table->string('approved_role')->nullable();
            $table->string('legacy_receipt_number')->nullable();
            $table->longText('legacy_xml_request')->nullable();
            $table->longText('legacy_xml_response')->nullable();
            $table->json('published_event_payload')->nullable();
            $table->string('published_event_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_dispatches');
    }
};
