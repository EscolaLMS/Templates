<?php

namespace EscolaLms\Templates\Enum;

use EscolaLms\Core\Enums\BasicEnum;

use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;

class CertificateVar extends BasicEnum
{
    const DATE_FINISHED         = "@VarDateFinished";
    const STUDENT_FIRST_NAME    = "@VarStudentFirstName";
    const STUDENT_LAST_NAME     = "@VarStudentLastName";
    const STUDENT_FULL_NAME     = "@VarStudentFullName";
    const TUTOR_FIRST_NAME      = "@VarTutorFirstName";
    const TUTOR_LAST_NAME       = "@VarTutorLastName";
    const TUTOR_FULL_NAME       = "@VarTutorFullName";
    const COURSE_TITLE          = "@VarCourseTitle";


    public static function getMockVariables()
    {

        $faker = \Faker\Factory::create();
        return [
            self::DATE_FINISHED => $faker->date("Y-m-d H:i:s"),
            self::STUDENT_FIRST_NAME => $faker->firstName,
            self::STUDENT_LAST_NAME => $faker->lastName,
            self::STUDENT_FULL_NAME => $faker->name,
            self::TUTOR_FIRST_NAME => $faker->firstName,
            self::TUTOR_LAST_NAME => $faker->firstName,
            self::TUTOR_FULL_NAME =>  $faker->firstName,
            self::COURSE_TITLE =>  $faker->sentence,
        ];
    }

    public static function getVariablesFromContent(Course $course, User $user)
    {
        return [
            self::DATE_FINISHED => date("Y-m-d H:i:s"), // TODO how to get this date from progress? 
            self::STUDENT_FIRST_NAME => $user->firstName,
            self::STUDENT_LAST_NAME => $user->lastName,
            self::STUDENT_FULL_NAME => $user->name,
            self::TUTOR_FIRST_NAME => $course->author->firstName,
            self::TUTOR_LAST_NAME => $course->author->firstName,
            self::TUTOR_FULL_NAME => $course->author->firstName,
            self::COURSE_TITLE => $course->title,
        ];
    }
}
