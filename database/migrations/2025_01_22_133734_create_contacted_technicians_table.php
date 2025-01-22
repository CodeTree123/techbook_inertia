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
        Schema::create('contacted_technicians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wo_id')->constrained('work_orders')->onDelete('cascade');
            $table->bigInteger('tech_id');
            $table->string('tech_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('message')->nullable();
            $table->string('res_note')->nullable();
            $table->tinyInteger('is_responded')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacted_technicians');
    }
};
