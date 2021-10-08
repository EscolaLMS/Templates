<?php

namespace EscolaLms\Templates\Mail;

use Illuminate\Mail\Mailable;

class TemplatePreview extends Mailable
{

    public $markdown;

    public function __construct(string $markdown)
    {
        $this->markdown = $markdown;
    }

    public function build()
    {
        return $this
            ->html($this->markdown);
    }
}
