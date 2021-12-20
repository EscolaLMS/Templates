<?php

namespace EscolaLms\Templates\Core;

use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Templates\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Models\Template;

abstract class AbstractTemplateVariableClass extends BasicEnum implements TemplateVariableContract
{
    public static function variables(): array
    {
        return static::getValues();
    }

    public static function processTemplateAfterSaving(Template $template): Template
    {
        return $template;
    }
}
