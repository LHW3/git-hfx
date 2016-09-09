<?php

// Overrides original operations class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/revslider_operations.class.php';

class RevOperations extends RevOperationsOriginal {

	/**
	 *
	 * get post types with categories for client side.
	 */
	public static function getPostTypesWithCatsForClient(){
		return Mage::helper('nwdrevslider')->getProductCategoriesForClient();
	}

	/**
	 *
	 * preview slider output
	 * if output object is null - create object
	 */
	public function previewOutput($sliderID,$output = null, $storeID = 0){

		if($sliderID == "empty_output"){
			$this->loadingMessageOutput();
			exit();
		}

		if($output == null)
			$output = new RevSliderOutput();

		$slider = new RevSlider();
		$slider->initByID($sliderID);

		$output->setPreviewMode($storeID);

		//put the output html
		$urlPreviewPattern = UniteBaseClassRev::$url_ajax_actions."&client_action=preview_slider&sliderid=".$sliderID."&lang=[lang]&nonce=[nonce]";

		$setBase = (Mage::helper('nwdrevslider')->isSsl()) ? "https://" : "http://";

		?>
			<html>
				<head>
					<link rel='stylesheet' href='<?php echo Mage::getDesign()->getSkinUrl('css/nwdthemes/revslider/rs/settings.css'); ?>' type='text/css' media='all' />
					<?php
					$db = new UniteDBRev();

					$styles = $db->fetch(GlobalsRevSlider::$table_css);
					$styles = UniteCssParserRev::parseDbArrayToCss($styles, "\n");
					$styles = UniteCssParserRev::compress_css($styles);

                    echo '<style type="text/css">'.$styles.'</style>'; //.$stylesinnerlayers

					$http = (Mage::helper('nwdrevslider')->isSsl()) ? 'https' : 'http';

					$custom_css = RevOperations::getStaticCss();
					echo '<style type="text/css">'.UniteCssParserRev::compress_css($custom_css).'</style>';
					?>

					<script type='text/javascript' src='<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS); ?>nwdthemes/jquery-1.11.0.min.js'></script>
					<script type='text/javascript' src='<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS); ?>nwdthemes/jquery-migrate-1.2.1.min.js'></script>
					<script type='text/javascript' src='<?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS); ?>nwdthemes/jquery.noconflict.js'></script>

					<script type='text/javascript' src='<?php echo Mage::getDesign()->getSkinUrl('js/nwdthemes/revslider/rs/jquery.themepunch.tools.min.js'); ?>'></script>
					<script type='text/javascript' src='<?php echo Mage::getDesign()->getSkinUrl('js/nwdthemes/revslider/rs/jquery.themepunch.revolution.min.js'); ?>'></script>
				</head>
				<body style="padding:0px;margin:0px;">
					<?php
						$output->putSliderBase($sliderID, $storeID);
					?>
				</body>
			</html>
		<?php
		exit();
	}

	/*
	 * show only the markup for jQuery version of plugin
	 */
	public function previewOutputMarkup($sliderID,$output = null){

		if($sliderID == "empty_output"){
			$this->loadingMessageOutput();
			exit();
		}

		if($output == null)
			$output = new RevSliderOutput();

		$slider = new RevSlider();
		$slider->initByID($sliderID);

		$output->setPreviewMode();

		//put the output html
		$urlPlugin = "http://yourpluginpath/";
		$urlPreviewPattern = UniteBaseClassRev::$url_ajax_actions."&client_action=preview_slider&only_markup=true&sliderid=".$sliderID."&lang=[lang]&nonce=[nonce]";

		$setBase = (Mage::helper('nwdrevslider')->isSsl()) ? "https://" : "http://";

		?>
		<html>
		<head>
			<script type='text/javascript' src='<?php echo $setBase; ?>ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
		</head>
		<body style="padding:0px;margin:0px;">
		<?php
		//UniteBaseClassRev::$url_plugin

		ob_start();
		?><link rel='stylesheet' href='<?php echo $urlPlugin?>css/settings.css?rev=<?php echo GlobalsRevSlider::SLIDER_REVISION; ?>' type='text/css' media='all' />
<?php
		$_usedStyles = array();
		$_slides = $slider->getSlides();
		foreach ($_slides as $_slide) {
			$_layers = $_slide->getLayers();
			foreach ($_layers as $_layer) {
				$_style = isset($_layer['style']) ? $_layer['style'] : '';
				if ($_style && ! in_array($_style, $_usedStyles))
				{
					$_usedStyles[] = $_style;
				}
			}
		}
		echo Mage::helper('nwdrevslider')->inlcudeStyleFonts($_usedStyles);

	$http = (Mage::helper('nwdrevslider')->isSsl()) ? 'https' : 'http';
	?>

<script type='text/javascript' src='<?php echo $urlPlugin?>js/jquery.themepunch.tools.min.js?rev=<?php echo GlobalsRevSlider::SLIDER_REVISION; ?>'></script>
<script type='text/javascript' src='<?php echo $urlPlugin?>js/jquery.themepunch.revolution.min.js?rev=<?php echo GlobalsRevSlider::SLIDER_REVISION; ?>'></script>
<?php
		$head_content = ob_get_contents();
		ob_clean();
		ob_end_clean();

		ob_start();

		$custom_css = RevOperations::getStaticCss();
		echo $custom_css."\n\n";

		echo '/*****************'."\n";
		echo ' ** '.__('CAPTIONS CSS')."\n";
		echo ' ****************/'."\n\n";
		$db = new UniteDBRev();
		$styles = $db->fetch(GlobalsRevSlider::$table_css);
		echo UniteCssParserRev::parseDbArrayToCss($styles, "\n");

		$style_content = ob_get_contents();
		ob_clean();
		ob_end_clean();

		ob_start();

		$output->putSliderBase($sliderID);

		$content = ob_get_contents();
		ob_clean();
		ob_end_clean();

		$script_content = substr($content, strpos($content, '<script type="text/javascript">'), strpos($content, '</script>') + 9 - strpos($content, '<script type="text/javascript">'));
		$content = htmlentities(str_replace($script_content, '', $content));
		$script_content = str_replace('				', '', $script_content);
		$script_content = str_replace(array('<script type="text/javascript">', '</script>'), '', $script_content);

		?>
		<style>
			body 	 { font-family:sans-serif; font-size:12px;}
			textarea { background:#f1f1f1; border:#ddd; font-size:10px; line-height:16px; margin-bottom:40px; padding:10px;}
			.rev_cont_title { color:#000; text-decoration:none;font-size:14px; line-height:24px; font-weight:800;background: #D5D5D5;padding: 10px;}
			.rev_cont_title a,
			.rev_cont_title a:visited { margin-left:25px;font-size:12px;line-height:12px;float:right;background-color:#8e44ad; color:#fff; padding:8px 10px;text-decoration:none;}
			.rev_cont_title a:hover	  { background-color:#9b59b6}
		</style>
		<p><?php $dir = Mage::getBaseUrl('media'); ?>
			<?php echo Mage::helper('nwdrevslider')->__('Replace image path:'); ?>
			<?php echo Mage::helper('nwdrevslider')->__('From:'); ?>
			<input type="text" name="orig_image_path" value="<?php echo $dir; ?>" />
			<?php echo Mage::helper('nwdrevslider')->__('To:'); ?>
			<input type="text" name="replace_image_path" value="" />
			<input id="rev_replace_images" type="button" name="replace_images" value="<?php echo Mage::helper('nwdrevslider')->__('Replace'); ?>" />
		</p>

		<div class="rev_cont_title"><?php echo Mage::helper('nwdrevslider')->__('Header'); ?> <a class="button-primary revpurple export_slider_standalone copytoclip" data-idt="rev_head_content"  href="javascript:void(0);" original-title=""><?php echo Mage::helper('nwdrevslider')->__('Mark to Copy'); ?></a><div style="clear:both"></div></div>
		<textarea id="rev_head_content" readonly="true" style="width: 100%; height: 100px; color:#3498db"><?php echo $head_content; ?></textarea>
		<div class="rev_cont_title"><?php echo Mage::helper('nwdrevslider')->__('CSS'); ?><a class="button-primary revpurple export_slider_standalone copytoclip" data-idt="rev_style_content"  href="javascript:void(0);" original-title=""><?php echo Mage::helper('nwdrevslider')->__('Mark to Copy'); ?></a></div>
		<textarea id="rev_style_content" readonly="true" style="width: 100%; height: 100px;"><?php echo $style_content; ?></textarea>
		<div class="rev_cont_title"><?php echo Mage::helper('nwdrevslider')->__('Body'); ?><a class="button-primary revpurple export_slider_standalone copytoclip" data-idt="rev_the_content"  href="javascript:void(0);" original-title=""><?php echo Mage::helper('nwdrevslider')->__('Mark to Copy'); ?></a></div>
		<textarea id="rev_the_content" readonly="true" style="width: 100%; height: 100px;"><?php echo $content; ?></textarea>
		<div class="rev_cont_title"><?php echo Mage::helper('nwdrevslider')->__('Script'); ?><a class="button-primary revpurple export_slider_standalone copytoclip" data-idt="rev_script_content"  href="javascript:void(0);" original-title=""><?php echo Mage::helper('nwdrevslider')->__('Mark to Copy'); ?></a></div>
		<textarea id="rev_script_content" readonly="true" style="width: 100%; height: 100px;"><?php echo $script_content; ?></textarea>

		<script>
			jQuery('body').on('click','.copytoclip',function() {
				jQuery("#"+jQuery(this).data('idt')).select();
			});
			jQuery('#rev_replace_images').on('click', function() {
				var originalPath = jQuery('input[name=orig_image_path]').val();
				var replacePath = jQuery('input[name=replace_image_path]').val();
				var revContent = $('#rev_the_content').val();
				$('#rev_the_content').val(revContent.replace(originalPath, replacePath));
				jQuery('input[name=orig_image_path]').val(replacePath);
				jQuery('input[name=replace_image_path]').val(originalPath);
			});
		</script>
		</body>
		</html>
		<?php
		exit();
	}

	/**
	 *
	 * get contents of the static css file
	 */
	public static function getStaticCss(){
		if( ! Mage::getModel('nwdrevslider/options')->getOption('revslider-static-css')){
			$contentCSS = @file_get_contents(GlobalsRevSlider::$filepath_static_captions);
			self::updateStaticCss($contentCSS);
		}
		$contentCSS = Mage::getModel('nwdrevslider/options')->getOption('revslider-static-css', '');

		return($contentCSS);
	}

	/**
	 *
	 * get contents of the static css file
	 */
	public static function updateStaticCss($content){

		$content = str_replace(array("\'", '\"', '\\\\'),array("'", '"', '\\'), trim($content));

		$c = Mage::getModel('nwdrevslider/options')->getOption('revslider-static-css', '');
		$c = Mage::getModel('nwdrevslider/options')->updateOption('revslider-static-css', $content);

		Mage::helper('nwdrevslider/css')->putStaticCss();

		return $content;
	}

	/**
	 *
	 * output loading message
	 */
	public function loadingMessageOutput(){
		?>
		<div class="message_loading_preview"><?php echo Mage::helper('nwdrevslider')->__("Loading Preview...")?></div>
		<?php
	}

	/**
	 *
	 * get all font family types
	 */
	public function getArrFontFamilys($slider){
		//Web Safe Fonts
		$fonts = array(
			//Serif Fonts
			'Georgia, serif',
			'"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'"Times New Roman", Times, serif',

			//Sans-Serif Fonts
			'Arial, Helvetica, sans-serif',
			'"Arial Black", Gadget, sans-serif',
			'"Comic Sans MS", cursive, sans-serif',
			'Impact, Charcoal, sans-serif',
			'"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			'Tahoma, Geneva, sans-serif',
			'"Trebuchet MS", Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',

			//Monospace Fonts
			'"Courier New", Courier, monospace',
			'"Lucida Console", Monaco, monospace'
		);

		// NWD Google Fonts

		$_googleFonts = Mage::getModel('nwdall/system_config_googlefonts')->toOptionArray();
		foreach ($_googleFonts as $_font) {
			$fonts[] = $_font['value'];
		}

		return $fonts;
	}

	/**
	 *
	 * update captions css file content
	 * @return new captions html select
	 */
	public function updateCaptionsContentData($content){
		if(isset($content['handle'])) {

			$handle = $content['handle'];

			$arrUpdate = array();
			$arrUpdate["params"] = stripslashes(json_encode(str_replace("'", '"', $content['params'])));
			$arrUpdate["hover"] = stripslashes(json_encode(str_replace("'", '"', @$content['hover'])));
			$arrUpdate["settings"] = stripslashes(json_encode(str_replace("'", '"', @$content['settings'])));

			$_cssItem = Mage::getModel('nwdrevslider/css')->getCollection()
				->addFieldToFilter('handle', '.tp-caption.' . $handle)
				->setPageSize(1)
				->getFirstItem();

			$_cssItem->addData($arrUpdate)->save();
		}

		//output captions array
		$operations = new RevOperations();
		$cssContent = $operations->getCaptionsContent();
		$arrCaptions = $operations->getArrCaptionClasses($cssContent);
		return($arrCaptions);
	}

}
