<?php

namespace Webkul\UVDesk\AutomationBundle\Workflow\Events;

use Webkul\UVDesk\AutomationBundle\Workflow\Event;
use Webkul\UVDesk\AutomationBundle\Workflow\FunctionalGroup;
use Webkul\UVDesk\CoreFrameworkBundle\Entity\Ticket;
use Webkul\UVDesk\CoreFrameworkBundle\Entity\Thread;

abstract class TicketActivity extends Event
{
    abstract public static function getId();
    abstract public static function getDescription();

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::TICKET;
    }

    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setThread(Thread $thread)
    {
        $this->thread = $thread;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }
}
