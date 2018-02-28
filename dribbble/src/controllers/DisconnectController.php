<?php
/**
 * @author     ByMayo
 * @package    Dribbble
 * @since      2.0.0
 * @copyright  Copyright (c) 2018 ByMayo
 */

namespace bymayo\dribbble\controllers;

use bymayo\dribbble\Dribbble;

use Craft;
use craft\web\Controller;

class DisconnectController extends Controller
{

    // Protected Properties
    // =========================================================================
    
    protected $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
	    
	    Dribbble::getInstance()->dribbbleService->disconnect();
	    return $this->redirect('settings/plugins/dribbble');
	    
    }
	
}
