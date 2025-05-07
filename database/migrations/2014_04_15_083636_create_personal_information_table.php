<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personal_information', function (Blueprint $table) {
            $table->id();
            $table->string('matrix_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->date('birth_date');
            $table->enum('gender', ['woman', 'man']);
            $table->string('address');
            $table->string('phone_number');
            $table->string('departement');
            $table->string('study_program');
            $table->integer('entry_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_information');
    }
};
