<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Core\TemplatePreview;
use EscolaLms\Templates\Core\TemplateSectionSchema;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Facades\Template as FacadesTemplate;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\Templates\Services\Contracts\TemplateChannelServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateEventServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateVariablesServiceContract;
use Illuminate\Support\Facades\Log;

class TemplateEventService implements TemplateEventServiceContract
{
    protected array $events = [];

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

        if (!array_key_exists($eventClass, $this->events) || !array_key_exists($channelClass, $this->events[$eventClass])) {
            $this->events[$eventClass][$channelClass] = $variableClass;
        }
    }

    public function getVariableClassName(string $eventClass, string $channelClass): ?string
    {
        return $this->events[$eventClass][$channelClass] ?? null;
    }

    public function getRegisteredEvents(): array
    {
        return $this->events;
    }

    public function getRegisteredChannels(): array
    {
        return $this->channelService->list();
    }

    public function getRegisteredEventsWithTokens(): array
    {
        $result = [];
        foreach ($this->events as $event => $channels) {
            foreach ($channels as $channel => $variableClass) {
                $sections = [];
                $requiredVariables = [];
                foreach ($channel::sections() as $sectionSchema) {
                    /** @var TemplateSectionSchema $sectionSchema */
                    $sections[$sectionSchema->getKey()] = [
                        'type' => $sectionSchema->getType()->value,
                        'required' => $sectionSchema->getRequired(),
                        'readonly' => $sectionSchema->getReadonly(),
                        'default_content' => '',
                        'required_variables' => $variableClass::requiredVariablesInSection($sectionSchema->getKey()),
                    ];
                    $requiredVariables = array_merge($requiredVariables, $variableClass::requiredVariablesInSection($sectionSchema->getKey()));
                }
                foreach ($variableClass::requiredSections() as $section) {
                    $sections[$section]['required'] = true;
                }
                foreach ($variableClass::defaultSectionsContent() as $section => $content) {
                    $sections[$section]['default_content'] = $content;
                }
                $result[$event][$channel] = [
                    'class' => $variableClass,
                    'assignableClass' => $variableClass::assignableClass(),
                    'variables' => $variableClass::variables(),
                    'required_variables' =>  array_unique(array_merge($variableClass::requiredVariables(), $requiredVariables)),
                    'sections' => $sections,
                ];
            }
        }
        return $result;
    }

    public function handleEvent(EventWrapper $event): void
    {
        if (!array_key_exists($event->eventClass(), $this->events)) {
            return;
        }

        foreach ($this->events[$event->eventClass()] as $channelClass => $variableClass) {
            $template = $this->getTemplateForEvent($event, $channelClass, $variableClass);

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

    protected function getTemplateForEvent(EventWrapper $event, string $channelClass, string $variableClass): ?Template
    {
        if ($variableClass::assignableClass()) {
            return $this->repository->findTemplateAssigned($event->eventClass(), $channelClass, $variableClass::assignableClass(), $event->assignable($variableClass::assignableClass()));
        }
        return $this->repository->findTemplateDefault($event->eventClass(), $channelClass);
    }

    public function createDefaultTemplatesForChannel(string $channelClass): void
    {
        foreach ($this->events as $eventClass => $channels) {
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

    public function sendPreview(User $user, Template $template): TemplatePreview
    {
        $channelClass = $template->channel;
        $sections = $template->previewContent($user);
        $sent = $channelClass::preview($user, $sections);
        return new TemplatePreview($user, $sections, $sent);
    }
}
