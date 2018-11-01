<?php

namespace Webkul\UVDesk\AutomationBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webkul\UVDesk\AutomationBundle\Entity\Workflow;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\UVDesk\AutomationBundle\Workflow\Event as WorkflowEvent;
use Webkul\UVDesk\AutomationBundle\Workflow\Action as WorkflowAction;

class WorkflowListener
{
    private $container;
    private $entityManager;
    private $registeredWorkflowEvents = [];
    private $registeredWorkflowActions = [];

    public function __construct(ContainerInterface $container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    public function registerWorkflowEvent(WorkflowEvent $serviceTag)
    {
        $this->registeredWorkflowEvents[] = $serviceTag;
    }

    public function registerWorkflowAction(WorkflowAction $serviceTag)
    {
        $this->registeredWorkflowActions[] = $serviceTag;
    }

    public function getRegisteredWorkflowEvent($eventId)
    {
        foreach ($this->registeredWorkflowEvents as $workflowDefinition) {
            if ($workflowDefinition->getId() == $eventId) {
                return $workflowDefinition;
            }
        }

        return null;
    }

    public function getRegisteredWorkflowEvents()
    {
        return $this->registeredWorkflowEvents;
    }

    public function getRegisteredWorkflowActions()
    {
        return $this->registeredWorkflowActions;
    }

    public function executeWorkflow(GenericEvent $event)
    {
        $workflowCollection = $this->entityManager->getRepository('UVDeskAutomationBundle:Workflow')->getEventWorkflows($event->getSubject());

        if (!empty($workflowCollection)) {
            foreach ($workflowCollection as $workflow) {
                $totalConditions = 0;
                $totalEvaluatedConditions = 0;

                foreach ($this->evaluateWorkflowConditions($workflow) as $workflowCondition) {
                    dump($workflowCondition);
                    die;
                    // $totalEvaluatedConditions++;

                    // if (isset($workflowCondition['type']) && $this->checkCondition($workflowCondition)) {
                    //     $totalConditions++;
                    // }

                    // if (isset($workflowCondition['or'])) {
                    //     foreach ($workflowCondition['or'] as $orCondition) {
                    //         $flag = $this->checkCondition($orCondition);
                    //         if ($flag) {
                    //             $totalConditions++;
                    //         }
                    //     }
                    // }
                }

                if ($totalEvaluatedConditions > 0 || $totalConditions >= $totalEvaluatedConditions) {
                    $this->applyWorkflowActions($workflow , $event->getArgument('entity'));
                }
            }
        }
    }

    private function evaluateWorkflowConditions(Workflow $workflow)
    {
        $index = -1;
        $workflowConditions = [];

        if ($workflow->getConditions() == null) {
            return $workflowConditions;
        }

        foreach ($workflow->getConditions() as $condition) {
            if (!empty($condition['operation']) && $condition['operation'] != "&&") {
                if (!isset($finalConditions[$index]['or'])) {
                    $finalConditions[$index]['or'] = [];
                }

                $workflowConditions[$index]['or'][] = $condition;
            } else {
                $index++;
                $workflowConditions[] = $condition;
            }
        }

        return $workflowConditions;
    }

    private function applyWorkflowActions(Workflow $workflow, $entity)
    {
        foreach ($workflow->getActions() as $attributes) {
            if (empty($attributes['type'])) {
                continue;
            }

            foreach ($this->getRegisteredWorkflowActions() as $workflowAction) {
                if ($workflowAction->getId() == $attributes['type']) {
                    $workflowAction->applyAction($this->container, $entity, $attributes['value']);
                }
            }
        }
    }
}
