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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('registration_number', 50);
            $table->string('make', 100);
            $table->string('model', 100);
            $table->year('year')->nullable();
            $table->string('color', 50)->nullable();
            $table->enum('vehicle_type', ['sedan', 'suv', 'hatchback', 'coupe', 'convertible', 'truck', 'van', 'motorcycle', 'other'])->default('sedan');
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid', 'cng', 'lpg'])->default('petrol');
            $table->unsignedInteger('mileage')->nullable();
            $table->string('vin_number', 50)->nullable();
            $table->string('insurance_number', 100)->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('registration_number');
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
