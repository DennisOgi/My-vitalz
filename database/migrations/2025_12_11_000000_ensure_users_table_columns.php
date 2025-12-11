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
        Schema::table('users', function (Blueprint $table) {
            // Ensure gender column exists
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable();
            }
            
            // Ensure dob column exists
            if (!Schema::hasColumn('users', 'dob')) {
                $table->string('dob')->nullable();
            }

            // Ensure hospital column exists
            if (!Schema::hasColumn('users', 'hospital')) {
                $table->boolean('hospital')->default(0);
            }

            // Ensure other potentially missing columns exist
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('users', 'zip_code')) {
                $table->string('zip_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_pic')) {
                $table->string('profile_pic')->nullable();
            }
            if (!Schema::hasColumn('users', 'doctor')) {
                $table->boolean('doctor')->default(0);
            }
            if (!Schema::hasColumn('users', 'patient')) {
                $table->boolean('patient')->default(0);
            }
            if (!Schema::hasColumn('users', 'pharmacy')) {
                $table->boolean('pharmacy')->default(0);
            }
            if (!Schema::hasColumn('users', 'sales_rep')) {
                $table->boolean('sales_rep')->default(0);
            }
            if (!Schema::hasColumn('users', 'admin')) {
                $table->boolean('admin')->default(0);
            }
            if (!Schema::hasColumn('users', 'authen')) {
                $table->string('authen')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed as we are just ensuring columns exist
    }
};
