<?php

/**
 * Order
 * 
 * Order extends Mobject. <br>
 * OrderException extends Exception. <br>
 * 
 * Felkoder <br>
 * -1 $offerId har ett felaktigt värde <br>
 * -2 Order måste vara betald <br>
 * -3 $typ har ett felaktigt värde <br>
 * -4 Felaktig källa <br>
 * -5 Kvittot är mer än 10 minuter gammalt <br>
 * -6 Kunde inte logga in på FTP  <br>
 * -7 Felaktigt ordertyp från prenshop <br>
 * -8 RefId saknas i anropet <br>
 * -9 Felaktigt ordertyp inget epostkvitto skickat <br> 
 * -10 Kunde inte skapa företagsfil <br>	
 * 
 */
class Order extends Mobject {

  protected $id;
  protected $orderStatus;
  protected $kundnummer;
  protected $refId;
  protected $typ;
  protected $skapadDatum;
  protected $medlem;
  protected $medlem_id;
  protected $foretag;
  protected $foretag_id;
  protected $betald;
  protected $foretagLosen;
  protected $antal;  //antalet av beställd produkt, dvs frakt får här en 1
  protected $filnamn;
  protected $filnamnFaktura;
  protected $ip;
  protected $browser;
  // Orderdata
  protected $items;
  protected $orderId;
  protected $date;
  protected $companyName;
  protected $payerName;
  protected $payerAddress;
  protected $payerCo;
  protected $payerZipCode;
  protected $payerCity;
  protected $payerEmail;
  protected $payerPhone;
  protected $payerMobile;
  protected $payerCountry;
  protected $payment;
  protected $reciverName;
  protected $reciverAddress;
  protected $reciverCo;
  protected $reciverZipCode;
  protected $reciverCity;
  protected $reciverEmail;
  protected $reciverPhone;
  protected $reciverMobile;
  protected $reciverCountry;
  protected $price;       //enhetspriset
  protected $quantity;    //antalet stegräknare
  protected $item;        //textuella namnet för produkten 
  protected $magazineId;
  protected $campaignId;
  protected $offerId;
  protected $isValid; //if the foretag isValid	
  protected $sum;     //totala summan av alla ordrar med samma refid
  protected $sumMoms; //totala summan + moms av alla ordrar med samma refid
  protected $expired;
  protected $kanal; //var har de hört talas om motiomera 
  protected $orderRefCode;  
  protected $compAffCode; //affiliate kod för företagsanmälningar 
  protected $fields = array(
      "id" => "int",
      "orderStatus" => "str",
      "kundnummer" => "int",
      "refId" => "str", // skapas av MotioMera

      "typ" => "str",
      "skapadDatum" => "str",
      "filnamn" => "str",
      "filnamnFaktura" => "str",
      "medlem_id" => "int",
      "foretag_id" => "int",
      "betald" => "int",
      "foretagLosen" => "str",
      "antal" => "int",
      "items" => "int",
      "browser" => "str",
      "ip" => "str",
      "orderId" => "str", // skapas av Allers

      "date" => "str",
      "companyName" => "str",
      "payerName" => "str",
      "payerAddress" => "str",
      "payerCo" => "str",
      "payerZipCode" => "str",
      "payerCity" => "str",
      "payerEmail" => "str",
      "payerPhone" => "str",
      "payerMobile" => "str",
      "payerCountry" => "str",
      "payment" => "str",
      "reciverName" => "str",
      "reciverAddress" => "str",
      "reciverCo" => "str",
      "reciverZipCode" => "str",
      "reciverCity" => "str",
      "reciverEmail" => "str",
      "reciverPhone" => "str",
      "reciverMobile" => "str",
      "reciverCountry" => "str",
      "price" => "int",
      "quantity" => "int",
      "item" => "str",
      "magazineId" => "int",
      "campaignId" => "str",
      "offerId" => "str",
      "sum" => "int",
      "sumMoms" => "int",
      "expired" => "int",
      "kanal" => "str",
      "orderRefCode" => "str",      
      "compAffCode" => "str",
      "isValid" => "int",
  );
  //static $kampanjkoder = array("måbra1" => "free", "mabra1" => "free", "krillo" => "free", "mabra2" => "UE03", "måbra2" => "UE03");  //special campaign codes see actions/newuser.php
  static $kampanjkoder = array("krillo" => "free");  //special campaign codes see actions/newuser.php  
  static $moms = array('name' => 'Moms', 'percent' => 1.25, 'text' => '25%');
  static $discountcodes = array(
      'mm10' => array(
          'percent' => .90,
          'text' => '10% rabatt'
      ),
      'kap15' => array(
          'percent' => .85,
          'text' => '15% rabatt'
      ),
      'mop20' => array(
          'percent' => .80,
          'text' => '20% rabatt'
      ),
  );
  static $campaignCodes = array(
      "FRAKT00" => array(
          "typ" => "frakt",
          "text" => "Frakt",
          "extra" => " kr - fraktfritt, instruktioner mailas till dig.",
          "pris" => 0,
          "dagar" => 0,
          "popupid" => 22,
          "public" => TRUE,
      ),
      "FRAKT01" => array(
          "typ" => "frakt",
          "text" => "Frakt",
          "extra" => " kr ex. moms.",
          "pris" => 50,
          "dagar" => 0,
          "popupid" => 22,
          "public" => TRUE,
      ),
      "FRAKT02" => array(
          "typ" => "frakt",
          "text" => "Frakt",
          "extra" => " kr ex. moms.",
          "pris" => 20,
          "dagar" => 0,
          "popupid" => 22,
          "public" => TRUE,
      ),
      "RE03" => array(
          "typ" => "foretag",
          "text" => "Med stegräknare",
          "extra" => " ex. moms.",
          "pris" => 289,
          "dagar" => 31,
          "popupid" => 22,
          "public" => TRUE,
      ),
      "RE04" => array(
          "typ" => "foretag",
          "text" => "Utan stegräknare",
          "extra" => " ex. moms.",
          "pris" => 169,
          "dagar" => 31,
          "popupid" => 21,
          "public" => TRUE,
      ),
      "PRIV3" => array(
          "typ" => "medlem",
          "text" => "3 månader MotioMera",
          "pris" => 79,
          "dagar" => 91,
          "popupid" => 30,
          "levelid" => 1,
          "public" => TRUE,
      ),
      "PRIV12" => array(
          "typ" => "medlem",
          "text" => "12 månader MotioMera",
          "pris" => 199,
          "dagar" => 91,
          "popupid" => 30,
          "levelid" => 1,
          "public" => TRUE,
      ),
      "STEG01" => array(
          "typ" => "steg",
          "text" => "med stegräknare ",
          "extra" => "Silva ex step, vit",          
          "pris" => 100,
          "dagar" => 0,
          "popupid" => 30,
          "levelid" => 1,
          "public" => TRUE,
      ),

      "RE06" => array(
          "typ" => "medlem",
          "text" => "3 månader MotioMera + stegräknare",
          "pris" => 179,
          "dagar" => 91,
          "popupid" => 14,
          "levelid" => 1,
          "public" => TRUE,
      ),
      "RE09" => array(
          "typ" => "medlem",
          "text" => "12 månader MotioMera",
          "pris" => 199,
          "dagar" => 365,
          "popupid" => 17,
          "levelid" => 1,
          "public" => TRUE,
      ),
      "RE10" => array(
          "typ" => "medlem",
          "text" => "12 månader MotioMera + stegräknare",
          "pris" => 349,
          "dagar" => 365,
          "popupid" => 18,
          "levelid" => 1,
          "public" => TRUE,
      ),
      "UE03" => array(
          "typ" => "medlem",
          "text" => "4 veckor MotioMera <b>med</b> stegräknare",
          "pris" => 139,
          "dagar" => 35,
          "popupid" => 22,
          "levelid" => 1,
          "public" => FALSE,
      ),
  );

  const TABLE = "mm_order";
  const MAGAZINEID = "231";
  const SVAR_URL = "http://www.se";
  const OI_STEGRAKNARE = "RE03";
  const ORDERSTATUS_BEGIN = 10;
  const ORDERSTATUS_PAID = 20;
  const ORDERSTATUS_CUST_NO = 30;
  const ORDERSTATUS_PSW_FILE = 40;
  const ORDERSTATUS_FTP = 50;
  const ORDERSTATUS_RENEWED = 55;
  const MAX_LENGTH_AFFCODE = 20;
  const KAMP_KOD_OGILTIG = -2;

  /**
   * Constructor
   * "isValid" is set default to 1 until correct order flow is implemented  Krillo 090428 
   */
  public function __construct($typ, $objekt, $offerId, $antal = 1, $kanal = "saknas", $compAffCode = "saknas", $isValid = 1, $dummy_object = false) {
    $this->setIsValid($isValid);  //since this must be set for both dummy and real objects			
    if (!$dummy_object) {
      $this->setRefId($this->generateRefId());
      $this->setBetald(false);
      $this->setTyp($typ);
      $this->setAntal($antal);
      $this->setOrderStatus(self::ORDERSTATUS_BEGIN);

      switch (true) {
        case $typ == "medlem":
          $this->setMedlem($objekt);
          $this->setOfferId($offerId);
          $this->setSkapadDatum(date("Y-m-d H:i:s"));
          $this->setBrowser();
          $this->setIpNr();
          $this->setKanal($kanal);
          $this->setCompAffCode($compAffCode);
          $this->commit();
          $this->gorUppslag($offerId);
          break;
        case $typ == "foretag" || $typ == "foretag_tillagg":
          $this->setForetag($objekt);
          $this->setAntal($antal);
          $this->setCampaignId($offerId);
          $this->setOfferId($offerId);
          $this->setSkapadDatum(date("Y-m-d H:i:s"));
          $this->setBrowser();
          $this->setIpNr();
          $this->setKanal($kanal);
          $this->setCompAffCode($compAffCode);
          $this->commit();
          break;
        default:
          ;
          break;
      }
    }
  }

  /**
   * Construct an empty Order object
   * "isValid" is set default to 1 until correct order flow is implemented  Krillo 090428 
   * 
   */
  public static function __getEmptyObject() {
    $class = get_class();
    return new $class(null, null, null, null, null, null, 1, true);
  }

  /**
   * This function constructs an Order object with same params as the constructor but with a submitted refId  
   * Created by krillo 090618
   */
  public static function __constructOrderWithSameRefId($typ, $objekt, $offerId, $antal = 1, $kanal, $compAffCode, $isValid = 1, $dummy_object = false, $refId) {
    //Order::krilloLogToFile("construct Order with same refid");    
    $class = get_class();
    $order = new $class($typ, $objekt, $offerId, $antal, $kanal, $compAffCode, $isValid, $dummy_object);
    $order->setRefId($refId);
    return $order;
  }

  /**
   * just for logging
   */
  public function krilloLogToFile($msg) {
    global $SETTINGS;
    $logfile = LOG_DIR . "/krillo.log";
    try {
      $fh = fopen($logfile, 'a');
      fwrite($fh, date("Y-m-d H:i:s") . "  " . $msg . "\n");
      fclose($fh);
    } catch (Exception $e) {
      echo 'Caught exception: ', $e->getMessage(), "\n";
    }
  }

  // STATIC FUNCTIONS ///////////////////////////////////////

  /**
   * Return true if all input prices match to calculations
   * 
   * @param type $RE03
   * @param type $RE04
   * @param type $exmoms_in
   * @param type $freight_in
   * @param type $total_in
   * @param type $incmoms_in
   * @return boolean 
   */
  public static function priceCheck($RE03, $RE04, $exmoms_in, $FREIGHT, $total_in, $incmoms_in, $discountcode) {
    $RE03_price = (int) self::$campaignCodes['RE03']['pris'];
    $RE04_price = (int) self::$campaignCodes['RE04']['pris'];

    //fix this later
    //$discount = self::$discountcode[$discountcode]['percent'];

    $FREIGHT_price = (int) self::$campaignCodes[$FREIGHT]['pris'];
    $exmoms = ($RE03 * $RE03_price) + ($RE04 * $RE04_price);
    $total = $exmoms + $FREIGHT_price;
    $incmoms = $total * self::$moms['percent'];
    $incmoms = (int) ceil($incmoms);
    //echo'<br> exmoms '. $exmoms_in . $exmoms . '<br>';
    //echo 'total '.  $total . $total_in . '<br>';
    //echo 'incmoms '.  $incmoms . $incmoms_in. '<br>';
    if ($exmoms == $exmoms_in && $total == $total_in && $incmoms == $incmoms_in) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Return true if all input prices match to calculations
   * 
   * @param type $PRIV3
   * @param type $PRIV12
   * @param type $STEG01
   * @param type $FRAKT02
   * @param type $total_in
   * @param type $discountcode
   * @return boolean 
   */
  public static function priceCheckPrivate($PRIV3, $PRIV12, $STEG01, $FRAKT02, $total_in,  $discountcode) {
    $PRIV3_price = (int) self::$campaignCodes['PRIV3']['pris'];
    $PRIV12_price = (int) self::$campaignCodes['PRIV12']['pris'];
    $STEG01_price = (int) self::$campaignCodes['STEG01']['pris'];
    $FRAKT02_price = (int) self::$campaignCodes['FRAKT02']['pris'];
    $total = ($PRIV3_price * $PRIV3) + ($PRIV12_price * $PRIV12) + ($STEG01 * $STEG01_price) + ($FRAKT02_price * $FRAKT02);
    if ($total == $total_in) {
      return true;
    } else {
      return false;
    }
  }

  
  
  
  
  
  /**
   * setup a payson connection, return the PayResponse object.
   * Only step 1 and 2 are done here
   * 
   * To initiate a direct payment the steps are as follows
   *  1. Set up the details for the payment
   *  2. Initiate payment with Payson
   *  3. Verify that it suceeded
   *  4. Forward the user to Payson to complete the payment
   */
  public static function setupPaysonConnection($payerEmail, $payerFname, $payerLname, $sumtopay, $paysonMsg) {
    global $SETTINGS;
    require_once '../php/libs/payson/paysonapi.php';
    $credentials = new PaysonCredentials($SETTINGS["paysonAgentId"], $SETTINGS["paysonMD5"]);
    $api = new PaysonApi($credentials);

    // Step 1: Set up details
    $receiver = new Receiver($SETTINGS["paysonReceiverEmail"], $sumtopay); // The receiver and amount you want to charge the user, here in SEK (the default currency)
    $receivers = array($receiver);

    // Details about the user that is the sender of the money
    $sender = new Sender($payerEmail, $payerFname, $payerLname);
    $payData = new PayData($SETTINGS["paysonReturnUrl"], $SETTINGS["paysonCancelUrl"], $SETTINGS["paysonIpnUrl"], $paysonMsg, $sender, $receivers);
    $payData->setGuaranteeOffered(GuaranteeOffered::NO);

    // Step 2 initiate payment
    $payResponse = $api->pay($payData);

    $data['payResponse'] = $payResponse;
    $data['api'] = $api;
    return $data;
  }

  /**
   * Finds rows in mm_order that has no kundnummer. The kundnummer is generated in AS400 a day after an order is layed.
   * If successful, the order status is set to 30. Kundnummmer is also set in the Foretag object (mm_foretag).
   * A row in mm_foretag is the parent to the order rows mm_order.
   * Changes Krillo 090712
   */
  public static function hamtaNyaKundnummer() {
    Misc::logMotiomera("Start: Order::hamtaNyaKundnummer(), Get customer numbers from AS400, orders are lifted to status 30", 'info');
    global $db;
    $sql = "SELECT id FROM " . self::TABLE . " WHERE typ = 'foretag' AND (kundnummer IS NULL OR kundnummer=1) AND orderId <> '' AND orderStatus = 20";
    $ordrar = Order::listByIds($db->valuesAsArray($sql));
    foreach ($ordrar as $order) {
      $kundnummer = $order->hamtaKundnummer();
      if ($kundnummer !== false && substr_count($kundnummer, "Err") == 0) {
        $order->setKundnummer($kundnummer);
        $order->setOrderStatus(self::ORDERSTATUS_CUST_NO);
        $order->commit();
        $order->getForetag()->setKundnummer($kundnummer);
        $order->getForetag()->commit();
        Misc::logMotiomera("fetched customerId: " . $kundnummer . ", order_id: " . $order->getOrderId() . ", foretag:  " . $order->getForetag()->getCompanyName(), 'ok');
      } else {
        Misc::logMotiomera("customerId is missing " . $kundnummer . ", order_id: " . $order->getOrderId() . ", foretag: " . $order->getForetag()->getCompanyName(), 'error');
      }
    }
    Misc::logMotiomera("End: Order::hamtaNyaKundnummer()", 'info');
  }

  /**
   * Find all foretag_tillagg orders in status 20. 
   * If kundnummer is set for parent order then copy it and lift to status 30. 
   * The parent is found via the foretag_id 
   *
   * Added by krillo 100225
   */
  public static function liftTillaggOrderStatus() {
    Misc::logMotiomera("Start: Order::liftTillaggOrderStatus(), Copy customer numbers from parent orders to foretag_tillagg, status -> 30", 'info');
    global $db;
    $sql = "SELECT id, foretag_id FROM " . self::TABLE . " WHERE typ = 'foretag_tillagg' AND (kundnummer IS NULL OR kundnummer=1) AND orderId <> '' AND orderStatus = 20";
    $ordrar = Order::listByIds($db->valuesAsArray($sql));
    foreach ($ordrar as $order) {
      try {
        $foretag_id = $order->getForetagId();
        $sql = "SELECT kundnummer FROM " . self::TABLE . " WHERE typ = 'foretag' AND orderStatus >= 30 AND foretag_id = $foretag_id";
        $kundnummer = $db->value($sql);
        if (!empty($kundnummer)) {
          $order->setKundnummer($kundnummer);
          $order->setOrderStatus(self::ORDERSTATUS_CUST_NO);
          $order->commit();
          Misc::logMotiomera("Tillaggsorder -> status 30, foretag: " . $order->getForetag()->getCompanyName() . ", order_id: " . $order->getOrderId() . ",  CustomerId: " . $kundnummer, 'ok');
        } else {
          Misc::logMotiomera("Customer nbr missing for: " . $order->getForetag()->getCompanyName() . ", order_id: " . $order->getOrderId(), 'WARNING');
        }
      } catch (Exception $e) {
        Misc::logMotiomera("Problems with order_id: " . $order->getOrderId(), 'error');
      }
    }
    Misc::logMotiomera("End: Order::liftTillaggOrderStatus()", 'info');
  }

  public static function getCampaignCodes($typ) {
    $campaigns = self::$campaignCodes;
    $result = array();
    foreach ($campaigns as $code => $campaign) {
      if ($campaign["typ"] == $typ)
        $result[$code] = $campaign;
    }
    return $result;
  }

  /**
   * This function checks if kampanjkod submitted from a user is valid (blimedlem.php)
   * The $kod should be in lowercase UTF8
   * see /ajax/actions/validate  for example
   * @param <type> $kod
   * @return <type>
   */
  public static function giltigKampanjkod($kod) {
    if (array_key_exists($kod, self::$kampanjkoder)) {
      return true;
    } else {
      return self::KAMP_KOD_OGILTIG;
    }
  }

  /**
   * List all orders. If $showValid is true then an extra "where" parameter isValid = 1 is added.
   * It is only possible to send one or none parameter to "Mobject->lister" some trix are made here (Dr. Krillo)
   * See Mobject->lister for more details about parameters
   * created by krillo 20090804
   */
  public static function listOrder($offset, $limit, $field, $search, $way, $showValid = null) {
    $newField = "id";
    //if (DEBUG) {		
    //	echo "Foretag.php->listForetag()<br>";
    //	echo "showValid = " .$showValid . "<br>";
    //	echo "field = " .$field . "<br>";
    //	echo "search = " .$search . "<br>";
    //}
    //if no params at all, add "isValid = 1" when missing or not false
    if ($field == null && $search == null && ($showValid == "true" || $showValid == null )) {
      $newField = "isValid";
      $search = 1;
      //if(DEBUG){
      // 	echo "first <br>";
      //}
    } else {
      if ($field != null && $search != null && ($showValid == "true" || $showValid == null )) {    //trick in the extra param
        $newField = "isValid=1 and " . $field;
        //if(DEBUG){
        //	echo "second <br>";
        //}
      } else {
        $newField = $field;
        //if(DEBUG){
        //	echo "third - all fields <br>";
        //}
      }
    }
    return parent::lister(get_class(), $newField, $search, "id", $offset, $limit, $search, $way);
  }

  /**
   * List all orders. If $showValid is true then an extra "where" parameter isValid = 1 is added.
   * It is only possible to send one or none parameter to "Mobject->lister" some trix are made here (Dr. Krillo)
   * See Mobject->lister for more details about parameters
   * created by krillo 20090804
   */
  public static function listOrderKrillo($offset, $limit, $field, $search, $way, $showValid = null, $showForetag = false) {
    $newField = "id";

    $foretagString = "";
    if ($showForetag) {
      $foretagString = " typ IN ('foretag', 'foretag_tillagg', 'foretag_again' ) AND ";
    }

    switch (true) {
      //if no params at all, add "isValid = 1"
      case ($field == null && $search == null && ($showValid == "true" || $showValid == null )):
        $newField = "isValid";
        $search = 1;
        break;
      case ($field != null && $search != null && ($showValid == "true" || $showValid == null )):
        $newField = "isValid=1 and " . $field; //trick in the extra param
        break;
      default :
        $newField = $field;
        break;
    }

    $newField = $foretagString . $newField;
    return parent::lister(get_class(), $newField, $search, "id", $offset, $limit, $search, $way);
  }

  /**
   * This function returns an array with id, item, price and quantity from all orders with same refId
   * When somenone puts an order both with and without pedometer there will be two order lines with the same refid
   * Added by Krillo 090708  
   */
  public static function listOrderDataByRefId($refId) {
    global $db;
    $sql = "SELECT id, item, price, antal, payment, orderRefCode, sumMoms, medlem_id  FROM " . self::classToTable(get_class()) . " WHERE refId = '" . $refId . "'";
    $res = $db->query($sql);
    $items = array();
    while ($data = mysql_fetch_assoc($res)) {
      $items[] = $data;
    }
    return $items;
  }

  /**
   * Load by RefId.
   * Optionally submit camaignId.
   * Changed by Krillo 090703  
   */
  public static function loadByRefId($refId, $CampaignId = "") {
    global $db;
    $sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE refId = '" . Security::secure_data($refId) . "'";
    if ($CampaignId != "") {
      $sql .= "and campaignId = '" . Security::secure_data($CampaignId) . "'";
    }
    $order = self::loadById($db->value($sql));
    //if(mktime() - strtotime($order->getDate()) > 60*10)
    //	throw new OrderException("Kvittot är mer än 10 minuter gammalt", -5);
    return $order;
  }

  public static function loadById($id) {
    return parent::loadById($id, get_class());
  }

  /**
   * Loads the latest registered Order object by foretags_id (db name)
   * Added by krillo 20090304
   */
  public static function loadByForetagId($foretagid) {
    global $db;
    $sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE foretag_id = '" . Security::secure_data($foretagid) . "' order by id desc limit 1";
    $order = self::loadById($db->value($sql));
    return $order;
  }

  public static function listByIds($ids) {
    return parent::listByIds(get_class(), $ids);
  }

  public static function loadByOrderId($id) {
    global $db;
    $sql = "SELECT serialize FROM " . self::classToTable(get_class()) . " WHERE orderId = '" . Security::secure_data($id) . "'";
    return unserialize($db->value($sql));
  }

  public static function listAll() {
    
  }

  // PUBLIC FUNCTIONS ///////////////////////////////////////



  public function callPaypal($kampanjkod = "", $antal = "") {
    global $SETTINGS;
  }

  /**
   * echo mondays at least 14 days ahead
   * @param int $count default count 15
   */
  public static function getMondays($count = 15) {
    $output = '';
    $addDays = 14 + (8 - date("w"));  //the fist monday at least 14 days ahead
    $firstMonday = date("Y-m-d", strtotime(date("Y-m-d") . "+$addDays days"));
    $monday = $firstMonday;
    for ($i = 0; $i < $count; ++$i) {
      $datumalternativ = $monday;
      $datumstralt = "Måndagen den " . date('j', strtotime($monday)) . " " . ucfirst(Misc::month(date('n', strtotime($monday))));   //nice format
      $monday = date("Y-m-d", strtotime($monday . "+7 days"));
      $output .= '<option value="' . $datumalternativ . '">' . $datumstralt . '</option>';
    }
    return $output;
  }

  /**
   * This function sends a purchase request to the prenshop.
   * 
   * In some cases it is possible to send multiple order lines at the same time. 
   * Kampanjkod and antal are semicolon separated strings. 
   * The $kampanjkod looks typically like "RE04;RE03"  and $antal like "15;5"
   * Modified by krillo 090618
   */
  public function gorUppslag($kampanjkod = "", $antal = "") {
    global $SETTINGS;

    switch ($this->getTyp()) {
      case ("medlem"):
        $OfferId = $this->getOfferId();
        $CampaignId = $this->getCampaignId();
        $Quantity = $this->getAntal();
        $MagazineId = self::MAGAZINEID;
        $fnamn = urlencode($this->getMedlem()->getFNamn());
        $enamn = urlencode($this->getMedlem()->getENamn());
        $stad = urlencode($this->getMedlem()->getKommun()->getOrt());
        $epost = urlencode($this->getMedlem()->getEpost());
        $postnummer = "";
        $adress = "";
        $foretag = "";
        $customerId = "";
        $itemType = "";
        $CustomerCo = "";
        $CustomerPhone = "";
        $CustomerMobile = "";
        break;
      case ("foretag"):
        $foretag = $this->getForetag();
        $OfferId = $kampanjkod;  //$kampanjkod looks typically like "RE04;RE03" 
        $CampaignId = $kampanjkod;
        $Quantity = $antal;      //$antal looks like "15;5"
        $MagazineId = self::buildMagazineIdString($kampanjkod);    //looks like "231;231"	
        $fnamn = "";
        $enamn = "";
        $stad = "";
        $epost = "";
        $postnummer = "";
        $adress = "";
        $foretagNamn = urlencode($foretag->getNamn());
        $customerId = "";
        $purchaseType = "com";   //prenshop wants to know if it is an company order - both faktura and leverans adderss
        $CustomerCo = "";
        $CustomerPhone = "";
        $CustomerMobile = "";
        $CustomerCo = "";
        $CustomerPhone = "";
        $CustomerMobile = "";
        $ReceiverName = "";
        $ReceiverStrAdr = "";
        $ReceiverCo = "";
        $ReceiverCity = "";
        $ReceiverZip = "";
        $ReceiverEmail = "";
        $ReceiverPhone = "";
        $ReceiverMobile = "";
        $ReceiverCompany = "";
        break;
      case ("foretag_tillagg"):
        $foretag = $this->getForetag();
        $OfferId = $kampanjkod;  //$kampanjkod looks typically like "RE04;RE03" 
        $CampaignId = $kampanjkod;
        $Quantity = $antal;      //$antal looks like "15;5"
        $MagazineId = self::buildMagazineIdString($kampanjkod);   //looks like "231;231"   		
        $fnamn = urlencode($foretag->getPayerName());
        $enamn = "";
        $stad = urlencode($foretag->getPayerCity());
        $epost = urlencode($foretag->getPayerEmail());
        $postnummer = urlencode($foretag->getPayerZipCode());
        $adress = urlencode($foretag->getPayerAddress());
        $foretagNamn = urlencode($foretag->getNamn());
        $customerId = urlencode($foretag->getKundnummer());
        $purchaseType = "com";   //prenshop wants to know if it is an company order - both faktura and leverans adderss
        $CustomerCo = urlencode($foretag->getPayerCo());
        $CustomerPhone = urlencode($foretag->getPayerPhone());
        $CustomerMobile = urlencode($foretag->getPayerMobile());
        $ReceiverName = urlencode($foretag->getReciverName());
        $ReceiverStrAdr = urlencode($foretag->getReciverAddress());
        $ReceiverCo = urlencode($foretag->getReciverCo());
        $ReceiverCity = urlencode($foretag->getReciverCity());
        $ReceiverZip = urlencode($foretag->getReciverZipCode());
        $ReceiverEmail = urlencode($foretag->getReciverEmail());
        $ReceiverPhone = urlencode($foretag->getReciverPhone());
        $ReceiverMobile = urlencode($foretag->getReciverMobile());
        $ReceiverCompany = urlencode($foretag->getReciverCompanyName());
        break;
      case ("foretag_again"):
        $foretag = $this->getForetag();
        $OfferId = $this->getOfferId();
        $CampaignId = $this->getCampaignId();
        $Quantity = $this->getAntal();
        $MagazineId = self::MAGAZINEID;
        $fnamn = urlencode($foretag->getPayerName());
        $enamn = "";
        $stad = urlencode($foretag->getPayerCity());
        $epost = urlencode($foretag->getPayerEmail());
        $postnummer = urlencode($foretag->getPayerZipCode());
        $adress = urlencode($foretag->getPayerAddress());
        $foretagNamn = urlencode($foretag->getNamn());
        $customerId = urlencode($foretag->getKundnummer());
        $purchaseType = "com";   //prenshop wants to know if it is an company order - both faktura and leverans adderss
        $CustomerCo = urlencode($foretag->getPayerCo());
        $CustomerPhone = urlencode($foretag->getPayerPhone());
        $CustomerMobile = urlencode($foretag->getPayerMobile());
        $ReceiverName = urlencode($foretag->getReciverName());
        $ReceiverStrAdr = urlencode($foretag->getReciverAddress());
        $ReceiverCo = urlencode($foretag->getReciverCo());
        $ReceiverCity = urlencode($foretag->getReciverCity());
        $ReceiverZip = urlencode($foretag->getReciverZipCode());
        $ReceiverEmail = urlencode($foretag->getReciverEmail());
        $ReceiverPhone = urlencode($foretag->getReciverPhone());
        $ReceiverMobile = urlencode($foretag->getReciverMobile());
        $ReceiverCompany = urlencode($foretag->getReciverCompanyName());
        break;
      default :
        $errMess = "Felaktig ordertyp Order::gorUppslag() - " . $this->getTyp();
        throw new OrderException($errMess, -1);
        break;
    }
    $url = $SETTINGS["UPPSLAG_URL"] . "?RefId=" . $this->getRefId();

    if ($this->getTyp() == "medlem") {
      $url.= "&MagazineId=$MagazineId&CampaignId=$CampaignId&OfferId=$OfferId&Quantity=$Quantity";
      $url.= "&CustomerName=$fnamn+$enamn&CustomerStrAdr=$adress&CustomerCity=$stad&CustomerZip=$postnummer&CustomerEmail=$epost&CustomerCompany=";
    } else {//company order				
      $url.= "&MagazineId=$MagazineId&CampaignId=$CampaignId&OfferId=$OfferId&Quantity=$Quantity";
      $url.= "&CustomerName=$fnamn+$enamn&CustomerStrAdr=$adress&CustomerCo=$CustomerCo&CustomerCity=$stad&CustomerZip=$postnummer";
      $url.= "&CustomerEmail=$epost&CustomerCompany=$foretagNamn&IType=$purchaseType&CustomerId=$customerId&CustomerPhone=$CustomerPhone&CustomerMobile=$CustomerMobile";
      $url.= "&ReceiverName=$ReceiverName&ReceiverStrAdr=$ReceiverStrAdr&ReceiverCo=$ReceiverCo&ReceiverCity=$ReceiverCity&ReceiverZip=$ReceiverZip";
      $url.= "&ReceiverEmail=$ReceiverEmail&ReceiverPhone=$ReceiverPhone&ReceiverMobile=$ReceiverMobile&ReceiverCompany=$ReceiverCompany";
    }
    /* if (DEBUG){ //not able to use debug mode on production if this is enabled
      $url.= "&MagazineId=$MagazineId&CampaignId=$CampaignId&OfferId=$OfferId&Quantity=$Quantity";
      $url.= "&CustomerName=Pia+Karlsson&CustomerStrAdr=Häradsgatan%204%20B&CustomerCo=$CustomerCo&CustomerCity=Helsingborg&CustomerZip=256%2059";
      $url.= "&CustomerEmail=faktura@test.se&CustomerCompany=Kapten+AB&IType=com&CustomerId=200345924&CustomerPhone=0105886432&CustomerMobile=0765275118";
      $url.= "&ReceiverName=Mottagarnamn&ReceiverStrAdr=Mottagargatan+6&ReceiverCo=mott+co&ReceiverCity=Motala&ReceiverZip=591+70";
      $url.= "&ReceiverEmail=mottagare@test.se&ReceiverPhone=333333&ReceiverMobile=444444&ReceiverCompany=Kapten+AG";
      } */

    header("Location: " . $url);
  }

  /**
   * If kampanjkod has more than one item then the Magazineid should have the same number of items
   * Since motiomera only sells one typ of item, just multiply it, semicolon separated in this format: 231;231
   * Created by krillo 090622  
   */
  private function buildMagazineIdString($kampanjkod) {
    $nofSemi = substr_count($kampanjkod, ";");
    $magazineId = self::MAGAZINEID;
    if ($nofSemi >= 1) {
      for ($i = 1; $i <= $nofSemi; $i++) {
        $magazineId .= ";" . self::MAGAZINEID;
      }
    }
    return $magazineId;
  }

  /**
   * Gets the kundnummer from prenshopen
   */
  public function hamtaKundnummer() {
    global $SETTINGS;
    $url = $SETTINGS["KUNDNUMMER_URL"] . $this->getOrderId();

    if ($f = curl_init($url)) {
      curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($f, CURLOPT_HEADER, 0);
      $content = curl_exec($f);
      curl_close($f);
      return $content;
    } else {
      return false;
    }
  }

  /**
   * Writes the "foretagsfil". Status about to be 40 
   * Changes by krillo 090821
   * The temporary pass is submitted and then deleted from db. The temp pass and the real are the same.  
   * / 
    public function skrivFil()
    {
    global $SETTINGS;
    $kundnummer = $this->getKundnummer();
    $foretagsnamn = $this->getForetag()->getNamn();
    $anstallda = $this->getQuantity();
    $stegraknare = ($this->getOfferId() == self::OI_STEGRAKNARE) ? $this->getQuantity() : 0;
    $startdatum = $this->getForetag()->getStartdatum();
    $anamn = $this->getForetag()->getANamn();
    //$losenord = Security::generateCode(8);
    $losenord = $this->getForetag()->getTempLosenord();
    $namn = ($this->getReciverName());
    $adress = ($this->getReciverAddress());
    $co = ($this->getReciverCo());
    $zip = ($this->getReciverZipCode());
    $city = ($this->getReciverCity());
    $country = ($this->getReciverCountry());
    $content = implode(array(
    $kundnummer,
    $foretagsnamn,
    $anstallda,
    $stegraknare,
    $startdatum,
    $anamn,
    $losenord,
    $namn,
    $adress,
    $co,
    $zip,
    $city,
    $country
    ) , ";");
    $content = str_replace(";;", "; ;", $content);
    $content = htmlspecialchars_decode($content);
    $nycklar = $this->getForetag()->listNycklar();
    foreach($nycklar as $nyckel) {
    $content.= "\n" . $nyckel["nyckel"];
    }
    $this->setFilnamnAuto();
    $filnamn = $this->getFilnamn();
    $lokalFil = FORETAGSFIL_LOCAL_PATH . "/" . $filnamn;
    $serverFil = FORETAGSFIL_REMOTE_PATH . "/" . $filnamn;

    if ($fh = fopen($lokalFil, 'w')) {
    fwrite($fh, $content);
    fclose($fh);
    $this->getForetag()->setLosenord($losenord);
    $this->setOrderStatus(self::ORDERSTATUS_PSW_FILE);
    $this->getForetag()->setTempLosenord(NULL);
    $this->getForetag()->commit();
    } else {
    throw new OrderException("Kunde inte skapa företagsfil: " . $lokalFil, -10);
    }
    $this->commit();
    return true;
    }
   */

  /**
   * Sends the email receipt, depending on order type different mails are sent
   * Changed by krillo 090819 
   */
  public function sendEmailReciept() {
    $email = new MMSmarty();
    $email->assign("order", $this);
    $email->assign("medlem", Medlem::loadById($this->getMedlemId()));
    $subject = "Kvitto";

    switch ($this->getTyp()) {
      case ("medlem"):
        $body = $email->fetch('epostkvitto.tpl');
        $epost = $this->getMedlem()->getEpost();
        Misc::sendEmail($epost, null, $subject, $body);
        break;
      default :
        Order::logEmailSend(false, $subject, 'Felaktigt ordertyp inget epostkvitto skickat:  ' . $epost);
        throw new OrderException("Felaktigt ordertyp inget epostkvitto skickat: " . $order, -9);
        break;
    }
  }

  public static function genRefId() {
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $result = "";
    for ($i = 0; $i < 28; $i++) {
      $result.= $letters[mt_rand(0, strlen($letters) - 1)];
    }
    return $result;
  }

  public function generateRefId() {
    $letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $result = "";
    for ($i = 0; $i < 28; $i++) {
      $result.= $letters[mt_rand(0, strlen($letters) - 1)];
    }
    return $result;
  }

  // SETTERS & GETTERS //////////////////////////////////////


  public function getId() {
    return $this->id;
  }

  public function getKundnummer() {
    return $this->kundnummer;
  }

  public function getKanal() {
    return $this->kanal;
  }

  public function getCompAffCode() {
    return $this->compAffCode;
  }

  public function getRefId() {
    return $this->refId;
  }

  public function getBetald() {
    return ($this->betald == "1") ? true : false;
  }

  public function getSkapadDatum() {
    return $this->skapadDatum;
  }

  public function getTyp() {
    return $this->typ;
  }

  public function getMedlem() {

    if (!$this->medlem)
      $this->medlem = Medlem::loadById($this->getMedlemId());
    return $this->medlem;
  }

  public function getMedlemId() {
    return $this->medlem_id;
  }

  public function getForetag() {
    if (!$this->foretag)
      $this->foretag = Foretag::loadById($this->foretag_id);
    return $this->foretag;
  }

  public function getFilnamn() {
    return $this->filnamn;
  }
  public function getFilnamnFaktura() {
    return $this->filnamnFaktura;
  }

  
  
  public function getForetagId() {
    return $this->foretag_id;
  }

  /*
    public function getForetagLosen()
    {
    return $this->foretagLosen;
    }
   */

  public function getAntal() {
    return $this->antal;
  }

  public function getItems() {
    return $this->items;
  }

  public function getOrderId() {
    return $this->orderId;
  }

  public function getDate() {
    return $this->date;
  }

  public function getCompanyName() {
    return htmlspecialchars_decode($this->companyName);
  }

  /**
   * This is often used in the admin user interface
   * Return the string ERROR if the object is unloadable
   *
   * @return string
   */
  public function getPayerEmail() {
    try {
      if ($this->getTyp() == 'medlem') {
        return $this->getMedlem()->getEpost();
      } else { //foretag aso
        return $this->getForetag()->getPayerEmail();
      }
    } catch (Exception $e) {
      return 'ERROR';
    }
  }

  /**
   * This is often used in the admin user interface
   * Return the string ERROR if the object is unloadable
   *
   * @return string
   */
  public function getPayerName() {
    try {
      if ($this->getTyp() == 'medlem') {
        return $this->getMedlem()->getFNamn() . ' ' . $this->getMedlem()->getENamn();
      } else { //foretag aso
        return $this->getForetag()->getPayerName();
      }
    } catch (Exception $e) {
      return 'ERROR';
    }
  }

  public function getOrderRefCode() {
    return $this->orderRefCode;
  }

  /*
    public function getPayerName()
    {
    return $this->payerName;
    }

    public function getPayerAddress()
    {
    return $this->payerAddress;
    }

    public function getPayerCo()
    {
    return $this->payerCo;
    }

    public function getPayerZipCode()
    {
    return $this->payerZipCode;
    }

    public function getPayerCity()
    {
    return $this->payerCity;
    }

    public function getPayerEmail()
    {
    return $this->payerEmail;
    }

    public function getPayerPhone()
    {
    return $this->payerPhone;
    }

    public function getPayerMobile()
    {
    return $this->payerMobile;
    }

    public function getPayerCountry()
    {
    return $this->payerCountry;
    }
   */

  public function getPayment() {
    return $this->payment;
  }

  /*
    public function getReciverName()
    {
    return $this->reciverName;
    }

    public function getReciverAddress()
    {
    return $this->reciverAddress;
    }

    public function getReciverCo()
    {
    return $this->reciverCo;
    }

    public function getReciverZipCode()
    {
    return $this->reciverZipCode;
    }

    public function getReciverCity()
    {
    return $this->reciverCity;
    }

    public function getReciverEmail()
    {
    return $this->reciverEmail;
    }

    public function getReciverPhone()
    {
    return $this->reciverPhone;
    }

    public function getReciverMobile()
    {
    return $this->reciverMobile;
    }

    public function getReciverCountry()
    {
    return $this->reciverCountry;
    }
   */

  public function getPrice() {
    return $this->price;
  }

  public function getQuantity() {
    return $this->quantity;
  }

  public function getItem() {
    return $this->item;
  }

  public function getMagazineId() {
    return $this->magazineId;
  }

  public function getCampaignId() {
    return $this->campaignId;
  }

  public function getOfferId() {
    return $this->offerId;
  }

  public function getIsValid() {
    return $this->isValid;
  }

  public function getSum() {
    return $this->sum;
  }

  public function getSumMoms() {
    return $this->sumMoms;
  }

  public function getExpired() {
    return ($this->expired == "1") ? true : false;
  }

  public function getOrderStatus() {
    return $this->orderStatus;
  }

  public function getIp() {
    return $this->ip;
  }

  /**
   * List all orders for a foretag
   * added by krillo 20090805
   */
  public function listOrderIdsByForetagId($id) {
    global $db;
    $sql = "SELECT id FROM " . self::TABLE . " WHERE typ in ('foretag', 'foretag_tillagg', 'foretag_again' ) AND foretag_id = $id";
    $orderIdsByForetagId = Order::listByIds($db->valuesAsArray($sql));
    return $orderIdsByForetagId;
  }

  /**
   * Returns a twodimensional array with orderid, foretag_id, orderstatus, refid, skapadDatum of typ foretagforetag, foretag_tillagg, foretag_again
   * OrderId is the key
   * Optionally submit foretagid, orderstatus, refid, if 0 then all foretag-orders are returned 
   * 
   * @param int $foretagId
   * @param int $refid
   * @param int $orderStatus
   * @return array
   *
   * added by krillo 20100121
   */
  public static function getOrdersByForetagId($foretagId = 0, $orderStatus = 0, $refid = 0) {
    $otionals = ' ';
    if ($foretagId != 0) {
      $otionals .= ' foretag_id = ' . $foretagId . ' AND ';
    }
    if ($refid != 0) {
      $otionals .= ' refId = ' . $refid . ' AND ';
    }
    if ($orderStatus != 0) {
      $otionals .= ' orderStatus = ' . $orderStatus . ' AND ';
    }
    try {
      global $db;
      $sql = "SELECT id, foretag_id, typ, orderstatus, refid, skapadDatum FROM " . self::TABLE . " WHERE  $otionals  typ in ('foretag', 'foretag_tillagg', 'foretag_again' )";
      $foretagOrders = $db->allValuesAsArray($sql);
//Order::krilloLogToFile("Order->getOrdersByForetagId() \n" . $sql . "\n" . print_r($foretagOrders, true) );      
      return $foretagOrders;
    } catch (Exception $e) {
      return "0";
    }
  }

  /**
   * This function returns all filenames that are in status 40. 
   * The filenmanes are distinct, i.e. the same filenamne can correspond to more than one order-row
   * @return array of filenames
   * krillo 100127
   */
  public static function getFilesToUpload() {
    try {
      global $db;
      $sql = "SELECT distinct(filnamn) FROM " . self::TABLE . " WHERE  orderstatus = 40";
      $filenames = $db->valuesAsArray($sql);
      return $filenames;
    } catch (Exception $e) {
      return "0";
    }
  }

  /**
   * This function updates all orderlines to 50 where status is 40 and filnamn corresponds to the submitted
   * Returns 1 for success 0 for fail. It also echos error messages (caught into cron logfile)
   * @param string $file
   * krillo 100127
   */
  public static function updateOrdersByFilename($file) {
    try {
      global $db;
      $sql = "UPDATE " . self::TABLE . " SET orderStatus = " . self::ORDERSTATUS_FTP . " WHERE  orderstatus = 40 AND filnamn = '$file'";
      $result = $db->query($sql);
      return $result;
    } catch (Exception $e) {
      return "0";
    }
  }

  public function setOrderStatus($orderStatus) {
    $this->orderStatus = $orderStatus;
  }

  public function setOrderRefCode($arg) {
    $this->orderRefCode = $arg;
  }
  
  
  public function setKundnummer($kundnummer) {
    $this->kundnummer = $kundnummer;
  }

  /**
   * Get kundnummer from base order and add it 
   * Krillo 090605
   */
  public function setTillaggKundnummer() {
    //TODO krillo get kundnummer from base order
    $kundnummer = "111111111";
    $this->kundnummer = $kundnummer;
  }

  public function setRefId($refId) {
    $this->refId = $refId;
  }

  public function setBetald($betald) {
    $this->betald = ($betald) ? 1 : 0;
  }

  public function setSkapadDatum($datum) {
    $this->skapadDatum = $datum;
  }

  public function setTyp($typ) {

    if (!in_array($typ, array(
                "medlem",
                "foretag",
                "foretag_again",
                "foretag_tillagg",
            )))
      throw new OrderException('$typ har ett felaktigt värde', -3);
    $this->typ = $typ;
  }

  public function setBrowser() {
    $this->browser = Medlem::getRawCurrentBrowserVersion();
  }

  public function setIpNr($ip = '') {
    if ($ip == '') {
      $ip = Medlem::getCurrentIpNr();  //does not work, krillo 2012-03-10 
    }
    $this->ip = $ip;
  }

  public function setKanal($kanal) {
    $this->kanal = $kanal;
  }

  public function setCompAffCode($compAffCode) {
    if (strlen($compAffCode) > self::MAX_LENGTH_AFFCODE) {
      $compAffCode = substr($compAffCode, 0, self::MAX_LENGTH_AFFCODE - 1);
    }
    $this->compAffCode = $compAffCode;
  }

  public function setMedlem(Medlem $medlem) {
    $this->medlem = $medlem;
    $this->medlem_id = $medlem->getId();
  }

  public function setMedlemId($id) {
    $this->medlem_id = $id;
    $this->medlem = null;
  }

  public function setForetag(Foretag $foretag) {
    $this->foretag = $foretag;
    $this->foretag_id = $foretag->getId();
  }

  public function setForetagId($id) {
    $this->foretag_id = $id;
    $this->foretag = null;
  }

  /*
    public function setForetagLosen($losen)
    {
    $this->foretagLosen = $losen;
    }
   */

  public function setAntal($antal) {
    $this->antal = $antal;
  }

  public function setFilnamn($filname) {
    $this->filnamn = $filname;
  }
  public function setFilnamnFaktura($arg) {
    $this->filnamnFaktura = $arg;
  }

  public function setItems($items) {
    $this->items = $items;
  }

  public function setOrderId($orderId) {
    $this->orderId = $orderId;
  }

  public function setDate($date = 'now') {
    if ($date == 'now') {
      $date = date('Y-m-d H:i:s');
    }
    $this->date = $date;
  }

  public function setCompanyName($name) {
    $this->companyName = $name;
  }

  /* 	
    public function setPayerName($payerName)
    {
    $this->payerName = $payerName;
    }

    public function setPayerAddress($payerAddress)
    {
    $this->payerAddress = $payerAddress;
    }

    public function setPayerCo($payerCo)
    {
    $this->payerCo = $payerCo;
    }

    public function setPayerZipCode($payerZipCode)
    {
    $this->payerZipCode = $payerZipCode;
    }

    public function setPayerCity($payerCity)
    {
    $this->payerCity = $payerCity;
    }

    public function setPayerEmail($payerEmail)
    {
    $this->payerEmail = $payerEmail;
    }

    public function setPayerPhone($payerPhone)
    {
    $this->payerPhone = $payerPhone;
    }

    public function setPayerMobile($payerMobile)
    {
    $this->payerMobile = $payerMobile;
    }

    public function setPayerCountry($payerCountry)
    {
    $this->payerCountry = $payerCountry;
    }
   */

  public function setPayment($payment) {
    $this->payment = $payment;
  }

  /*
    public function setReciverName($reciverName)
    {
    $this->reciverName = $reciverName;
    }

    public function setReciverAddress($reciverAddress)
    {
    $this->reciverAddress = $reciverAddress;
    }

    public function setReciverCo($reciverCo)
    {
    $this->reciverCo = $reciverCo;
    }

    public function setReciverZipCode($reciverZipCode)
    {
    $this->reciverZipCode = $reciverZipCode;
    }

    public function setReciverCity($reciverCity)
    {
    $this->reciverCity = $reciverCity;
    }

    public function setReciverEmail($reciverEmail)
    {
    $this->reciverEmail = $reciverEmail;
    }

    public function setReciverPhone($reciverPhone)
    {
    $this->reciverPhone = $reciverPhone;
    }

    public function setReciverMobile($reciverMobile)
    {
    $this->reciverMobile = $reciverMobile;
    }

    public function setReciverCountry($reciverCountry)
    {
    $this->reciverCountry = $reciverCountry;
    }
   */

  public function setPrice($price) {
    $this->price = $price;
  }

  public function setQuantity($quantity) {
    $this->quantity = $quantity;
  }

  public function setItem($item) {
    $this->item = $item;
  }

  public function setMagazineId($magazineId) {
    $this->magazineId = $magazineId;
  }

  public function setCampaignId($campaignId) {
    $this->setOfferId($campaignId);
  }

  public function setOfferId($offerId) {
    if (!in_array($offerId, array_keys(self::$campaignCodes))) {
      throw new OrderException('$offerId har ett felaktigt värde', -1);
    }
    $this->offerId = $offerId;
    $this->campaignId = $offerId;
  }

  public function setIsValid($isValid) {
    $this->isValid = $isValid;
  }

  public function setSum($sum) {
    $this->sum = $sum;
  }

  public function setSumMoms($arg) {
    $this->sumMoms = $arg;
  }

  public function setExpired($expired) {
    $this->expired = ($expired) ? "1" : "0";
  }

}

?>
