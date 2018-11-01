<?php

namespace Webkul\UVDesk\AutomationBundle\Controller\Automations;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WorkflowXHR extends Controller
{
    public function workflowsListXhr(Request $request)
    {
        if (!$this->get('user.service')->checkPermission('ROLE_AGENT_MANAGE_WORKFLOW_AUTOMATIC')) {
            return $this->redirect($this->generateUrl('helpdesk_member_dashboard'));
        }

        $json = [];
        $repository = $this->getDoctrine()->getRepository('UVDeskAutomationBundle:Workflow');
        $json = $repository->getWorkflows($request->query, $this->container);

        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function WorkflowsxhrAction(Request $request)
    {
        if (!$this->get('user.service')->checkPermission('ROLE_AGENT_MANAGE_WORKFLOW_AUTOMATIC')) {
            return $this->redirect($this->generateUrl('helpdesk_member_dashboard'));
        }
        
        $json = [];
        $error = false;
        if($request->isXmlHttpRequest()){
            if($request->getMethod() == 'POST'){
                $em = $this->getDoctrine()->getManager();
                //sort order update
                $workflows = $em->getRepository("UVDeskAutomationBundle:Workflow")->findAll();
                   
                $sortOrders = $request->request->get('orders');
                if(count($workflows)) {
                    foreach ($workflows as $id => $workflow) {
                        if(!empty($sortOrders[$workflow->getId()])) {
                            $workflow->setSortOrder($sortOrders[$workflow->getId()]);
                            $em->persist($workflow);
                        } else {
                            $error = true;
                            break;                        
                        }
                    }
                    $em->flush();
                }
                if(!$error) {
                    $json['alertClass'] = 'success';
                    $json['alertMessage'] = $this->get('translator')->trans('Success! Order has been updated successfully.');
                }
            }
            elseif($request->getMethod() == 'DELETE') {
                //$this->isAuthorized(self::ROLE_REQUIRED_AUTO);

                $em = $this->getDoctrine()->getManager();
                $id = $request->attributes->get('id');
                //$workFlow = $this->getWorkflow($id, 'Events');
                $workFlow = $em->getRepository("UVDeskAutomationBundle:Workflow")
                            ->findOneBy(array('id' => $id));

                if (!empty($workFlow)) {
                    $em->remove($workFlow);
                    $em->flush();
                } else {
                    $error = true;
                }

                if (!$error) {
                    $json['alertClass'] = 'success';
                    $json['alertMessage'] = $this->get('translator')->trans('Success! Workflow has been removed successfully.');
                }
            }
        }
        if($error){
            $json['alertClass'] = 'danger';
            $json['alertMessage'] = $this->get('translator')->trans('Warning! You are not allowed to perform this action.');
        }
        $response = new Response(json_encode($json));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
