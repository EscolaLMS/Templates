<?php

namespace EscolaLms\Templates\Facades;

use EscolaLms\Templates\Testing\TemplateFake;
use Illuminate\Support\Facades\Facade;
use EscolaLms\Templates\Events\EventWrapper;
use EscolaLms\Templates\Services\Contracts\TemplateEventServiceContract;

/**
 * @method static void    register(string $eventClass, string $channelClass, string $variableClass)
 * @method static void    handleEvent(EventWrapper $event)
 * @method static ?string getVariableClassName(string $eventClass, string $channelClass)
 * @method static array   getRegisteredEvents()
 * @method static array   getRegisteredChannels()
 * @method static array   getRegisteredEventsWithTokens()
 * @method static bool    assertEventHandled(string $eventClass, string $channelClass, ?string $variableClass = null) 
 * @method static void    createDefaultTemplatesForChannel(string $channelClass)
 * 
 * @see \EscolaLms\Templates\Services\TemplateEventService
 */
class Template extends Facade
{
    /**
     * Replace the bound instance with a fake.
     */
    public static function fake()
    {
        $fake = app(TemplateFake::class);
        $fake->setRegisteredEvents(self::getRegisteredEvents());

        static::swap($fake);

        return $fake;
    }

    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return TemplateEventServiceContract::class;
    }
}
