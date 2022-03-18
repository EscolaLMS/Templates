<?php

namespace EscolaLms\Templates\Events;

use EscolaLms\Core\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManuallyTriggeredEvent
{
    use Dispatchable, SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
