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
require_once $folderIncludes . 'base_front.class.php';

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
require_once $libFolder . '/revslider/revslider_front.php';

class Nwdthemes_Revslider_Block_Revslider extends Mage_Core_Block_Template {

	protected $_revSliderFront;

	protected function _construct() {
        parent::_construct();
		$this->setTemplate('nwdthemes/revslider/revslider.phtml');
		$this->_revSliderFront = Mage::getSingleton('RevSliderFront');
	}

	public function renderSlider() {
		if ( Mage::helper('nwdall')->getCfg('general/enabled', 'nwdrevslider_config') )
		{
			ob_start();
			$slider = RevSliderOutput::putSlider( $this->getData('alias') );
			$content = ob_get_contents();
			ob_clean();
			ob_end_clean();
		}
		else
		{
			$content = '';
		}
		return $content;
	}

}
