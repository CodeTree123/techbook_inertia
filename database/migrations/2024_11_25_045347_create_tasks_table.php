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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wo_id')->constrained('work_orders')->onDelete('cascade');
            $table->bigInteger('tech_id')->nullable();
            $table->enum('type',['call','collect_signature','custom_task','download_file','shipping_details','send_email','upload_file','upload_photo'])->default('custom_task');
            $table->text('reason')->nullable();
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('from')->nullable();
            $table->string('item')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
