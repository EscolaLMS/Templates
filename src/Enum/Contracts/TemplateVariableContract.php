<?php

namespace EscolaLms\Templates\Enum\Contracts;

interface TemplateVariableContract
{
    public static function getMockVariables(): array;

    public static function getVariablesFromContent(): array;

    public static function getRequiredVariables(): array;

    public static function isValid(string $content): bool;
}
