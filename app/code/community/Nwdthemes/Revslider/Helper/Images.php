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

class Nwdthemes_Revslider_Helper_Images extends Mage_Cms_Helper_Wysiwyg_Images {

	const IMAGE_DIR = 'revslider';
	const IMAGE_THUMB_DIR = 'revslider_thumbs';

	/**
	 * Get images directory
	 *
	 * @return string
	 */
	
	public function getImageDir() {
		return self::IMAGE_DIR;
	}

	/**
	 * Get image thumbs directory
	 *
	 * @return string
	 */
	
	public function getImageThumbDir() {
		return self::IMAGE_THUMB_DIR;
	}
	
    /**
     * Images Storage root directory
     *
     * @return string
     */
    public function getStorageRoot() {
        return Mage::getConfig()->getOptions()->getMediaDir() . DS . self::IMAGE_DIR . DS;
    }

    /**
     * Check whether using static URLs is allowed
     * always allowed for Revslider
     *
     * @return boolean
     */
    public function isUsingStaticUrlsAllowed() {
		return true;
    }

	/**
	 * Resize image
	 *
	 * @param string $fileName
	 * @param int $width
	 * @param int $height
	 * @return string Resized image url
	 */

	public function resizeImg($fileName, $width, $height = '') {

		if ( ! $height)
		{
			$height = $width;
		}

		$thumbDir = self::IMAGE_THUMB_DIR;
		$resizeDir = $thumbDir . "/resized_{$width}x{$height}";

		$ioFile = new Varien_Io_File();
		$ioFile->checkandcreatefolder(Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $resizeDir);

		$imageParts = explode('/', $fileName);
		$imageFile = end($imageParts);

		$folderURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		$imageURL = $folderURL . $fileName;

		$basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $fileName;
		$newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $resizeDir . DS . $imageFile;

		if ($width != '')
		{
			if (file_exists($basePath) && is_file($basePath) && ! file_exists($newPath))
			{
				$imageObj = new Varien_Image($basePath);
				$imageObj->constrainOnly(TRUE);
				$imageObj->keepAspectRatio(TRUE);
				$imageObj->keepFrame(FALSE);
				$imageObj->keepTransparency(TRUE);
				//$imageObj->backgroundColor(array(255,255,255));
				$imageObj->resize($width, $height);
				$imageObj->save($newPath);
			}

			$resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $resizeDir . '/' . $imageFile;
		}
		else
		{
			$resizedURL = $imageURL;
		}
		return $resizedURL;
	}

}
