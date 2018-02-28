<?php
/**
 * @author     ByMayo
 * @package    Dribbble
 * @since      2.0.0
 * @copyright  Copyright (c) 2018 ByMayo
 */

namespace bymayo\dribbble\services;

use bymayo\dribbble\Dribbble;

use Craft;
use craft\base\Component;
use craft\helpers\UrlHelper;
use craft\services\Plugins;

class DribbbleService extends Component
{
	
    // Public Methods
    // =========================================================================
	
	public function auth($type, $limit)
	{
		return 'https://api.dribbble.com/v2/' . $type . '?per_page=' . $limit .'&access_token=' . Dribbble::$plugin->getSettings()->oauthAccessToken;
	}
	
    public function parseJson($json)
    {

	     $jsonArray = array();
	     $jsonArray = json_decode($json, true);
	     
	     if (json_last_error() === JSON_ERROR_NONE) {
		     return $jsonArray;
		 }
		 
		 return false;

    }

    public function request($authUrl)
    {
	    
		$client  = new \GuzzleHttp\Client();
		$response = $client->request('GET', $authUrl);
		
		return $this->parseJson($response->getBody());
	    
    }
    
    public function get($type, $limit)
    {
	    return $this->request($this->auth($type, $limit));
    }
    
    public function disconnect()
    {
		return $this->saveAccessToken(null);
    }
    
    public function saveAccessToken($value)
    {
	    
		return Craft::$app->getPlugins()->savePluginSettings(
			    	Craft::$app->getPlugins()->getPlugin('dribbble'), 
			    	array(
			    		'oauthAccessToken' => $value
			    	)
			    );
	    
    }
    
    public function connect()
    {

		$provider = new \CrewLabs\OAuth2\Client\Provider\Dribbble(
			[
			    'clientId' => Dribbble::$plugin->getSettings()->clientId,
			    'clientSecret' => Dribbble::$plugin->getSettings()->clientSecret,
			    'redirectUri' => UrlHelper::actionUrl('dribbble/connect')
			]
		);
		
		if (!isset($_GET['code'])) 
		{
		
		    $authUrl = $provider->getAuthorizationUrl();
		    $_SESSION['oauth2state'] = $provider->getState();
		    header('Location: '. $authUrl);
		    exit;
		
		} 
		elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) 
		{
		
		    unset($_SESSION['oauth2state']);
		    return false;
		
		} 
		else 
		{
		
		    $token = $provider->getAccessToken(
		    	'authorization_code', 
		    	[
		        	'code' => $_GET['code']
				]
			);
			
			return $this->saveAccessToken($token->getToken());

		}

    }

}
