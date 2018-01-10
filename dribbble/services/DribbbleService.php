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
		return 'https://api.dribbble.com/v2/' . $type . '?per_page=' . $limit .'&access_token=' . $this->getSetting('accessToken');
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
	    
		$client  = new \Guzzle\Http\Client(
			$authUrl, 
			array(
				'request.options' => array(
					'exceptions' => false,
				)
			)
		);
		$request = $client->get($authUrl);
		$response = $request->send();
		
		return $this->parseJson($response->getBody(true), true);
	    
    }
    
    public function get($type, $limit)
    {
	    
	    return $this->request($this->auth($type, $limit));
		
    }
    
    public function oauth()
	{
		
		$provider = new \CrewLabs\OAuth2\Client\Provider\Dribbble([
		    'clientId'          => '0643f71110b2d862e9e8a9b7b4f029015076cbde5c4513fb87b55a20f5f8ec36',
		    'clientSecret'      => '7e9af7d98edc65b3d30cf7ca1ebaa42739d42cab4b778415b90769d964974e2d',
		    'redirectUri'       => 'http://madebyshape.local/admin/dribbble/oauth',
		]);
		
		if (!isset($_GET['code'])) {
		
		    $authUrl = $provider->getAuthorizationUrl();
		    $_SESSION['oauth2state'] = $provider->getState();
		    header('Location: '. $authUrl);
		    exit;
		
		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
		
		    unset($_SESSION['oauth2state']);
		    exit('Invalid state');
		
		} else {
		
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
		    
		    DribbblePlugin::log('Test');
		    DribbblePlugin::log($token->getToken());
		    
		    return true;

		}
		
	}

}