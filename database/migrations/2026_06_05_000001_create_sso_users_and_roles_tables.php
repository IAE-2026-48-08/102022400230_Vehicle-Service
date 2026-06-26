<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('sso_users', function (Blueprint $table) {
            $table->id();
            $table->string('sso_subject')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->json('last_jwt_payload')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });

        Schema::create('role_sso_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sso_user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'sso_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_sso_user');
        Schema::dropIfExists('sso_users');
        Schema::dropIfExists('roles');
    }
};
