<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('cold_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('cargo_type_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('departure_hub_id')->nullable()->constrained('hubs')->restrictOnDelete();
            $table->foreignId('destination_hub_id')->nullable()->constrained('hubs')->restrictOnDelete();
            $table->decimal('required_temp_c', 6, 2)->nullable();
            $table->decimal('min_logged_temp', 6, 2)->nullable();
            $table->decimal('max_logged_temp', 6, 2)->nullable();
            $table->dateTime('dispatch_time')->nullable();
            $table->dateTime('arrival_time')->nullable();
            $table->string('temp_compliance_status')->default('Pending');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('cold_trips');
    }
};
