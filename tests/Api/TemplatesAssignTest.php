<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Facades\Template as FacadesTemplate;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Models\TemplateSection;
use EscolaLms\Templates\Tests\Mock\TestChannel;
use EscolaLms\Templates\Tests\Mock\TestEventWithGetters;
use EscolaLms\Templates\Tests\Mock\TestVariables;
use EscolaLms\Templates\Tests\Mock\TestVariablesWithAssignableClass;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesAssignTest extends TestCase
{
    use DatabaseTransactions;
    use CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        FacadesTemplate::register(TestEventWithGetters::class, TestChannel::class, TestVariablesWithAssignableClass::class);
    }

    private function uri(int $id): string
    {
        return sprintf('/api/admin/templates/%s', $id);
    }

    public function testAdminCanAssignTemplate()
    {
        $this->authenticateAsAdmin();

        $user = $this->makeStudent();

        $template = Template::factory()->createOne([
            'event' => TestEventWithGetters::class,
            'channel' => TestChannel::class,
        ]);

        TemplateSection::factory(['key' => 'title', 'template_id' => $template->getKey()])->create();
        TemplateSection::factory(['key' => 'content', 'template_id' => $template->getKey(), 'content' => TestVariables::VAR_USER_EMAIL . '_' . TestVariables::VAR_FRIEND_EMAIL])->create();

        $this->assertNull($template->assignable);

        $response = $this->actingAs($this->user, 'api')->postJson($this->uri($template->id) . '/assign', [
            'assignable_id' => $user->getKey()
        ]);
        $response->assertOk();

        $template->refresh();

        $this->assertEquals($user->getKey(), $template->assignable->getKey());
        $this->assertEquals($user->email, $template->assignable->email);
    }
}
