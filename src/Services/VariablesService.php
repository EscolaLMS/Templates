<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;
use EscolaLms\Templates\Models\Certificate;
use EscolaLms\Templates\Enum\Email\CertificateVar as EmailCertificateVar;
use EscolaLms\Templates\Enum\Pdf\CertificateVar as PdfCertificateVar;

class VariablesService implements VariablesServiceContract
{
    private static array $tokens = [
        'pdf' => [
            'certificate' => PdfCertificateVar::class
        ],
        'email' => [
            'certificate' => EmailCertificateVar::class
        ]
    ];

    public static function addToken(TemplateVariableContract $token, string $type = 'pdf'): array
    {
        self::$tokens[$type] = $token;
        return self::$tokens;
    }

    public function getAvailableTokens(): array
    {
        return [
            'pdf' => array_map(fn ($class) => $class::getValues(), self::$tokens['pdf']),
            'email' => array_map(fn ($class) => $class::getValues(), self::$tokens['email'])
        ];
    }

    public function getMockVariables(string $classType, string $type ='pdf'): array
    {
        return self::$tokens[$type][$classType]::getMockValues();
    }

    public function getCertificateVariables(Course $course, User $user): array
    {
        return CertificateVar::getVariablesFromContent($course, $user);
    }
}
