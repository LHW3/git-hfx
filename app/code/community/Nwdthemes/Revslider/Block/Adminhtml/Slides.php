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

class Nwdthemes_Revslider_Block_Adminhtml_Slides extends Mage_Adminhtml_Block_Template {

	/**
	 * Constructor
	 */

	public function __construct() {

		parent::__construct();

		$operations = new RevOperations();

		$sliderID = $this->getRequest()->getParam('id');
		$storeID = $this->getRequest()->getParam('store_id', 0);

		if(empty($sliderID))
			UniteFunctionsRev::throwError("Slider ID not found");

		$slider = new RevSlider();
		$slider->initByID($sliderID);
		$sliderParams = $slider->getParams();

		$arrSliders = $slider->getArrSlidersShort($sliderID);
		$selectSliders = UniteFunctionsRev::getHTMLSelect($arrSliders,"","id='selectSliders'",true);

		$numSliders = count($arrSliders);

		//set iframe parameters
		$width = $sliderParams["width"];
		$height = $sliderParams["height"];

		$iframeWidth = $width+60;
		$iframeHeight = $height+50;

		$iframeStyle = "width:".$iframeWidth."px;height:".$iframeHeight."px;";

		$arrSlides = $slider->getSlides(false, $storeID);

		$numSlides = count($arrSlides);

		$linksSliderSettings = $this->helper("adminhtml")->getUrl('adminhtml/nwdrevslider/slider/id/' . $sliderID);

		$patternViewSlide = $this->helper("adminhtml")->getUrl('adminhtml/nwdrevslider/slider/id/[slideid]');

		$useStaticLayers = $slider->getParam("enable_static_layers","off");

		//treat in case of slides from gallery
		if($slider->isSlidesFromPosts() == false)
		{

			$templateName = "slides_gallery";

			// store view

			$selectStoreView = Mage::app()->getLayout()->createBlock('core/html_select')
				->setName('select_storeview')
				->setId('select_storeview')
				->setTitle('Store View')
				->setValue($storeID)
				->setOptions( Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true) )
				->getHtml();

			$this->assign('storeID', $storeID);
			$this->assign('selectStoreView', $selectStoreView);
			$this->setTemplate('nwdthemes/revslider/templates/slides_gallery.phtml');
		}
		else
		{
			//slides from posts

			$templateName = "slides_posts";

			$sourceType = $slider->getParam("source_type","posts");
			$showSortBy = ($sourceType == "posts")?true:false;
			$showDelete = ($sourceType == "specific_posts")?true:false;

			//get button links
			$urlNewPost = $this->helper('adminhtml')->getUrl('adminhtml/catalog_product/new');
			$linkNewPost = UniteFunctionsRev::getHtmlLink($urlNewPost, $this->__("<i class='revicon-pencil-1'></i>New Product"),"button_new_post","button-primary revblue",true);

			//get ordering
			$arrSortBy = $this->helper('nwdrevslider')->getArrSortBy();
			$sortBy = $slider->getParam("post_sortby",RevSlider::DEFAULT_POST_SORTBY);
			$selectSortBy = UniteFunctionsRev::getHTMLSelect($arrSortBy,$sortBy,"id='select_sortby'",true);

			$this->assign('showSortBy', $showSortBy);
			$this->assign('showDelete', $showDelete);
			$this->assign('selectSortBy', $selectSortBy);
			$this->assign('linkNewPost', $linkNewPost);
			$this->setTemplate('nwdthemes/revslider/templates/slides_posts.phtml');
		}

		$this->assign('sliderID', $sliderID);
		$this->assign('iframeStyle', $iframeStyle);
		$this->assign('patternViewSlide', $patternViewSlide);
		$this->assign('selectSliders', $selectSliders);
		$this->assign('linksSliderSettings', $linksSliderSettings);
		$this->assign('useStaticLayers', $useStaticLayers);
		$this->assign('arrSlides', $arrSlides);
		$this->assign('slider', $slider);
		$this->assign('numSlides', $numSlides);
		$this->assign('numSliders', $numSliders);
	}

}
