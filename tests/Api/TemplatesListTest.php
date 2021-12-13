<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesListTest extends TestCase
{
    use DatabaseTransactions;

    private string $uri = '/api/templates';

    public function testAdminCanListEmpty()
    {
        $this->authenticateAsAdmin();

        $response = $this->actingAs($this->user, 'api')->getJson('/api/admin/templates');
        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'data',
            'meta',
            'message'
        ]);
        $response->assertJsonCount(0, 'data');
    }

    public function testAdminCanList()
    {
        $this->authenticateAsAdmin();

        $templates = Template::factory()
            ->count(10)
            ->create();

        $templatesArr = $templates->map(function (Template $p) {
            return $p->toArray();
        })->toArray();

        $response = $this->actingAs($this->user, 'api')->getJson('/api/admin/templates');
        $response->assertOk();
        $response->assertJsonFragment(
            $templatesArr[0],
        );
    }
}
