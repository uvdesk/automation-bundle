<?php
namespace Webkul\UVDesk\AutomationBundle\Controller\Automations;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Actions extends Controller
{    
    const ROLE_REQUIRED_AUTO = 'ROLE_AGENT_MANAGE_WORKFLOW_AUTOMATIC';
    const ROLE_REQUIRED_MANUAL = 'ROLE_AGENT_MANAGE_WORKFLOW_MANUAL';

    public function trans($text)
    {
        return $this->container->get('translator')->trans($text);
    }

    public function getEntity(Request $request) 
    {
        $workflowActionId = $request->attributes->get('entity');

        foreach ($this->container->get('workflow.listener.alias')->getRegisteredWorkflowActions() as $workflowAction) {
            if ($workflowAction->getId() == $workflowActionId) {
                $options = $workflowAction->getOptions($this->container);
                dump($options); die;
                if (!empty($options)) {
                    return new Response(json_encode($options), 200, ['Content-Type' => 'application/json']);
                }
                break;
            }
        }
        return new Response(json_encode([
            'alertClass'    => 'danger',
            'alertMessage'  => 'Warning! You are not allowed to perform this action.',
        ]), 200, ['Content-Type' => 'application/json']);

        // switch ($request->attributes->get('entity')) {
        //     case 'cc':
        //     case 'bcc':
        //         $results = $this->container->get('user.service')->getCustomersPartial();
        //         $jsonResults[] = ['id' => 'responsePerforming', 'name' => $this->trans('action.responsePerforming.agent')];
        //         foreach ($results as $key => $result) {
        //             $jsonResults[] = [
        //                         'id' => $result['email'],
        //                         'name' => $result['email'],
        //                         ];
        //         }
        //         $json = json_encode($jsonResults);
        //         $results = [];
        //         break;
        // }
    }
}
