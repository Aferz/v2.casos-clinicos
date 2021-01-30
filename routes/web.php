<?php

use App\Http\Actions\Web\DeleteUser;
use App\Http\Actions\Web\ExportClinicalCase;
use App\Http\Actions\Web\ExportClinicalCasesList;
use App\Http\Actions\Web\ExportUsersList;
use App\Http\Actions\Web\ShowClinicalCasePage;
use App\Http\Actions\Web\ShowClinicalCasesDirectoryPage;
use App\Http\Actions\Web\ShowClinicalCasesPage;
use App\Http\Actions\Web\ShowConfigPage;
use App\Http\Actions\Web\ShowCreateClinicalCasePage;
use App\Http\Actions\Web\ShowCreateUserPage;
use App\Http\Actions\Web\ShowEditClinicalCasePage;
use App\Http\Actions\Web\ShowEditUserPage;
use App\Http\Actions\Web\ShowUsersPage;
use App\Models\ClinicalCase;

$router->group([
    'middleware' => ['auth', 'saned.registered-in-service', 'user.prevent-login'],
], function ($router) {
    $router->get('/', ShowClinicalCasesDirectoryPage::class)
        ->name('directory');

    $router->get('/config', ShowConfigPage::class)
        ->name('config');

    $router->group([
        'prefix' => 'clinical-cases',
    ], function ($router) {
        $id = ClinicalCase::routeModelBindingIdField();

        $router->get('', ShowClinicalCasesPage::class)
            ->name('clinical-cases.index');

        $router->get('create', ShowCreateClinicalCasePage::class)
            ->name('clinical-cases.create');

        $router->get('export', ExportClinicalCasesList::class)
            ->name('clinical-cases.export-list');

        $router->get("{clinicalCase:$id}", ShowClinicalCasePage::class)
            ->name('clinical-cases.show');

        $router->get("{clinicalCase:$id}/edit", ShowEditClinicalCasePage::class)
            ->name('clinical-cases.edit');

        $router->get("{clinicalCase:$id}/export", ExportClinicalCase::class)
            ->name('clinical-cases.export');
    });

    $router->group([
        'prefix' => 'users',
    ], function ($router) {
        $router->get('', ShowUsersPage::class)
            ->name('users.index');

        $router->get('export', ExportUsersList::class)
            ->name('users.export-list');

        $router->get('create', ShowCreateUserPage::class)
            ->name('users.create');

        $router->delete('{user}', DeleteUser::class)
            ->name('users.delete');

        $router->get('{user}/edit', ShowEditUserPage::class)
            ->name('users.edit');
    });
});
