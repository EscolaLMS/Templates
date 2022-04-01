<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Services\Contracts\EventServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateEventServiceContract;

class EventService implements EventServiceContract
{
    private TemplateEventServiceContract $templateEventService;

    public function __construct(TemplateEventServiceContract $templateEventService)
    {
        $this->templateEventService = $templateEventService;
    }

    public function dispatchEventManuallyForUsers(array $users, Template $template): void
    {
        $channelClass = $template->channel;
        $variableClass = $this->templateEventService->getVariableClassName($template->event, $channelClass);

        foreach ($users as $user) {
            $user = is_int($user) ? User::find($user) : $user;

            if ($user) {
                $event = new EventWrapper(new ManuallyTriggeredEvent($user));

                $variables = $variableClass::variablesFromEvent($event);
                $sections = $template->generateContent($variables);
                $sections['template_id'] = $template->id;

                $channelClass::send($event, $sections);
            }
        }
    }
}
