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

class Nwdthemes_Revslider_Block_Adminhtml_Slide extends Nwdthemes_Revslider_Block_Adminhtml_Block_Template {

	/**
	 * Constructor
	 */

	public function __construct() {

		parent::__construct();

		//get input
		$slideID = $this->getRequest()->getParam('id');

		//init slide object
		$slide = new RevSlide();
		$slide->initByID($slideID);

		$slideParams = $slide->getParams();

		$operations = new RevOperations();

		//init slider object
		$sliderID = $slide->getSliderID();
		$slider = new RevSlider();
		$slider->initByID($sliderID);
		$sliderParams = $slider->getParams();
		$arrSlideNames = $slider->getArrSlideNames();

		//check if slider is template
		$sliderTemplate = $slider->getParam("template","false");

		//set slide delay
		$sliderDelay = $slider->getParam("delay","9000");
		$slideDelay = $slide->getParam("delay","");
		if(empty($slideDelay))
			$slideDelay = $sliderDelay;

		require $this->getSettingsFilePath("slide_settings");
		require $this->getSettingsFilePath("layer_settings");

		$settingsLayerOutput = new UniteSettingsProductSidebarRev();
		$settingsSlideOutput = new UniteSettingsRevProductRev();

		$arrLayers = $slide->getLayers();

		$loadGoogleFont = $slider->getParam("load_googlefont","false");

		//get settings objects
		$settingsLayer = $this->getSettings("layer_settings");
		$settingsSlide = $this->getSettings("slide_settings");

		$cssContent = $this->getSettings("css_captions_content");
		$arrCaptionClasses = $operations->getArrCaptionClasses($cssContent);
		$arrFontFamily = $operations->getArrFontFamilys($slider);
		$arrCSS = $operations->getCaptionsContentArray();

		$arrButtonClasses = $operations->getButtonClasses();
		$urlCaptionsCSS = GlobalsRevSlider::$urlCaptionsCSS;

		$arrAnim = $operations->getFullCustomAnimations();

		//set layer caption as first caption class
		$firstCaption = !empty($arrCaptionClasses)?$arrCaptionClasses[0]:"";
		$settingsLayer->updateSettingValue("layer_caption",$firstCaption);

		//set stored values from "slide params"
		$settingsSlide->setStoredValues($slideParams);

		//init the settings output object
		$settingsLayerOutput->init($settingsLayer);
		$settingsSlideOutput->init($settingsSlide);

		//set various parameters needed for the page
		$width = $sliderParams["width"];
		$height = $sliderParams["height"];
		$imageUrl = $slide->getImageUrl();
		$imageID = $slide->getImageID();

		$imageFilename = $slide->getImageFilename();

		$style = "height:".$height."px;"; //
		$divLayersWidth = "width:".$width."px;";
		$divbgminwidth = "min-width:".$width."px;";

		//set iframe parameters
		$iframeWidth = $width+60;
		$iframeHeight = $height+50;

		$iframeStyle = "width:".$iframeWidth."px;height:".$iframeHeight."px;";

		$closeUrl = $this->getViewUrl(RevSliderAdmin::VIEW_SLIDES,"id=".$sliderID);

		$jsonLayers = UniteFunctionsRev::jsonEncodeForClientSide($arrLayers);
		$jsonCaptions = UniteFunctionsRev::jsonEncodeForClientSide($arrCaptionClasses);
		$jsonFontFamilys = UniteFunctionsRev::jsonEncodeForClientSide($arrFontFamily);
		$arrCssStyles = UniteFunctionsRev::jsonEncodeForClientSide($arrCSS);

		$arrCustomAnim = UniteFunctionsRev::jsonEncodeForClientSide($arrAnim);

		//bg type params
		$bgType = UniteFunctionsRev::getVal($slideParams, "background_type","image");
		$slideBGColor = UniteFunctionsRev::getVal($slideParams, "slide_bg_color","#E7E7E7");
		$divLayersClass = "slide_layers";
		$bgSolidPickerProps = 'class="inputColorPicker slide_bg_color disabled" disabled="disabled"';

		$bgFit = UniteFunctionsRev::getVal($slideParams, "bg_fit","cover");
		$bgFitX = intval(UniteFunctionsRev::getVal($slideParams, "bg_fit_x","100"));
		$bgFitY = intval(UniteFunctionsRev::getVal($slideParams, "bg_fit_y","100"));

		$bgPosition = UniteFunctionsRev::getVal($slideParams, "bg_position","center top");
		$bgPositionX = intval(UniteFunctionsRev::getVal($slideParams, "bg_position_x","0"));
		$bgPositionY = intval(UniteFunctionsRev::getVal($slideParams, "bg_position_y","0"));

		$bgEndPosition = UniteFunctionsRev::getVal($slideParams, "bg_end_position","center top");
		$bgEndPositionX = intval(UniteFunctionsRev::getVal($slideParams, "bg_end_position_x","0"));
		$bgEndPositionY = intval(UniteFunctionsRev::getVal($slideParams, "bg_end_position_y","0"));

		$kenburn_effect = UniteFunctionsRev::getVal($slideParams, "kenburn_effect","off");
		$kb_duration = UniteFunctionsRev::getVal($slideParams, "kb_duration", $sliderParams["delay"]);
		$kb_easing = UniteFunctionsRev::getVal($slideParams, "kb_easing","Linear.easeNone");
		$kb_start_fit = UniteFunctionsRev::getVal($slideParams, "kb_start_fit","100");
		$kb_end_fit = UniteFunctionsRev::getVal($slideParams, "kb_end_fit","100");

		$bgRepeat = UniteFunctionsRev::getVal($slideParams, "bg_repeat","no-repeat");

		$slideBGExternal = UniteFunctionsRev::getVal($slideParams, "slide_bg_external","");

		$style_wrapper = '';
		$class_wrapper = '';

		switch($bgType){
			case "trans":
				$divLayersClass = "slide_layers";
				$class_wrapper = "trans_bg";
			break;
			case "solid":
				$style_wrapper .= "background-color:".$slideBGColor.";";
				$bgSolidPickerProps = 'class="inputColorPicker slide_bg_color" style="background-color:'.$slideBGColor.'"';
			break;
			case "image":
				$style_wrapper .= "background-image:url('".$imageUrl."');";
				if($bgFit == 'percentage'){
					$style_wrapper .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
				}else{
					$style_wrapper .= "background-size: ".$bgFit.";";
				}
				if($bgPosition == 'percentage'){
					$style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
				}else{
					$style_wrapper .= "background-position: ".$bgPosition.";";
				}
				$style_wrapper .= "background-repeat: ".$bgRepeat.";";
			break;
			case "external":
				$style_wrapper .= "background-image:url('".$slideBGExternal."');";
				if($bgFit == 'percentage'){
					$style_wrapper .= "background-size: ".$bgFitX.'% '.$bgFitY.'%;';
				}else{
					$style_wrapper .= "background-size: ".$bgFit.";";
				}
				if($bgPosition == 'percentage'){
					$style_wrapper .= "background-position: ".$bgPositionX.'% '.$bgPositionY.'%;';
				}else{
					$style_wrapper .= "background-position: ".$bgPosition.";";
				}
				$style_wrapper .= "background-repeat: ".$bgRepeat.";";
			break;
		}

		$slideTitle = $slide->getParam("title","Slide");
		$slideOrder = $slide->getOrder();

		$this
			->assign('loadGoogleFont', $loadGoogleFont)
			->assign('settingsLayerOutput', $settingsLayerOutput)
			->assign('slider', $slider)
			->assign('slide', $slide)
			->assign('sliderTemplate', $sliderTemplate)
			->assign('slideOrder', $slideOrder)
			->assign('slideTitle', $slideTitle)
			->assign('slideDelay', $slideDelay)
			->assign('sliderParams', $sliderParams)
			->assign('arrSlideNames', $arrSlideNames)
			->assign('arrCustomAnim', $arrCustomAnim)
			->assign('slideID', $slideID)
			->assign('sliderID', $sliderID)
			->assign('settingsSlideOutput', $settingsSlideOutput)
			->assign('imageUrl', $imageUrl)
			->assign('imageID', $imageID)
			->assign('bgType', $bgType)
			->assign('slideBGColor', $slideBGColor)
			->assign('divLayersClass', $divLayersClass)
			->assign('bgSolidPickerProps', $bgSolidPickerProps)
			->assign('bgFit', $bgFit)
			->assign('bgFitX', $bgFitX)
			->assign('bgFitY', $bgFitY)
			->assign('bgRepeat', $bgRepeat)
			->assign('bgPosition', $bgPosition)
			->assign('bgPositionX', $bgPositionX)
			->assign('bgPositionY', $bgPositionY)
			->assign('bgEndPosition', $bgEndPosition)
			->assign('bgEndPositionX', $bgEndPositionX)
			->assign('bgEndPositionY', $bgEndPositionY)
			->assign('slideBGExternal', $slideBGExternal)
			->assign('slideBGColor', $slideBGColor)
			->assign('style', $style)
			->assign('iframeStyle', $iframeStyle)
			->assign('closeUrl', $closeUrl)
			->assign('jsonLayers', $jsonLayers)
			->assign('jsonCaptions', $jsonCaptions)
			->assign('jsonFontFamilys', $jsonFontFamilys)
			->assign('arrCssStyles', $arrCssStyles)
			->assign('arrButtonClasses', $arrButtonClasses)
			->assign('urlCaptionsCSS', $urlCaptionsCSS)
			->assign('divLayersWidth', $divLayersWidth)
			->assign('divbgminwidth', $divbgminwidth)
			->assign('style_wrapper', $style_wrapper)
			->assign('class_wrapper', $class_wrapper)
			->assign('kenburn_effect', $kenburn_effect)
			->assign('kb_duration', $kb_duration)
			->assign('kb_easing', $kb_easing)
			->assign('kb_start_fit', $kb_start_fit)
			->assign('kb_end_fit', $kb_end_fit);
	}

}
