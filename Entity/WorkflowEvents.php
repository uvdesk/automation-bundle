<?php

namespace Webkul\UVDesk\AutomationBundle\Entity;

/**
 * WorkflowEvents
 */
class WorkflowEvents
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $eventId;

    /**
     * @var string
     */
    private $event;

    /**
     * @var \Webkul\UVDesk\AutomationBundle\Entity\Workflow
     */
    private $workflow;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set eventId
     *
     * @param integer $eventId
     *
     * @return WorkflowEvents
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Get eventId
     *
     * @return integer
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set event
     *
     * @param string $event
     *
     * @return WorkflowEvents
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set workflow
     *
     * @param \Webkul\UVDesk\AutomationBundle\Entity\Workflow $workflow
     *
     * @return WorkflowEvents
     */
    public function setWorkflow(\Webkul\UVDesk\AutomationBundle\Entity\Workflow $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return \Webkul\UVDesk\AutomationBundle\Entity\Workflow
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }
}

