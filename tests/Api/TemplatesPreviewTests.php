<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Templates\Mail\TemplatePreview;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class TemplatesPreviewTests extends TestCase
{
    use DatabaseTransactions;

    public function testAdminCanCreateEmailPreview()
    {
        $this->authenticateAsAdmin();
        $template = Template::factory()->makeOne([
            'type' => 'email',
            'vars_set' => 'certificates',
            'content' => '<a href="mailto:@VarStudentEmail">@VarStudentEmail</a> <code>HelloWorld</code>'
        ]);
        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            $template->toArray()
        );

        $response->assertStatus(201);

        $id = $response->getData()->data->id;

        Mail::fake();

        $response = $this->actingAs($this->user, 'api')->get(
            '/api/admin/templates/' . $id . '/preview',
            $template->toArray()
        );

        $response->assertOk();

        // Mail::assertSent(TemplatePreview::class);

        Mail::assertSent(function (TemplatePreview $mail) {
            return strpos($mail->markdown, '<code>HelloWorld</code>') !== FALSE;
        });
    }

    public function testAdminCanCreatePDFPreview()
    {
        $this->authenticateAsAdmin();
        $template = Template::factory()->makeOne([
            'type' => 'pdf',
            'vars_set' => 'certificates',
            'content' => 'Date course was finished: @VarDateFinished'
        ]);
        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            $template->toArray()
        );

        $response->assertStatus(201);

        $id = $response->getData()->data->id;    

        $response = $this->actingAs($this->user, 'api')->get(
            '/api/admin/templates/' . $id . '/preview',
            $template->toArray()
        );

        $response->assertOk();

    }
}
