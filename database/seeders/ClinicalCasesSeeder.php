<?php

namespace Database\Seeders;

use App\Models\ClinicalCase;
use App\Models\ClinicalCaseBibliography;
use App\Models\ClinicalCaseComment;
use App\Models\ClinicalCaseLike;
use App\Models\ClinicalCaseMedia;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class ClinicalCasesSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('id', '>', 3)->get();

        foreach ($users as $user) {
            $otherUsers = $users->filter(fn ($u) => $u->id !== $user->id);

            foreach (range(0, 5) as $index) {
                $clinicalCase = $this->createClinicalCase($user);

                $this->createClinicalCaseBibliographies($clinicalCase);
                $this->createClinicalCaseMedia($clinicalCase);
                $this->createClinicalCaseComments($clinicalCase, $otherUsers);
                $this->createClinicalCaseLikes($clinicalCase, $otherUsers);
            }
        }
    }

    protected function createClinicalCase(User $user): ClinicalCase
    {
        $isSent = random_int(0, 2) === 0;
        $isPublished = $isSent && random_int(0, 2) === 0;

        return create(ClinicalCase::class, [
            'user_id' => $user->id,
            'sent_at' => $isSent ? now() : null,
            'published_at' => $isPublished ? now() : null,
        ]);
    }

    protected function createClinicalCaseBibliographies(ClinicalCase $clinicalCase): void
    {
        create(ClinicalCaseBibliography::class, [
            'clinical_case_id' => $clinicalCase->id,
        ], [], random_int(0, 2));
    }

    protected function createClinicalCaseMedia(ClinicalCase $clinicalCase): void
    {
        create(ClinicalCaseMedia::class, [
            'clinical_case_id' => $clinicalCase->id,
        ], ['image'], random_int(1, 3));
    }

    protected function createClinicalCaseComments(ClinicalCase $clinicalCase, Collection $otherUsers): void
    {
        foreach (range(0, random_int(0, 2)) as $index) {
            $parentComent = create(ClinicalCaseComment::class, [
                'clinical_case_id' => $clinicalCase->id,
                'user_id' => $otherUsers->random()->id,
            ]);

            if (rand(0, 3) === 0) {
                foreach (range(1, 3) as $index) {
                    create(ClinicalCaseComment::class, [
                        'parent_comment_id' => $parentComent->id,
                        'clinical_case_id' => $clinicalCase->id,
                        'user_id' => $otherUsers->random()->id,
                    ]);
                }
            }
        }
    }

    protected function createClinicalCaseLikes(ClinicalCase $clinicalCase, Collection $otherUsers): void
    {
        $users = $otherUsers->shuffle()->slice(0, random_int(0, 2));

        foreach ($users as $user) {
            create(ClinicalCaseLike::class, [
                'clinical_case_id' => $clinicalCase->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
