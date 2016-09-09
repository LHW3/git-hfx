<?php

// Overrides original functions class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/framework/functions.class.php';

class UniteFunctionsRev extends UniteFunctionsRevOriginal {

	/**
	 *
	 * decode json from the client side
	 */
	public static function jsonDecodeFromClientSide($data){

		$_data = $data;//stripslashes($data);
		$_data = str_replace('&#092;"','\"',$_data);
		$_data = json_decode($_data);
		$_data = (array)$_data;

		if ( ! $_data)
		{
			$_data = str_replace('&#092;"','\"',$data);
			$_data = json_decode($_data);
			$_data = (array)$_data;
		}

		return($_data);
	}


}
