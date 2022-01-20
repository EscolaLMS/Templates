<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Facades\Template as FacadesTemplate;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\Mock\TestChannel;
use EscolaLms\Templates\Tests\Mock\TestEventWithGettersAndToArray;
use EscolaLms\Templates\Tests\Mock\TestVariablesWithAssignableClass;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesListTest extends TestCase
{
    use DatabaseTransactions;

    private string $uri = '/api/templates';

    protected function setUp(): void
    {
        parent::setUp();

        FacadesTemplate::register(TestEventWithGettersAndToArray::class, TestChannel::class, TestVariablesWithAssignableClass::class);
    }

    public function testAdminCanListEmpty(): void
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

    public function testAdminCanList(): void
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

    public function testGuestCannotList(): void
    {
        $response = $this->getJson('/api/admin/templates');

        $response->assertUnauthorized();
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
                array_merge(
                    $template->toArray(),
                    [
                        'is_assignable' => true,
                        'assignable_class' => User::class,
                        'variable_class' => TestVariablesWithAssignableClass::class,
                        'assignables' => [],
                        'sections' => []
                    ]
                )
            ]
        ]);
    }
}
