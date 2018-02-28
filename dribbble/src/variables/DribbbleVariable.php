<?php
 
/**
 * @author     ByMayo
 * @package    Dribbble
 * @since      2.0.0
 * @copyright  Copyright (c) 2018 ByMayo
 */

namespace bymayo\dribbble\variables;

use bymayo\dribbble\Dribbble;

use Craft;

class DribbbleVariable
{
	
    // Public Methods
    // =========================================================================
    
    public function get($type, $limit = 10)
    {
	    return Dribbble::$plugin->dribbbleService->get($type, $limit);
    }
    
}
