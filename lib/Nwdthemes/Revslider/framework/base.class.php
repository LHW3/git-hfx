<?php

// Overrides original base class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/framework/base.class.php';

class UniteBaseClassRev extends UniteBaseClassRevOriginal {

	/**
	 *
	 * the constructor
	 */
	public function __construct($mainFile,$t){

		self::$mainFile = $mainFile;
		self::$t = $t;

		//set plugin dirname (as the main filename)
		$info = pathinfo($mainFile);
		$baseName = $info["basename"];
		$filename = str_replace(".php","",$baseName);

		self::$dir_plugin = $filename;

		self::$path_plugin = dirname(self::$mainFile)."/";
		self::$path_settings = Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/settings/';
		self::$path_temp = self::$path_plugin."temp/";

		//set cache path:
		self::setPathCache();

		//update globals oldversion flag
		GlobalsRevSlider::$isNewVersion = true;
	}

	/**
	 *
	 * set cache path for images. for multisite it will be current blog content folder
	 */

	private static function setPathCache(){
		self::$path_cache = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
	}

	/**
	 *
	 * add some wordpress action
	 */
	protected static function addAction($action,$eventFunction) {
	}

	/**
	 *
	 * get POST var
	 */
	protected static function getPostVar($key,$defaultValue = "") {
		return Mage::app()->getRequest()->getPost($key, $defaultValue);
	}

	/**
	 *
	 * get GET var
	 */
	protected static function getGetVar($key,$defaultValue = "") {
		return Mage::app()->getRequest()->getParam($key, $defaultValue);
	}

	/**
	 *
	 * get post or get variable
	 */
	protected static function getPostGetVar($key,$defaultValue = "") {
		return Mage::app()->getRequest()->getParam($key, $defaultValue);
	}

}
