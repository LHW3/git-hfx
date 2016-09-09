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

class Nwdthemes_Revslider_Block_Adminhtml_Sliders_List extends Nwdthemes_Revslider_Block_Adminhtml_Block_Template {

	public $outputTemplates;
	public $no_sliders;
	public $limit;
	public $otype;
	public $arrSliders;
	public $arrSlidersTemplates;

	/**
	 * Before rendering
	 */

	public function _beforeToHtml() {

		$this->outputTemplates = $this->getData('outputTemplates');
		$this->no_sliders = $this->getData('no_sliders');
		$this->arrSliders = $this->getData('arrSliders');
		$this->arrSlidersTemplates = $this->getData('arrSlidersTemplates');

		if( ! $this->outputTemplates)
		{
			$this->limit = $this->getRequest()->getParam('limit', 10);
			$this->otype = 'reg';
		}
		else
		{
			$this->limit = $this->getRequest()->getParam('limit_t', 10);
			$this->otype = 'temp';
		}
	}

}
