<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mysql')->create('events', function (Blueprint $table) {
            $table->id();

            $table->string('name', 10)->nullable()->index();
            $table->char('typeID', 5)->nullable()->index();
            $table->string('extra', 10);
            $table->string('round', 3)->nullable();
            $table->char('ageGroupID', 3)->nullable()->index();
            $table->char('gender', 1)->nullable()->index();
            $table->unsignedBigInteger('meetingID')->nullable();
            $table->string('wind', 10)->nullable();
            $table->text('note')->nullable();
            $table->integer('distance')->nullable()->index();
            $table->unsignedInteger('masterMultiEventID')->nullable();
            $table->smallInteger('heat')->unsigned()->nullable();

            $table->boolean('uploaded')->default(false);

            $table->timestamps();
            $table->foreign('meetingID')->references('id')->on('meetings');
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('events');
    }
};
