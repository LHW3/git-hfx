<?php

// Overrides original revslider admin class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/revslider/revslider_admin.php';

class RevSliderAdmin extends RevSliderAdminOriginal {

	/**
	 *
	 * the constructor
	 */

	public function __construct(){

		UniteBaseClassRev::__construct('', $this);

		//set table names
		GlobalsRevSlider::$table_sliders = GlobalsRevSlider::TABLE_SLIDERS_NAME;
		GlobalsRevSlider::$table_slides = GlobalsRevSlider::TABLE_SLIDES_NAME;
		GlobalsRevSlider::$table_static_slides = GlobalsRevSlider::TABLE_STATIC_SLIDES_NAME;
		GlobalsRevSlider::$table_settings = GlobalsRevSlider::TABLE_SETTINGS_NAME;
		GlobalsRevSlider::$table_css = GlobalsRevSlider::TABLE_CSS_NAME;
		GlobalsRevSlider::$table_layer_anims = GlobalsRevSlider::TABLE_LAYER_ANIMS_NAME;

		GlobalsRevSlider::$filepath_backup = self::$path_plugin."backup/";
		GlobalsRevSlider::$filepath_captions = self::$path_plugin."rs-plugin/css/captions.css";
		GlobalsRevSlider::$urlCaptionsCSS = Mage::getDesign()->getSkinUrl('css/nwdthemes/revslider/dynamic.css');
		GlobalsRevSlider::$urlStaticCaptionsCSS = Mage::getDesign()->getSkinUrl('css/nwdthemes/revslider/static.css');
		GlobalsRevSlider::$filepath_dynamic_captions = Mage::getDesign()->getSkinBaseDir() . "/revslider/css/dynamic-captions.css";
		GlobalsRevSlider::$filepath_static_captions = Mage::getDesign()->getSkinBaseDir() . "/revslider/css/static-captions.css";
		GlobalsRevSlider::$filepath_captions_original = Mage::getDesign()->getSkinBaseDir() . "/revslider/css/captions-original.css";
		GlobalsRevSlider::$urlExportZip = Mage::getBaseDir('tmp') . '/revslider_export.zip';

		$this->init();
	}

	/**
	 *
	 * init all actions
	 */
	private function init(){
		global $revSliderAsTheme;
		self::requireSettings("general_settings");
	}

	/**
	 *
	 * onAjax action handler
	 */
	public static function onAjaxAction(){

		$slider = new RevSlider();
		$slide = new RevSlide();
		$operations = new RevOperations();

		$action = self::getPostGetVar("client_action");
		$data = self::getPostGetVar("data");

		try{

			switch($action){
				case "export_slider":
					$sliderID = self::getGetVar("sliderid");
					$dummy = self::getGetVar("dummy");
					$slider->initByID($sliderID);
					$slider->exportSlider($dummy);
				break;
				case "import_slider":
					$updateAnim = self::getPostGetVar("update_animations");
					$updateStatic = self::getPostGetVar("update_static_captions");
					self::importSliderHandle(null, $updateAnim, $updateStatic);
				break;
				case "import_slider_slidersview":
					$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
					$updateAnim = self::getPostGetVar("update_animations");
					$updateStatic = self::getPostGetVar("update_static_captions");
					self::importSliderHandle($viewBack, $updateAnim, $updateStatic);
				break;
				case "create_slider":
					self::requireSettings("slider_settings");
					$settingsMain = self::getSettings("slider_main");
					$settingsParams = self::getSettings("slider_params");

					$data = $operations->modifyCustomSliderParams($data);

					$newSliderID = $slider->createSliderFromOptions($data,$settingsMain,$settingsParams);

					self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("The slider successfully created"),
								self::getViewUrl("sliders"));

				break;
				case "update_slider":
					self::requireSettings("slider_settings");
					$settingsMain = self::getSettings("slider_main");
					$settingsParams = self::getSettings("slider_params");

					$data = $operations->modifyCustomSliderParams($data);

					$slider->updateSliderFromOptions($data,$settingsMain,$settingsParams);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Slider updated"));
				break;

				case "delete_slider":

					$isDeleted = $slider->deleteSliderFromData($data);

					if(is_array($isDeleted)){
						$isDeleted = implode(', ', $isDeleted);
						self::ajaxResponseError("Template can't be deleted, it is still being used by the following Sliders: ".$isDeleted);
					}else{
						self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("The slider deleted"),
								self::getViewUrl(self::VIEW_SLIDERS));
					}
				break;
				case "duplicate_slider":

					$slider->duplicateSliderFromData($data);

					self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("The duplicate successfully, refreshing page..."),
								self::getViewUrl(self::VIEW_SLIDERS));
				break;
				case "add_slide":
					$numSlides = $slider->createSlideFromData($data);
					$sliderID = $data["sliderid"];

					if($numSlides == 1){
						$responseText = Mage::helper('nwdrevslider')->__("Slide Created");
					}
					else
						$responseText = $numSlides . " ".Mage::helper('nwdrevslider')->__("Slides Created");

					$urlRedirect = self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID");
					self::ajaxResponseSuccessRedirect($responseText,$urlRedirect);

				break;
				case "add_slide_fromslideview":
					$slideID = $slider->createSlideFromData($data,true);
					$urlRedirect = self::getViewUrl(self::VIEW_SLIDE,"id=$slideID");
					$responseText = Mage::helper('nwdrevslider')->__("Slide Created, redirecting...");
					self::ajaxResponseSuccessRedirect($responseText,$urlRedirect);
				break;
				case "update_slide":
					require self::getSettingsFilePath("slide_settings");

					$slide->updateSlideFromData($data,$slideSettings);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Slide updated"));
				break;
				case "update_static_slide":
					$slide->updateStaticSlideFromData($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Static Global Layers updated"));
				break;
				case "delete_slide":
					$isPost = $slide->deleteSlideFromData($data);
					if($isPost)
						$message = Mage::helper('nwdrevslider')->__("Product Slide Deleted Successfully");
					else
						$message = Mage::helper('nwdrevslider')->__("Slide Deleted Successfully");

					$sliderID = UniteFunctionsRev::getVal($data, "sliderID");
					self::ajaxResponseSuccessRedirect($message, self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));
				break;
				case "duplicate_slide":
					$sliderID = $slider->duplicateSlideFromData($data);
					self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("Slide Duplicated Successfully"),
								self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));
				break;
				case "copy_move_slide":
					$sliderID = $slider->copyMoveSlideFromData($data);

					self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("The operation successfully, refreshing page..."),
								self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));
				break;
				case "get_static_css":
					$contentCSS = $operations->getStaticCss();
					self::ajaxResponseData($contentCSS);
				break;
				case "get_dynamic_css":
					$contentCSS = $operations->getDynamicCss();
					self::ajaxResponseData($contentCSS);
				break;
				case "insert_captions_css":
					$arrCaptions = $operations->insertCaptionsContentData($data);
					Mage::helper('nwdrevslider/css')->putDynamicCss();
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("CSS saved succesfully!"),array("arrCaptions"=>$arrCaptions));
				break;
				case "update_captions_css":
					$arrCaptions = $operations->updateCaptionsContentData($data);
					Mage::helper('nwdrevslider/css')->putDynamicCss();
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("CSS saved succesfully!"),array("arrCaptions"=>$arrCaptions));
				break;
				case "delete_captions_css":
					$arrCaptions = $operations->deleteCaptionsContentData($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Style deleted succesfully!"),array("arrCaptions"=>$arrCaptions));
				break;
				case "update_static_css":
					$staticCss = $operations->updateStaticCss($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("CSS saved succesfully!"),array("css"=>$staticCss));
				break;
				case "insert_custom_anim":
					$arrAnims = $operations->insertCustomAnim($data); //$arrCaptions =
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Animation saved succesfully!"), $arrAnims); //,array("arrCaptions"=>$arrCaptions)
				break;
				case "update_custom_anim":
					$arrAnims = $operations->updateCustomAnim($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Animation saved succesfully!"), $arrAnims); //,array("arrCaptions"=>$arrCaptions)
				break;
				case "delete_custom_anim":
					$arrAnims = $operations->deleteCustomAnim($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Animation saved succesfully!"), $arrAnims); //,array("arrCaptions"=>$arrCaptions)
				break;
				case "update_slides_order":
					$slider->updateSlidesOrderFromData($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Order updated successfully"));
				break;
				case "change_slide_image":
					$slide->updateSlideImageFromData($data);
					$sliderID = UniteFunctionsRev::getVal($data, "slider_id");
					self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("Slide Changed Successfully"),
								self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));
				break;
				case "preview_slide":
					$operations->putSlidePreviewByData($data);
				break;
				case "preview_slider":
					$sliderID = UniteFunctionsRev::getPostGetVariable("sliderid");
					$storeID = UniteFunctionsRev::getPostGetVariable("store_id", 0);
					$do_markup = UniteFunctionsRev::getPostGetVariable("only_markup");

					if($do_markup == 'true')
						$operations->previewOutputMarkup($sliderID);
					else
						$operations->previewOutput($sliderID, null, $storeID);
				break;
				case "toggle_slide_state":
					$currentState = $slide->toggleSlideStatFromData($data);
					self::ajaxResponseData(array("state"=>$currentState));
				break;
				case "slide_lang_operation":
					$responseData = $slide->doSlideLangOperation($data);
					self::ajaxResponseData($responseData);
				break;
				case "update_plugin":
					self::updatePlugin(self::DEFAULT_VIEW);
				break;
				case "update_text":
					self::updateSettingsText();
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("All files successfully updated"));
				break;
				case "update_general_settings":
					$operations->updateGeneralSettings($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("General settings updated"));
				break;
				case "update_posts_sortby":
					$slider->updatePostsSortbyFromData($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Sortby updated"));
				break;
				case "replace_image_urls":
					$slider->replaceImageUrlsFromData($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Image urls replaced"));
				break;
				case "reset_slide_settings":
					$slider->resetSlideSettings($data);
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("Settings in all Slides changed"));
				break;
				case "activate_purchase_code":

					$result = false;

					if(!empty($data['username']) && !empty($data['api_key']) && !empty($data['code'])){

						$result = $operations->checkPurchaseVerification($data);

					}else{
						UniteFunctionsRev::throwError(Mage::helper('nwdrevslider')->__('The API key, the Purchase Code and the Username need to be set!', REVSLIDER_TEXTDOMAIN));
						exit();
					}

					if($result){
						self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("Purchase Code Successfully Activated"),
								self::getViewUrl(self::VIEW_SLIDERS));
					}else{
						UniteFunctionsRev::throwError(Mage::helper('nwdrevslider')->__('Purchase Code is invalid', REVSLIDER_TEXTDOMAIN));
					}
				break;
				case "deactivate_purchase_code":
					$result = $operations->doPurchaseDeactivation($data);

					if($result){
						self::ajaxResponseSuccessRedirect(
								Mage::helper('nwdrevslider')->__("Successfully removed validation"),
								self::getViewUrl(self::VIEW_SLIDERS));
					}else{
						UniteFunctionsRev::throwError(Mage::helper('nwdrevslider')->__('Could not remove Validation!', REVSLIDER_TEXTDOMAIN));
					}
				break;
				case "dismiss_notice":
					Mage::getModel('nwdrevslider/options')->updateOption('revslider-valid-notice', 'false');
					self::ajaxResponseSuccess(Mage::helper('nwdrevslider')->__("."));
				break;

				default:
					self::ajaxResponseError("wrong ajax action: $action ");
				break;
			}

		}
		catch(Exception $e){

			$message = $e->getMessage();
			if($action == "preview_slide" || $action == "preview_slider"){
				echo $message;
				exit();
			}

			self::ajaxResponseError($message);
		}

		//it's an ajax action, so exit
		self::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
		exit();
	}

	/**
	 *
	 * create db tables
	 * Do not need it
	 */
	public static function createDBTables() {
	}

	/**
	 *
	 * import slideer handle (not ajax response)
	 */
	private static function importSliderHandle($viewBack = null, $updateAnim = true, $updateStatic = true){

		Mage::helper('nwdrevslider')->dmp(Mage::helper('nwdrevslider')->__("importing slider setings and data..."));

		$slider = new RevSlider();
		$response = $slider->importSliderFromPost($updateAnim, $updateStatic);
		$sliderID = $response["sliderID"];

		if(empty($viewBack)){
			$viewBack = self::getViewUrl(self::VIEW_SLIDER,"id=".$sliderID);
			if(empty($sliderID))
				$viewBack = self::getViewUrl(self::VIEW_SLIDERS);
		}

		//handle error
		if($response["success"] == false){
			$message = $response["error"];
			Mage::helper('nwdrevslider')->dmp("<b>Error: ".$message."</b>");
			echo UniteFunctionsRev::getHtmlLink($viewBack, Mage::helper('nwdrevslider')->__("Go Back"));
		}
		else{	//handle success, js redirect.
			Mage::helper('nwdrevslider')->dmp(Mage::helper('nwdrevslider')->__("Slider Import Success, redirecting..."));
			echo "<script>location.href='$viewBack'</script>";
		}
		exit();
	}

	public static function enqueue_styles(){
	}

}
