<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->string('truck_plate')->nullable()->unique();
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->string('driver_name')->nullable()->unique();
        });

        Schema::table('cargo_types', function (Blueprint $table) {
            $table->string('cargo_type')->nullable()->unique();
        });

        Schema::table('hubs', function (Blueprint $table) {
            $table->string('hub_name')->nullable()->unique();
        });

        Schema::table('cold_trips', function (Blueprint $table) {
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
        });
    }

    public function down(): void
    {
        Schema::table('cold_trips', function (Blueprint $table) {
            $table->dropConstrainedForeignId('truck_id');
            $table->dropConstrainedForeignId('driver_id');
            $table->dropConstrainedForeignId('cargo_type_id');
            $table->dropConstrainedForeignId('departure_hub_id');
            $table->dropConstrainedForeignId('destination_hub_id');
            $table->dropColumn([
                'required_temp_c',
                'min_logged_temp',
                'max_logged_temp',
                'dispatch_time',
                'arrival_time',
                'temp_compliance_status',
            ]);
        });

        Schema::table('trucks', function (Blueprint $table) {
            $table->dropUnique('trucks_truck_plate_unique');
            $table->dropColumn('truck_plate');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropUnique('drivers_driver_name_unique');
            $table->dropColumn('driver_name');
        });

        Schema::table('cargo_types', function (Blueprint $table) {
            $table->dropUnique('cargo_types_cargo_type_unique');
            $table->dropColumn('cargo_type');
        });

        Schema::table('hubs', function (Blueprint $table) {
            $table->dropUnique('hubs_hub_name_unique');
            $table->dropColumn('hub_name');
        });
    }
};
