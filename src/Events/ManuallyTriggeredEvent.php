<?php

namespace EscolaLms\Templates\Events;

use EscolaLms\Core\Models\User;
use EscolaLms\Courses\Models\Course;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManuallyTriggeredEvent
{
    use Dispatchable, SerializesModels;

    private User $user;
    private ?Course $course;

    public function __construct(User $user, Course $course = null)
    {
        $this->user = $user;
        $this->course = $course;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }
}
