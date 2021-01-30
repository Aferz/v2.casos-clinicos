<?php

use App\Models\SiteConfiguration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteConfigurationTable extends Migration
{
    public function up(): void
    {
        Schema::create('site_configuration', function (Blueprint $table) {
            $table->id();
            $table->timestamp('restrict_doctor_access_at')->nullable();
            $table->timestamp('restrict_coordinator_access_at')->nullable();
            $table->timestamp('restrict_clinical_case_creation_at')->nullable();
            $table->timestamp('restrict_clinical_case_evaluation_at')->nullable();
        });

        SiteConfiguration::create([
            'restrict_doctor_access_at' => null,
            'restrict_coordinator_access_at' => null,
            'restrict_clinical_case_creation_at' => null,
            'restrict_clinical_case_evaluation_at' => null,
        ]);
    }

    public function down(): void
    {
        //
    }
}
