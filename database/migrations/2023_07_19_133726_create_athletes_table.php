<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('mysql')->create('athletes', function (Blueprint $table) {
            $table->id();

            $table->string('firstName', 20)->index();
            $table->string('lastName', 50)->index();
            $table->date('dateOfBirth')->nullable();
            $table->char('gender', 1)->index();
            $table->tinyInteger('showResult')->default(0);

            $table->boolean('uploaded')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('athletes');
    }
};