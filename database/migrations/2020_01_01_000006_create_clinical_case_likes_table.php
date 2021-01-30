<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicalCaseLikesTable extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_case_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('clinical_case_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('clinical_case_id')
                ->references('id')
                ->on('clinical_cases');
        });
    }

    public function down(): void
    {
        //
    }
}
