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
        Schema::create('Subjects', function (Blueprint $table) {
            $table->id(); 
            $table->string('Subject_Code');
            $table->string('Description');
            $table->integer('Lec');
            $table->integer('Lab');
            $table->integer('Units');
            $table->string('Pre_Req')->nullable();
            $table->string('Year_Level');
            $table->integer('Semester');
            $table->string('College');
            $table->string('Department');
            $table->string('Program');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Subjects');
    }
};
