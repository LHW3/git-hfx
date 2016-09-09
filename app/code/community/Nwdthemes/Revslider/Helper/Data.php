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

class Nwdthemes_Revslider_Helper_Data extends Mage_Core_Helper_Abstract {
	
	/**
	 * Set selected option in select
	 */

	public function selected($var, $value = '') {
		echo $var == $value ? ' selected="selected" ' : '';
	}

	/**
	 * Set checkbox to be checked
	 */

	public function checked($var, $value = '') {
		echo $var == $value ? ' checked="checked" ' : '';
	}

	/**
	 * Get extensions version
	 */

	public function getVersion() {
		return (string) Mage::getConfig()->getNode()->modules->Nwdthemes_Revslider->version;
	}

	/**
	 * Replicates WP style pagination
	 */

	public function paginateLinks( $args = '' ) {

		$total        = 1;
		$current      = Mage::app()->getRequest()->getParam('paged', 1);

		$defaults = array(
			'total' => $total,
			'current' => $current,
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __('&laquo; Previous'),
			'next_text' => __('Next &raquo;'),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'plain',
			'add_args' => false, // array of query args to add
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => ''
		);

		$args = array_merge( $defaults, $args );

		// Who knows what else people pass in $args
		$total = (int) $args['total'];
		if ( $total < 2 ) {
			return;
		}
		$current  = (int) $args['current'];
		$end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
		if ( $end_size < 1 ) {
			$end_size = 1;
		}
		$mid_size = (int) $args['mid_size'];
		if ( $mid_size < 0 ) {
			$mid_size = 2;
		}
		$add_args = is_array( $args['add_args'] ) ? $args['add_args'] : false;
		$r = '';
		$page_links = array();
		$dots = false;

		if ( $args['prev_next'] && $current && 1 < $current ) :
			$link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current - 1, $link );
			if ( $add_args )
				$link = Mage::helper('nwdrevslider')->add_query_arg( $add_args, $link );
			$link .= $args['add_fragment'];

			/**
			 * Filter the paginated links for the given archive pages.
			 *
			 * @since 3.0.0
			 *
			 * @param string $link The paginated link URL.
			 */
			$page_links[] = '<a class="prev page-numbers" href="' . Mage::helper("adminhtml")->getUrl($link) . '">' . $args['prev_text'] . '</a>';
		endif;
		for ( $n = 1; $n <= $total; $n++ ) :
			if ( $n == $current ) :
				$page_links[] = "<span class='page-numbers current'>" . $args['before_page_number'] . $n . $args['after_page_number'] . "</span>";
				$dots = true;
			else :
				if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
					$link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
					$link = str_replace( '%#%', $n, $link );
					if ( $add_args )
						$link = Mage::helper('nwdrevslider')->add_query_arg( $add_args, $link );
					$link .= $args['add_fragment'];

					/** This filter is documented in wp-includes/general-template.php */
					$page_links[] = "<a class='page-numbers' href='" . Mage::helper("adminhtml")->getUrl($link) . "'>" . $args['before_page_number'] . $n . $args['after_page_number'] . "</a>";
					$dots = true;
				elseif ( $dots && ! $args['show_all'] ) :
					$page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
					$dots = false;
				endif;
			endif;
		endfor;
		if ( $args['prev_next'] && $current && ( $current < $total || -1 == $total ) ) :
			$link = str_replace( '%_%', $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current + 1, $link );
			if ( $add_args )
				$link = Mage::helper('nwdrevslider')->add_query_arg( $add_args, $link );
			$link .= $args['add_fragment'];

			/** This filter is documented in wp-includes/general-template.php */
			$page_links[] = '<a class="next page-numbers" href="' . Mage::helper("adminhtml")->getUrl($link) . '">' . $args['next_text'] . '</a>';
		endif;
		switch ( $args['type'] ) {
			case 'array' :
				return $page_links;

			case 'list' :
				$r .= "<ul class='page-numbers'>\n\t<li>";
				$r .= join("</li>\n\t<li>", $page_links);
				$r .= "</li>\n</ul>\n";
				break;

			default :
				$r = join("\n", $page_links);
				break;
		}
		return $r;
	}

	/**
	 * Add arguments to url
	 */

	public function add_query_arg($args = array(), $link) {
		if ( is_array($args) )
		{
			foreach ($args as $_key => $_val) {
				$link .= $_key . '/' . $_val . '/';
			}
		}
		return $link;
	}

	/**
	 * Get options to sort product slides by
	 */

	public function getArrSortBy() {
		return array(
			'position'	=> 'Position',
			'id'		=> 'ID',
			'name'		=> 'Name',
			'price'		=> 'Price'
		);
	}

	/**
	 * Get product categories tree for product sliders selection
	 */

	public function getProductCategoriesForClient() {

		$categoriesCollection = Mage::getModel('catalog/category')
				->getCollection()
				->addAttributeToSelect('name')
				->addAttributeToSort('path', 'asc');

		$categories = array();
		foreach ($categoriesCollection as $categoryId => $category) {
			$categoryArray = $category->getData();
			if ($categoryId > 1 && isset($categoryArray['name']) && isset($categoryArray['level']))
			{
				$categories[] = array(
					'label' => str_repeat('- ', $categoryArray['level'] - 1) . $categoryArray['name'],
					'value' => 'category_' . $categoryId
				);
			}
		}

		return array('category' => $categories);
	}

	/**
	 * Gets products array by csv ids
	 *
	 * @param string $strID comma separated list of ids
	 * @return array of products
	 */

	public function getProductsByIDs($strID) {

		$_arrID = explode(',', $strID);

		$_products = Mage::getModel('catalog/product')->getCollection();
		if ( ! Mage::helper('nwdall')->getCfg('options/show_out_of_stock', 'cataloginventory'))
		{
			Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($_products);
		}
		$_products	
			->addAttributeToFilter('entity_id', array('in' => $_arrID))
			->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
			->addFieldToFilter('visibility', array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG))
			->addAttributeToSelect('*');

		$_arrProducts = array();
		foreach ($_products as $_product) {
			$_arrProducts[$_product->getData('entity_id')] = $this->_prepareProduct($_product);
		}

		$arrProducts = array();
		foreach ($_arrID as $_ID) {
			if (isset($_arrProducts[$_ID]))
			{
				$arrProducts[] = $_arrProducts[$_ID];
			}
		}

		return $arrProducts;
	}

	/**
	 * Gets products by csv categories ids
	 *
	 * @param string $strID comma separated list of category ids
	 * @return array of products
	 */

	public function getProductsByCategoryIDs($strID, $sortBy, $sortDir, $limit) {
		
		$_products = Mage::getModel('catalog/product')->getCollection();
		if ( ! Mage::helper('nwdall')->getCfg('options/show_out_of_stock', 'cataloginventory'))
		{
			Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($_products);
		}
		$_products
			->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
			->addAttributeToFilter('category_id', array('in' => explode(',', $strID)))
			->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
			->addFieldToFilter('visibility', array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH, Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG))
			->addAttributeToSelect('*')
			->setOrder($sortBy, $sortDir)
			->setPageSize($limit);
		$_products->getSelect()->group('e.entity_id');

		$arrProducts = array();
		foreach ($_products as $_product) {
			$arrProducts[] = $this->_prepareProduct($_product);;
		}
		
		return $arrProducts;
	}

	/**
	 * Prepare product data for slider
	 *
	 * @param object $product
	 * @return array
	 */

	private function _prepareProduct($product) {
		$arrProduct = $product->getData();
		try{
			$arrProduct['image'] = $product->getImageUrl();
		} catch (Exception $e) {
			$arrProduct['image'] = '';
		}
		$arrProduct['cart_link'] = Mage::helper('checkout/cart')->getAddUrl($product);
		$arrProduct['wishlist_link'] = Mage::helper('wishlist')->getAddUrl($product);
		return $arrProduct;
	}

	/**
	 * Limit number of words in string
	 *
	 * @param string $string String to process
	 * @param int $words Count of words to truncate string, no limit if 0
	 *
	 * @return string Truncated string
	 */

	public function limitWords($string, $words = 0) {
		if ($words)
		{
			if (str_word_count($string, 0) > $words)
			{
				$_words = str_word_count($string, 2);
				$_pos = array_keys($_words);
				$string = substr($string, 0, $_pos[$words]) . '&hellip;';
			}
		}
		return $string;
	}

	/**
	 * Get image path by url
	 *
	 * @param string $imageUrl
	 * @return string Image path
	 */

	public function getImagePathFromURL($imageUrl) {
		$imagePath = str_replace(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB), '', $imageUrl);
		return $imagePath;
	}

	/**
	 * Get image url by path
	 *
	 * @param string $imagePath
	 * @return string Image path
	 */

	public function getImageUrlFromPath($imagePath = '') {
		if ($imagePath && ( strpos($imagePath, 'http://') && strpos($imagePath, 'https://') ) === false)
		{
			$imageUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $imagePath;
		}
		else
		{
			$imageUrl = $imagePath;
		}
		return $imageUrl;
	}

	/**
	 * Import media file
	 *
	 * @param string $sourceFilePath source of media file to import
	 * @param string $targetPath path to put file in new location
	 * @return array Data about imported media file
	 */

	public function importMedia($file_url, $folder_name) {

		$artDir = 'media/revslider/';

		$mediaPath = Mage::getBaseDir() . '/';

		//if the directory doesn't exist, create it
		if ( ! file_exists($mediaPath . $artDir)) mkdir($mediaPath . $artDir);
		if ( ! file_exists($mediaPath . $artDir . $folder_name)) mkdir($mediaPath . $artDir . $folder_name);

		//rename the file... alternatively, you could explode on "/" and keep the original file name

		$filename = basename($file_url);

		if(@fclose(@fopen($file_url, "r"))){ //make sure the file actually exists

			$saveDir = $mediaPath . $artDir . $folder_name . $filename;
			copy($file_url, $saveDir);

			return array("path" => $artDir . $folder_name . $filename);
		}else{

			return false;
		}
	}

	/**
	 * Get store options for multiselect
	 *
	 * @return array Array of store options
	 */

	public function getStoreOptions() {
		$storeValues = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
		$storeValues = $this->_makeFlatStoreOptions($storeValues);
		return $storeValues;
	}

	/**
	 * Make flat store options
	 *
	 * @param array $storeValues Store values tree array
	 * @retrun array Flat store values array
	 */

	private function _makeFlatStoreOptions($storeValues) {
		$arrStoreValues = array();
		foreach ($storeValues as $_storeValue) {
			if ( ! is_array($_storeValue['value']) )
			{
				$arrStoreValues[] = $_storeValue;
			}
			else
			{
				$arrStoreValues[] = array(
					'label'	=> $_storeValue['label'],
					'value' => 'option_disabled'
				);
				$_arrSubStoreValues = $this->_makeFlatStoreOptions($_storeValue['value']);
				foreach ($_arrSubStoreValues as $_subStoreValue) {
					$arrStoreValues[] = $_subStoreValue;
				}
			}
		}
		return $arrStoreValues;
	}

	/**
	 * Get list of NWD Google Fonts in JSON
	 *
	 * @return string JSON encoded array of fonts
	 */

	public function jsonGoogleFonts() {
		$_googleFonts = Mage::getModel('nwdall/system_config_googlefonts')->toOptionArray();
		$fonts = array();
		foreach ($_googleFonts as $_font) {
			$fonts[] = $_font['value'];
		}
		return json_encode($fonts);
	}

	/**
	 * Generate font include for admin slide editor
	 *
	 * @param mixed $styles Array of styles to include or all by default if not specified
	 * @return string Include string
	 */

	public function inlcudeStyleFonts($styles = 'all') {

		// get dynamic and static css

		$strCss = Mage::getModel('nwdrevslider/options')->getOption('revslider-static-css', '');
		if ($styles == 'all' || (is_array($styles) && count($styles)))
		{
			$cssCollection = Mage::getModel('nwdrevslider/css')->getCollection();
			if ($styles != 'all' && is_array($styles) && count($styles))
			{
				foreach ($styles as $_key => $_style) {
					$styles[$_key] = '.tp-caption.' . $_style;
				}
				$cssCollection->addFieldToFilter('handle', array('in' => $styles));
			}
			foreach ($cssCollection as $_cssItem) {
				$strCss .= $_cssItem->getHover() . $_cssItem->getParams();
			}
		}

		// find fonts used in it

		$usedGoogleFonts = array();
		$googleFonts = Mage::getModel('nwdall/system_config_googlefonts')->toOptionArray();
		foreach ($googleFonts as $_googleFont) {
			if ( $_googleFont['value'] && ! in_array($_googleFont['value'], $usedGoogleFonts) && strpos($strCss, $_googleFont['value']) !== false)
			{
				$usedGoogleFonts[] = $_googleFont['value'];
			}
		}

		// generate include string

		$strInclude = '';
		if ($usedGoogleFonts)
		{
			foreach ($usedGoogleFonts as $_key => $_value)
			{
				$usedGoogleFonts[$_key] = str_replace(' ', '+', $_value) . ':300,400,600,700,800';
			}
			$strInclude = "<link href='//fonts.googleapis.com/css?family=" . implode('|', $usedGoogleFonts) . "' rel='stylesheet' type='text/css' />";
		}
		return $strInclude;
	}

	/**
	 * Get store list by ids list
	 *
	 * @param string $storeIDs CSV list of store ids
	 * @return string List of store view names
	 */

	public function getStoreListBtIDs($storeIDs = '0') {
		$arrStoreIDs = explode(',', $storeIDs);
		$arrStoreOptions = $this->getStoreOptions();
		$arrStoreNames = array();
		foreach ($arrStoreOptions as $_storeOption) {
			if (in_array($_storeOption['value'], $arrStoreIDs))
			{
				$arrStoreNames[] = trim($_storeOption['label'], ' ');
			}
		}
		return implode(', ', $arrStoreNames);
	}

	/**
	 * Convert CSS string from DB format to Array
	 *
	 * @param string $cssString String to convert
	 * @return array
	 */

	public function stringCssToArray($cssString) {
		$_arrStyle = explode('","', rtrim(ltrim($cssString, '{"'), '}"'));
		$arrCss = array();
		if (is_array($_arrStyle))
		{
			foreach ($_arrStyle as $_style) {
				list($_key, $_val) = explode('":"', $_style);
				$arrCss[$_key] = $_val;
			}
		}
		return $arrCss;
	}
	
	/**
	 * Check if current connection is SSL
	 *
	 * @return boolean
	 */
	
	public function isSsl() {
		return Mage::app()->getStore()->isCurrentlySecure();
	}
	
	/**
	 * Dump output
	 *
	 * @param var $str
	 */
	
	public function dmp($str) {
		echo "<div align='left'>";
		echo "<pre>";
		print_r($str);
		echo "</pre>";
		echo "</div>";		
	}

	/**
	 * Get store view select html
	 *
	 * @param string $name
	 * @param int $value
	 * @param string $title
	 * @return string Store select html
	 */

	public function getStoreSelectHtml($name, $value = 0, $title = '') {
		$storeViewSelectHtml = Mage::app()->getLayout()->createBlock('core/html_select')
			->setName($name)
			->setId($name)
			->setTitle($title)
			->setValue($value)
			->setOptions( Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true) )
			->getHtml();
		return $storeViewSelectHtml;
	}
}
