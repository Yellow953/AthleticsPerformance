<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('mysql')->create('competitors', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('athleteID')->index()->nullable();
            $table->string('name', 30)->nullable()->index();
            $table->char('gender', 1)->index();
            $table->char('teamID', 4)->default('UNA')->index();
            $table->smallInteger('year');
            $table->char('ageGroupID', 3)->nullable()->index();

            $table->boolean('uploaded')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('competitors');
    }
};