<?php
/**
 * @author     ByMayo
 * @package    Dribbble
 * @since      2.0.0
 * @copyright  Copyright (c) 2018 ByMayo
 */

namespace bymayo\dribbble\models;

use bymayo\dribbble\Dribbble;

use Craft;
use craft\base\Model;

class Settings extends Model
{
	
    // Public Properties
    // =========================================================================

    public $clientId;
    public $clientSecret;
    public $oauthAccessToken;

    // Public Methods
    // =========================================================================

    public function rules()
    {
        return [
			[['clientId', 'clientSecret'], 'required']
        ];
    }
    
}
