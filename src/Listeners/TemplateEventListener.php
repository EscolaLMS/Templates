<?php

namespace EscolaLms\Templates\Listeners;

use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Facades\Template;

class TemplateEventListener
{
    public function handle(object $event)
    {
        Template::handleEvent(new EventWrapper($event));
    }
}
