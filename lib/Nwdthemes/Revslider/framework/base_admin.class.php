<?php

 // Overrides original base admin class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/framework/base_admin.class.php';

class UniteBaseAdminClassRev extends UniteBaseAdminClassRevOriginal {

	/**
	 *
	 * register the "onActivate" event
	 */
	protected function addEvent_onActivate($eventFunc = "onActivate"){
	}

	protected function addAction_onActivate() {
	}

	/**
	 *
	 * require settings file, the filename without .php
	 */

	public static function requireSettings($settingsFile){

		try{
			require self::$path_settings."$settingsFile.php";
		}catch (Exception $e){
			echo "<br><br>Settings ($settingsFile) Error: <b>".$e->getMessage()."</b>";
			Mage::helper('nwdrevslider')->dmp($e->getTraceAsString());
		}
	}

	/**
	 *
	 * get settings object
	 */
	public static function getSettings($key) {
		return parent::getSettings($key);
	}

	/**
	 *
	 * get path to settings file
	 */
	public static function getSettingsFilePath($name) {
		return Mage::getBaseDir('lib') . '/Nwdthemes/Revslider' . parent::getSettingsFilePath($name);
	}

	/**
	 *
	 * store settings
	 */
	public static function storeSettings($name, $settings) {
		parent::storeSettings($name, $settings);
	}
	
	/**
	 *
	 * get url to some view.
	 */
	public static function getViewUrl($viewName,$urlParams="") {
		$_link = 'adminhtml/nwdrevslider/' . $viewName . '/';
		if ($urlParams)
		{
			$_args = array();
			$arrParams = explode('&', $urlParams);
			foreach ($arrParams as $_param) {
				$_arrParam = explode('=', $_param);
				$_args[$_arrParam[0]] = $_arrParam[1];
			}
			$_link = Mage::helper('nwdrevslider')->add_query_arg($_args, $_link);
		}
		return Mage::helper("adminhtml")->getUrl($_link);
	}

}
