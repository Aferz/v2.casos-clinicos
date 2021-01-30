<?php

namespace App\Providers;

use App\Models\SiteConfiguration;
use App\Services\Auth\EloquentSanedUserProvider;
use App\Services\EvaluationCriteria\EvaluationCriteriaCollection;
use App\Services\Features\FeaturesCollection;
use App\Services\Fields\FieldsCollection;
use App\Services\Saned\SanedUserSynchronizer;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class ClinicalCaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerSiteConfiguration();
        $this->registerCarbonLocale();
        $this->registerSanedUserProvider();
        $this->registerSanedUserSynchronizer();
        $this->registerFields();
        $this->registerEvaluationCriteria();
        $this->registerFeatures();
        $this->registerBladeClassesDirective();
        $this->registerBladeFeatureDirective();
    }

    public function boot()
    {
        $this->bootDatabaseSchema();
    }

    protected function registerSiteConfiguration(): void
    {
        $this->app->singleton(SiteConfiguration::class, function () {
            return SiteConfiguration::first();
        });
    }

    protected function registerCarbonLocale(): void
    {
        Carbon::setLocale(config('app.locale'));
    }

    protected function registerSanedUserProvider(): void
    {
        Auth::provider('eloquent-saned', function ($app, $config) {
            return new EloquentSanedUserProvider($app['hash'], $config['model']);
        });
    }

    protected function registerSanedUserSynchronizer(): void
    {
        $this->app->singleton(SanedUserSynchronizer::class, function () {
            return new SanedUserSynchronizer;
        });
    }

    protected function registerFields(): void
    {
        $fields = [];

        foreach (config('cc.fields') as $name => $data) {
            $class = Arr::pull($data, 'type');

            $this->app->instance("fields.$name", $fields[] = new $class($name, $data));
        }

        $this->app->instance('fields', new FieldsCollection($fields));
        $this->app->alias('fields', FieldsCollection::class);
    }

    protected function registerEvaluationCriteria(): void
    {
        $criteria = [];

        foreach (config('cc.evaluation.criteria') as $criterion => $data) {
            $class = Arr::pull($data, 'type');
            $defaults = config("cc.evaluation.defaults.$class");

            $this->app->instance("criteria.$criterion", $criteria[] = new $class($criterion, array_merge($defaults, $data)));
        }

        $this->app->instance('criteria', new EvaluationCriteriaCollection($criteria));
        $this->app->alias('criteria', EvaluationCriteriaCollection::class);
    }

    protected function registerFeatures(): void
    {
        $features = [];

        foreach (config('cc.features') as $name => $data) {
            $features[] = $class = Arr::pull($data, 'class');

            $this->app->instance($class, new $class($data));
            $this->app->alias($class, "features.$name");
        }

        $this->app->instance('features', new FeaturesCollection($features));
        $this->app->alias('features', FeaturesCollection::class);
    }

    protected function registerBladeClassesDirective(): void
    {
        Blade::directive('classes', function ($expression) {
            return "class=\"<?php echo(resolve_classes($expression)) ?>\"";
        });
    }

    protected function registerBladeFeatureDirective(): void
    {
        Blade::if('feature', function ($feature) {
            if (is_array($feature)) {
                foreach ($feature as $f) {
                    if (features($f)->enabled()) {
                        return true;
                    }
                }

                return false;
            }

            return features($feature)->enabled();
        });
    }

    protected function bootDatabaseSchema(): void
    {
        Schema::defaultStringLength(191);
    }
}
