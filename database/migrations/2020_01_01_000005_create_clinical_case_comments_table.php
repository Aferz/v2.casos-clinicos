<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicalCaseCommentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_case_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_case_id');
            $table->foreignId('user_id');
            $table->foreignId('parent_comment_id')->nullable();
            $table->string('comment', 1000);
            $table->timestamps();

            $table->foreign('clinical_case_id')
                ->references('id')
                ->on('clinical_cases');
        });

        Schema::table('clinical_case_comments', function (Blueprint $table) {
            $table->foreign('parent_comment_id')
                ->references('id')
                ->on('clinical_case_comments');
        });
    }

    public function down(): void
    {
        //
    }
}
