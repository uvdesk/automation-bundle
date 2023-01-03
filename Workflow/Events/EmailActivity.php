<?php

namespace Webkul\UVDesk\AutomationBundle\Workflow\Events;

use Webkul\UVDesk\AutomationBundle\Workflow\Event;
use Webkul\UVDesk\AutomationBundle\Workflow\FunctionalGroup;

abstract class EmailActivity extends Event
{
    abstract public static function getId();
    abstract public static function getDescription();

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::EMAIL;
    }

    public function setEmailHeaders(array $emailHeaders = [])
    {
        $this->emailHeaders = $emailHeaders;

        return $this;
    }

    public function getEmailHeaders()
    {
        return $this->emailHeaders;
    }

    public function setResolvedEmailHeaders(array $resolvedEmailHeaders = [])
    {
        $this->resolvedEmailHeaders = $resolvedEmailHeaders;

        return $this;
    }

    public function getResolvedEmailHeaders()
    {
        return $this->resolvedEmailHeaders;
    }
}
