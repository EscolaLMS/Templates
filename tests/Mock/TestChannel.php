<?php

namespace EscolaLms\Templates\Tests\Mock;

use EscolaLms\Templates\Contracts\TemplateChannelContract;
use EscolaLms\Templates\Core\AbstractTemplateChannelClass;
use EscolaLms\Templates\Enums\TemplateSectionTypeEnum;
use EscolaLms\Templates\Events\EventWrapper;

class TestChannel extends AbstractTemplateChannelClass implements TemplateChannelContract
{
    public static array $handledNotifications = [];

    public static function send(EventWrapper $event, array $sections): bool
    {
        self::$handledNotifications[] = self::preview($event, $sections);
        return true;
    }

    public static function preview(EventWrapper $event, array $sections): array
    {
        return [
            'event' => $event,
            'title' => $sections['title'],
            'content' => $sections['content'],
            'url' => $sections['url'] ?? null,
        ];
    }

    public static function sections(): array
    {
        return [
            'title' => TemplateSectionTypeEnum::SECTION_TEXT,
            'content' => TemplateSectionTypeEnum::SECTION_HTML,
            'url' => TemplateSectionTypeEnum::SECTION_URL,
        ];
    }

    public static function sectionsRequired(): array
    {
        return [
            'title',
            'content',
        ];
    }
}
