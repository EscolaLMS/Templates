<?php

namespace EscolaLms\Templates\Database\Factories;

use EscolaLms\Templates\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TemplateFactory extends Factory
{
    protected $model = Template::class;

    public function definition()
    {
        $title = $this->faker->catchPhrase;
        return [
            'name' => Str::slug($title, '-'),
            'type' => 'pdf',
            'vars_set' => 'certificates',
            'content' => '<h1>This is a template</h1>',
        ];
    }
}
