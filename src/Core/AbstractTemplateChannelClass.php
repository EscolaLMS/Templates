<?php

namespace EscolaLms\Templates\Core;

use EscolaLms\Templates\Contracts\TemplateChannelContract;

abstract class AbstractTemplateChannelClass implements TemplateChannelContract
{
    public static function sectionType(string $sectionKey): ?string
    {
        return static::sectionExists($sectionKey) ? static::sections()[$sectionKey] : null;
    }

    public static function sectionExists(string $sectionKey): bool
    {
        return array_key_exists($sectionKey, static::sections());
    }
}
