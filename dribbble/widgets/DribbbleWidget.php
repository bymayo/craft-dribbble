<?php
/**
 * dribbble plugin for Craft CMS
 *
 * Dribbble Widget
 *
 * @author    Jason Mayo
 * @copyright Copyright (c) 2017 Jason Mayo
 * @link      bymayo.co.uk
 * @package   Dribbble
 * @since     1.0.0
 */
namespace Craft;
class DribbbleWidget extends BaseWidget
{
    /**
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('dribbble');
    }
    /**
     * @return mixed
     */
    public function getBodyHtml()
    {
        // Include our Javascript & CSS
        craft()->templates->includeCssResource('dribbble/css/widgets/DribbbleWidget.css');
        craft()->templates->includeJsResource('dribbble/js/widgets/DribbbleWidget.js');
        /* -- Variables to pass down to our rendered template */
        $variables = array();
        $variables['settings'] = $this->getSettings();
        return craft()->templates->render('dribbble/widgets/DribbbleWidget_Body', $variables);
    }
    /**
     * @return int
     */
    public function getColspan()
    {
        return 1;
    }
    /**
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'someSetting' => array(AttributeType::String, 'label' => 'Some Setting', 'default' => ''),
        );
    }
    /**
     * @return mixed
     */
    public function getSettingsHtml()
    {

/* -- Variables to pass down to our rendered template */

        $variables = array();
        $variables['settings'] = $this->getSettings();
        return craft()->templates->render('dribbble/widgets/DribbbleWidget_Settings',$variables);
    }

    /**
     * @param mixed $settings  The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings($settings)
    {

/* -- Modify $settings here... */

        return $settings;
    }
}