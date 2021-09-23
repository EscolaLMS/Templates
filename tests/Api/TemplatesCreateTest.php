<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesCreateTest extends TestCase
{
    use DatabaseTransactions;

    private function uri(string $slug): string
    {
        return sprintf('/api/admin/templates%s', $slug);
    }

    public function testAdminCanCreateTemplate()
    {
        $this->authenticateAsAdmin();
        $template = Template::factory()->makeOne(['active' => false]);
        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            $template->toArray()
        );

        $response->assertStatus(201);

        $response2 = $this->getJson(
            '/api/templates/' . $template->slug,
        );

        $response2->assertStatus(403);

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
            collect($template->getAttributes())->except('id', 'slug', 'title')->toArray()
        );
        $response->assertStatus(422);
        $response = $this->getJson(
            '/api/templates/' . $template->slug,
        );

        $response->assertNotFound();
    }

    public function testAdminCannotCreateTemplateWithoutContent()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->makeOne();
        $response = $this->actingAs($this->user, 'api')->postJson(
            '/api/admin/templates',
            collect($template->getAttributes())->except('id', 'slug', 'content')->toArray()
        );
        $response->assertStatus(422);
        //TODO: make sure the template doesn't exists
    }

    public function testAdminCannotCreateDuplicateTemplate()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();
        $duplicate = Template::factory()->makeOne($template->getAttributes());
        $response = $this->actingAs($this->user, 'api')->postJson('/api/admin/templates', $template->toArray());
        $response->assertStatus(422);
    }

    public function testGuestCannotCreateTemplate()
    {
        $template = Template::factory()->makeOne();
        $response = $this->postJson(
            '/api/admin/templates',
            collect($template->getAttributes())->except('id', 'slug')->toArray()
        );
        $response->assertUnauthorized();
    }
}
