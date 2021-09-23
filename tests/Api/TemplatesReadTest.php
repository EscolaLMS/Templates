<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesReadTest extends TestCase
{
    use DatabaseTransactions;

    private function uri(string $slug): string
    {
        return sprintf('/api/templates/%s', $slug);
    }

    public function testCanReadExisting()
    {
        $template = Template::factory()->createOne();

        $response = $this->getJson($this->uri($template->slug));
        $response->assertOk();
        $response->assertJsonFragment(collect($template->getAttributes())->except('id', 'slug')->toArray());
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
        $response->assertJsonFragment(collect($template->getAttributes())->except('id', 'slug')->toArray());
    }
}
