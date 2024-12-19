<?php

namespace Webkul\UVDesk\AutomationBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\DependencyInjection\ContainerInterface;

// @TODO: Refactor this code
class ActivityEvent extends Event
{
    private $entity;
    private $eventName;
    private $container;
    private $user;
    private $targetEntity;
    private $userType;
    private $notePlaceholders;
    private $subject;
    private $socialMedium;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setParameters($params)
    {
        $this->entity = $params['entity'];
        $this->eventName = $params['event'];
        $this->userType = isset($params['userType']) ? $params['userType'] : 'agent';

        if (isset($params['notePlaceholders'])) {
            $this->notePlaceholders = $params['notePlaceholders'];
        }

        if (isset($params['targetEntity'])) {
            $this->targetEntity = $params['targetEntity'];
        }

        if (isset($params['user'])) {
            $this->user = $params['user'];
        }

        if (
            isset($params['subject']) 
            && $params['subject'] != ''
        ) {
            $this->subject = $params['subject'];
        }

        $this->socialMedium = isset($params['socialMedium']) ? $params['socialMedium'] : false;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getNotePlaceholders()
    {
        return $this->notePlaceholders;
    }

    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getCurrentUser()
    {
        $user = $this->container->get('user.service')->getSessionUser();

        return !empty($user) ? $user : $this->user;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getSocialMedium()
    {
        return $this->socialMedium;
    }
}
