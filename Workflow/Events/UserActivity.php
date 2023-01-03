<?php

namespace Webkul\UVDesk\AutomationBundle\Workflow\Events;

use Webkul\UVDesk\AutomationBundle\Workflow\Event;
use Webkul\UVDesk\AutomationBundle\Workflow\FunctionalGroup;
use Webkul\UVDesk\CoreFrameworkBundle\Entity\User;

abstract class UserActivity extends Event
{
    abstract public static function getId();
    abstract public static function getDescription();

    public static function getFunctionalGroup()
    {
        return FunctionalGroup::USER;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
