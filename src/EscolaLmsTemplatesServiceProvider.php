<?php

namespace EscolaLms\Templates;

use EscolaLms\Templates\AuthServiceProvider;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\Templates\Repository\TemplateRepository;
use EscolaLms\Templates\Services\Contracts\TemplateChannelServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateEventServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateVariablesServiceContract;
use EscolaLms\Templates\Services\TemplateChannelService;
use EscolaLms\Templates\Services\TemplateEventService;
use EscolaLms\Templates\Services\TemplateService;
use EscolaLms\Templates\Services\TemplateVariablesService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * SWAGGER_VERSION
 */

class EscolaLmsTemplatesServiceProvider extends ServiceProvider
{
    public $singletons = [
        TemplateChannelServiceContract::class => TemplateChannelService::class,
        TemplateEventServiceContract::class => TemplateEventService::class,
        TemplateRepositoryContract::class => TemplateRepository::class,
        TemplateServiceContract::class => TemplateService::class,
        TemplateVariablesServiceContract::class => TemplateVariablesService::class,
    ];

    public function register()
    {
        $this->app->register(AuthServiceProvider::class);

        $this->app->singleton('escolalms-facade-templates', function ($app) {
            return app(TemplateEventService::class);
        });

        Event::listen('EscolaLms*', function ($eventName, array $data) {
            app(TemplateEventListener::class)->handle($data[0]);
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
