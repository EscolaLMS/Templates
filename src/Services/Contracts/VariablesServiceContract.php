<?php

namespace EscolaLms\Templates\Services\Contracts;

/**
 * Interface VariablesServiceContract
 * @package EscolaLms\Templates\Services\Contracts
 */
interface VariablesServiceContract
{
    public static function addToken(string $variableSetClass, string $type = 'pdf', string $vars_set = 'certificate'): array;

    public function getAvailableTokens(): array;

    public function getMockVariables(string $vars_set, string $type = 'pdf'): array;

    public function getVariableEnumClassName(?string $type, ?string $vars_set): string;
}
