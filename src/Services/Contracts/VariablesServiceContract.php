<?php

namespace EscolaLms\Templates\Services\Contracts;

use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;


/**
 * Interface VariablesServiceContract
 * @package EscolaLms\Templates\Services\Contracts
 */
interface VariablesServiceContract
{
    public function getCertificateVariables(Course $course, User $user) : array;

    public function getAvailableTokens(): array;

    public function getMockVariables(string $className): array;



}
