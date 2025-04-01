<?php

namespace Webkul\UVDesk\AutomationBundle\Workflow;

use Symfony\Contracts\EventDispatcher\Event as EventDispatcherEvent;

abstract class Event extends EventDispatcherEvent
{
    abstract public static function getId();
    abstract public static function getDescription();
    abstract public static function getFunctionalGroup();
}
