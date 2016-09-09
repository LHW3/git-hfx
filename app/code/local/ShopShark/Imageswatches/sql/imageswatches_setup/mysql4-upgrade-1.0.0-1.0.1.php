<?php

/**
 * ShopShark Image Swatches Extension
 * @version   1.0 09.05.2014
 * @author    ShopShark http://www.shopshark.net <info@shopshark.net>
 * @copyright Copyright (C) 2010 - 2014 ShopShark
 */
$installer = $this;

$installer->startSetup();


$installer->run("
ALTER TABLE `{$this->getTable('imageswatches/swatch')}` ADD `custom_html` varchar(255) NOT NULL AFTER `option_id`;
");

$installer->endSetup();
