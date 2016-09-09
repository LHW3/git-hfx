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

class Nwdthemes_Revslider_Block_Adminhtml_Sliders extends Mage_Adminhtml_Block_Template {

	/**
	 * Constructor
	 */

	public function __construct() {

		parent::__construct();

		$orders = false;
		$orderst = false;

		$_ot = $this->getRequest()->getParam('ot');
		$_order = $this->getRequest()->getParam('order');
		$_type = $this->getRequest()->getParam('type');

		if ($_ot && $_order && $_type)
		{
			$order = array();
			switch($_ot){
				case 'alias':
					$order['alias'] = ($_order == 'asc') ? 'ASC' : 'DESC';
				break;
				case 'name':
				default:
					$order['title'] = ($_order == 'asc') ? 'ASC' : 'DESC';
				break;
			}

			if($_type != 'reg')
			{
				$orderst = $order;
			}
			else
			{
				$orders = $order;
			}
		}

		$slider = new RevSlider();
		$arrSliders = $slider->getArrSliders(false, $orders);
		$arrSlidersTemplates = $slider->getArrSliders(true, $orderst);

		$exampleID = 'slider1';
		if(!empty($arrSliders))
			$exampleID = $arrSliders[0]->getAlias();

		$outputTemplates = false;
		$latest_version = Mage::helper('nwdrevslider')->getVersion();
		if(version_compare($latest_version, GlobalsRevSlider::SLIDER_REVISION, '>'))
		{
			//neue version existiert
		}
		else
		{
			//up to date
		}

		$this->assign('arrSliders', $arrSliders);
		$this->assign('arrSlidersTemplates', $arrSlidersTemplates);
		$this->assign('exampleID', $exampleID);
		$this->assign('latest_version', $latest_version);
	}

}
