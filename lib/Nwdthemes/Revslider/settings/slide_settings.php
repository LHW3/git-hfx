<?php

	//set Slide settings
	$arrTransitions = $operations->getArrTransition();
	$arrPremiumTransitions = $operations->getArrTransition(true);
	$defaultTransition = $operations->getDefaultTransition();

	$arrSlideNames = array();
	if(isset($slider) && $slider->isInited())
		$arrSlideNames = $slider->getArrSlideNames();

	$slideSettings = new UniteSettingsAdvancedRev();

	//title
	$params = array("description"=>Mage::helper('nwdrevslider')->__("The title of the slide, will be shown in the slides list."),"class"=>"medium");
	$slideSettings->addTextBox("title",Mage::helper('nwdrevslider')->__("Slide"),Mage::helper('nwdrevslider')->__("Slide Title"), $params);

	// store view

	$arrParams = array(
		'minwidth'		=> '250px',
		'description' 	=> Mage::helper('nwdrevslider')->__('Slide will be visible on selected stores')
	);
	$slideSettings->addChecklist("store_id", Mage::helper('nwdrevslider')->getStoreOptions(), Mage::helper('nwdrevslider')->__("Store View"), 0, $params);

	//state
	$params = array("description"=>Mage::helper('nwdrevslider')->__("The state of the slide. The unpublished slide will be excluded from the slider."));
	$slideSettings->addSelect("state",array("published"=>Mage::helper('nwdrevslider')->__("Published"),"unpublished"=>Mage::helper('nwdrevslider')->__("Unpublished")),Mage::helper('nwdrevslider')->__("State"),"published",$params);

	$params = array("description"=>Mage::helper('nwdrevslider')->__("If set, slide will be visible after the date is reached"));
	$slideSettings->addDatePicker("date_from","",Mage::helper('nwdrevslider')->__("Visible from"), $params);

	$params = array("description"=>Mage::helper('nwdrevslider')->__("If set, slide will be visible till the date is reached"));
	$slideSettings->addDatePicker("date_to","",Mage::helper('nwdrevslider')->__("Visible until"), $params);

	$slideSettings->addHr("");

	//transition
	$params = array("description"=>Mage::helper('nwdrevslider')->__("The appearance transitions of this slide."),"minwidth"=>"250px");
	$slideSettings->addChecklist("slide_transition",$arrTransitions,Mage::helper('nwdrevslider')->__("Transitions"),$defaultTransition,$params);

	//slot amount
	$params = array("description"=>Mage::helper('nwdrevslider')->__("The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy.")
		,"class"=>"small","datatype"=>"number"
	);
	$slideSettings->addTextBox("slot_amount","7",Mage::helper('nwdrevslider')->__("Slot Amount"), $params);

	//rotation:
	$params = array("description"=>Mage::helper('nwdrevslider')->__("Rotation (-720 -> 720, 999 = random) Only for Simple Transitions.")
		,"class"=>"small","datatype"=>"number"
	);
	$slideSettings->addTextBox("transition_rotation","0",Mage::helper('nwdrevslider')->__("Rotation"), $params);

	//transition speed
	$params = array("description"=>Mage::helper('nwdrevslider')->__("The duration of the transition (Default:300, min: 100 max 2000). ")
		,"class"=>"small","datatype"=>"number"
	);
	$slideSettings->addTextBox("transition_duration","300",Mage::helper('nwdrevslider')->__("Transition Duration"), $params);


	if(!isset($sliderDelay))
		$sliderDelay = 0;

	//delay
	$params = array("description"=>Mage::helper('nwdrevslider')->__("A new delay value for the Slide. If no delay defined per slide, the delay defined via Options ("). $sliderDelay .Mage::helper('nwdrevslider')->__("ms) will be used")
		,"class"=>"small","datatype"=>UniteSettingsRev::DATATYPE_NUMBEROREMTY
	);
	$slideSettings->addTextBox("delay","",Mage::helper('nwdrevslider')->__("Delay"), $params);

	$params = array("description"=>Mage::helper('nwdrevslider')->__("")
		,"class"=>"small"
	);
	$slideSettings->addRadio("save_performance", array("on"=>Mage::helper('nwdrevslider')->__("On"),"off"=>Mage::helper('nwdrevslider')->__("Off")), Mage::helper('nwdrevslider')->__("Save Performance"),"off", $params);

	$slideSettings->addHr("");

	//-----------------------

	//enable link
	$slideSettings->addSelect_boolean("enable_link", Mage::helper('nwdrevslider')->__("Enable Link"), false, Mage::helper('nwdrevslider')->__("Enable"),Mage::helper('nwdrevslider')->__("Disable"));

	$slideSettings->startBulkControl("enable_link", UniteSettingsRev::CONTROL_TYPE_SHOW, "true");

		//link type
		$slideSettings->addRadio("link_type", array("regular"=>Mage::helper('nwdrevslider')->__("Regular"),"slide"=>Mage::helper('nwdrevslider')->__("To Slide")), Mage::helper('nwdrevslider')->__("Link Type"),"regular");

		//link
		$params = array('id'=>'rev_link', "description"=>Mage::helper('nwdrevslider')->__("A link on the whole slide pic (use %view_link% or %cart_link% in template sliders to link to a product or add to cart)"));
		$slideSettings->addTextBox("link","",Mage::helper('nwdrevslider')->__("Slide Link"), $params);

		//link target
		$params = array("description"=>Mage::helper('nwdrevslider')->__("The target of the slide link"));
		$slideSettings->addSelect("link_open_in",array("same"=>Mage::helper('nwdrevslider')->__("Same Window"),"new"=>Mage::helper('nwdrevslider')->__("New Window")),Mage::helper('nwdrevslider')->__("Link Open In"),"same",$params);

		//num_slide_link
		$arrSlideLink = array();
		$arrSlideLink["nothing"] = Mage::helper('nwdrevslider')->__("-- Not Chosen --");
		$arrSlideLink["next"] = Mage::helper('nwdrevslider')->__("-- Next Slide --");
		$arrSlideLink["prev"] = Mage::helper('nwdrevslider')->__("-- Previous Slide --");

		$arrSlideLinkLayers = $arrSlideLink;
		$arrSlideLinkLayers["scroll_under"] = Mage::helper('nwdrevslider')->__("-- Scroll Below Slider --");

		foreach($arrSlideNames as $slideNameID=>$arr){
			$slideName = $arr["title"];
			$arrSlideLink[$slideNameID] = $slideName;
			$arrSlideLinkLayers[$slideNameID] = $slideName;
		}

		$slideSettings->addSelect("slide_link", $arrSlideLink, "Link To Slide","nothing");

		$params = array("description"=>"The position of the link related to layers");
		$slideSettings->addRadio("link_pos", array("front"=>"Front","back"=>"Back"), "Link Position","front",$params);

		//$slideSettings->addHr("link_sap");

	$slideSettings->endBulkControl();

		$slideSettings->addControl("link_type", "slide_link", UniteSettingsRev::CONTROL_TYPE_ENABLE, "slide");
		$slideSettings->addControl("link_type", "link", UniteSettingsRev::CONTROL_TYPE_DISABLE, "slide");
		$slideSettings->addControl("link_type", "link_open_in", UniteSettingsRev::CONTROL_TYPE_DISABLE, "slide");

	//-----------------------

	$slideSettings->addHr("");

	$params = array("description"=>Mage::helper('nwdrevslider')->__("Slide Thumbnail. If not set - it will be taken from the slide image."));
	$slideSettings->addImage("slide_thumb", "",Mage::helper('nwdrevslider')->__("Thumbnail") , $params);

	//$params = array("description"=>Mage::helper('nwdrevslider')->__("Apply to full width mode only. Centering vertically slide images."));
	//$slideSettings->addCheckbox("fullwidth_centering", false, Mage::helper('nwdrevslider')->__("Full Width Centering"), $params);


	//add background type (hidden)
	$slideSettings->addTextBox("background_type","image",Mage::helper('nwdrevslider')->__("Background Type"), array("hidden"=>true));
	//store settings

	$slideSettings->addHr("");

	$params = array("description"=>Mage::helper('nwdrevslider')->__('Adds a unique class to the li of the Slide like class="rev_special_class" (add only the classnames, seperated by space)'));
	$slideSettings->addTextBox("class_attr","",Mage::helper('nwdrevslider')->__("Class"), $params);

	$params = array("description"=>Mage::helper('nwdrevslider')->__('Adds a unique ID to the li of the Slide like id="rev_special_id" (add only the id)'));
	$slideSettings->addTextBox("id_attr","",Mage::helper('nwdrevslider')->__("ID"), $params);

	$params = array("description"=>Mage::helper('nwdrevslider')->__('Adds a unique Attribute to the li of the Slide like attr="rev_special_attr" (add only the attribute)'));
	$slideSettings->addTextBox("attr_attr","",Mage::helper('nwdrevslider')->__("Attribute"), $params);

	$params = array("description"=>Mage::helper('nwdrevslider')->__('Add as many attributes as you wish here. (i.e.: data-layer="firstlayer" data-custom="somevalue")'));
	$slideSettings->addTextArea("data_attr","",Mage::helper('nwdrevslider')->__("Custom Fields"), $params);

	//debug_print_backtrace();
	self::storeSettings("slide_settings",$slideSettings);
