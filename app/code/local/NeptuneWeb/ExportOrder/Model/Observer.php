<?php

include_once('dawnlevy_config.inc.php');

# clean for inclusion in the EDI file by replacing characters and optionally trimming to specific length
function cleanForEdi($string, $trimToLength=0) {
    # replace `
    $string = preg_replace('/`/', '', $string);
    # replace the following characters * ~ >
    $string = preg_replace('/[\*~>]/', '-', $string);
    if ($trimToLength > 0 && strlen($string) > $trimToLength) {
        $string = substr($string, 0, $trimToLength);
    }
    return $string;
}

function nwGenerateEDI850($order) {

    define(EDI_SEGMENT_DELIMITER, "~\n");
    #define(EDI_SEGMENT_DELIMITER, "~");

    $outputDirectory = DAWNLEVY_OUTPUTDIRECTORY;
    $backupOutputDirectory = DAWNLEVY_BACKUP_OUTPUTDIRECTORY;
    
    ob_start();
    
    #$order = $observer->getEvent()->getOrder();
    
    $storeId = $order->getStoreId();
    
    $orderItems = $order->getAllItems();
    
    $orderId = $order->getIncrementId();
    
    $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
    
    $streetBA=$order->getBillingAddress()->getStreet();
    $streetSA=$order->getShippingAddress()->getStreet();
    
    $shippingMethod = $order->getShippingMethod();
    # format is carrier_method
    # eg: fedex_FEDEXGROUND
    # eg: usps_Priority Mail Flat Rate Envelope
    $data = explode('_', $shippingMethod);
    $shippingCarrier = $data[0];
    $shippingCarrierMethod = $data[1];
    
    $ServiceLevelCode = "";
    switch ($shippingCarrierMethod) {
        # these are for Fedex
        case "FEDEXGROUND":
        $ServiceLevelCode = "SG";
        break;
        case "FEDEX3DAYFREIGHT":
        $ServiceLevelCode = "3D";
        break;
        case "FEDEX2DAY":
        $ServiceLevelCode = "SE";
        break;
        case "PRIORITYOVERNIGHT":
        $ServiceLevelCode = "P1";
        break;
        case "STANDARDOVERNIGHT":
        $ServiceLevelCode = "ON";
        break;
        case "FIRSTOVERNIGHT":
        $ServiceLevelCode = "PN";
        break;
        case "FEDEX1DAYFREIGHT":
        $ServiceLevelCode = "D1";
        break;
        case "FEDEX2DAYFREIGHT":
        $ServiceLevelCode = "SE";
        break;
        case "GROUNDHOMEDELIVERY":
        $ServiceLevelCode = "R1";
        break;
        case "FEDEXEXPRESSSAVER":
        $ServiceLevelCode = "CX";
        break;
        case "INTERNATIONALPRIORITY":
        $ServiceLevelCode = "I2";
        break;
        case "INTERNATIONALPRIORITY FREIGHT":
        $ServiceLevelCode = "IS";
        break;
        # these are for USPS
        case "First-Class Mail Package":
        $ServiceLevelCode = "SG";
        break;
        case "Express Mail":
        $ServiceLevelCode = "SE";
        break;
        case "Priority Mail":
        $ServiceLevelCode = "ON";
        break;
        # these are for UPS
        /*
        CDU = Standard UPS
        CD3 = 3rd Day UPS
        CDO = Overnight UPS
        */
        case "GND":
        $ServiceLevelCode = "CDU";
        break;
        case "3DS":
        $ServiceLevelCode = "CD3";
        break;
        case "1DA":
        $ServiceLevelCode = "CDO";
        break;
    }
    
    # handle table rate if used
    # it is identified by the word bestway as the shipping method
    # assume this will be fedex ground
    if ($shippingCarrierMethod == "bestway") {
        $shippingCarrierMethod = "FEDEXGROUND";
        $ServiceLevelCode = "SG";
    }
    
    # if no method found then use the cheapest way
    if (!$shippingCarrierMethod) {
        $shippingCarrierMethod = "FEDEXGROUND";
        $ServiceLevelCode = "SG";
    }
    
    error_log(print_r("in observer 4", true) . "\n\n", 3, DAWNLEVY_LOG);

    # when run from within magento these functions return utc timestamps
    #$date = date("ymd");
    #$date2 = date("Ymd");
    #$time = date("Hi");
    
    # get timestamps adjusted for a local timezone as set in magento
    # look at toString() in app/code/core/Zend/Date.php for formatting the date
    $date = Mage::app()
        ->getLocale()
        ->date(strtotime($order->getCreatedAtStoreDate()), null, null, false)
        ->toString('yyMMdd');
        
    $date2 = Mage::app()
        ->getLocale()
        ->date(strtotime($order->getCreatedAtStoreDate()), null, null, false)
        ->toString('yyyyMMdd');
    
    $time = Mage::app()
        ->getLocale()
        ->date(strtotime($order->getCreatedAtStoreDate()), null, null, false)
        ->toString('HHmm');
        
    #$InterchangeSenderID = "ANCHORWEB";
    $InterchangeSenderID = "001379288";
    #$InterchangeSenderIDISA = "ANCHORWEB      "; # must be 15 characters
    $InterchangeSenderIDISA = "001379288      "; # must be 15 characters
    #$InterchangeReceiverID = "169112419AH1";
    $InterchangeReceiverID = "001379288";
    #$InterchangeReceiverIDISA = "169112419AH1   "; # must be 15 characters
    $InterchangeReceiverIDISA = "001379288      "; # must be 15 characters
    #$InterchangeControlNumber = $orderId; # need to be incremented, unique id - will use order id from magento
    $InterchangeControlNumber = $order->getId(); # need to use id from the db because it must be numeric (and magento is creating numbers like 10000123-1)
    # $InterchangeControlNumber muyst be 9 digits, need to pad with zeros
    if (strlen($InterchangeControlNumber) < 9) {
        $InterchangeControlNumber = str_pad($InterchangeControlNumber, 9, '0', STR_PAD_LEFT);
    }
    $PoNumber = $orderId; # order id from magento
    $ProductionOrTest = "P";
    #$ProductionOrTest = "T";
    
    # the entire ISA line must be 106 characters long
    #print "ISA*00*          *00*          *08*$InterchangeSenderIDISA*14*$InterchangeReceiverIDISA*$date*$time*:*00501*$InterchangeControlNumber*0*$ProductionOrTest*>" . EDI_SEGMENT_DELIMITER;
    print "ISA*00*          *00*          *01*$InterchangeSenderIDISA*14*$InterchangeReceiverIDISA*$date*$time*U*00401*$InterchangeControlNumber*0*$ProductionOrTest*>" . EDI_SEGMENT_DELIMITER;
    
    print "GS*PO*$InterchangeSenderID*$InterchangeReceiverID*$date2*$time*$InterchangeControlNumber*X*004010" . EDI_SEGMENT_DELIMITER;
    
    $TransactionSetControlID = $InterchangeControlNumber;
    
    $segmentCount = 1;
    
    print "ST*850*$TransactionSetControlID" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    $orderNumber = $PoNumber;
    $orderDate = date("Ymd", strtotime($order->getCreatedAt()));
    
    print "BEG*00*SA*$orderNumber**$orderDate" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    # bill to
    $billToName = cleanForEdi($order->getBillingAddress()->getFirstname() . " " . $order->getBillingAddress()->getLastname(), 60);
    print "N1*BT*$billToName" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    $billToLocation1 = cleanForEdi($streetBA[0], 55);
    $billToLocation2 = cleanForEdi((count($streetBA)==2)?$streetBA[1]:'', 55);
    print "N3*$billToLocation1*$billToLocation2" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    $billToCity = cleanForEdi($order->getBillingAddress()->getCity(), 30);
    $billToState = cleanForEdi($order->getBillingAddress()->getRegionCode());
    $billToPostalCode = cleanForEdi($order->getBillingAddress()->getPostcode(), 15);
    $billToCountry = cleanForEdi($order->getBillingAddress()->getCountry());
    print "N4*$billToCity*$billToState*$billToPostalCode*$billToCountry" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    error_log(print_r("in observer 5", true) . "\n\n", 3, DAWNLEVY_LOG);

    # ship date
    #print "DTM*010*20100520" . EDI_SEGMENT_DELIMITER;
    #$segmentCount++;
    
    # cancel date
    #print "DTM*001*20100527" . EDI_SEGMENT_DELIMITER;
    #$segmentCount++;
    
    /*
    # shipping method
    if ($shippingCarrier == 'usps') {
        $anchorSCAC = 'USPS';
    } else {
        $anchorSCAC = 'FDEG';
    }
    */
    
    print "TD5*****" . $ServiceLevelCode . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    $shipToName = cleanForEdi($order->getShippingAddress()->getFirstname() . " " . $order->getShippingAddress()->getLastname(), 60);
    $customerId = $customer->getId(); # unique customer id
    
    # get id for shipping address
    #$shippingAddressId = $order->getShippingAddress()->getId();
    
    print "N1*ST*$shipToName" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    $address1 = cleanForEdi($streetSA[0], 55);
    $address2 = cleanForEdi((count($streetSA)==2)?$streetSA[1]:'', 55);
    
    print "N3*$address1*$address2" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    $city = cleanForEdi($order->getShippingAddress()->getCity(), 30);
    $state = cleanForEdi($order->getShippingAddress()->getRegionCode());
    $zip = cleanForEdi($order->getShippingAddress()->getPostcode(), 15);
    $country = cleanForEdi($order->getShippingAddress()->getCountry());
    
    print "N4*$city*$state*$zip*$country" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    /*
# phone must be 10 digits
    $phone = cleanForEdi($order->getShippingAddress()->getTelephone());
$phone = preg_replace('/[^\d]/', '', $phone);
$phone = substr($phone, 0, 10);
    
    print "PER*BD**TE*$phone" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    */

    error_log(print_r("in observer 5", true) . "\n\n", 3, DAWNLEVY_LOG);

    $NumberofDetailLines = 0;
    
    # first loop over order items and create a custom array indexed by item id which is needed for finding parent configurable products
    $newOrderItems = array();
    foreach ($orderItems as $item) {
        $id = $item->getId();
        $qty = intval($item->getQtyOrdered());
        $newOrderItem = array(
            'id' => $id,
            'parentItemId' =>$item->getParentItemId(),
            'qty' => $qty,
            'sku' => cleanForEdi($item->getSku(), 48),
            'unitPrice' => sprintf("%.02f", ($item->getRowTotal() - $item->getDiscountAmount()) / $qty),
            'name' => $item->getName(),
            'productType' => $item->getProductType(),
            );
        
        $newOrderItems[$newOrderItem['id']] = $newOrderItem;
    }
    
    foreach ($newOrderItems as $item) {
        if ($item['productType'] == 'simple' && $item['unitPrice'] == 0 && $item['parentItemId']) {
            # simple product but with a parent, assuming this is part of a configurable product
            $unitPrice = $newOrderItems[$item['parentItemId']]['unitPrice'];
        } else if ($item['productType'] == 'configurable') {
            # no output for configurable products
            continue;
        } else {
            # the rest
            $unitPrice = $item['unitPrice'];
        }
        
        $qty = $item['qty'];
        $sku = $item['sku'];
        
        print "PO1**$qty*EA*$unitPrice*WE*UP*$sku" . EDI_SEGMENT_DELIMITER;
        $segmentCount++;
        
        $name = cleanForEdi($item['name'], 80);
        print "PID*F****$name" . EDI_SEGMENT_DELIMITER;
        $segmentCount++;
        
        $NumberofDetailLines++;
    }
    
    # line item for shipping
    #$totalShipping = sprintf("%.02f", $order->getShippingAmount() - $order->getShippingDiscountAmount());
    
    #print "PO1**1*EA*$totalShipping*LE*VN*SNHDL" . EDI_SEGMENT_DELIMITER;
    #print "PO1**1*EA*$totalShipping*LE*VN*Y19" . EDI_SEGMENT_DELIMITER;
    #$segmentCount++;
    
    #print "AMT*1*$totalShipping" . EDI_SEGMENT_DELIMITER;
    #$segmentCount++;
    
    #$NumberofDetailLines = count($orderItems);
    
    print "CTT*$NumberofDetailLines" . EDI_SEGMENT_DELIMITER;
    $segmentCount++;
    
    #$orderTotal = sprintf("%.02f", $order->getGrandTotal() - $order->getTaxAmount());
    
    #print "AMT*GV*$orderTotal" . EDI_SEGMENT_DELIMITER;
    #$segmentCount++;
    
    print "SE*$segmentCount*$InterchangeControlNumber" . EDI_SEGMENT_DELIMITER;
    
    print "GE*1*$InterchangeControlNumber" . EDI_SEGMENT_DELIMITER;
    
    print "IEA*1*$InterchangeControlNumber" . EDI_SEGMENT_DELIMITER;
    
    error_log(print_r("in observer 6", true) . "\n\n", 3, DAWNLEVY_LOG);

    $string = ob_get_clean();
    
    $outFile = $outputDirectory . "/" . DAWNLEVY_PREFIX . "order-". $orderId . ".txt";

    error_log(print_r("in observer 7", true) . "\n\n", 3, DAWNLEVY_LOG);

    $fp = fopen($outFile, "w");
    fwrite($fp, $string);
    fclose($fp);
    
    $backupFile = $backupOutputDirectory . "/" . DAWNLEVY_PREFIX . "order-". $orderId . ".txt";
    
    $fp = fopen($backupFile, "w");
    fwrite($fp, $string);
    fclose($fp);
    
}


class NeptuneWeb_ExportOrder_Model_Observer {
    
    public function sales_order_payment_place_end($observer) {
        
        error_log(print_r("in observer", true) . "\n\n", 3, DAWNLEVY_LOG);
        
        $payment = $observer->getEvent()->getPayment();
        
        error_log(print_r("in observer 2", true) . "\n\n", 3, DAWNLEVY_LOG);
        
        $order = $payment->getOrder();
        
        error_log(print_r("in observer 3", true) . "\n\n", 3, DAWNLEVY_LOG);
        
        nwGenerateEDI850($order);
        
        error_log(print_r("in observer 8", true) . "\n\n", 3, DAWNLEVY_LOG);
        
    }
    
    public function sales_order_invoice_pay($observer) {
        
        error_log(print_r("in observer", true) . "\n\n", 3, DAWNLEVY_LOG);
        
        $invoice = $observer->getEvent()->getInvoice();
        
        error_log(print_r("in observer 2", true) . "\n\n", 3, DAWNLEVY_LOG);
        
        $order = $invoice->getOrder();
        
        error_log(print_r("in observer 3", true) . "\n\n", 3, DAWNLEVY_LOG);
        
        nwGenerateEDI850($order);
        
        error_log(print_r("in observer 8", true) . "\n\n", 3, DAWNLEVY_LOG);
        
    }
}

?>
