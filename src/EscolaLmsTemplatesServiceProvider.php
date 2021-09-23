<?php

namespace EscolaLms\Templates;

use Illuminate\Support\ServiceProvider;
use EscolaLms\Templates\AuthServiceProvider;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\Templates\Repository\TemplateRepository;
use EscolaLms\Templates\Services\TemplateService;
use EscolaLms\Templates\Services\VariablesService;
use EscolaLms\Courses\Events\CourseCompleted;
use EscolaLms\Templates\Jobs\ProcessCertificate;
use function Illuminate\Events\queueable;
use Illuminate\Support\Facades\Event;

/**
 * SWAGGER_VERSION
 */

class EscolaLmsTemplatesServiceProvider extends ServiceProvider
{
    public $singletons = [
        TemplateRepositoryContract::class => TemplateRepository::class,
        TemplateServiceContract::class => TemplateService::class,
        VariablesServiceContract::class => VariablesService::class
    ];

    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
    }

    private function loadListeners()
    {
        Event::listen(function (CourseCompleted $event) {
            $course_id = $event->getCourse()->id;
            $user_id = $event->getUser()->id;
            ProcessCertificate::dispatch($course_id, $user_id);
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../views', 'templates');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadListeners();
    }
}
