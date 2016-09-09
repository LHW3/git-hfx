<?php

	$generalSettings = new UniteSettingsRev();

	$generalSettings->addRadio("includes_globally",
							   array("on"=>Mage::helper('nwdrevslider')->__("On"),"off"=>Mage::helper('nwdrevslider')->__("Off")),
							   Mage::helper('nwdrevslider')->__("Include RevSlider libraries globally"),
							   "on",
							   array("description"=>Mage::helper('nwdrevslider')->__("Add slider css and js includes on all pages. If turned to off they will be added to specified pages only.")));

	$generalSettings->addTextBox("pages_for_includes", "",Mage::helper('nwdrevslider')->__("Pages to include RevSlider libraries"),
								  array("description"=>Mage::helper('nwdrevslider')->__("Specify the page handles that the front end includes will be included in. Example: cms_page_view,catalog_product_view")));

	$generalSettings->addRadio("show_dev_export",
							   array("on"=>Mage::helper('nwdrevslider')->__("On"),"off"=>Mage::helper('nwdrevslider')->__("Off")),
							   Mage::helper('nwdrevslider')->__("Enable Markup Export option"),
							   "off",
							   array("description"=>Mage::helper('nwdrevslider')->__("This will enable the option to export the Slider Markups to copy/paste it directly into websites.")));

	$generalSettings->addRadio("enable_logs",
							   array("on"=>Mage::helper('nwdrevslider')->__("On"),"off"=>Mage::helper('nwdrevslider')->__("Off")),
							   Mage::helper('nwdrevslider')->__("Enable Logs"),
							   "off",
							   array("description"=>Mage::helper('nwdrevslider')->__("Enable console logs for debugging.")));

	//get stored values
	$operations = new RevOperations();
	$arrValues = $operations->getGeneralSettingsValues();
	$generalSettings->setStoredValues($arrValues);

	self::storeSettings("general", $generalSettings);
