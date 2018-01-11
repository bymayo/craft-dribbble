<?php
namespace Craft;

/**
 * Navigation controller
 */
class DribbbleController extends BaseController
{
    
    public function actionConnect()
    {
	    
	    if (!craft()->dribbble->connect())
	    {
		    craft()->userSession->setError(Craft::t('Couldn’t connect to Dribbble.'));
	    }
	    
	    $this->redirect('settings/plugins/dribbble');
	    
    }
    
    public function actionDisconnect()
    {
	    
	    craft()->dribbble->disconnect();
	    $this->redirect('settings/plugins/dribbble');
	    
	}
    
}
