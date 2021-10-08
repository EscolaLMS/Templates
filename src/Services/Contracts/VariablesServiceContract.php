<?php

namespace EscolaLms\Templates\Services\Contracts;

use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;

/**
 * Interface VariablesServiceContract
 * @package EscolaLms\Templates\Services\Contracts
 */
interface VariablesServiceContract
{
    public static function addToken(TemplateVariableContract $token, string $type = 'pdf'): array;

    public function getAvailableTokens(): array;

    public function getMockVariables(string $className): array;

}
