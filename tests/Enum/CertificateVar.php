<?php

namespace EscolaLms\Templates\Tests\Enum;

use EscolaLms\Auth\Models\User;
use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;
use Illuminate\Support\Str;

class CertificateVar extends BasicEnum implements TemplateVariableContract
{
    const DATE_FINISHED         = "@VarDateFinished";
    const STUDENT_FIRST_NAME    = "@VarStudentFirstName";
    const STUDENT_LAST_NAME     = "@VarStudentLastName";
    const STUDENT_FULL_NAME     = "@VarStudentFullName";
    const STUDENT_EMAIL         = "@VarStudentEmail";

    public static function getMockVariables(): array
    {

        $faker = \Faker\Factory::create();
        return [
            self::DATE_FINISHED => $faker->date("Y-m-d H:i:s"),
            self::STUDENT_EMAIL => $faker->email,
            self::STUDENT_FIRST_NAME => $faker->firstName,
            self::STUDENT_LAST_NAME => $faker->lastName,
            self::STUDENT_FULL_NAME => $faker->name,
        ];
    }

    public static function getVariablesFromContent(User $user = null): array
    {
        return [
            self::DATE_FINISHED => date("Y-m-d H:i:s"),
            self::STUDENT_EMAIL => $user->email,
            self::STUDENT_FIRST_NAME => $user->firstName,
            self::STUDENT_LAST_NAME => $user->lastName,
            self::STUDENT_FULL_NAME => $user->name,
        ];
    }

    public static function getRequiredVariables(): array
    {
        return [
            self::STUDENT_EMAIL
        ];
    }

    public static function isValid(string $content): bool
    {
        return Str::containsAll($content, self::getRequiredVariables());
    }
}
