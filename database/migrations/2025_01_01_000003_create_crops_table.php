<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('variety')->nullable();
            $table->date('planting_date');
            $table->date('expected_harvest_date');
            $table->date('actual_harvest_date')->nullable();
            $table->decimal('yield_kg', 10, 2)->nullable();
            $table->enum('status', ['growing', 'harvested', 'failed'])->default('growing');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
