<?php

namespace EscolaLms\Templates\Tests\Enum\Email;

use EscolaLms\Templates\Tests\Enum\CertificateVar as CommonCertificateVar;
use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;

class CertificateVar extends CommonCertificateVar
{
    const STUDENT_EMAIL          = "@VarStudentEmail";

    public static function getMockVariables(): array
    {
        $faker = \Faker\Factory::create();
        $vars = parent::getMockVariables();
        return array_merge($vars, [
            self::STUDENT_EMAIL => $faker->email
        ]);
    }

    public static function getVariablesFromContent(Course $course = null, User $user = null): array
    {
        $vars = parent::getVariablesFromContent($course, $user);
        return array_merge($vars, [
            self::STUDENT_EMAIL => $user->email
        ]);
    }
}
