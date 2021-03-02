<?php

namespace Database\Seeders;

use App\Models\ClinicalCaseSpeciality;
use App\Models\Country;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        $this->createCountries();
        $this->createSpecialities();
        $this->createClinicalCaseSpecialities();
        $this->createAdmins();
        $this->createCoordinators();
    }

    protected function createCountries(): void
    {
        Country::insert([
            ['id' => 33, 'name' => 'Brasil'],
            ['id' => 46, 'name' => 'Chile'],
            ['id' => 52, 'name' => 'Colombia'],
            ['id' => 60, 'name' => 'Costa Rica'],
            ['id' => 65, 'name' => 'República Dominicana'],
            ['id' => 66, 'name' => 'Ecuador'],
            ['id' => 68, 'name' => 'El Salvador'],
            ['id' => 94, 'name' => 'Guatemala'],
            ['id' => 102, 'name' => 'Honduras'],
            ['id' => 146, 'name' => 'México'],
            ['id' => 157, 'name' => 'Nicaragua'],
            ['id' => 170, 'name' => 'Panamá'],
            ['id' => 173, 'name' => 'Perú'],
        ]);
    }

    protected function createSpecialities(): void
    {
        Speciality::insert([
            ['id' => 3, 'name' => __('Anesthesiology')],
            ['id' => 4, 'name' => __('Family and Community Medicine')],
            ['id' => 12, 'name' => __('Endocrinology')],
            ['id' => 21, 'name' => __('Internal Medicine')],
            ['id' => 22, 'name' => __('Preventive medicine and public health')],
            ['id' => 25, 'name' => __('Neurosurgery')],
            ['id' => 26, 'name' => __('Neurology')],
            ['id' => 29, 'name' => __('Medical Oncology')],
            ['id' => 33, 'name' => __('Rheumatology')],
            ['id' => 37, 'name' => __('Orthopedic Surgery and Traumatology')],
            ['id' => 43, 'name' => __('General medicine')],
        ]);
    }

    protected function createClinicalCaseSpecialities(): void
    {
        ClinicalCaseSpeciality::insert([
            ['id' => 1, 'name' => __('Endocrine')],
            ['id' => 2, 'name' => __('Nutritionist')],
            ['id' => 3, 'name' => __('Surgical')],
            ['id' => 4, 'name' => __('Pediatric')],
            ['id' => 5, 'name' => __('Oncologist')],
            ['id' => 6, 'name' => __('Geriatric')],
            ['id' => 7, 'name' => __('Digestive')],
        ]);
    }

    protected function createAdmins(): void
    {
        User::create([
            'name' => 'Admin',
            'lastname1' => 'Admin',
            'email' => 'admin@casosclinicos.com',
            'password' => bcrypt('Password1%'),
            'is_admin' => true,
        ]);
    }

    protected function createCoordinators(): void
    {
        $coordinator1 = User::create([
            'name' => 'Coordinador',
            'lastname1' => '1',
            'email' => 'coordinator@casosclinicos.com',
            'password' => bcrypt('Password1%'),
            'is_coordinator' => true,
        ]);

        $coordinator1->clinicalCaseSpecialities()
            ->attach([1, 2, 3, 4]);

        $coordinator2 = User::create([
            'name' => 'Coordinador',
            'lastname1' => '2',
            'email' => 'coordinator2@casosclinicos.com',
            'password' => bcrypt('Password1%'),
            'is_coordinator' => true,
        ]);

        $coordinator2->clinicalCaseSpecialities()
            ->attach([5, 6, 7]);
    }
}
