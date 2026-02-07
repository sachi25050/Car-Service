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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('service_type', ['car_wash', 'maintenance', 'repair', 'addon', 'other'])->default('car_wash');
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->decimal('base_price', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_appointment')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('category_id');
            $table->index('service_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
