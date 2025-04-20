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
        Schema::table('captcha_logs', function (Blueprint $table) {
            $table->string('entered_captcha')->nullable(); // Add entered_captcha field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('captcha_logs', function (Blueprint $table) {
            $table->dropColumn('entered_captcha'); // Drop the field if rolling back
        });
    }
};
