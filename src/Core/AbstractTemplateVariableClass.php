<?php

namespace EscolaLms\Templates\Core;

use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Templates\Contracts\TemplateVariableContract;

abstract class AbstractTemplateVariableClass extends BasicEnum implements TemplateVariableContract
{
    public static function variables(): array
    {
        return static::getValues();
    }
}
