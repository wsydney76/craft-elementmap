<?php
/**
 * Element Map plugin for Craft 3.0
 * @copyright Copyright Charlie Development
 */

namespace wsydney76\elementmap\assets;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ElementMapBundle extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = '@wsydney76/elementmap/assets/dist';

		$this->depends = [
			CpAsset::class,
		];

		$this->css = [
			'elementmap.css',
		];

		$this->js = [
		  'elementmap.js'
        ];

		parent::init();
	}
}
