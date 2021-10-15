<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Enum\EmptyVariableSet;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use InvalidArgumentException;

class VariablesService implements VariablesServiceContract
{
    private static array $tokens = [];

    public static function addToken(string $variableSetClass, string $type = 'pdf', string $vars_set = 'certificate'): array
    {
        if (!is_a($variableSetClass, TemplateVariableContract::class, true)) {
            throw new InvalidArgumentException();
        }
        self::$tokens[$type][$vars_set] = $variableSetClass;
        return self::$tokens;
    }

    public function getAvailableTokens(): array
    {
        return array_map(fn ($classes) => array_map(fn ($class) => $class::getValues(), $classes), self::$tokens);
    }

    public function getMockVariables(string $vars_set, string $type = 'pdf'): array
    {
        return $this->getVariableEnumClassName($type, $vars_set)::getMockVariables();
    }

    public function getVariableEnumClassName(?string $type, ?string $vars_set): string
    {
        return isset(self::$tokens[$type][$vars_set]) ? self::$tokens[$type][$vars_set] : EmptyVariableSet::class;
    }
}
