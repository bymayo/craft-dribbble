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
        parent::init();
    }

    public function getName()
    {
         return Craft::t('Dribbble');
    }
    public function getDescription()
    {
        return Craft::t('Gets data from Dribbble API');
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
        return '1.0.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Jason Mayo';
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
            'accessToken' => array(AttributeType::String, 'label' => 'Access Token', 'default' => ''),
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