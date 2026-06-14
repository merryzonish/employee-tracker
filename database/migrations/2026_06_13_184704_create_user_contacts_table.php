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
        Schema::create('user_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Contact Information
            $table->string('type', 100);
            $table->string('label', 100)->nullable();
            $table->text('value');

            // Status
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->datetime('verified_at')->nullable();

            // Additional Info
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_contacts');
    }
};