<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('mysql')->create('records', function (Blueprint $table) {
            $table->id();

            $table->date('date')->index();
            $table->string('venue', 50)->nullable();
            $table->char('io', 1)->nullable()->index();
            $table->char('ageGroupID', 3)->nullable()->index();
            $table->char('gender', 1)->nullable()->index();
            $table->char('typeID', 5)->nullable()->index();
            $table->string('name', 20)->nullable()->index();
            $table->string('extra', 10)->nullable()->index();
            $table->string('competitor', 80)->nullable();
            $table->char('teamID', 4)->nullable()->index();
            $table->string('result', 10)->nullable();
            $table->text('note')->nullable();
            $table->string('wind', 10)->nullable();
            $table->string('date2', 15)->nullable();
            $table->tinyInteger('current')->default(1);
            $table->integer('distance')->nullable()->index();
            $table->unsignedInteger('athleteID')->nullable()->index();
            $table->smallInteger('points')->nullable()->index();
            $table->decimal('resultValue', 7, 2)->nullable()->index();
            $table->unsignedInteger('resultID')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('records');
    }
};