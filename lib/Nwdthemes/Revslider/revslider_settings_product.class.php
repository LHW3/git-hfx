<?php

// Overrides original revslider product class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/revslider_settings_product.class.php';

class RevSliderSettingsProduct extends RevSliderSettingsProductOriginal {

	/**
	 *
	 * draw responsitive settings value
	 */
	protected function drawResponsitiveSettings($setting){
		$id = $setting["id"];

		$w1 = UniteFunctionsRev::getVal($setting, "w1");
		$w2 = UniteFunctionsRev::getVal($setting, "w2");
		$w3 = UniteFunctionsRev::getVal($setting, "w3");
		$w4 = UniteFunctionsRev::getVal($setting, "w4");
		$w5 = UniteFunctionsRev::getVal($setting, "w5");
		$w6 = UniteFunctionsRev::getVal($setting, "w6");

		$sw1 = UniteFunctionsRev::getVal($setting, "sw1");
		$sw2 = UniteFunctionsRev::getVal($setting, "sw2");
		$sw3 = UniteFunctionsRev::getVal($setting, "sw3");
		$sw4 = UniteFunctionsRev::getVal($setting, "sw4");
		$sw5 = UniteFunctionsRev::getVal($setting, "sw5");
		$sw6 = UniteFunctionsRev::getVal($setting, "sw6");

		$disabled = (UniteFunctionsRev::getVal($setting, "disabled") == true);

		$strDisabled = "";
		if($disabled == true)
			$strDisabled = "disabled='disabled'";

		?>
		<table>
			<tr>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Screen Width")?>1:
				</td>
				<td>
					<input id="<?php echo $id?>_w1" name="<?php echo $id?>_w1" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w1?>">
				</td>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Slider Width")?>1:
				</td>
				<td>
					<input id="<?php echo $id?>_sw1" name="<?php echo $id?>_sw1" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw1?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Screen Width")?>2:
				</td>
				<td>
					<input id="<?php echo $id?>_w2" name="<?php echo $id?>_w2" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w2?>">
				</td>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Slider Width")?>2:
				</td>
				<td>
					<input id="<?php echo $id?>_sw2" name="<?php echo $id?>_sw2" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw2?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Screen Width")?>3:
				</td>
				<td>
					<input id="<?php echo $id?>_w3" name="<?php echo $id?>_w3" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w3?>">
				</td>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Slider Width")?>3:
				</td>
				<td>
					<input id="<?php echo $id?>_sw3" name="<?php echo $id?>_sw3" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw3?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Screen Width")?>4:
				</td>
				<td>
					<input type="text" id="<?php echo $id?>_w4" name="<?php echo $id?>_w4" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w4?>">
				</td>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Slider Width")?>4:
				</td>
				<td>
					<input type="text" id="<?php echo $id?>_sw4" name="<?php echo $id?>_sw4" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw4?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Screen Width")?>5:
				</td>
				<td>
					<input type="text" id="<?php echo $id?>_w5" name="<?php echo $id?>_w5" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w5?>">
				</td>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Slider Width")?>5:
				</td>
				<td>
					<input type="text" id="<?php echo $id?>_sw5" name="<?php echo $id?>_sw5" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw5?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Screen Width")?>6:
				</td>
				<td>
					<input type="text" id="<?php echo $id?>_w6" name="<?php echo $id?>_w6" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w6?>">
				</td>
				<td>
					<?php echo Mage::helper('nwdrevslider')->__("Slider Width")?>6:
				</td>
				<td>
					<input type="text" id="<?php echo $id?>_sw6" name="<?php echo $id?>_sw6" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw6?>">
				</td>
			</tr>

		</table>
		<?php
	}


}
