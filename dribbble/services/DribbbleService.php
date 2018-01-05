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

}