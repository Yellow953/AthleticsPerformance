<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('mysql')->create('competitors', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('athleteID')->nullable();
            $table->string('name', 30)->nullable()->index();
            $table->char('gender', 1)->index();
            $table->char('teamID', 4)->default('UNA')->index();
            $table->smallInteger('year');
            $table->char('ageGroupID', 3)->nullable()->index();

            $table->boolean('uploaded')->default(false);

            $table->timestamps();
            $table->foreign('athleteID')->references('id')->on('athletes');
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('competitors');
    }
};