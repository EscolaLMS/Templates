<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Tests\Enum\Pdf\CertificateVar as PdfCertificateVar;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesCreateTest extends TestCase
{
    use DatabaseTransactions;

    private function uri(string $suffix): string
    {
        return sprintf('/api/admin/templates%s', $suffix);
    }

    public function testAdminCanCreateTemplate()
    {
        $this->authenticateAsAdmin();
        $template = Template::factory()->makeOne(['name' => 'false']);
        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            $template->toArray()
        );

        $response->assertStatus(201);

        $response3 = $this->actingAs($this->user, 'api')->getJson(
            '/api/admin/templates/' . $template->id,
        );

        $response3->assertOk();
    }

    public function testAdminCannotCreateTemplateWithoutTitle()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->makeOne();
        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            collect($template->getAttributes())->except('id', 'name', 'type')->toArray()
        );
        $response->assertStatus(422);
        $response = $this->getJson(
            '/api/templates/' . $template->id,
        );

        $response->assertNotFound();
    }

    public function testAdminCannotCreateTemplateWithInvalidContent()
    {
        $variablesService = resolve(VariablesServiceContract::class);
        $variablesService::addToken(PdfCertificateVar::class, 'pdf', 'certificates');

        $this->authenticateAsAdmin();

        $template = Template::factory()->makeOne();
        $template->type = 'pdf';
        $template->vars_set = 'certificates';

        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            $template->getAttributes()
        );
        $response->assertStatus(422);
    }

    public function testGuestCannotCreateTemplate()
    {
        $template = Template::factory()->makeOne();
        $response = $this->postJson(
            '/api/admin/templates',
            collect($template->getAttributes())->except('id', 'name')->toArray()
        );
        $response->assertUnauthorized();
    }
}
