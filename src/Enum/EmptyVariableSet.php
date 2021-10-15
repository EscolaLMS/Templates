<?php

namespace EscolaLms\Templates\Enum;

use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;

class EmptyVariableSet extends BasicEnum implements TemplateVariableContract
{
    public static function getMockVariables(): array
    {
        return [];
    }

    public static function getVariablesFromContent(): array
    {
        return [];
    }

    public static function getRequiredVariables(): array
    {
        return [];
    }

    public static function isValid(string $content): bool
    {
        return true;
    }
}
