<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Tests\Enum\Email\CertificateVar as EmailCertificateVar;
use EscolaLms\Templates\Tests\Enum\Pdf\CertificateVar as PdfCertificateVar;
use EscolaLms\Templates\Tests\Enum\Pdf\CertificateVar;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesUpdateTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $variablesService = resolve(VariablesServiceContract::class);
        $variablesService::addToken(EmailCertificateVar::class, 'email', 'certificates');
        $variablesService::addToken(PdfCertificateVar::class, 'pdf', 'certificates');
    }

    private function uri(int $id): string
    {
        return sprintf('/api/admin/templates/%s', $id);
    }

    public function testAdminCanUpdateExistingTemplate()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();
        $templateNew = Template::factory()->makeOne();
        $templateNew->content .= CertificateVar::COURSE_TITLE;

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri($template->id),
            [
                'name' => $templateNew->name,
                'content' => $templateNew->content,
            ]
        );
        $response->assertOk();
        $template->refresh();

        $this->assertEquals($templateNew->name, $template->name);
        $this->assertEquals($templateNew->content, $template->content);
    }

    public function testAdminCanUpdateExistingTemplateWithMissingName()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();
        $templateNew = Template::factory()->makeOne();
        $templateNew->content .= CertificateVar::COURSE_TITLE;

        $oldName = $template->name;
        $oldContent = $template->content;

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri($template->id),
            [
                'content' => $templateNew->content,
            ]
        );
        $response->assertStatus(200);
        $template->refresh();

        $this->assertEquals($oldName, $template->name);
        $this->assertEquals($templateNew->content, $template->content);
        $this->assertNotEquals($oldContent, $templateNew->content);
    }

    public function testAdminCanUpdateExistingTemplateWithMissingContent()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();
        $templateNew = Template::factory()->makeOne();
        $oldName = $template->name;
        $oldContent = $template->content;

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri($template->id),
            [
                'name' => $templateNew->name,
            ]
        );
        $response->assertStatus(200);
        $template->refresh();

        $this->assertEquals($templateNew->name, $template->name);
        $this->assertEquals($oldContent, $template->content);
    }

    public function testAdminCannotUpdateMissingTemplate()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->makeOne();

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri(99999999),
            [
                'name' => $template->name,
                'content' => $template->content,
            ]
        );

        $response->assertStatus(404);
        $this->assertEquals(0, $template->newQuery()->where('id', $template->id)->count());
    }

    public function testGuestCannotUpdateExistingTemplate()
    {
        $template = Template::factory()->createOne();
        $templateNew = Template::factory()->makeOne();

        $oldName = $template->name;
        $oldContent = $template->content;

        $response = $this->patchJson(
            $this->uri($template->id),
            [
                'name' => $templateNew->name,
                'content' => $templateNew->content,
            ]
        );
        $response->assertUnauthorized();
        $template->refresh();

        $this->assertEquals($oldName, $template->name);
        $this->assertEquals($oldContent, $template->content);
    }
}
