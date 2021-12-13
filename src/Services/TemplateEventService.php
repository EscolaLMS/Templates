<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\Templates\Services\Contracts\TemplateChannelServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateEventServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateVariablesServiceContract;
use Illuminate\Support\Facades\Log;

class TemplateEventService implements TemplateEventServiceContract
{
    protected static array $templates = [];

    protected TemplateRepositoryContract $repository;
    protected TemplateChannelServiceContract $channelService;
    protected TemplateVariablesServiceContract $variableService;

    public function __construct(
        TemplateRepositoryContract $repository,
        TemplateVariablesServiceContract $variableService,
        TemplateChannelServiceContract $channelService
    ) {
        $this->repository = $repository;
        $this->variableService = $variableService;
        $this->channelService = $channelService;
    }

    public function register(string $eventClass, string $channelClass, string $variableClass): void
    {
        $this->channelService->register($channelClass);
        $this->variableService->registerForChannel($variableClass, $channelClass);

        if (!array_key_exists($eventClass, self::$templates) || !array_key_exists($channelClass, self::$templates[$eventClass])) {
            self::$templates[$eventClass][$channelClass] = $variableClass;
        }
    }

    public function getVariableClassName(string $eventClass, string $channelClass): ?string
    {
        return self::$templates[$eventClass][$channelClass] ?? null;
    }

    public function getRegisteredEvents(): array
    {
        return self::$templates;
    }

    public function getRegisteredEventsWithTokens(): array
    {
        $result = [];
        foreach (self::$templates as $event => $channels) {
            foreach ($channels as $channel => $variableClass) {
                $sections = [];
                $requiredVariables = [];
                foreach ($channel::sections() as $section => $type) {
                    $sections[$section] = [
                        'type' => $type,
                        'required' => false,
                        'default_content' => '',
                        'required_variables' => $variableClass::requiredVariablesInSection($section),
                    ];
                    $requiredVariables = array_merge($requiredVariables, $variableClass::requiredVariablesInSection($section));
                }
                foreach ($channel::sectionsRequired() as $section) {
                    $sections[$section]['required'] = true;
                }
                foreach ($variableClass::defaultSectionsContent() as $section => $content) {
                    $sections[$section]['default_content'] = $content;
                }
                $result[$event][$channel] = [
                    'class' => $variableClass,
                    'assignableClass' => $variableClass::assignableClass(),
                    'variables' => $variableClass::variables(),
                    'required_variables' => array_merge($variableClass::requiredVariables(), array_unique($requiredVariables)),
                    'sections' => $sections,
                ];
            }
        }
        return $result;
    }

    public function handleEvent(EventWrapper $event): void
    {
        if (array_key_exists($event->eventClass(), self::$templates)) {
            foreach (self::$templates[$event->eventClass()] as $channelClass => $variableClass) {
                if ($variableClass::assignableClass()) {
                    $template = $this->repository->findTemplateAssigned($event->eventClass(), $channelClass, $variableClass::assignableClass(), $event->assignable($variableClass::assignableClass()));
                } else {
                    $template = $this->repository->findTemplateDefault($event->eventClass(), $channelClass);
                }
                if (!$template) {
                    Log::error(__('Template not found when handling registered Event', ['event' => $event->eventClass(), 'channel' => $channelClass, 'variables' => $variableClass]));
                    continue;
                }
                if (!$template->is_valid) {
                    Log::error(__('Template is invalid for handling registered Event', ['event' => $event->eventClass(), 'channel' => $channelClass, 'variables' => $variableClass, 'template_name' => $template->name, 'template_id' => $template->getKey()]));
                    continue;
                }
                $variables = $variableClass::variablesFromEvent($event);
                $sections = $template->generateContent($variables);
                if (!$this->channelService->validateTemplateSections($channelClass, $sections)) {
                    Log::error(__('Template sections evaluate to incorrect types', ['event' => $event->eventClass(), 'channel' => $channelClass, 'variables' => $variableClass, 'template_name' => $template->name, 'template_id' => $template->getKey()]));
                    continue;
                }
                $channelClass::send($event, $sections);
            }
        }
    }

    public function createDefaultTemplatesForChannel(string $channelClass): void
    {
        foreach (self::$templates as $eventClass => $channels) {
            if (array_key_exists($channelClass, $channels)) {
                $variableClass = $channels[$channelClass];
                $template = $this->repository->findTemplateDefault($eventClass, $channelClass);
                if (!$template) {
                    $this->repository->createWithSections([
                        'name' => 'Default template for event ' . class_basename($eventClass) . ' on ' . class_basename($channelClass) . ' channel',
                        'event' => $eventClass,
                        'channel' => $channelClass,
                        'default' => true,
                    ], $variableClass::defaultSectionsContent());
                }
            }
        }
    }
}
