<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_case_id');
            $table->foreignId('user_id');
            $table->string('criterion');
            $table->string('value');
            $table->string('comment', 500)->nullable();
            $table->timestamps();

            $table->foreign('clinical_case_id')
                ->references('id')
                ->on('clinical_cases');

            $table->foreign('user_id')
                ->references('id')
                ->on('clinical_cases');
        });
    }

    public function down()
    {
        //
    }
}
