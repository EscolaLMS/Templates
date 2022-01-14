<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\Mock\TestChannel;
use EscolaLms\Templates\Tests\Mock\TestEventWithGettersAndToArray;
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

    public function testAdminCanListWithFilters()
    {
        $this->authenticateAsAdmin();

        $templates = Template::factory()
            ->count(9)
            ->create();

        $template = Template::factory()->create([
            'event' => TestEventWithGettersAndToArray::class,
            'channel' => TestChannel::class
        ]);

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/admin/templates', [
            'event' => TestEventWithGettersAndToArray::class,
            'channel' => TestChannel::class,
        ]);
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'data' => [
                array_merge($template->toArray(), ['assignables' => [], 'sections' => []])
            ]
        ]);
    }
}
