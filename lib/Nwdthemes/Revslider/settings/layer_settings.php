<?php

	$operations = new RevOperations();

	//set Layer settings
	$contentCSS = $operations->getCaptionsContent();
	$arrAnimations = $operations->getArrAnimations();
	$arrEndAnimations = $operations->getArrEndAnimations();

	$htmlButtonDown = '<div id="layer_captions_down" style="width:30px; text-align:center;padding:0px;" class="revgray button-primary"><i class="eg-icon-down-dir"></i></div>';
	$buttonEditStyles = UniteFunctionsRev::getHtmlLink("javascript:void(0)", "<i class=\"revicon-magic\"></i>Edit Style","button_edit_css","button-primary revblue");
	$buttonEditStylesGlobal = UniteFunctionsRev::getHtmlLink("javascript:void(0)", "<i class=\"revicon-palette\"></i>Edit Global Style","button_edit_css_global","button-primary revblue");

	$arrSplit = $operations->getArrSplit();
	$arrEasing = $operations->getArrEasing();
	$arrEndEasing = $operations->getArrEndEasing();

	$captionsAddonHtml = $htmlButtonDown.$buttonEditStyles.$buttonEditStylesGlobal;

	//set Layer settings
	$layerSettings = new UniteSettingsAdvancedRev();
	$layerSettings->addSection(Mage::helper('nwdrevslider')->__("Layer Params"),Mage::helper('nwdrevslider')->__("layer_params"));
	$layerSettings->addSap(Mage::helper('nwdrevslider')->__("Layer Params"),Mage::helper('nwdrevslider')->__("layer_params"));
	$layerSettings->addTextBox("layer_caption", Mage::helper('nwdrevslider')->__("caption_green"), Mage::helper('nwdrevslider')->__("Style"),array(UniteSettingsRev::PARAM_ADDTEXT=>$captionsAddonHtml,"class"=>"textbox-caption"));

	$addHtmlTextarea = '';
	if($sliderTemplate == "true"){
		$addHtmlTextarea .= UniteFunctionsRev::getHtmlLink("javascript:void(0)", "Insert Meta","linkInsertTemplate","disabled revblue button-primary");
	}
	$addHtmlTextarea .= UniteFunctionsRev::getHtmlLink("javascript:void(0)", "Insert Button","linkInsertButton","disabled revblue button-primary");

	$layerSettings->addTextArea("layer_text", "",Mage::helper('nwdrevslider')->__("Text / Html"),array("class"=>"area-layer-params",UniteSettingsRev::PARAM_ADDTEXT_BEFORE_ELEMENT=>$addHtmlTextarea));
	$layerSettings->addTextBox("layer_image_link", "",Mage::helper('nwdrevslider')->__("Image Link"),array("class"=>"text-sidebar-link","hidden"=>true));
	$layerSettings->addSelect("layer_link_open_in",array("same"=>Mage::helper('nwdrevslider')->__("Same Window"),"new"=>Mage::helper('nwdrevslider')->__("New Window")),Mage::helper('nwdrevslider')->__("Link Open In"),"same",array("hidden"=>true));

	$layerSettings->addSelect("layer_animation",$arrAnimations,Mage::helper('nwdrevslider')->__("Start Animation"),"fade");
	$layerSettings->addSelect("layer_easing", $arrEasing, Mage::helper('nwdrevslider')->__("Start Easing"),"Power3.easeInOut");
	$params = array("unit"=>Mage::helper('nwdrevslider')->__("ms"));
	$paramssplit = array("unit"=>Mage::helper('nwdrevslider')->__(" ms (keep it low i.e. 1- 200)"));
	$layerSettings->addTextBox("layer_speed", "","Start Duration",$params);
	$layerSettings->addTextBox("layer_splitdelay", "10","Split Delay",$paramssplit);
	$layerSettings->addSelect("layer_split", $arrSplit, Mage::helper('nwdrevslider')->__("Split Text per"),"none");
	$layerSettings->addCheckbox("layer_hidden", false,Mage::helper('nwdrevslider')->__("Hide Under Width"));

	$params = array("hidden"=>true);
	$layerSettings->addTextBox("layer_link_id", "",Mage::helper('nwdrevslider')->__("Link ID"),$params);
	$layerSettings->addTextBox("layer_link_class", "",Mage::helper('nwdrevslider')->__("Link Classes"),$params);
	$layerSettings->addTextBox("layer_link_title", "",Mage::helper('nwdrevslider')->__("Link Title"),$params);
	$layerSettings->addTextBox("layer_link_rel", "",Mage::helper('nwdrevslider')->__("Link Rel"),$params);

	//scale for img
	$textScaleX = Mage::helper('nwdrevslider')->__("Width");
	$textScaleProportionalX = Mage::helper('nwdrevslider')->__("Width/Height");
	$params = array("attrib_text"=>"data-textproportional='".$textScaleProportionalX."' data-textnormal='".$textScaleX."'", "hidden"=>false);
	$layerSettings->addTextBox("layer_scaleX", "",Mage::helper('nwdrevslider')->__("Width"),$params);
	$layerSettings->addTextBox("layer_scaleY", "",Mage::helper('nwdrevslider')->__("Height"),array("hidden"=>false));
	$layerSettings->addCheckbox("layer_proportional_scale", false,Mage::helper('nwdrevslider')->__("Scale Proportional"),array("hidden"=>false));

	$arrParallaxLevel = array(
							'-' => Mage::helper('nwdrevslider')->__('No Movement'),
							'1' => Mage::helper('nwdrevslider')->__('1'),
							'2' => Mage::helper('nwdrevslider')->__('2'),
							'3' => Mage::helper('nwdrevslider')->__('3'),
							'4' => Mage::helper('nwdrevslider')->__('4'),
							'5' => Mage::helper('nwdrevslider')->__('5'),
							'6' => Mage::helper('nwdrevslider')->__('6'),
							'7' => Mage::helper('nwdrevslider')->__('7'),
							'8' => Mage::helper('nwdrevslider')->__('8'),
							'9' => Mage::helper('nwdrevslider')->__('9'),
							'10' => Mage::helper('nwdrevslider')->__('10'),
							);
	$layerSettings->addSelect("parallax_level", $arrParallaxLevel, Mage::helper('nwdrevslider')->__("Level"),"nowrap", array("hidden"=>false));


	//put left top
	$textOffsetX = Mage::helper('nwdrevslider')->__("OffsetX");
	$textX = Mage::helper('nwdrevslider')->__("X");
	$params = array("attrib_text"=>"data-textoffset='".$textOffsetX."' data-textnormal='".$textX."'");
	$layerSettings->addTextBox("layer_left", "",Mage::helper('nwdrevslider')->__("X"),$params);

	$textOffsetY = Mage::helper('nwdrevslider')->__("OffsetY");
	$textY = Mage::helper('nwdrevslider')->__("Y");
	$params = array("attrib_text"=>"data-textoffset='".$textOffsetY."' data-textnormal='".$textY."'");
	$layerSettings->addTextBox("layer_top", "",Mage::helper('nwdrevslider')->__("Y"),$params);

	$layerSettings->addTextBox("layer_align_hor", "left",Mage::helper('nwdrevslider')->__("Hor Align"),array("hidden"=>true));
	$layerSettings->addTextBox("layer_align_vert", "top",Mage::helper('nwdrevslider')->__("Vert Align"),array("hidden"=>true));

	$para = array("unit"=>Mage::helper('nwdrevslider')->__("&nbsp;(example: 50px, auto)"), 'hidden'=>true);
	$layerSettings->addTextBox("layer_max_width", "auto",Mage::helper('nwdrevslider')->__("Max Width"),$para);
	$layerSettings->addTextBox("layer_max_height", "auto",Mage::helper('nwdrevslider')->__("Max Height"),$para);

	$layerSettings->addTextBox("layer_2d_rotation", "0",Mage::helper('nwdrevslider')->__("2D Rotation"),array("hidden"=>false, 'unit'=>'&nbsp;(-360 - 360)'));
	$layerSettings->addTextBox("layer_2d_origin_x", "50",Mage::helper('nwdrevslider')->__("Rotation Origin X"),array("hidden"=>false, 'unit'=>'%&nbsp;(-100 - 200)'));
	$layerSettings->addTextBox("layer_2d_origin_y", "50",Mage::helper('nwdrevslider')->__("Rotation Origin Y"),array("hidden"=>false, 'unit'=>'%&nbsp;(-100 - 200)'));

	//advanced params
	$arrWhiteSpace = array("normal"=>Mage::helper('nwdrevslider')->__("Normal"),
						"pre"=>Mage::helper('nwdrevslider')->__("Pre"),
						"nowrap"=>Mage::helper('nwdrevslider')->__("No-wrap"),
						"pre-wrap"=>Mage::helper('nwdrevslider')->__("Pre-Wrap"),
						"pre-line"=>Mage::helper('nwdrevslider')->__("Pre-Line"));


	$layerSettings->addSelect("layer_whitespace", $arrWhiteSpace, Mage::helper('nwdrevslider')->__("White Space"),"nowrap", array("hidden"=>true));


	$layerSettings->addSelect("layer_slide_link", $arrSlideLinkLayers, Mage::helper('nwdrevslider')->__("Link To Slide"),"nothing");

	$params = array("unit"=>Mage::helper('nwdrevslider')->__("px"),"hidden"=>true);
	$layerSettings->addTextBox("layer_scrolloffset", "0",Mage::helper('nwdrevslider')->__("Scroll Under Slider Offset"),$params);

	$layerSettings->addButton("button_change_image_source", Mage::helper('nwdrevslider')->__("Change Image Source"),array("hidden"=>true,"class"=>"button-primary revblue"));
	$layerSettings->addTextBox("layer_alt", "","Alt Text",array("hidden"=>true, "class"=>"area-alt-params"));
	$layerSettings->addButton("button_edit_video", Mage::helper('nwdrevslider')->__("Edit Video"),array("hidden"=>true,"class"=>"button-primary revblue"));



	$params = array("unit"=>Mage::helper('nwdrevslider')->__("ms"));
	$paramssplit = array("unit"=>Mage::helper('nwdrevslider')->__(" ms (keep it low i.e. 1- 200)"));
	$params_1 = array("unit"=>Mage::helper('nwdrevslider')->__("ms"), 'hidden'=>true);
	$layerSettings->addTextBox("layer_endtime", "",Mage::helper('nwdrevslider')->__("End Time"),$params_1);
	$layerSettings->addTextBox("layer_endspeed", "",Mage::helper('nwdrevslider')->__("End Duration"),$params);
	$layerSettings->addTextBox("layer_endsplitdelay", "10","End Split Delay",$paramssplit);
	$layerSettings->addSelect("layer_endsplit", $arrSplit, Mage::helper('nwdrevslider')->__("Split Text per"),"none");
	$layerSettings->addSelect("layer_endanimation",$arrEndAnimations,Mage::helper('nwdrevslider')->__("End Animation"),"auto");
	$layerSettings->addSelect("layer_endeasing", $arrEndEasing, Mage::helper('nwdrevslider')->__("End Easing"),"nothing");
	$params = array("unit"=>Mage::helper('nwdrevslider')->__("ms"));

	//advanced params
	$arrCorners = array("nothing"=>Mage::helper('nwdrevslider')->__("No Corner"),
						"curved"=>Mage::helper('nwdrevslider')->__("Sharp"),
						"reverced"=>Mage::helper('nwdrevslider')->__("Sharp Reversed"));
	$params = array();
	$layerSettings->addSelect("layer_cornerleft", $arrCorners, Mage::helper('nwdrevslider')->__("Left Corner"),"nothing",$params);
	$layerSettings->addSelect("layer_cornerright", $arrCorners, Mage::helper('nwdrevslider')->__("Right Corner"),"nothing",$params);
	$layerSettings->addCheckbox("layer_resizeme", true,Mage::helper('nwdrevslider')->__("Responsive Through All Levels"),$params);

	$params = array();
	$layerSettings->addTextBox("layer_id", "",Mage::helper('nwdrevslider')->__("ID"),$params);
	$layerSettings->addTextBox("layer_classes", "",Mage::helper('nwdrevslider')->__("Classes"),$params);
	$layerSettings->addTextBox("layer_title", "",Mage::helper('nwdrevslider')->__("Title"),$params);
	$layerSettings->addTextBox("layer_rel", "",Mage::helper('nwdrevslider')->__("Rel"),$params);

	//Loop Animation
	$arrAnims = array("none"=>Mage::helper('nwdrevslider')->__("Disabled"),
						"rs-pendulum"=>Mage::helper('nwdrevslider')->__("Pendulum"),
                        "rs-rotate"=>Mage::helper('nwdrevslider')->__("Rotate"),
						"rs-slideloop"=>Mage::helper('nwdrevslider')->__("Slideloop"),
						"rs-pulse"=>Mage::helper('nwdrevslider')->__("Pulse"),
						"rs-wave"=>Mage::helper('nwdrevslider')->__("Wave")
						);

	$params = array();
	$layerSettings->addSelect("layer_loop_animation", $arrAnims, Mage::helper('nwdrevslider')->__("Animation"),"none",$params);
	$layerSettings->addTextBox("layer_loop_speed", "2",Mage::helper('nwdrevslider')->__("Speed"),array("unit"=>Mage::helper('nwdrevslider')->__("&nbsp;(0.00 - 10.00)")));
	$layerSettings->addTextBox("layer_loop_startdeg", "-20",Mage::helper('nwdrevslider')->__("Start Degree"));
	$layerSettings->addTextBox("layer_loop_enddeg", "20",Mage::helper('nwdrevslider')->__("End Degree"),array("unit"=>Mage::helper('nwdrevslider')->__("&nbsp;(-720 - 720)")));
	$layerSettings->addTextBox("layer_loop_xorigin", "50",Mage::helper('nwdrevslider')->__("x Origin"),array("unit"=>Mage::helper('nwdrevslider')->__("%")));
	$layerSettings->addTextBox("layer_loop_yorigin", "50",Mage::helper('nwdrevslider')->__("y Origin"),array("unit"=>Mage::helper('nwdrevslider')->__("% (-250% - 250%)")));
	$layerSettings->addTextBox("layer_loop_xstart", "0",Mage::helper('nwdrevslider')->__("x Start Pos."),array("unit"=>Mage::helper('nwdrevslider')->__("px")));
	$layerSettings->addTextBox("layer_loop_xend", "0",Mage::helper('nwdrevslider')->__("x End Pos."),array("unit"=>Mage::helper('nwdrevslider')->__("px (-2000px - 2000px)")));
	$layerSettings->addTextBox("layer_loop_ystart", "0",Mage::helper('nwdrevslider')->__("y Start Pos."),array("unit"=>Mage::helper('nwdrevslider')->__("px")));
	$layerSettings->addTextBox("layer_loop_yend", "0",Mage::helper('nwdrevslider')->__("y End Pos."),array("unit"=>Mage::helper('nwdrevslider')->__("px (-2000px - 2000px)")));
	$layerSettings->addTextBox("layer_loop_zoomstart", "1",Mage::helper('nwdrevslider')->__("Start Zoom"));
	$layerSettings->addTextBox("layer_loop_zoomend", "1",Mage::helper('nwdrevslider')->__("End Zoom"),array("unit"=>Mage::helper('nwdrevslider')->__("&nbsp;(0.00 - 20.00)")));
	$layerSettings->addTextBox("layer_loop_angle", "0",Mage::helper('nwdrevslider')->__("Angle"),array("unit"=>Mage::helper('nwdrevslider')->__("° (0° - 360°)")));
	$layerSettings->addTextBox("layer_loop_radius", "10",Mage::helper('nwdrevslider')->__("Radius"),array("unit"=>Mage::helper('nwdrevslider')->__("px (0px - 2000px)")));
	$layerSettings->addSelect("layer_loop_easing", $arrEasing, Mage::helper('nwdrevslider')->__("Easing"),"Power3.easeInOut");

	self::storeSettings("layer_settings",$layerSettings);

	//store settings of content css for editing on the client.
	self::storeSettings("css_captions_content",$contentCSS);

