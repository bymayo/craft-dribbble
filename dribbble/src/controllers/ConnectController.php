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

class ConnectController extends Controller
{

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
	    
	    $redirect = $this->redirect('settings/plugins/dribbble');
	    
	    if (!Dribbble::$plugin->dribbbleService->connect())
	    {
		    Craft::$app->getSession()->setError(\Craft::t('app', 'Couldn’t connect to Dribbble.'));
			return $redirect;
	    }
	    
		return $redirect;
	    
    }
	
}
