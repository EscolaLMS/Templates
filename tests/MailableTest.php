<?php

namespace EscolaLms\Templates\Tests;

use EscolaLms\Templates\Tests\TestCase;
use EscolaLms\Templates\Mail\TemplatePreview;

class MailableTest extends TestCase
{
    public function testTemplatePreview()
    {
        $markdown = '<code></code>';
        $mail = new TemplatePreview($markdown);
        $mail->build();

        $this->assertEquals($mail->markdown, $markdown);
    }
}
