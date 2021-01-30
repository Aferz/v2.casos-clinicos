<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicalCaseBibliographiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinical_case_bibliographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_case_id');
            $table->text('bibliography');
            $table->timestamps();

            $table->foreign('clinical_case_id')
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
        //
    }
}
