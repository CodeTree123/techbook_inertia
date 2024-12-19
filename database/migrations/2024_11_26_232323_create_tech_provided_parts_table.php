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
        Schema::create('tech_provided_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wo_id')->constrained('work_orders')->onDelete('cascade');
            $table->bigInteger('tech_id')->nullable();
            $table->string('part_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('price',8,2)->nullable();
            $table->decimal('amount',8,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_provided_parts');
    }
};
