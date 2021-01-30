<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicalCaseMediaTable extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_case_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_case_id');
            $table->string('type');
            $table->string('path');
            $table->text('description')->nullable();
            $table->timestamps();

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
