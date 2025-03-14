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
        Schema::create('company_information', function (Blueprint $table) {
            // Company Information
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('primary_number');
            $table->string('secondary_number')->nullable();

            //Headquarters Information
            $table->string('hq_address');
            $table->string('hq_phone');
            $table->string('hq_fax');
            $table->string('hq_website');
            $table->time('hq_opening_time');
            $table->time('hq_closing_time');

            //other information
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_information');
    }
};
