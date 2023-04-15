<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->enum('status', ['active','pending', 'inactive'])->default('pending'); //status: active, pending, inactive
            $table->boolean('score_reporting')->default(false); // true = show score, false = hide score
            $table->timestamp('start_time');
            $table->timestamp('end_time')->default('2030-12-31 23:59:59');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
};
