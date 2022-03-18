<?php

namespace EscolaLms\Templates\Services\Contracts;

interface EventServiceContract
{
    public function dispatchEventManuallyForUsers(array $users = []): void;
}
