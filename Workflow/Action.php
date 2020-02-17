<?php

namespace Webkul\UVDesk\AutomationBundle\Workflow;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;

abstract class Action
{
    public static function getId()
    {
        return null;
    }

    public static function getDescription()
    {
        return null;
    }

    public static function getFunctionalGroup()
    {
        return null;
    }

    public static function dynamicTranslation($data) : string
    {
        $request = Request::createFromGlobals(); 
        $path = $request->getPathInfo(); 
        $locale = explode("/", $path);
        $translator = new Translator($locale[1]);

        switch($locale[1])
        {
            case 'en':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.en.yml", 'en');
                break;
            case 'es':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.es.yml", 'es');
                break;
            case 'fr':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.fr.yml", 'fr');
                break;
            case 'da':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.da.yml", 'da');
                break;
            case 'de':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.de.yml", 'de');
                break;
            case 'it':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.it.yml", 'it');
                break;
            case 'ar':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.ar.yml", 'ar');
                break;
            case 'tr':
                $translator->addLoader('yaml', new YamlFileLoader()); 
                $translator->addResource('yaml',__DIR__."/../../../../../translations/messages.tr.yml", 'tr');
                break;
        }

        return $translator->trans($data); 
    }

    abstract public static function getOptions(ContainerInterface $container);
    abstract public static function applyAction(ContainerInterface $container, $entity, $value);
}
