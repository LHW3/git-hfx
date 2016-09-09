<?php

// Overrides original global rev slider class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/revslider_globals.class.php';

class GlobalsRevSlider extends GlobalsRevSliderOriginal {

	const SLIDER_REVISION = '1.0.4.6.3';
	const TABLE_SLIDERS_NAME = "sliders";
	const TABLE_SLIDES_NAME = "slides";
	const TABLE_STATIC_SLIDES_NAME = "static";
	const TABLE_SETTINGS_NAME = "settings";
	const TABLE_CSS_NAME = "css";
	const TABLE_LAYER_ANIMS_NAME = "animations";

}
