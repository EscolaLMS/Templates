<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Templates\Mail\TemplatePreview;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Tests\Enum\Email\CertificateVar as EmailCertificateVar;
use EscolaLms\Templates\Tests\Enum\Pdf\CertificateVar as PdfCertificateVar;

class TemplatesPreviewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $variablesService = resolve(VariablesServiceContract::class);
        $variablesService::addToken(EmailCertificateVar::class, 'email', 'certificates');
        $variablesService::addToken(PdfCertificateVar::class, 'pdf', 'certificates');
    }

    public function testAdminCanListVariables()
    {
        $this->authenticateAsAdmin();
        $response = $this->actingAs($this->user, 'api')->getJson(
            '/api/admin/templates/variables'
        );

        $response->assertOk();

        $variables = $response->getData()->data;

        $this->assertTrue(isset($variables->email->certificates));
        $this->assertTrue(isset($variables->pdf->certificates));
        $this->assertIsArray($variables->email->certificates);
        $this->assertIsArray($variables->pdf->certificates);
    }

    /*
    public function testAdminCanCreateEmailPreview()
    {
        $this->authenticateAsAdmin();
        $template = Template::factory()->makeOne([
            'type' => 'email',
            'vars_set' => 'certificates',
            'content' => '<a href="mailto:@VarStudentEmail">@VarStudentEmail</a>
            <code>HelloWorld</code>
            <date>@VarDateFinished</date>
            '
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

        Mail::assertSent(TemplatePreview::class);

        Mail::assertSent(function (TemplatePreview $mail) {
            return strpos($mail->markdown, '<code>HelloWorld</code>') !== false;
        });

        Mail::assertSent(function (TemplatePreview $mail) {
            $date = preg_match_all('/<date>(.*?)<\/date>/s', $mail->markdown, $matches);
            return strtotime($matches[1][0]) !== false;
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
    */
}
