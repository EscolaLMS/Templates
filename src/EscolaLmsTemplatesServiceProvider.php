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

        Event::listen('EscolaLms*', function ($eventName, array $data) {
            app(TemplateEventListener::class)->handle($data[0]);
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->extendResources();
    }

    private function extendResources(): void
    {
        if (!class_exists(\EscolaLms\Auth\EscolaLmsAuthServiceProvider::class)) {
            return;
        }

        \EscolaLms\Auth\Http\Resources\UserResource::extend(fn($thisObj) => [
            'notification_channel' => json_decode($thisObj->notification_channel),
        ]);

        \EscolaLms\Auth\Dtos\UserUpdateDto::extendConstructor([
            'notification_channel' => fn ($request) => json_encode($request->input('notification_channel')),
        ]);

        \EscolaLms\Auth\Dtos\UserUpdateDto::extendToArray([
            'notification_channel' => fn ($thisObj) => $thisObj->notification_channel
        ]);
    }
}
