<?php

namespace EscolaLms\Templates\Contracts;

use EscolaLms\Templates\Core\TemplateSectionSchema;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Models\Template;
use Illuminate\Support\Collection;

interface TemplateChannelContract
{
    public static function send(EventWrapper $event, array $sections): bool;
    public static function preview(EventWrapper $event, array $sections): array;

    public static function sections(): Collection;
    public static function sectionsRequired(): array;
    public static function sectionsReadonly(): array;

    public static function section(string $sectionKey): ?TemplateSectionSchema;
    public static function sectionExists(string $sectionKey): bool;

    public static function processTemplateAfterSaving(Template $template): Template;
}
