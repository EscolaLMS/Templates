<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Repository\TemplateRepository;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesUpdateTest extends TestCase
{
    use DatabaseTransactions;

    private function uri(int $id): string
    {
        return sprintf('/api/admin/templates/%s', $id);
    }

    public function testAdminCanUpdateExistingTemplate()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();
        $templateNew = Template::factory()->makeOne();

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri($template->id),
            [
                'title' => $templateNew->title,
                'content' => $templateNew->content,
            ]
        );
        $response->assertOk();
        $template->refresh();

        $this->assertEquals($templateNew->title, $template->title);
        $this->assertEquals($templateNew->content, $template->content);
    }

    public function testAdminCanUpdateExistingTemplateWithMissingTitle()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();
        $templateNew = Template::factory()->makeOne();
        $oldTitle = $template->title;
        $oldContent = $template->content;

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri($template->id),
            [
                'content' => $templateNew->content,
            ]
        );
        $response->assertStatus(200);
        $template->refresh();

        $this->assertEquals($oldTitle, $template->title);
        $this->assertEquals($templateNew->content, $template->content);
    }

    public function testAdminCanUpdateExistingTemplateWithMissingContent()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->createOne();
        $templateNew = Template::factory()->makeOne();
        $oldTitle = $template->title;
        $oldContent = $template->content;

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri($template->id),
            [
                'title' => $templateNew->title,
            ]
        );
        $response->assertStatus(200);
        $template->refresh();

        $this->assertEquals($templateNew->title, $template->title);
        $this->assertEquals($oldContent, $template->content);
    }

    public function testAdminCannotUpdateMissingTemplate()
    {
        $this->authenticateAsAdmin();

        $template = Template::factory()->makeOne();

        $response = $this->actingAs($this->user, 'api')->patchJson(
            $this->uri(99999999),
            [
                'title' => $template->title,
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

        $oldTitle = $template->title;
        $oldContent = $template->content;

        $response = $this->patchJson(
            $this->uri($template->id),
            [
                'title' => $templateNew->title,
                'content' => $templateNew->content,
            ]
        );
        $response->assertUnauthorized();
        $template->refresh();

        $this->assertEquals($oldTitle, $template->title);
        $this->assertEquals($oldContent, $template->content);
    }
}
