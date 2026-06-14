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
        Schema::create('user_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Account Information
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('account_number');

            // Account Identifiers
            $table->string('iban')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('transit_number')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('bic_code')->nullable();

            // Bank Address
            $table->string('bank_address')->nullable();
            $table->string('bank_city')->nullable();
            $table->string('bank_state')->nullable();
            $table->string('bank_country')->nullable();
            $table->string('bank_postal_code')->nullable();

            // Settings
            $table->string('currency', 10)->default('USD');
            $table->boolean('is_primary')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bank_accounts');
    }
};