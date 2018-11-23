<?php

namespace Webkul\UVDesk\AutomationBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webkul\UVDesk\AutomationBundle\Entity\Workflow;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\UVDesk\CoreBundle\Entity\Ticket;
use Webkul\UVDesk\AutomationBundle\Workflow\Event as WorkflowEvent;
use Webkul\UVDesk\AutomationBundle\Workflow\Action as WorkflowAction;

class WorkflowListener
{
    private $container;
    private $entityManager;
    public $notePlaceholders = array();
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
    // Apply manual workflow(Prepared response)
    public function applyResponse($event , $object) {
        if($object instanceof Ticket && $object->getIsTrashed())
            return;
        $this->object = $object;
        $translator = $this->container->get('translator');
        foreach($event->getActions() as $action) {
            switch ($action['type']) {
                case 'uvdesk.ticket.update_priority':
                    if( ($object instanceof Ticket || $object instanceof Task) && $action['value'] ) {
                        $priority = $this->entityManager->getRepository('UVDeskCoreBundle:TicketPriority')->find($action['value']);
                        $object->setPriority($priority);
                        $this->entityManager->persist($object);
                        $this->entityManager->flush();
                    }
                    break;
                case 'uvdesk.ticket.update_type':
                    if($object instanceof Ticket && $action['value']) {
                        $type = $this->entityManager->getRepository('UVDeskCoreBundle:TicketType')->find($action['value']);
                        if($type) {
                            $object->setType($type);
                            $this->entityManager->persist($object);
                            $this->entityManager->flush();
                        } else {
                            // Ticket Type Not Found. Disable Workflow/Prepared Response
                            $this->disableEvent($event, $object);
                        }
                    }
                    break;
                case 'uvdesk.ticket.update_status':
                    if($object instanceof Ticket && $action['value']) {
                        $status = $this->entityManager->getRepository('UVDeskCoreBundle:TicketStatus')->find($action['value']);
                        $object->setStatus($status);
                        $this->entityManager->persist($object);
                        $this->entityManager->flush();
                        //$this->container->get('ticket.service')->calculateResolveTime($object);
                    }
                    break;
                case 'uvdesk.ticket.update_tag':
                    if($object instanceof Ticket) {
                        $isAlreadyAdded = 0;
                        $tags = $this->container->get('ticket.service')->getTicketTagsById($object->getId());
                        if(is_array($tags)) {
                            foreach ($tags as $tag) {
                                if($tag['id'] == $action['value'])
                                    $isAlreadyAdded = 1;
                            }
                        }
                        if(!$isAlreadyAdded) {
                            $tag = $this->entityManager->getRepository('UVDeskCoreBundle:Tag')->find($action['value']);
                            if($tag) {
                                $object->addSupportTag($tag);
                                $this->entityManager->persist($object);
                                $this->entityManager->flush();
                            } else {
                                // Ticket Tag Not Found. Disable Workflow/Prepared Response
                                //$this->disableEvent($event, $object);
                            }
                        }
                    }
                    break;
                case 'uvdesk.agent.add_note':
                    if($object instanceof Ticket) {
                        $data = array();
                        $data['ticket'] = $object;
                        $data['threadType'] = 'note';
                        $data['source'] = 'website';
                        $data['message'] = $action['value']; 
                        $data['createdBy'] = 'System';
                        $this->container->get('ticket.service')->createThread($object,$data);
                    }
                    break;
                // case 'mail_last_collaborator':
                //     if($object instanceof Ticket) {
                //         $emailTemplate = $this->container->get('email.service')->getEmailTemplate($action['value']);
                //         if(count($object->getCollaborators()) && $emailTemplate) {
                //             $mailData = array();
                //             $createThread = $this->container->get('ticket.service')->getCreateReply($object->getId(),false);
                //             $mailData['references'] = $createThread['messageId'];
                //             $mailData['replyTo'] = $object->getUniqueReplyTo();
                //             if(!$object->lastCollaborator) {
                //                 try {
                //                     $object->lastCollaborator = $object->getCollaborators()[ -1 + count($object->getCollaborators()) ];
                //                 } catch(\Exception $e) {
                //                 }
                //             }
                //             if($object->lastCollaborator) {
                //                 $mailData['email'] = $object->lastCollaborator->getEmail();
                //                 $placeHolderValues = $this->container->get('ticket.service')->getTicketPlaceholderValues($object,'customer');
                //                 $mailData['subject'] = $this->container->get('email.service')
                //                                             ->getProcessedSubject($emailTemplate->getSubject(),$placeHolderValues);
                //                 $mailData['message'] = $this->container->get('email.service')
                //                                             ->getProcessedTemplate($emailTemplate->getMessageInline(),$placeHolderValues);
                //                 $this->sendMail($mailData);
                //             }
                //         }
                //     }
                //     break;
                case 'uvdesk.ticket.assign_agent':
                    if($object instanceof Ticket) {
                        if ($action['value'] == 'responsePerforming' && is_object($currentUser = $this->container->get('security.context')->getToken()->getUser())) {
                            $agent = $currentUser;
                        } else {
                            $agent = $this->entityManager->getRepository('UVDeskCoreBundle:User')->find($action['value']);
                            if ($agent) {
                                $agent = $this->entityManager->getRepository('UVDeskCoreBundle:User')->findOneBy(array('email' => $agent->getEmail()));
                            }
                        }
                        if ($agent) {
                            if($this->entityManager->getRepository('UVDeskCoreBundle:User')->findOneBy(array('id' => $agent->getId()))) {
                                $object->setAgent($agent);
                                $this->entityManager->persist($object);
                                $this->entityManager->flush();
                            }
                        } else {
                            // Agent Not Found. Disable Workflow/Prepared Response
                            $this->disableEvent($event, $object);
                        }
                    }
                    break;
                    case 'uvdesk.ticket.assign_group':
                    if($object instanceof Ticket) {
                        $group = $this->entityManager->getRepository('UVDeskCoreBundle:SupportGroup')->find($action['value']);
                        if($group) {
                            $object->setSupportGroup($group);
                            $this->entityManager->persist($object);
                            $this->entityManager->flush();
                        } else {
                            // User Group Not Found. Disable Workflow/Prepared Response
                            $this->disableEvent($event, $object);
                        }
                    }
                    break;
                case 'uvdesk.ticket.assign_team':
                    if($object instanceof Ticket) {
                        $subGroup = $this->entityManager->getRepository('UVDeskCoreBundle:SupportTeam')->find($action['value']);
                        if($subGroup) {
                            $object->setSupportTeam($subGroup);
                            $this->entityManager->persist($object);
                            $this->entityManager->flush();
                        } else {
                            // User Sub Group Not Found. Disable Workflow/Prepared Response
                            $this->disableEvent($event, $object);
                        }
                    }
                    break;
                case 'uvdesk.ticket.delete':
                    if($object instanceof Ticket) {
                        $this->entityManager->remove($object);
                        $this->entityManager->flush();
                    }
                    break;
                case 'uvdesk.ticket.mark_spam':
                    if($object instanceof Ticket) {
                        $status = $this->entityManager->getRepository('UVDeskCoreBundle:TicketStatus')->find(6);
                        $object->setStatus($status);
                        $this->entityManager->persist($object);
                        $this->entityManager->flush();
                    }
                    break;
            }
        }
    }
}
