<?php

namespace Webkul\UVDesk\AutomationBundle\UIComponents\Dashboard\Homepage\Items;

use Webkul\UVDesk\CoreFrameworkBundle\Dashboard\Segments\HomepageSectionItem;
use Webkul\UVDesk\CoreFrameworkBundle\UIComponents\Dashboard\Homepage\Sections\Productivity;

class Workflows extends HomepageSectionItem
{
    CONST SVG = <<<SVG
<svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
<g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
<g id="SVGRepo_iconCarrier"><path d="M7.5,15.5h-5a1,1,0,0,0-1,1v5a1,1,0,0,0,1,1h5a1,1,0,0,0,1-1V20H12a1,1,0,0,0,0-2H8.5V16.5A1,1,0,0,0,7.5,15.5Zm-1,5h-3v-3h3ZM4,8.858V13a1,1,0,0,0,2,0V8.858a4,4,0,1,0-2,0ZM5,3A2,2,0,1,1,3,5,2,2,0,0,1,5,3ZM20,15.142V12a1,1,0,0,0-2,0v3.142a4,4,0,1,0,2,0ZM19,21a2,2,0,1,1,2-2A2,2,0,0,1,19,21ZM16.5,8.5h5a1,1,0,0,0,1-1v-5a1,1,0,0,0-1-1h-5a1,1,0,0,0-1,1V4H12a1,1,0,0,0,0,2h3.5V7.5A1,1,0,0,0,16.5,8.5Zm1-5h3v3h-3Z"></path></g></svg>
SVG;

    public static function getIcon() : string
    {
        return self::SVG;
    }

    public static function getTitle() : string
    {
        return "Workflows";
    }

    public static function getRouteName() : string
    {
        return 'helpdesk_member_workflow_collection';
    }

    public static function getRoles() : array
    {
        return ['ROLE_AGENT_MANAGE_WORKFLOW_AUTOMATIC'];
    }

    public static function getSectionReferenceId() : string
    {
        return Productivity::class;
    }
}
