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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('Subject_Code')->nullable();
            $table->string('Description')->nullable();
            $table->integer('Lec')->nullable();
            $table->integer('Lab')->nullable();
            $table->integer('Units')->nullable();
            $table->string('Pre_Req')->nullable();
            $table->string('Year_Level')->nullable();
            $table->string('Semester')->nullable();
            $table->string('College')->nullable();
            $table->string('Department')->nullable();
            $table->string('Program')->nullable();
            $table->string('Academic_Year')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
