<?php

				//set "slider_main" settings
				$sliderMainSettings = new UniteSettingsAdvancedRev();

				$sliderMainSettings->addTextBox("title", "",Mage::helper('nwdrevslider')->__("Slider Title"),array("description"=>Mage::helper('nwdrevslider')->__("The title of the slider. Example: Slider1"),"required"=>"true"));
				$sliderMainSettings->addTextBox("alias", "",Mage::helper('nwdrevslider')->__("Slider Alias"),array("description"=>Mage::helper('nwdrevslider')->__("The alias that will be used for embedding the slider. Example: slider1"),"required"=>"true"));
				$sliderMainSettings->addTextBox("shortcode", "",Mage::helper('nwdrevslider')->__("Slider Shortcode"), array("readonly"=>true,"class"=>"code"));
				$sliderMainSettings->addHr();

				//source type
				$arrSourceTypes = array("posts"=>Mage::helper('nwdrevslider')->__("Products"),
										"specific_posts"=>Mage::helper('nwdrevslider')->__("Specific Products"),
										"gallery"=>Mage::helper('nwdrevslider')->__("Gallery"));

				$sliderMainSettings->addRadio("source_type",$arrSourceTypes,Mage::helper('nwdrevslider')->__("Source Type"), "gallery");

				$arrParams = array("description"=>Mage::helper('nwdrevslider')->__("Type here the Products IDs you want to use separated by coma. ex: 23,24,25"));
				$sliderMainSettings->addTextBox("posts_list","",Mage::helper('nwdrevslider')->__("Specific Products List"),$arrParams);
				$sliderMainSettings->addControl("source_type", "posts_list", UniteSettingsRev::CONTROL_TYPE_SHOW, "specific_posts");

				$sliderMainSettings->startBulkControl("source_type", UniteSettingsRev::CONTROL_TYPE_SHOW, "posts");

					//post types
					$arrPostTypes = array('category' => 'Category');
					$arrParams = array("args"=>"multiple size='5'");
					$sliderMainSettings->addSelect("post_types", $arrPostTypes, Mage::helper('nwdrevslider')->__("Source Types"),"",$arrParams);

					//post categories
					$arrParams = array("args"=>"multiple size='7'");
					$sliderMainSettings->addSelect("post_category", Mage::helper('nwdrevslider')->getProductCategoriesForClient(), Mage::helper('nwdrevslider')->__("Product Categories"),"",$arrParams);

					//sort by
					$arrSortBy = Mage::helper('nwdrevslider')->getArrSortBy();

					//events integration
					if(UniteEmRev::isEventsExists()){
						$arrEventsFilter = UniteEmRev::getArrFilterTypes();
						$sliderMainSettings->addHr();
						$sliderMainSettings->addSelect("events_filter", $arrEventsFilter, Mage::helper('nwdrevslider')->__("Filter Events By"),UniteEmRev::DEFAULT_FILTER);
						$sliderMainSettings->addHr();

						//add values to sortby array
						$arrEMSortBy = UniteEmRev::getArrSortBy();
						$arrSortBy = $arrSortBy+$arrEMSortBy;
					}

					$sliderMainSettings->addSelect("post_sortby", $arrSortBy, Mage::helper('nwdrevslider')->__("Sort Products By"),RevSlider::DEFAULT_POST_SORTBY);

					//sort direction
					$arrSortDir = array('ASC' => "Ascending", 'DESC' => "Descending");//UniteFunctionsWPRev::getArrSortDirection();

					$sliderMainSettings->addRadio("posts_sort_direction", $arrSortDir, Mage::helper('nwdrevslider')->__("Sort Direction"), RevSlider::DEFAULT_POST_SORTDIR);

					//max posts for slider
					$arrParams = array("class"=>"small","unit"=>"posts");
					$sliderMainSettings->addTextBox("max_slider_posts", "30", Mage::helper('nwdrevslider')->__("Max Products Per Slider"), $arrParams);

					//exerpt limit
					$arrParams = array("class"=>"small","unit"=>"words");
					$sliderMainSettings->addTextBox("excerpt_limit", "55", Mage::helper('nwdrevslider')->__("Limit The Description To"), $arrParams);

					//slider template
					$sliderMainSettings->addhr();

					$slider1 = new RevSlider();
					$arrSlidersTemplates = $slider1->getArrSlidersShort(null,RevSlider::SLIDER_TYPE_TEMPLATE);
					$sliderMainSettings->addSelect("slider_template_id", $arrSlidersTemplates, Mage::helper('nwdrevslider')->__("Template Slider"),"",array());

				$sliderMainSettings->endBulkControl();

				$sliderMainSettings->addControl("source_type", "slider_template_id", UniteSettingsRev::CONTROL_TYPE_HIDE, 'gallery');
				$sliderMainSettings->addControl("source_type", "excerpt_limit", UniteSettingsRev::CONTROL_TYPE_HIDE, 'gallery');

				$sliderMainSettings->addHr();

				//set slider type / texts
				$sliderMainSettings->addRadio("slider_type", array("fixed"=>Mage::helper('nwdrevslider')->__("Fixed"),
					"responsitive"=>Mage::helper('nwdrevslider')->__("Custom"),
					"fullwidth"=>Mage::helper('nwdrevslider')->__("Auto Responsive"),
					"fullscreen"=>Mage::helper('nwdrevslider')->__("Full Screen")
					),Mage::helper('nwdrevslider')->__("Slider Layout"),
					"fullwidth");

				$arrParams = array("class"=>"medium","description"=>Mage::helper('nwdrevslider')->__("Example: #header or .header, .footer, #somecontainer | The height of fullscreen slider will be decreased with the height of these Containers to fit perfect in the screen"));
				$sliderMainSettings->addTextBox("fullscreen_offset_container", "",Mage::helper('nwdrevslider')->__("Offset Containers"), $arrParams);
				$sliderMainSettings->addControl("slider_type", "fullscreen_offset_container", UniteSettingsRev::CONTROL_TYPE_SHOW, "fullscreen");

				$arrParams = array("class"=>"medium","description"=>Mage::helper('nwdrevslider')->__("Defines an Offset to the top. Can be used with px and %. Example: 40px or 10%"));
				$sliderMainSettings->addTextBox("fullscreen_offset_size", "",Mage::helper('nwdrevslider')->__("Offset Size"), $arrParams);
				$sliderMainSettings->addControl("slider_type", "fullscreen_offset_size", UniteSettingsRev::CONTROL_TYPE_SHOW, "fullscreen");

				$arrParams = array("description"=>Mage::helper('nwdrevslider')->__(""));
				$sliderMainSettings->addTextBox("fullscreen_min_height", "",Mage::helper('nwdrevslider')->__("Min. Fullscreen Height"), $arrParams);
				$sliderMainSettings->addControl("slider_type", "fullscreen_min_height", UniteSettingsRev::CONTROL_TYPE_SHOW, "fullscreen");

				$sliderMainSettings->addRadio("full_screen_align_force", array("on"=>Mage::helper('nwdrevslider')->__("On"), "off"=>Mage::helper('nwdrevslider')->__("Off")),Mage::helper('nwdrevslider')->__("FullScreen Align"),"off");
				$sliderMainSettings->addControl("slider_type", "full_screen_align_force", UniteSettingsRev::CONTROL_TYPE_SHOW, "fullscreen");

				$sliderMainSettings->addRadio("auto_height", array("on"=>Mage::helper('nwdrevslider')->__("On"), "off"=>Mage::helper('nwdrevslider')->__("Off")),Mage::helper('nwdrevslider')->__("Unlimited Height"),"off");
				$sliderMainSettings->addControl("slider_type", "auto_height", UniteSettingsRev::CONTROL_TYPE_SHOW, "fullwidth");

				$sliderMainSettings->addRadio("force_full_width", array("on"=>Mage::helper('nwdrevslider')->__("On"), "off"=>Mage::helper('nwdrevslider')->__("Off")),Mage::helper('nwdrevslider')->__("Force Full Width"),"off");

				$arrParams = array("description"=>Mage::helper('nwdrevslider')->__(""));
				$sliderMainSettings->addTextBox("min_height", "0",Mage::helper('nwdrevslider')->__("Min. Height"), $arrParams);

				$paramsSize = array("width"=>960,"height"=>350,"datatype"=>UniteSettingsRev::DATATYPE_NUMBER,"description"=>Mage::helper('nwdrevslider')->__('
- The <span class="prevxmpl">LAYERS GRID</span> is the container of layers within the <span class="prevxmpl">SLIDER</span> <br>
- The "Grid Size" setting does not relate to the actual "Slider Size". <br>
- "Max Height" of the slider equals the "Grid Height"<br>
- "Slider Width" can be greater than the set "Grid Width"'));
				$sliderMainSettings->addCustom("slider_size", "slider_size","",Mage::helper('nwdrevslider')->__("Layers Grid Size"),$paramsSize);

				$paramsResponsitive = array("w1"=>940,"sw1"=>770,"w2"=>780,"sw2"=>500,"w3"=>510,"sw3"=>310,"datatype"=>UniteSettingsRev::DATATYPE_NUMBER);
				$sliderMainSettings->addCustom("responsitive_settings", "responsitive","",Mage::helper('nwdrevslider')->__("Responsive Sizes"),$paramsResponsitive);
				$sliderMainSettings->addControl("slider_type", "responsitive", UniteSettingsRev::CONTROL_TYPE_SHOW, "responsitive");
				
				$sliderMainSettings->addHr();

				self::storeSettings("slider_main",$sliderMainSettings);

				//set "slider_params" settings.
				$sliderParamsSettings = new UniteSettingsAdvancedRev();
				$sliderParamsSettings->loadXMLFile(self::$path_settings."/slider_settings.xml");

				//update transition type setting.
				$settingFirstType = $sliderParamsSettings->getSettingByName("first_transition_type");
				$operations = new RevOperations();
				$arrTransitions = $operations->getArrTransition();
				if(count($arrTransitions) == 0) $arrTransitions = $operations->getArrTransition(true); //get premium transitions
				$settingFirstType["items"] = $arrTransitions;
				$sliderParamsSettings->updateArrSettingByName("first_transition_type", $settingFirstType);

				//store params
				self::storeSettings("slider_params",$sliderParamsSettings);
