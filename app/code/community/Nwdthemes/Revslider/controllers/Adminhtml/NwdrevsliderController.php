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


class Nwdthemes_Revslider_Adminhtml_NwdrevsliderController extends Mage_Adminhtml_Controller_Action {

	private $_revSliderAdmin;

	/**
	 * Check permissions
	 */

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('nwdthemes/nwdrevslider');
    }

	/**
	 * Init action
	 */

	protected function _initAction() {
		$this->_revSliderAdmin = Mage::getSingleton('RevSliderAdmin');
		return $this;
	}

	/**
	 * Init page
	 */

	protected function _initPage() {

		$this->_initAction();

		$this->loadLayout()
			->_setActiveMenu('nwdthemes/nwdrevslider/nwdrevslider')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Revolution Slider'), Mage::helper('adminhtml')->__('Revolution Slider'));
			
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
	}

	/**
	 * Default page
	 */

	public function indexAction() {
		if ( ! $this->_checkTablesErrors() )
		{
		    $this->slidersAction();
		}
		else
		{
			$this->_redirect('*/*/error');
		}
	}

	/**
	 * Sliders page
	 */

	public function slidersAction() {
		$this->_initPage();
		$this->_setTitle(Mage::helper('nwdrevslider')->__('Sliders Dashboard'));
		$this->renderLayout();
	}

	/**
	 * Slider page
	 */

	public function sliderAction() {
		$this->_initPage();
		$this->_setTitle(Mage::helper('nwdrevslider')->__('Slider Settings'));
		$this->renderLayout();
	}

	/**
	 * Slider template page
	 */

	public function slidertemplateAction() {
		$this->_initPage();
		$this->_setTitle(Mage::helper('nwdrevslider')->__('Template Settings'));
		$this->renderLayout();
	}

	/**
	 * Slides page
	 */

	public function slidesAction() {
		$this->_initPage();
		$this->_setTitle(Mage::helper('nwdrevslider')->__('Slides List'));
		$this->renderLayout();
	}

	/**
	 * Slide page
	 */

	public function slideAction() {
		$this->_initPage();
		$this->_setTitle(Mage::helper('nwdrevslider')->__('Edit Slide'));
		$this->renderLayout();
	}

	/**
	 * Error page
	 */

	public function errorAction() {
		if ( ! $strError = $this->_checkTablesErrors() )
		{
			$this->_redirect('*/*/index');
		}
		else
		{
		    Mage::getSingleton('adminhtml/session')->addError($strError);
		    $this->loadLayout()->_setActiveMenu('nwdthemes/nwdrevslider/nwdrevslider');
			$this->_setTitle(Mage::helper('nwdrevslider')->__('Error'));
			$this->renderLayout();
		}
	}

	/**
	 * Admin Ajax actions
	 */

	public function ajaxAction() {
		$this->_initAction();
		$this->_revSliderAdmin->onAjaxAction();
	}

	/**
	 * Set page title
	 *
	 * @param string $title
	 */

	private function _setTitle($title) {
		$this->getLayout()->getBlock('head')->setTitle(Mage::helper('nwdrevslider')->__('Revolution Slider - ') . $title);
	}

	/**
	 * Check if db tables exists
	 *
	 * @return boolean
	 */

	private function _checkTablesErrors() {
		$_missingTables = array();
		$_resources = array('css', 'animations', 'options', 'settings', 'sliders', 'slides', 'static');
		foreach ($_resources as $_resource) {
			$_tableName = Mage::getSingleton('core/resource')->getTableName('nwdrevslider/' . $_resource);
			if ( Mage::getSingleton('core/resource')->getConnection('core_read')->showTableStatus($_tableName) === false)
			{
				$_missingTables[] = $_tableName;
			}
		}
		return $_missingTables ? Mage::helper('nwdrevslider')->__('Database tables not found: ') . implode(', ', $_missingTables) : '';
	}
}
