<?php

namespace App\Livewire\SiteConfig;

use App\Models\SiteConfiguration;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class EditForm extends Component
{
    public ?string $restrictDoctorAccessAt = null;
    public ?string $restrictCoordinatorAccessAt = null;
    public ?string $restrictClinicalCaseCreationAt = null;
    public ?string $restrictClinicalCaseEvaluationAt = null;

    public function mount(SiteConfiguration $siteConfiguration): void
    {
        $this->prepareDate($siteConfiguration, 'restrictDoctorAccessAt');
        $this->prepareDate($siteConfiguration, 'restrictCoordinatorAccessAt');
        $this->prepareDate($siteConfiguration, 'restrictClinicalCaseCreationAt');
        $this->prepareDate($siteConfiguration, 'restrictClinicalCaseEvaluationAt');
    }

    public function render(): View
    {
        return view('livewire.site-config.edit-form');
    }

    /**
     * @return Redirector|RedirectResponse
     */
    public function save(SiteConfiguration $siteConfiguration)
    {
        $this->setDate($siteConfiguration, 'restrictDoctorAccessAt');
        $this->setDate($siteConfiguration, 'restrictCoordinatorAccessAt');
        $this->setDate($siteConfiguration, 'restrictClinicalCaseCreationAt');
        $this->setDate($siteConfiguration, 'restrictClinicalCaseEvaluationAt');

        $siteConfiguration->save();

        return redirect()->route('directory');
    }

    protected function prepareDate(
        SiteConfiguration $siteConfiguration,
        string $field
    ): void {
        $name = Str::snake($field);

        if ($value = $siteConfiguration->{$name}) {
            $this->{$field} = $value->format('Y-m-d');
        }
    }

    protected function setDate(
        SiteConfiguration $siteConfiguration,
        string $field
    ): void {
        $name = Str::snake($field);
        $value = $this->{$field};

        $siteConfiguration->{$name} = $value ? Carbon::create($value) : null;
    }
}
