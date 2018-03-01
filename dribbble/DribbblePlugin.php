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
        return Craft::t('Connect to Dribbble API to pull in shots, projects, user etc via Twig.');
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/bymayo/dribbble/blob/craft-2/README.md';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://github.com/bymayo/dribbble/blob/craft-2/releases.json';
    }

    public function getVersion()
    {
        return '1.0.3';
    }

    public function getSchemaVersion()
    {
        return '1.0.3';
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