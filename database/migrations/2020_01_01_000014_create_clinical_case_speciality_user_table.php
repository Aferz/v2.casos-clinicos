<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicalCaseSpecialityUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinical_case_speciality_user', function (Blueprint $table) {
            $table->foreignId('clinical_case_speciality_id');
            $table->foreignId('user_id');

            $table->foreign('clinical_case_speciality_id', 'ccsu_foreign')
                ->references('id')
                ->on('clinical_case_specialities');

            $table->foreign('user_id')
                ->references('id')
                ->on('clinical_cases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinical_case_specialities');
    }
}
