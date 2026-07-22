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
        Schema::disableForeignKeyConstraints();

        Schema::create('locations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('locations');
            $table->foreignId('org_corp_id')->nullable()->constrained('org_corps');
            $table->string('name');
            $table->string('type');
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('location_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->enum('type', ['primary', 'secondary', 'temporary'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('attributes')->nullable();
            $table->morphs('assignable');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_assignments');
        Schema::dropIfExists('locations');
    }
};
