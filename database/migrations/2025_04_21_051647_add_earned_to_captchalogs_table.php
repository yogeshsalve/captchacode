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
            $table->decimal('earned', 8, 2)->default(0)->after('status'); // Adjust 'after' to match your table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('captcha_logs', function (Blueprint $table) {
            $table->dropColumn('earned');
        });
    }
};
