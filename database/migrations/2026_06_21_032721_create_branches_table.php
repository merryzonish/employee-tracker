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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->nullable();

            $table->boolean('is_main')->default(false);

            $table->string('type')->nullable();

            $table->foreignId('country_id')->nullable()
                ->constrained('countries')
                ->nullOnDelete();

            $table->foreignId('state_id')->nullable()
                ->constrained('states')
                ->nullOnDelete();

            $table->foreignId('city_id')->nullable()
                ->constrained('cities')
                ->nullOnDelete();

            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();

            $table->enum('status', ['active', 'inactive', 'closed'])
                ->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};