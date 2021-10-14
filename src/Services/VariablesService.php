<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use InvalidArgumentException;

class VariablesService implements VariablesServiceContract
{
    private static array $tokens = [
        'pdf' => [
            
        ],
        'email' => [
            

        ]
    ];

    public static function addToken(string $tokenClass, string $type = 'pdf', string $subtype = 'certificate'): array
    {
        if (!is_a($tokenClass, TemplateVariableContract::class, true)) {
            throw new InvalidArgumentException();
        }
        self::$tokens[$type][$subtype] = $tokenClass;
        return self::$tokens;
    }

    public function getAvailableTokens(): array
    {
        return [
            'pdf' => array_map(fn ($class) => $class::getValues(), self::$tokens['pdf']),
            'email' => array_map(fn ($class) => $class::getValues(), self::$tokens['email'])
        ];
    }

    public function getMockVariables(string $classType, string $type = 'pdf'): array
    {
        return self::$tokens[$type][$classType]::getMockValues();
    }

    public function getVariableEnumClassName($type, $vars_set): string
    {
        return self::$tokens[$type][$vars_set];
    }
}
