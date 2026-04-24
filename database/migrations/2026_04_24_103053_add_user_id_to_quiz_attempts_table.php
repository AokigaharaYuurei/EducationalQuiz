<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('quiz_attempts', function (Blueprint $table) {
        $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        $table->foreignId('subject_id')->after('user_id')->constrained()->onDelete('cascade');
        $table->integer('score');
        $table->integer('total_points');
        $table->float('percentage');
        $table->json('answers_data')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            //
        });
    }
};
