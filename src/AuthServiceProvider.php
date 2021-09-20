<?php

namespace EscolaLms\Templates;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Policies\TemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Template::class => TemplatePolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
