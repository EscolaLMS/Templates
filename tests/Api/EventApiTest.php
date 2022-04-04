<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Facades\Template as FacadesTemplate;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\Mock\TestChannel;
use EscolaLms\Templates\Tests\Mock\TestVariables;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;

class EventApiTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        FacadesTemplate::register(ManuallyTriggeredEvent::class, TestChannel::class, TestVariables::class);
    }

    public function testManuallyTriggeredEventForbidden(): void
    {
        Event::fake([ManuallyTriggeredEvent::class]);

        $template = Template::factory()->create([
            'channel' => TestChannel::class,
            'event' => ManuallyTriggeredEvent::class,
        ]);

        $student = $this->makeStudent();

        $this->response = $this->actingAs($student, 'api')->postJson(
            '/api/admin/events/trigger-manually/' . $template->getKey(),
            ['users' => [$student->getKey()]]
        )->assertStatus(403);
    }

    public function testManuallyTriggeredEvent(): void
    {
        Event::fake([ManuallyTriggeredEvent::class]);

        $template = Template::factory()->create([
            'channel' => TestChannel::class,
            'event' => ManuallyTriggeredEvent::class,
        ]);

        $student = $this->makeStudent();
        $admin = $this->makeAdmin();

        $this->response = $this->actingAs($admin, 'api')->postJson(
            '/api/admin/events/trigger-manually/' . $template->getKey(),
            ['users' => [$student->getKey()]]
        )->assertOk();
    }
}
