<?php

namespace EscolaLms\Templates\Http\Controllers\Admin;


use Spatie\Browsershot\Browsershot;

use EscolaLms\Templates\Models\Template;


class PreviewController
{
    public function __invoke($id)
    {

        $template = Template::findOrFail($id);

        // TODO handle this 

        $faker = \Faker\Factory::create();

        $vars = [
            "@VarDateFinished" => $faker->date,
            "@VarStudentFirstName" => $faker->firstName,
            "@VarStudentLastName" => $faker->lastName,
            "@VarStudentFullName" => $faker->name,
            "@VarTutorFirstName" => $faker->firstName,
            "@VarTutorLastName" => $faker->lastName,
            "@VarTutorFullName" => $faker->name,
            "@VarCourseName" => $faker->sentence,
        ];

        
        $content = strtr($template->content, $vars);

        $content = view('templates::quill', ['body' => $content])->render();

        $filename = 'example.pdf';

        Browsershot::html($content)
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'single-process'
            ])
            ->timeout(120)
            ->save($filename);


        return response()->file(public_path($filename));
    }
}
