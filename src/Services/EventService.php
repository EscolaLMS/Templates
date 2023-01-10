<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Services\Contracts\EventServiceContract;

class EventService implements EventServiceContract
{
    public function dispatchEventManuallyForUsers(array $users, Template $template): bool
    {
        $channelClass = $template->channel;
        $variableClass = $template->variableClass;

        if (!$template->is_valid) {
            return false;
        }

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

        return true;
    }
}