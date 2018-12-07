<?php
namespace Webkul\UVDesk\AutomationBundle\Controller\Automations;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Webkul\UserBundle\Controller\BaseController;

class Conditions extends Controller
{    
    const ROLE_REQUIRED = 'ROLE_AGENT_MANAGE_WORKFLOW';

    public function getEntity(Request $request) 
    {
        $json = $results = $jsonResults = array();
        $error = false;

        if($request->isXmlHttpRequest()){
            if($request->getMethod() == 'GET' && (in_array($request->attributes->get('entity'), ['TicketPriority', 'TicketType', 'TicketStatus', 'source', 'agent', 'group','team', 'agent_name', 'agent_email', 'stage']) || $this->isCustomFieldEntity($request->attributes->get('entity'))) ){

                switch ($request->attributes->get('entity')) {
                    case 'TicketPriority':
                    case 'TicketStatus':
                        $results = $this->getDoctrine()
                                        ->getRepository('UVDeskCoreBundle:'.ucfirst($request->attributes->get('entity')))
                                        ->findBy(
                                            array(
                                                    // 'companyId' => $this->getCurrentCompany()->getId()
                                                )
                                        );
                        foreach ($results as $key => $result) {
                            $jsonResults[] = [
                                        'id' => $result->getId(),
                                        'name' => $result->getCode(),
                                     ];
                        }
                        $json = json_encode($jsonResults);
                        $results = [];
                        break;
                    case 'TicketType':
                        $results = $this->container->get('ticket.service')->getTypes();
                        $json = json_encode($results);
                        $results = [];
                        break;
                    case 'group':
                        $results = $this->container->get('user.service')->getSupportGroups();
                        $json = json_encode($results);
                        $results = [];
                        break;
                    case 'team':
                        $results = $this->container->get('user.service')->getSupportTeams();
                        $json = json_encode($results);
                        $results = [];
                        break;
                    case 'agent_email':
                        $results = $this->container->get('user.service')->getAgentsPartialDetails();
                        foreach ($results as $key => $result) {
                            $jsonResults[] = [
                                        'id' => $result['id'],
                                        'name' => $result['email'],
                                     ];
                        }
                        $json = json_encode($jsonResults);
                        $results = [];
                        break;
                    case 'agent_name':
                    case 'agent':
                        $results = $this->container->get('user.service')->getAgentPartialDataCollection();
                        $jsonResults[] = [
                                            'id' => 'actionPerformingAgent',
                                            'name' => $this->translate('Action Performing Agent')
                                            
                                        ];
                        foreach ($results as $key => $result) {
                            $jsonResults[] = [
                                        'id' => $result['id'],
                                        'name' => $result['name'],
                                     ];
                        }
                        $json = json_encode($jsonResults);
                        $results = [];
                        break;
                    case 'stage':
                        $results = $this->container->get('task.service')->getStages();
                        $json = json_encode($results);
                        $results = [];
                        break;
                    case 'source':
                        $allSources = $this->container->get('ticket.service')->getAllSources();
                        $apps = [
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'knock' => 'Binaka',
                            'formbuilder' => 'Form Builder',
                            'disqus-engage' => 'Disqus Engage',
                        ];

                        $results = [];
                
                        foreach($allSources as $key => $source) {
                           // if(empty($apps[$key]) || $this->container->get('uvdesk.service')->isThisAppInstalled($apps[$key])) {
                                $results[] = [
                                            'id' => $key,
                                            'name' => $source,
                                        ];
                           // }
                        };
                        $json = json_encode($results);
                        $results = [];
                        break;
                    case $this->isCustomFieldEntity($request->attributes->get('entity') ):
                        $cfId = str_replace(['customFields[', ']'], ['', ''], $request->attributes->get('entity') );

                        $em = $this->getDoctrine()->getManager();
                        $cf = $em->getRepository('UVDeskCoreBundle:CustomFields')
                                         ->findOneBy(['id' => $cfId ]);
                        $cfValues = $cf->getCustomFieldValues();
                        if($cf) {
                            foreach($cfValues as $cfValue) {
                                $results[] = [
                                    'id'   => $cfValue->getId(),
                                    'name' => $cfValue->getName(),
                                ];
                            }
                        }
                        break;                        
                    default:
                        $json = '{}';
                        break;
                }
                if($results) {
                    $ignoredArray = ['__initializer__', '__cloner__', '__isInitialized__', 'description', 'color', 'company', 'createdAt', 'users', 'isActive'];
                    $json = $this->get('default.service')->getSerializeObj($ignoredArray)->serialize($results, 'json');
                }
            }else
                $error = true;
        }

        if($error){
            $json['alertClass'] = 'danger';
            $json['alertMessage'] = $this->get('translator')->trans('Warning! You are not allowed to perform this action.');
        }

        if(is_array($json)) {
            $json = json_encode($json);
        }
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    protected function mapToIdName($value)
    {
        return [
            'id'    => $value,
            'name'  => $value,
        ];
    }

    protected function isCustomFieldEntity($entity)
    {
        return 0 === strpos($entity, 'customFields[' );
    } 
    
    public function translate($string,$params = array())
    {
        return $this->get('translator')->trans($string,$params);
    }
}
