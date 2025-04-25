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
        Schema::create('trycaptcha', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')
                  ->constrained()                     // references users.id
                  ->onDelete('cascade');
            $table->string('status', 20);           // e.g. 'correct' or 'incorrect'
            $table->decimal('earned', 8, 2)->default(0.00);
            $table->timestamps();                   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trycaptcha');
    }
};
