<?php

namespace EscolaLms\Templates\Services\Contracts;

use EscolaLms\Templates\Events\EventWrapper;

interface TemplateEventServiceContract
{
    public function register(string $eventClass, string $channelClass, string $variableClass): void;
    public function getVariableClassName(string $eventClass, string $channelClass): ?string;
    public function handleEvent(EventWrapper $event): void;
    public function getRegisteredEvents(): array;
    public function getRegisteredEventsWithTokens(): array;
    public function getRegisteredChannels(): array;
}
