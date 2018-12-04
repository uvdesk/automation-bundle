<?php

namespace Webkul\UVDesk\AutomationBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Webkul\UVDesk\AutomationBundle\Workflow\FunctionalGroup;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AutomationService
{
	private $container;
	private $requestStack;
    private $entityManager;

	public function __construct(ContainerInterface $container, RequestStack $requestStack, EntityManager $entityManager)
	{
		$this->container = $container;
		$this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function getWorkflowEvents()
    {
        return [
            FunctionalGroup::AGENT => 'Agent',
            FunctionalGroup::CUSTOMER => 'Customer',
            FunctionalGroup::TICKET => 'Ticket',
        ];
    }

    public function getWorkflowEventValues()
    {
        $ticketEventCollection = [];

        foreach ($this->container->get('workflow.listener.alias')->getRegisteredWorkflowEvents() as $workflowDefinition) {
            $functionalGroup = $workflowDefinition->getFunctionalGroup();

            if (!isset($ticketEventCollection[$functionalGroup])) {
                $ticketEventCollection[$functionalGroup] = [];
            }

            $ticketEventCollection[$functionalGroup][$workflowDefinition->getId()] = $workflowDefinition->getDescription();
        }

        return $ticketEventCollection;
    }

    public function getWorkflowConditions()
    {
        $conditions = [
            'ticket' => [
                'mail' => [
                    [
                        'lable' => 'From Email',
                        'value' => 'from_mail',
                        'match' => 'email'
                    ],
                    [
                        'lable' => 'To Email',
                        'value' => 'to_mail',
                        'match' => 'email'
                    ],
                ],
                'ticket' => [
                    [
                        'lable' => 'Subject',
                        'value' => 'subject',
                        'match' => 'string'
                    ],
                    [
                        'lable' => 'Description',
                        'value' => 'description',
                        'match' => 'string'
                    ],
                    [
                        'lable' => 'Subject or Description',
                        'value' => 'subject_or_description',
                        'match' => 'string'
                    ],
                    [
                        'lable' => 'Priority',
                        'value' => 'TicketPriority',
                        'match' => 'select'
                    ],
                    [
                        'lable' => 'Type',
                        'value' => 'TicketType',
                        'match' => 'select'
                    ],
                    [
                        'lable' => 'Status',
                        'value' => 'TicketStatus',
                        'match' => 'select'
                    ],
                    [
                        'lable' => 'Source',
                        'value' => 'source',
                        'match' => 'select'
                    ],
                    [
                        'lable' => 'Created',
                        'value' => 'created',
                        'match' => 'date'
                    ],
                    [
                        'lable' => 'Agent',
                        'value' => 'agent',
                        'match' => 'select'
                    ],
                    [
                        'lable' => 'Group',
                        'value' => 'group',
                        'match' => 'select'
                    ],
                    [
                        'lable' => 'Team',
                        'value' => 'team',
                        'match' => 'select'
                    ],
                ],
                'customer' => [
                    [
                        'lable' => 'Customer Name',
                        'value' => 'customer_name',
                        'match' => 'string'
                    ],
                    [
                        'lable' => 'Customer Email',
                        'value' => 'customer_email',
                        'match' => 'email'
                    ],
                ],
            ],
        ];
        
        return $conditions;
    }

    public function getWorkflowMatchConditions()
    {
        return [
            'email' => [
                [
                    'lable' => 'Is Equal To',
                    'value' => 'is'
                ],
                [
                    'lable' => 'Is Not Equal To',
                    'value' => 'isNot'
                ],
                [
                    'lable' => 'Contains',
                    'value' => 'contains'
                ],
                [
                    'lable' => 'Does Not Contain',
                    'value' => 'notContains'
                ],
            ],
            'string' => [
                [
                    'lable' => 'Is Equal To',
                    'value' => 'is'
                ],
                [
                    'lable' => 'Is Not Equal To',
                    'value' => 'isNot'
                ],
                [
                    'lable' => 'Contains',
                    'value' => 'contains'
                ],
                [
                    'lable' => 'Does Not Contain',
                    'value' => 'notContains'
                ],
                [
                    'lable' => 'Starts With',
                    'value' => 'startWith'
                ],
                [
                    'lable' => 'Ends With',
                    'value' => 'endWith'
                ],
            ],
            'select' => [
                [
                    'lable' => 'Is Equal To',
                    'value' => 'is'
                ],
                [
                    'lable' => 'Is Not Equal To',
                    'value' => 'isNot'
                ],
            ],
            'date' => [
                [
                    'lable' => 'Before',
                    'value' => 'before'
                ],
                [
                    'lable' => 'Before On',
                    'value' => 'beforeOn'
                ],
                [
                    'lable' => 'After',
                    'value' => 'after'
                ],
                [
                    'lable' => 'After On',
                    'value' => 'afterOn'
                ],
            ],
            'datetime' => [
                [
                    'lable' => 'Before',
                    'value' => 'beforeDateTime'
                ],
                [
                    'lable' => 'Before On',
                    'value' => 'beforeDateTimeOn'
                ],
                [
                    'lable' => 'After',
                    'value' => 'afterDateTime'
                ],
                [
                    'lable' => 'After On',
                    'value' => 'afterDateTimeOn'
                ],
            ],
            'time' => [
                [
                    'lable' => 'Before',
                    'value' => 'beforeTime'
                ],
                [
                    'lable' => 'Before On',
                    'value' => 'beforeTimeOn'
                ],
                [
                    'lable' => 'After',
                    'value' => 'afterTime'
                ],
                [
                    'lable' => 'After On',
                    'value' => 'afterTimeOn'
                ],
            ],
            'number' => [
                [
                    'lable' => 'Is Equal To',
                    'value' => 'is'
                ],
                [
                    'lable' => 'Is Not Equal To',
                    'value' => 'isNot'
                ],
                [
                    'lable' => 'Contains',
                    'value' => 'contains'
                ],
                [
                    'lable' => 'Greater Than',
                    'value' => 'greaterThan'
                ],
                [
                    'lable' => 'Less Than',
                    'value' => 'lessThan'
                ],
            ],
        ];
    }

    public function getWorkflowActions($force = false)
    {
        $ticketActionCollection = [];
        foreach ($this->container->get('workflow.listener.alias')->getRegisteredWorkflowActions() as $workflowDefinition) {
            $functionalGroup = $workflowDefinition->getFunctionalGroup();

            if (!isset($ticketActionCollection[$functionalGroup])) {
                $ticketActionCollection[$functionalGroup] = [];
            }

            $ticketActionCollection[$functionalGroup][$workflowDefinition->getId()] = $workflowDefinition->getDescription();
        }

        $actionRoleArray = [

             'ticket->TicketPriority' => 'ROLE_AGENT_UPDATE_TICKET_PRIORITY',
             'ticket->TicketType'     => 'ROLE_AGENT_UPDATE_TICKET_TYPE',
             'ticket->TicketStatus'   => 'ROLE_AGENT_UPDATE_TICKET_STATUS',
             'ticket->tag'            => 'ROLE_AGENT_ADD_TAG',
             'ticket->note'           => 'ROLE_AGENT_ADD_NOTE',
             'ticket->assign_agent'   => 'ROLE_AGENT_ASSIGN_TICKET',
             'ticket->assign_group'   => 'ROLE_AGENT_ASSIGN_TICKET_GROUP',
             'ticket->assign_team'    => 'ROLE_AGENT_ASSIGN_TICKET_GROUP',
             'ticket->mail_agent'     => 'ROLE_AGENT',
             'ticket->mail_group'     => 'ROLE_AGENT_MANAGE_GROUP',
             'ticket->mail_team'      => 'ROLE_AGENT_MANAGE_SUB_GROUP',
             'ticket->mail_customer'  => 'ROLE_AGENT',
             'ticket->mail_last_collaborator' => 'ROLE_AGENT',
             'ticket->delete_ticket'  => 'ROLE_AGENT_DELETE_TICKET',
             'ticket->mark_spam'      => 'ROLE_AGENT_UPDATE_TICKET_STATUS',

             'task->reply' => 'ROLE_AGENT',
             'task->mail_agent' => 'ROLE_AGENT',
             'task->mail_members' => 'ROLE_AGENT',
             'task->mail_last_member' => 'ROLE_AGENT',

             'customer->mail_customer' => 'ROLE_AGENT',

             'agent->mail_agent' => 'ROLE_AGENT',
             'agent->ticket_transfer' => 'ROLE_AGENT_ASSIGN_TICKET',
             'agent->task_transfer' => 'ROLE_AGENT_EDIT_TASK',
        ];

        // $resultArray = [];
        // foreach($actionRoleArray as $action => $role) {
        //     if($role == 'ROLE_AGENT' || $this->container->get('user.service')->checkPermission($role) || $force) {
        //         $actionPath = explode('->', $action);
        //         $resultArray[$actionPath[0]][$actionPath[1]] = $actionArray[$actionPath[0]][$actionPath[1]];
        //     }
        // }

        return $ticketActionCollection;
    }

    public function getPreparedResponseActions($force = false)
    {
        $ticketActionCollection = [];
        foreach ($this->container->get('prepared_response.listener.alias')->getRegisteredPreparedResponseActions() as $preparedResponseDefinition) {
            $functionalGroup = $preparedResponseDefinition->getFunctionalGroup();

            if (!isset($ticketActionCollection[$functionalGroup])) {
                $ticketActionCollection[$functionalGroup] = [];
            }

            $ticketActionCollection[$functionalGroup][$preparedResponseDefinition->getId()] = $preparedResponseDefinition->getDescription();
        }

        $actionRoleArray = [

             'ticket->TicketPriority' => 'ROLE_AGENT_UPDATE_TICKET_PRIORITY',
             'ticket->TicketType'     => 'ROLE_AGENT_UPDATE_TICKET_TYPE',
             'ticket->TicketStatus'   => 'ROLE_AGENT_UPDATE_TICKET_STATUS',
             'ticket->tag'            => 'ROLE_AGENT_ADD_TAG',
             'ticket->note'           => 'ROLE_AGENT_ADD_NOTE',
             'ticket->assign_agent'   => 'ROLE_AGENT_ASSIGN_TICKET',
             'ticket->assign_group'   => 'ROLE_AGENT_ASSIGN_TICKET_GROUP',
             'ticket->assign_team'    => 'ROLE_AGENT_ASSIGN_TICKET_GROUP',
             'ticket->mail_agent'     => 'ROLE_AGENT',
             'ticket->mail_group'     => 'ROLE_AGENT_MANAGE_GROUP',
             'ticket->mail_team'      => 'ROLE_AGENT_MANAGE_SUB_GROUP',
             'ticket->mail_customer'  => 'ROLE_AGENT',
             'ticket->mail_last_collaborator' => 'ROLE_AGENT',
             'ticket->delete_ticket'  => 'ROLE_AGENT_DELETE_TICKET',
             'ticket->mark_spam'      => 'ROLE_AGENT_UPDATE_TICKET_STATUS',

             'task->reply' => 'ROLE_AGENT',
             'task->mail_agent' => 'ROLE_AGENT',
             'task->mail_members' => 'ROLE_AGENT',
             'task->mail_last_member' => 'ROLE_AGENT',

             'customer->mail_customer' => 'ROLE_AGENT',

             'agent->mail_agent' => 'ROLE_AGENT',
             'agent->ticket_transfer' => 'ROLE_AGENT_ASSIGN_TICKET',
             'agent->task_transfer' => 'ROLE_AGENT_EDIT_TASK',
        ];

        // $resultArray = [];
        // foreach($actionRoleArray as $action => $role) {
        //     if($role == 'ROLE_AGENT' || $this->container->get('user.service')->checkPermission($role) || $force) {
        //         $actionPath = explode('->', $action);
        //         $resultArray[$actionPath[0]][$actionPath[1]] = $actionArray[$actionPath[0]][$actionPath[1]];
        //     }
        // }

        return $ticketActionCollection;
    }
}
