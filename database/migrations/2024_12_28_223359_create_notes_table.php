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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wo_id')->constrained('work_orders')->onDelete('cascade');
            $table->bigInteger('tech_id')->nullable();
            $table->bigInteger('related_note')->nullable();
            $table->bigInteger('team_id')->nullable();
            $table->enum('note_type',['general_notes','dispatch_notes','billing_notes','tech_support_notes','close_out_notes'])->default('general_notes');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
