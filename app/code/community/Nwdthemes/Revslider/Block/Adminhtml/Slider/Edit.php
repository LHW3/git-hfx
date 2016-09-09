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

class Nwdthemes_Revslider_Block_Adminhtml_Slider_Edit extends Mage_Adminhtml_Block_Template {

	private $_sliderTemplate = false;

	/**
	 * Constructor
	 */

	public function __construct() {

		parent::__construct();

		$revSliderAdmin = Mage::getSingleton('RevSliderAdmin');
		$revSliderAdmin->requireSettings("slider_settings");

		$settingsMain = $revSliderAdmin->getSettings("slider_main");
		$settingsParams = $revSliderAdmin->getSettings("slider_params");

		$settingsSliderMain = new RevSliderSettingsProduct();
		$settingsSliderParams = new UniteSettingsProductSidebarRev();

		//get taxonomies with cats
		$postTypesWithCats = RevOperations::getPostTypesWithCatsForClient();
		$jsonTaxWithCats = UniteFunctionsRev::jsonEncodeForClientSide($postTypesWithCats);

		//check existing slider data:
		$sliderID = $this->getRequest()->getParam('id');

		if( ! empty($sliderID))
		{
			$slider = new RevSlider();
			$slider->initByID($sliderID);

			//get setting fields
			$settingsFields = $slider->getSettingsFields();
			$arrFieldsMain = $settingsFields["main"];
			$arrFieldsParams = $settingsFields["params"];

			//modify arrows type for backword compatability
			$arrowsType = UniteFunctionsRev::getVal($arrFieldsParams, "navigation_arrows");
			switch($arrowsType){
				case "verticalcentered":
					$arrFieldsParams["navigation_arrows"] = "solo";
				break;
			}

			//set custom type params values:
			$settingsMain = RevSliderSettingsProduct::setSettingsCustomValues($settingsMain, $arrFieldsParams, $postTypesWithCats);

			//set setting values from the slider
			$settingsMain->setStoredValues($arrFieldsParams);

			$settingsParams->setStoredValues($arrFieldsParams);

			//update short code setting
			$shortcode = $slider->getShortcode();
			$settingsMain->updateSettingValue("shortcode", htmlentities($shortcode) );

			$linksEditSlides = $this->helper("adminhtml")->getUrl('adminhtml/nwdrevslider/slides/id/' . $sliderID);

			$settingsSliderParams->init($settingsParams);
			$settingsSliderMain->init($settingsMain);

			$settingsSliderParams->isAccordion(true);

			$this->assign('sliderID', $sliderID);
			$this->assign('linksEditSlides', $linksEditSlides);
			$this->assign('arrFieldsParams', $arrFieldsParams);
			$this->setTemplate('nwdthemes/revslider/templates/slider_edit.phtml');
		}
		else
		{
			//set custom type params values:
			$settingsMain = RevSliderSettingsProduct::setSettingsCustomValues($settingsMain, array(), $postTypesWithCats);

			$settingsSliderParams->init($settingsParams);
			$settingsSliderMain->init($settingsMain);

			$settingsSliderParams->isAccordion(true);

			$this->setTemplate('nwdthemes/revslider/templates/slider_new.phtml');
		}

		$this->assign('sliderTemplate', $this->_sliderTemplate);
		$this->assign('settingsSliderMain', $settingsSliderMain);
		$this->assign('settingsSliderParams', $settingsSliderParams);
		$this->assign('jsonTaxWithCats', $jsonTaxWithCats);
	}
}
