<?php

namespace Webkul\UVDesk\AutomationBundle\UIComponents\Dashboard\Homepage\Items;

use Webkul\UVDesk\CoreFrameworkBundle\Dashboard\Segments\HomepageSectionItem;
use Webkul\UVDesk\CoreFrameworkBundle\UIComponents\Dashboard\Homepage\Sections\Productivity;

class PreparedResponses extends HomepageSectionItem
{
    CONST SVG = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="60px" height="60px" viewBox="0 0 60 60">
    <path fill-rule="evenodd" d="M25.783,21.527L10.245,6.019,6.016,10.248,21.524,25.756ZM37.512,6.019l6.119,6.119L6.016,49.783l4.229,4.229L47.89,16.4l6.119,6.119V6.019h-16.5ZM38.5,34.245l-4.229,4.229,9.389,9.389-6.149,6.149h16.5v-16.5L47.89,43.634Z"></path>
</svg>
SVG;

    public static function getIcon() : string
    {
        return self::SVG;
    }

    public static function getTitle() : string
    {
        return "Prepared Responses";
    }

    public static function getRouteName() : string
    {
        return 'prepare_response_action';
    }

    public static function getRoles() : array
    {
        return ['ROLE_AGENT_MANAGE_WORKFLOW_MANUAL'];
    }

    public static function getSectionReferenceId() : string
    {
        return Productivity::class;
    }
}
