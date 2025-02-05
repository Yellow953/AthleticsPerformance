<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('mysql')->create('results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('eventID')->nullable();
            $table->unsignedBigInteger('competitorID')->nullable();
            $table->string('result', 10)->nullable();
            $table->tinyInteger('isHand')->nullable();
            $table->smallInteger('position')->nullable()->index();
            $table->string('wind', 10)->nullable();
            $table->text('note')->nullable();
            $table->smallInteger('points')->nullable()->index();
            $table->decimal('resultValue', 7, 2)->nullable()->index();
            $table->string('recordStatus', 2)->nullable();
            $table->smallInteger('heat')->unsigned()->nullable();
            $table->tinyInteger('isActive')->default(1);

            $table->boolean('uploaded')->default(false);

            $table->timestamps();
            $table->foreign('eventID')->references('id')->on('events');
            $table->foreign('competitorID')->references('id')->on('competitors');
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('results');
    }
};