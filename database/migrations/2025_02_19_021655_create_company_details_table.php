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
        Schema::create('company_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id')->nullable()->constrained('company_information')->nullOnDelete();
            $table->unsignedBigInteger('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            //Connected tables
            $table->longText('employees')->nullable();
            $table->longText('devices')->nullable();
            $table->longText('branches')->nullable();

            $table->longText('software_licenses')->nullable();

            //Webpage Documents
            $table->string('credential_type')->nullable();
            $table->string('credential_name')->nullable();
            $table->string('credential_url')->nullable();
            $table->string('credential_username')->nullable();
            $table->string('credential_password')->nullable();
            $table->string('credential_mfa')->nullable();
            $table->text('credential_notes')->nullable();

            $table->longText('attachments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_details');
    }
};
