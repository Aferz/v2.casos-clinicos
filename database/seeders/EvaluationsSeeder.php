<?php

namespace Database\Seeders;

use App\Models\ClinicalCase;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class EvaluationsSeeder extends Seeder
{
    public function run()
    {
        $coordinators = User::where('is_coordinator', true)->get();
        $clinicalCases = ClinicalCase::whereNotNull('sent_at')->get();

        foreach ($clinicalCases as $clinicalCase) {
            if ($clinicalCase->isPublished()) {
                $this->createForPublishedClinicalCase($clinicalCase, $coordinators);
            } else {
                $this->createForSentClinicalCase($clinicalCase, $coordinators);
            }
        }
    }

    protected function createForPublishedClinicalCase(ClinicalCase $clinicalCase, Collection $coordinators): void
    {
        $criteria = array_keys(config('cc.evaluation.criteria'));
        $minToAllowPublication = config('cc.evaluation.min_to_allow_publication');
        $evaluationsDone = 0;

        foreach (range($minToAllowPublication, $coordinators->count() - 1) as $index) {
            if ($evaluationsDone >= $minToAllowPublication && random_int(0, 2) > 0) {
                continue;
            }

            foreach ($criteria as $criterion) {
                $this->createEvaluation($criterion, $clinicalCase, $coordinators[$index]);
            }

            $evaluationsDone++;
        }
    }

    protected function createForSentClinicalCase(ClinicalCase $clinicalCase, Collection $coordinators): void
    {
        foreach (range(0, $coordinators->count() - 1) as $index) {
            if (random_int(0, 2) > 0) {
                continue;
            }

            foreach (criteria() as $criterion) {
                $this->createEvaluation($criterion->name(), $clinicalCase, $coordinators[$index]);
            }
        }
    }

    protected function createEvaluation(string $criterion, ClinicalCase $clinicalCase, User $coordinator): void
    {
        create(Evaluation::class, [
            'criterion' => $criterion,
            'clinical_case_id' => $clinicalCase->id,
            'user_id' => $coordinator->id,
        ]);
    }
}
