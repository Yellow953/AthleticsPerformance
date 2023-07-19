<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();

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
            $table->tinyInteger('isActive')->default(1);
            $table->tinyInteger('isNew')->default(1);
            $table->timestamp('createDate')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};