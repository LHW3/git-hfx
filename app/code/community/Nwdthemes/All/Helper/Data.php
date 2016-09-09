<?php

/**
 * Nwdthemes All Extension
 *
 * @package     All
 * @author		Nwdthemes <mail@nwdthemes.com>
 * @link		http://nwdthemes.com/
 * @copyright   Copyright (c) 2014. Nwdthemes
 * @license     http://themeforest.net/licenses/terms/regular
 */

class Nwdthemes_All_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Retrieve config value for store by path
	 *
	 * @param string $path
	 * @param string $section
	 * @param int $store
	 * @return mixed
	 */
	public function getCfg($path, $section = 'nwdall', $store = NULL)
	{
		if ($store == NULL) {
			$store = Mage::app()->getStore()->getId();
		}
		if (empty($path)) {
			$path = $section;
		} else {
			$path = $section . '/' . $path;
		}
		return Mage::getStoreConfig($path, $store);
	}
}
