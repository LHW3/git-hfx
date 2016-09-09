<?php

$orderId = intval($_GET['id']);
$orderId = 3;

require_once '/home/halifaxt/hfxperformance.com/html/staging/app/Mage.php';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

include('/home/halifaxt/hfxperformance.com/html/staging/app/code/local/NeptuneWeb/ExportOrder/Model/Observer.php');

$order = Mage::getModel('sales/order')->load($orderId); 

if ($order && $order->getId()) {

    #var_dump($order->getId());exit;
    
    nwGenerateEDI850($order);

}

?>
