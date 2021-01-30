<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\SiteConfiguration;

class ShowConfigPage extends Action
{
    public function __invoke(SiteConfiguration $config)
    {
        $this->authorizeUserTo('show', $config);

        return view('web.config.index');
    }
}
