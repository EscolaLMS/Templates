<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Services\Contracts\EventServiceContract;

class EventService implements EventServiceContract
{
    public function dispatchEventManuallyForUsers(array $users = []): void
    {
        foreach ($users as $user) {
            $user = is_int($user) ? User::find($user) : $user;

            if ($user) {
                event(new ManuallyTriggeredEvent($user));
            }
        }
    }
}
