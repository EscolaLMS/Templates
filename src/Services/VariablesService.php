<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;
use EscolaLms\Templates\Models\Certificate;

use EscolaLms\Templates\Enum\CertificateVar;

class VariablesService implements VariablesServiceContract
{

    public function getAvailableTokens(): array
    {
        return [
            'certificate' => CertificateVar::getValues()
        ];
    }

    public function getMockVariables(string $className): array
    {
        switch ($className) {
            case Certificate::class:
                return CertificateVar::getMockVariables();
            default:
                return [];
        }
    }

    public function getCertificateVariables(Course $course, User $user): array
    {
        return CertificateVar::getVariablesFromContent($course, $user);
    }
}
