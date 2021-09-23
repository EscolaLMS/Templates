<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesReadTest extends TestCase
{
    use DatabaseTransactions;

    private function uri(string $suffix): string
    {
        return sprintf('/api/templates/%s', $suffix);
    }

    public function testCannotFindMissing()
    {
        $response = $this->getJson($this->uri('non-existing-template'));
        $response->assertNotFound();
    }

    public function testAdminCanReadExistingById()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();

        $response = $this->actingAs($this->user, 'api')->getJson('/api/admin/templates/' . $template->getKey());
        $response->assertOk();
        $response->assertJsonFragment(collect($template->getAttributes())->except('id', 'created_at', 'updated_at')->toArray());
    }
}
