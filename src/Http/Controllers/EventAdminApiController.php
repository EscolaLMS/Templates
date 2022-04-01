<?php

namespace EscolaLms\Templates\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Templates\Http\Controllers\Contracts\EventAdminApiContract;
use EscolaLms\Templates\Http\Requests\EventTriggerRequest;
use EscolaLms\Templates\Services\Contracts\EventServiceContract;
use Illuminate\Http\JsonResponse;

class EventAdminApiController extends EscolaLmsBaseController implements EventAdminApiContract
{
    private EventServiceContract $eventService;

    public function __construct(EventServiceContract $eventService)
    {
        $this->eventService = $eventService;
    }

    public function triggerManually(EventTriggerRequest $request): JsonResponse
    {
        $this->eventService->dispatchEventManuallyForUsers($request->get('users'), $request->getTemplate());

        return $this->sendSuccess(__('Event triggered successfully'));
    }
}
