<?php

namespace EscolaLms\Templates\Services\Contracts;

/**
 * Interface VariablesServiceContract
 * @package EscolaLms\Templates\Services\Contracts
 */
interface VariablesServiceContract
{
    public static function addToken(string $tokenClass, string $type = 'pdf', string $subtype = 'certificate'): array;

    public function getAvailableTokens(): array;

    public function getMockVariables(string $className): array;

    public function getVariableEnumClassName($type, $vars_set): string;
}
