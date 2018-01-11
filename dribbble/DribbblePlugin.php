<?php
/**
 * Dribbble
 *
 * @author    Jason Mayo
 * @twitter   @madebymayo
 * @package   Dribbble
 *
 */

namespace Craft;

class DribbblePlugin extends BasePlugin
{

    public function init()
    {
	    
		require_once __DIR__ . '/vendor/autoload.php';
	    
        parent::init();
        
    }

    public function getName()
    {
         return Craft::t('Dribbble');
    }
    public function getDescription()
    {
        return Craft::t('Connect to Dribbble API to pull shots, buckets, user etc data.');
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/bymayo/dribbble/blob/master/README.md';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/bymayo/dribbble/master/releases.json';
    }

    public function getVersion()
    {
        return '1.0.1';
    }

    public function getSchemaVersion()
    {
        return '1.0.1';
    }

    public function getDeveloper()
    {
        return 'ByMayo';
    }

    public function getDeveloperUrl()
    {
        return 'bymayo.co.uk';
    }

    public function hasCpSection()
    {
        return false;
    }

    protected function defineSettings()
    {
        return array(
            'clientId' => array(AttributeType::String, 'label' => 'Client ID', 'default' => ''),
            'clientSecret' => array(AttributeType::String, 'label' => 'Client Secret', 'default' => ''),
            'oauthAccessToken' => array(AttributeType::String, 'label' => 'oAuth Access Token', 'default' => '')
        );
    }

    public function getSettingsHtml()
    {
		return craft()->templates->render(
		'dribbble/settings', 
			array(
				'settings' => $this->getSettings()
			)
		);
    }

}