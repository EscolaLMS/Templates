<?php

namespace EscolaLms\Templates\Contracts;

use EscolaLms\Templates\Events\EventWrapper;

interface TemplateChannelContract
{
    public static function send(EventWrapper $event, array $sections): bool;
    public static function preview(EventWrapper $event, array $sections): array;

    public static function sections(): array;
    public static function sectionsRequired(): array;

    public static function sectionType(string $sectionKey): ?string;
    public static function sectionExists(string $sectionKey): bool;
}
