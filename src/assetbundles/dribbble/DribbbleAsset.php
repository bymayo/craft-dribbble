<?php
/**
 * @author     ByMayo
 * @package    Dribbble
 * @since      2.0.0
 * @copyright  Copyright (c) 2018 ByMayo
 */

namespace bymayo\dribbble\assetbundles\Dribbble;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class DribbbleAsset extends AssetBundle
{
	
    // Public Methods
    // =========================================================================

    public function init()
    {
        $this->sourcePath = "@bymayo/dribbble/assetbundles/dribbble/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/Dribbble.css',
        ];

        parent::init();
    }
}
