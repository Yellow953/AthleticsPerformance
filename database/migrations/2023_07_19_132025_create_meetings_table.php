<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mysql')->create('meetings', function (Blueprint $table) {
            $table->id();
            $table->char('IDSecond', 10)->collation('latin1_swedish_ci');

            $table->char('ageGroupID', 3)->index();
            $table->string('name', 50)->nullable();
            $table->string('shortName', 20);
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->string('venue', 50)->nullable();
            $table->char('country', 3)->default('LBN')->nullable();
            $table->char('typeID', 3)->index()->nullable();
            $table->string('subgroup', 15);
            $table->string('picture', 20)->nullable();
            $table->string('picture2', 20)->nullable();
            $table->tinyInteger('isActive')->default(0);
            $table->tinyInteger('isNew')->default(0);

            $table->boolean('uploaded')->default(false);

            $table->timestamps();

            $table->char('io', 1)->default('O')->index();
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('meetings');
    }
};
