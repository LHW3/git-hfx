<?php

/**
 * Nwdthemes Revolution Slider Extension
 *
 * @package     Revslider
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2014. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */

$libFolder = Mage::getBaseDir('lib') . '/Nwdthemes/Revslider';

//include framework files
require_once $libFolder . '/framework/include_framework.php';

//include bases
require_once $folderIncludes . 'base.class.php';
require_once $folderIncludes . 'elements_base.class.php';
require_once $folderIncludes . 'base_admin.class.php';

//include product files
require_once $libFolder . '/revslider_settings_product.class.php';
require_once $libFolder . '/revslider_globals.class.php';
require_once $libFolder . '/revslider_operations.class.php';
require_once $libFolder . '/revslider_slider.class.php';
require_once $libFolder . '/revslider_output.class.php';
require_once $libFolder . '/revslider_slide.class.php';
require_once $libFolder . '/revslider_params.class.php';
require_once $libFolder . '/revslider_tinybox.class.php';

// include main classes
require_once $libFolder . '/revslider/revslider_admin.php';

class Nwdthemes_Revslider_Model_System_Config_Source_Revslider
{
	public function toOptionArray()
	{
		new RevSliderAdmin();
		$slider = new RevSlider();
		$arrSliders = $slider->getArrSliders();
		$options = array();
		foreach ( $arrSliders as $item ) {
			$options[] = array('value' => $item->getAlias(), 'label' => $item->getAlias());
		}
		return $options;
	}
}
