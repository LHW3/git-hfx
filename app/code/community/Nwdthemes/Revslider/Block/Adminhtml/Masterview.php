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

class Nwdthemes_Revslider_Block_Adminhtml_Masterview extends Mage_Adminhtml_Block_Page
{
	public function __construct() {
		$this->_controller = 'adminhtml_nwdrevslider';
		$this->_blockGroup = 'nwdrevslider';
		$this->_headerText = Mage::helper('nwdrevslider')->__('Revolution Slider');
		parent::__construct();
	}
	
	/**
	 * Check is folders exist and have writable permissions
	 *
	 * @return string Error message if exist
	 */ 
	
	public function checkFolderPermissionsErrors() {
		$arrFolders = array(
			'image_dir'		=> Mage::getConfig()->getOptions()->getMediaDir() . DS . Mage::helper('nwdrevslider/images')->getImageDir(),
			'thumb_dir'		=> Mage::getConfig()->getOptions()->getMediaDir() . DS . Mage::helper('nwdrevslider/images')->getImageThumbDir(),
			'admin_css_dir'	=> Mage::getBaseDir() . Mage::helper('nwdrevslider/css')->getAdminCssDir(),
			'front_css_dir'	=> Mage::getBaseDir() . Mage::helper('nwdrevslider/css')->getFrontCssDir()
		);
		
		$ioFile = new Varien_Io_File();

		$arrErrors = array();
		foreach ($arrFolders as $_folder) {
			try {
				if ( ! ( $ioFile->checkandcreatefolder($_folder) && $ioFile->isWriteable($_folder) ) )
				{
					$arrErrors[] = $_folder;
				}
			} catch (Exception $e) {
				$arrErrors[] = $_folder;
				Mage::logException($e);
			}			
		}
		
		if ( ! (in_array($arrFolders['admin_css_dir'], $arrErrors) || in_array($arrFolders['front_css_dir'], $arrErrors) ))
		{
			if ( ! file_exists($arrFolders['admin_css_dir'] . 'statics.css'))
			{
				Mage::helper('nwdrevslider/css')->putStaticCss();
			}
			if ( ! file_exists($arrFolders['admin_css_dir'] . 'dynamic.css'))
			{
				Mage::helper('nwdrevslider/css')->putDynamicCss();
			}
		}
		
		$strError = $arrErrors ? Mage::helper('nwdrevslider')->__('Following directories not found or not writable, please change permissions to: ') . implode(' , ', $arrErrors) : '';
		
		return $strError;
	}
}
