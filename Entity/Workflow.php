<?php

namespace Webkul\UVDesk\AutomationBundle\Entity;

/**
 * Workflow
 */
class Workflow
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $conditions;

    /**
     * @var array
     */
    private $actions;

    /**
     * @var integer
     */
    private $sortOrder;

    /**
     * @var boolean
     */
    private $isPredefind = true;

    /**
     * @var boolean
     */
    private $status = true;

    /**
     * @var \DateTime
     */
    private $dateAdded;

    /**
     * @var \DateTime
     */
    private $dateUpdated;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $workflowEvents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->workflowEvents = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Workflow
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Workflow
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set conditions
     *
     * @param array $conditions
     *
     * @return Workflow
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Get conditions
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Set actions
     *
     * @param array $actions
     *
     * @return Workflow
     */
    public function setActions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Get actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return Workflow
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Set isPredefind
     *
     * @param boolean $isPredefind
     *
     * @return Workflow
     */
    public function setIsPredefind($isPredefind)
    {
        $this->isPredefind = $isPredefind;

        return $this;
    }

    /**
     * Get isPredefind
     *
     * @return boolean
     */
    public function getIsPredefind()
    {
        return $this->isPredefind;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Workflow
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Workflow
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     *
     * @return Workflow
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Add workflowEvent
     *
     * @param \Webkul\UVDesk\AutomationBundle\Entity\WorkflowEvents $workflowEvent
     *
     * @return Workflow
     */
    public function addWorkflowEvent(\Webkul\UVDesk\AutomationBundle\Entity\WorkflowEvents $workflowEvent)
    {
        $this->workflowEvents[] = $workflowEvent;

        return $this;
    }

    /**
     * Remove workflowEvent
     *
     * @param \Webkul\UVDesk\AutomationBundle\Entity\WorkflowEvents $workflowEvent
     */
    public function removeWorkflowEvent(\Webkul\UVDesk\AutomationBundle\Entity\WorkflowEvents $workflowEvent)
    {
        $this->workflowEvents->removeElement($workflowEvent);
    }

    /**
     * Get workflowEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkflowEvents()
    {
        return $this->workflowEvents;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        // Add your code here
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        // Add your code here
    }
}

