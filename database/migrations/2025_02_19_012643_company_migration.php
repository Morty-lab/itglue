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
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Approval Information
            $table->string('approval_status')->default('pending');
            $table->text('admin_feedback')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Company Details
            $table->string('company_name');
            $table->string('primary_number');
            $table->string('secondary_number')->nullable();

            // Headquarters Information

            $table->string('hq_location_name');
            $table->string('hq_address');
            $table->string('hq_city');
            $table->string('hq_state');
            $table->string('hq_postal_code');
            $table->string('hq_country');
            $table->string('hq_province')->nullable();
            $table->string('hq_fax');
            $table->string('hq_website');
            $table->time('hq_opening_time');
            $table->time('hq_closing_time');

            // Other Information
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
