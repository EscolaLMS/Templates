<?php

namespace EscolaLms\Templates\Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Templates\Events\ManuallyTriggeredEvent;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;

class EventApiTest extends TestCase
{
    use DatabaseTransactions, CreatesUsers;

    public function testManuallyTriggeredEventForbidden(): void
    {
        Event::fake([ManuallyTriggeredEvent::class]);

        $student = $this->makeStudent();

        $this->response = $this->actingAs($student, 'api')->postJson(
            '/api/admin/events/trigger-manually',
            ['users' => [$student->getKey()]]
        )->assertStatus(403);
    }

    public function testManuallyTriggeredEvent(): void
    {
        Event::fake([ManuallyTriggeredEvent::class]);

        $student = $this->makeStudent();
        $admin = $this->makeAdmin();

        $this->response = $this->actingAs($admin, 'api')->postJson(
            '/api/admin/events/trigger-manually',
            ['users' => [$student->getKey()]]
        )->assertOk();

        Event::assertDispatched(ManuallyTriggeredEvent::class, function (ManuallyTriggeredEvent $event) use ($student) {
            $this->assertEquals($student->getKey(), $event->getUser()->getKey());
            return true;
        });
    }
}
