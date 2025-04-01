<?php

namespace Webkul\UVDesk\AutomationBundle\Workflow;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\UVDesk\AutomationBundle\Workflow\Event;

abstract class Action
{
    public static function getId()
    {
        return null;
    }

    public static function getDescription()
    {
        return null;
    }

    public static function getFunctionalGroup()
    {
        return null;
    }

    abstract public static function getOptions(ContainerInterface $container);
    abstract public static function applyAction(ContainerInterface $container, Event $event, $value);
}
