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

class DribbbleService extends BaseApplicationComponent
{
	
	public function getPlugin()
	{
		return craft()->plugins->getPlugin('dribbble');
	}
	
	public function getSetting($name)
	{
		return craft()->plugins->getPlugin('dribbble')->getSettings()->$name;
	}
	
	public function auth($type, $limit = null)
	{
		return 'https://api.dribbble.com/v2/' . $type . '?per_page=' . $limit .'&access_token=' . $this->getSetting('oauthAccessToken');
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
	    
		craft()->plugins->savePluginSettings(
	    	craft()->plugins->getPlugin('dribbble'), 
	    	array(
	    		'oauthAccessToken' => null
	    	)
	    );
	    
    }
    
    public function connect()
    {

		$provider = new \CrewLabs\OAuth2\Client\Provider\Dribbble([
		    'clientId'          => $this->getSetting('clientId'),
		    'clientSecret'      => $this->getSetting('clientSecret'),
		    'redirectUri'       => UrlHelper::getActionUrl('dribbble/connect'),
		]);
		
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
		    
		    craft()->plugins->savePluginSettings(
		    	craft()->plugins->getPlugin('dribbble'), 
		    	array(
		    		'oauthAccessToken' => $token->getToken()
		    	)
		    );
		    
		    return true;

		}

    }

}