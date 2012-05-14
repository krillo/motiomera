<?php

/**
 * Företag
 *
 * Felkoder
 * -1  Inga rättigheter <br>
 * -2  $medlem_id måste vara ett heltal <br>
 * -3  Företagsnamnet är upptaget <br>
 * -4  Användarnamnet är upptaget <br>
 * -5  Felaktigt lösenord <br>
 * -6  Ogiltig nyckel <br>
 * -7  Ogiltig företagsnyckel <br>
 * -8  $datum har ett felaktigt format <br>
 * -9  Ej giltigt <br>
 * -10 Felaktigt användarnamn <br>
 * -11 $datum har ett felaktigt format <br>
 */
class Foretag extends Mobject {

  protected $id; // int
  protected $namn; // string
  protected $kommun_id; // int
  protected $kommun; // object: Kommun
  protected $aNamn; // string
  protected $losenord; // string
  protected $sessionId; // string
  protected $giltig; // string
  protected $epost; // string
  protected $startdatum; // string
  protected $startdatumUnix; // string
  protected $slutdatum; // string
  protected $slutdatumUnix; // string
  protected $antalAnstallda;
  protected $nycklar = array(); // strings
  protected $medlemmar = array(); // objects: Medlem
  protected $lag = array(); // objects: Lag
  protected $creationDate; //foretaget skapades datum
  protected $kanal; //var har de hort talas om motiomera 
  protected $compAffCode; //affiliate kod för företagsanmälningar 
  protected $isValid; //if the foretag isValid
  protected $orderStatus; //orderstatus 
  protected $orderDatum; //orderstatus 
  protected $orderAntal; //antal frpn order tabellen 
  protected $orderSum; //ordersumman 
  protected $orderName; //företags beställares namn 
  protected $orderAddress; //företags adress 
  protected $orderZipCode; //företgas postnr 
  protected $orderCity; //företags ort
  protected $orderIdsByForetagId; //array alla ordrar som är kopplade till detta företag

  /* Krillo 090609 
   * fields moved from Order.php
   */
  protected $kundnummer;    //kundnummer från AS400, hämtas via cron
  protected $companyName;
  protected $payerCompanyName;
  protected $payerName;
  protected $payerFName;
  protected $payerLName;
  protected $payerAddress;
  protected $payerCo;
  protected $payerZipCode;
  protected $payerCity;
  protected $payerEmail;
  protected $payerPhone;
  protected $payerMobile;
  protected $payerCountry;
  protected $reciverCompanyName;
  protected $reciverName;
  protected $reciverAddress;
  protected $reciverCo;
  protected $reciverZipCode;
  protected $reciverCity;
  protected $reciverEmail;
  protected $reciverPhone;
  protected $reciverMobile;
  protected $reciverCountry;
  protected $tmpLosenord;
  protected $updated_date;
  protected $created_date;
  protected $orderId;
  protected $fields = array(
      "namn" => "str",
      "kommun_id" => "int",
      "aNamn" => "str",
      "losenord" => "str",
      "sessionId" => "str",
      "giltig" => "str",
      "startdatum" => "str",
      "epost" => "str",
      "kanal" => "str",
      "compAffCode" => "str",
      "isValid" => "int",
      "kundnummer" => "int",
      "companyName" => "str",
      "payerCompanyName" => "str",
      "payerName" => "str",
      "payerFName" => "str",
      "payerLName" => "str",
      "payerAddress" => "str",
      "payerCo" => "str",
      "payerZipCode" => "str",
      "payerCity" => "str",
      "payerEmail" => "str",
      "payerPhone" => "str",
      "payerMobile" => "str",
      "payerCountry" => "str",
      "reciverCompanyName" => "str",
      "reciverName" => "str",
      "reciverAddress" => "str",
      "reciverCo" => "str",
      "reciverZipCode" => "str",
      "reciverCity" => "str",
      "reciverEmail" => "str",
      "reciverPhone" => "str",
      "reciverMobile" => "str",
      "reciverCountry" => "str",
      "tmpLosenord" => "str",
      "updated_date" => "str",
      "created_date" => "str",
      "orderId" => "str",
  );

  const KEY_TABLE = "mm_foretagsnycklar";
  const TABLE = "mm_foretag";
  const FN_UPPTAGEN = - 1;
  const FN_OGILTIG = - 2;
  const STARTDATUM_INTERVAL_START = "2008-10-06";
  const STARTDATUM_INTERVAL_STOP = "2008-11-06";

  //081002 - changed to 40
  const TAVLINGSPERIOD_MEDLEMS_DAGAR = 40;
  const FORETAGSMEDLEMS_EXTRA_DAYS = 7;  //one week free after competition is finished
  const TAVLINGSPERIOD_DAGAR = 34;    //5 weeks -1 day
  const MAX_LENGTH_AFFCODE = 20;

  /**
   * Create a new Foretag
   * Since the isValid is a not null field it must always be read
   */
  public function __construct($namn, Kommun $kommun, $losenord, $startdatum, $kanal, $compAffCode, $isValid, $dummy_object = false) {
    $this->setIsValid($isValid);
    if (!$dummy_object) {
      $this->setNamn($namn);
      $this->setKommun($kommun);
      $this->setANamn($this->skapaANamn());
      $this->setStartdatum($startdatum);
      $this->setLosenord($losenord);
      $this->setKanal($kanal);
      $this->setCompAffCode($compAffCode);
      $this->commit();
      
      $this->getSlutdatum();  //save slutdatum
      $this->getSlutdatumUnix();  //save slutdatum
    }
  }

  public static function __getEmptyObject() {
    $class = get_class();
    return new $class(null, Kommun::__getEmptyObject(), null, null, null, null, null, 1, true);   //"isValid" and is set to 1 until correct order flow is implemented  Krillo 090428		
  }

  // STATIC FUNCTION ////////////////////////////////////////

  /**
   * Det händer att företag lägger flera ordrar fast vill att alla deltagarna ska tillhöra samma tävling. 
   * Denna metod slår ihop dessa ordrar genom att alla nycklarna för det första föreataget får det andra företagets id.
   * created by Krillo 090908
   */
  public static function mergeOrderNycklar($foretagIdFrom, $foretagIdTo) {
    global $db;
    $sql = "UPDATE " . self::KEY_TABLE . " SET lag_id = null, foretag_id = " . $foretagIdTo . " WHERE foretag_id = " . $foretagIdFrom;
    $db->query($sql);
    $result = "no";
    $affRows = mysql_affected_rows();
    switch ($affRows) {
      case ($affRows < 0):
        $result = "db_error";
        break;
      case ($affRows == 0):
        $result = "no_change";
        break;
      case ($affRows > 0):
        $result = "success";
        break;
    }
    return $result;
  }

  public static function giltigForetagsnyckel($nyckel) {
    global $db;
    $sql = "SELECT * FROM " . self::KEY_TABLE . " WHERE nyckel = '" . Security::secure_data($nyckel) . "'";
    $row = $db->row($sql);

    if (!$row)
      return self::FN_OGILTIG;
    else {

      if ($row["medlem_id"] != "")
        return self::FN_UPPTAGEN;
      else
        return true;
    }
  }

  public static function deleteForetagsnyckelWithNoForetag() {
    global $db;
    $sql = "DELETE FROM " . self::KEY_TABLE . " WHERE foretag_id NOT IN(SELECT id FROM " . self::TABLE . ")";
    $db->query($sql);
  }

  public static function skapaLosen() {
    $letters = "ABCEFGHJKLMNPQRSTUVWXYZ23456789";
    $losen = "";
    for ($i = 0; $i < 7; $i++) {
      $losen.= $letters[mt_rand(0, strlen($letters) - 1)];
    }
    return $losen;
  }

  public static function loadByForetagsnyckel($nyckel) {
    global $db;

    if (!self::giltigForetagsnyckel($nyckel))
      throw new ForetagException("Ogiltig f??agsnyckel", -7);
    $sql = "SELECT foretag_id FROM " . self::KEY_TABLE . " WHERE nyckel = '" . Security::secure_data($nyckel) . "'";
    return self::loadById($db->value($sql));
  }

  public static function loadById($id) {
    return parent::loadById($id, get_class());
  }

  public static function listAll() {
    return parent::lister(get_class(), "isValid", 1);
  }

//	public static function listForetag()
//	{
//		return parent::lister(get_class(), "isValid", 1);
//	} 

  /**
   * List all "foretag". If $showValid is true then an extra "where" parameter isValid = 1 is added.
   * It is only possible to send one or none parameter to "Mobject->lister" some trix are made here (Dr. Krillo)
   * 
   * See Mobject->lister for more details about parameters
   * 
   * Changed by krillo 20090426
   */
  public static function listForetag($offset, $limit, $field, $search, $way, $showValid = null) {

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

  public static function loadByMedlem(Medlem $medlem, $activeOnly = false) {
    global $db;
    $sql = "SELECT foretag_id, nyckel FROM " . self::KEY_TABLE . " WHERE medlem_id = " . $medlem->getId() . " ORDER BY datum DESC LIMIT 1";
    $result = $db->row($sql);

    $id = $result["foretag_id"];

    if ($id > 0) {
      $foretag = self::loadById($id);

      if ($activeOnly) {
        // check to make sure that the contest isn't over (1 day to allow for mondays)
        if ($foretag->aktivTavling(1)) {
          return $foretag;
        } else {
          return null;
        }
      } else {
        return $foretag;
      }
    } else {
      return null;
    }
  }

  /**
   * this function loads foretag by lag
   * and then returns the id as well, when fail to find the foretag then null is returned
   *
   * @param Lag $lagid 
   * @return int the foretag_id 
   * @author Aller Internet, Kristian Erendi
   */
  public static function loadByLag($lagid) {
    global $db;
    $sql = "SELECT foretag_id FROM mm_lag WHERE id = " . $lagid . " LIMIT 1";
    $result = $db->row($sql);
    $id = $result["foretag_id"];
    if ($id > 0) {
      $foretag = self::loadById($id);
      return $foretag;
    } else {
      return null;
    }
  }

  /**
   * krillo 091026 changed the sql to only get the records that have a competition thats ending.
   * This will work if the mail is sent on a friday
   */
  public static function sendRemindAboutSteg() {
    Misc::logMotiomera("Start foretag::sendRemindAboutSteg(),  Commence email sending for ended tavling. Please see email.log file", 'info');
    $emailName = "Tavling slutar - fredag";
    global $db;
    $sql = 'SELECT a.id FROM mm_medlem a, mm_foretagsnycklar b, mm_foretag c
    WHERE a.id = b.medlem_id
    AND b.foretag_id = c.id
    AND a.epostBekraftad = 1
    AND UNIX_TIMESTAMP(c.startDatum) >= ' . (time() - ((self::TAVLINGSPERIOD_DAGAR) * 86400)) .
            ' AND UNIX_TIMESTAMP(c.startDatum) < ' . (time() - ((self::TAVLINGSPERIOD_DAGAR - 4) * 86400));

    $subject = 'Öka takten, sista kvarten!';
    $message = 'Hej kära MotioMera-deltagare,

Helgen är nära och fortfarande finns chansen att snygga till siffrorna i stegtävlingen. Söndag är tävlingens sista dag, men under hela måndagen kan du registrera dina steg. På tisdag presenteras sen slutesultatet. Glöm alltså inte att registrera dina steg senast under måndagen!

Efter företagstävlingens slut finns möjlighet för alla deltagare att fortsätta använda MotioMera som privatperson. Helt gratis. Du kommer att få ett mail med mer information vid tävlingens slut. Det är också möjligt för ditt företag att genast starta en ny tävlingsomgång om ni vill.

Kör så det ryker! MVH

/Tidningen MåBra och alla i MotioMera-teamet

MotioMera - Sveriges roligaste stegtävling

www.motiomera.se

Allers förlag MåBra Kundservice 251 85 Helsingborg 042-444 30 25 kundservice@aller.se';

    $i = 1;
    $users = $db->valuesAsArray($sql);
    @file_put_contents(EMAIL_SEND_LOG_FILE, "\n**********  " . $emailName . " - " . count($users) . " adresser att skicka till *********** \n", FILE_APPEND);
    foreach ($users as $user) {
      $medlem = Medlem::loadById($user);
      if (isset($medlem)) {
        try {
          $logMessage = $i++ . ' | ' . $medlem->getForetag()->getNamn() . ' | id: ' . $medlem->getId() . ' | ' . $medlem->getAnamn();
          Misc::sendEmail($medlem->getEpost(), $SETTINGS["email"], $subject, $message, $logMessage);
        } catch (Exception $e) {
          //
        }
      }
    }
    Misc::logMotiomera("End foretag::sendRemindAboutSteg()", 'info');
  }

  /**
   * This function sends email to all members that hava finished a tavling.
   * This function gets the lastes written post in mm_tavling_save and checks if the stop_datum (for the competition) 
   * is less than 4 days ago, then send email to all with same tavlings_id.
   * 
   * The check is set to 4 days, which means that it is possible to run this script on mon, tue, wed and thursday.
   * It is designed to be run as a cron script every tuesday after saveAndEndForetagsTavling is completed
   * 
   * @author krillo
   */
  public static function foretagsTavlingEndSendEmail() {
    global $db;
    Misc::logMotiomera("Start foretag::foretagsTavlingEndSendEmail(), Commence email sending for ended tavling ", 'info');
    //get last user from mm_tavling_save, if correct date then send emails  
    $sql = 'SELECT tavlings_id, UNIX_TIMESTAMP(stop_datum) AS stop_datum FROM mm_tavling_save ORDER BY id DESC LIMIT 1';
    $lastUser = $db->row($sql);
    $tavlingsId = $lastUser['tavlings_id'];
    $stopDate = date("Ymd", $lastUser['stop_datum']);
    $today = date("Ymd");
    //$daysBetween = $today - $stopDate;  //not correct calculation..

    $between = time() - $lastUser['stop_datum'];
    $secPerDay = 24 * 60 * 60;
    $daysBetween = intval(floor($between / $secPerDay));

    Misc::logMotiomera("Days between today: $today and the last saved competition end (stopdate: $stopDate) is $daysBetween ", 'info');
    //continue with the email sending if last tavling stop date is less than 4 days old ( this code can be run: mon, tue, wed and thursday) 
    if ($daysBetween <= 4 && $daysBetween >= 0) {
      $sql = "SELECT medlem_id AS id FROM mm_tavling_save WHERE tavlings_id = $tavlingsId";
      $users = $db->valuesAsArray($sql);
      Misc::logMotiomera(count($users) . " users to email. See email.log file for further details", 'info');
      self::sendEndEmail($users, $tavlingsId);
    } else {
      Misc::logMotiomera("No tavling ended the last sunday", 'info');
    }
    Misc::logMotiomera("End foretag::foretagsTavlingEndSendEmail()", 'info');
  }

  /**
   * This function sends the email
   * It is designed to be called from called from foretagsTavlingEndSendEmail
   * 
   * @author krillo
   */
  public static function sendEndEmail($users, $tavlingsId) {
    $emailName = "Tavling avslutad - tisdag";
    $subject = 'Så här gick det i MotioMera!';

    $i = 1;
    @file_put_contents(EMAIL_SEND_LOG_FILE, "\n**********  " . $emailName . " - " . count($users) . " adresser att skicka till *********** \n", FILE_APPEND);
    foreach ($users as $user) {
      $medlem = Medlem::loadById($user);
      if (isset($medlem)) {
        if ($medlem->getForetag()) {
          try {
            $message = 'Grattis ' . $medlem->getFNamn() . '!

Du hör nu till en av dem som har klarat av en tävlingsomgång i stegtävlingen MotioMera! Sammanlagt gick du ' . $medlem->getStegTotal($medlem->getForetag()->getStartDatum(), $medlem->getForetag()->getSlutDatum()) . ' steg! Du kan se hela slutresultatet genom att gå in på denna sida: http://www.motiomera.se/pages/tavlingsres.php?id=' . $medlem->getId() . '&tid=' . $tavlingsId . '

Du vet väl att du kan fortsätta vara med i MotioMera som privatperson? Du registrerar dina steg precis som i företagstävlingen och du kan också skapa klubbar och bjuda in vänner. De steg som du gått under  företagstävlingen följer automatiskt med. Ditt kostnadsfria privatmedlemskap startar automatiskt och gäller under 3 månader. Du loggar in precis som vanligt på motiomera.se.

Hoppas att du har tyckt att tjänsten har varit givande och rolig. Maila oss gärna på motiomera@aller.se och säg vad du tyckte. Ris och ros. Vi lottar ut en stegräknare de luxe bland er som tycker till.

Tack för denna gång och hoppas vi ses snart igen på MotioMera! Hälsn /Tidningen MåBra och alla i MotioMera-teamet


MotioMera - Sveriges roligaste stegtävling

www.motiomera.se

Allers förlag MåBra Kundservice 251 85 Helsingborg 042-444 30 25 kundservice@aller.se';

            $logMessage = $i++ . ' | ' . $medlem->getForetag()->getNamn() . ' | id: ' . $medlem->getId() . ' | ' . $medlem->getAnamn() . ' | ' . ' http://www.motiomera.se/pages/tavlingsres.php?id=' . $medlem->getId() . '&tid=' . $tavlingsId;
            Misc::sendEmail($medlem->getEpost(), $SETTINGS["email"], $subject, $message, $logMessage);
          } catch (Exception $e) {
            //@file_put_contents(EMAIL_SEND_LOG_FILE, ' - ' . $e->getMessage() . "\n", FILE_APPEND);
          }
        }
      }
    }
  }

  /**
   * This function ends and saves a competition. 
   * The sql will only find ended competitions on tuesdays, it is supposed to run as a cron.  
   * The competitions and all the calculated data are saved in tables: mm_tavling_save, mm_tavling, mm_lag_save
   * A tavling_id is created for each competition.
   * Actions are logged to /motiomera/log/motiomera.log
   *
   * All members will have some extra days added, see the FORETAGSMEDLEMS_EXTRA_DAYS
   *  
   * @author krillo 
   * 
   * krillo 091026 changed the sql to only get the records that have a competition thats ending.
   * krillo 100420 changed to only save the competition to mm_tavling_save, mm_tavling, mm_lag_save
   * krillo 110511 changed to add extra days after closed competition FORETAGSMEDLEMS_EXTRA_DAYS
   * krillo 110817 changed to take a date parameter to be bale to run from admin. Please use only Tuesdays after finished competition
   */
  public static function saveAndEndForetagsTavling($date = null) {
    Misc::logMotiomera("Start foretag::saveAndEndForetagsTavling() ", 'info');
    global $db;
    if ($date == null) {
      $date = date("Ymd");
    }
    $time = strtotime($date);

    $sql = 'SELECT a.id FROM mm_medlem a, mm_foretagsnycklar b, mm_foretag c
    WHERE a.id = b.medlem_id
    AND b.foretag_id = c.id
    AND a.epostBekraftad = 1
    AND UNIX_TIMESTAMP(c.startDatum) >= ' . ($time - ((self::TAVLINGSPERIOD_DAGAR + 3) * 86400)) .
            ' AND UNIX_TIMESTAMP(c.startDatum) < ' . ($time - ((self::TAVLINGSPERIOD_DAGAR) * 86400));
    //echo $sql;

    $tavling = new Tavling('0000-00-00', '0000-00-00');
    $save = array();
    $i = 1;
    $users = $db->valuesAsArray($sql);
    if (count($users) == 0) {
      Misc::logMotiomera("No tavling ended this last sunday", 'info');
    } else {  //commence saving
      Misc::logMotiomera("End of tavling, saving data for " . count($users) . " members", 'info');
      foreach ($users as $user) {
        $medlem = Medlem::loadById($user);
        if (isset($medlem)) {
          $startDatum = $medlem->getForetag()->getStartDatum();
          $slutDatum = $medlem->getForetag()->getSlutdatum();
          if ($tavling->getStartDatum() == '0000-00-00') {
            $tavling->setStartDatum($startDatum);  // give the Tavling object a correct startdate as soon as we've got one (we only do this once)
            $tavling->setSlutDatum($slutDatum);
            $tavling->commit();
          }
          if ($medlem->getForetag()) {
            if ($medlem->getLag()) {   //members that are not in a lag, want also to be in the toplists  set lagId to -1  
              $lagId = $medlem->getLag()->getId();
            } else {
              $lagId = 0;
            }
            $steg = $medlem->getStegTotal($startDatum, $slutDatum);
            if ($steg > 0) {  //only save data for members who have more than 0 steg
              try {
                $save[] = array(
                    'medlem_id' => $medlem->getId(),
                    'foretag_id' => $medlem->getForetag()->getId(),
                    'lag_id' => $lagId,
                    'foretagsnyckel' => $medlem->getForetagsnyckel(),
                    'tavlings_id' => $tavling->getId(),
                    'steg' => $steg,
                    'start_datum' => $startDatum,
                    'stop_datum' => $slutDatum
                );
                Misc::logMotiomera(" " . $i++ . " tavling_id: " . $tavling->getId() . " | " . $medlem->getForetag()->getNamn() . ' | id: ' . $medlem->getId() . ' | ' . $medlem->getAnamn() . " | steg: $steg" . " | email: " . $medlem->getEpost(), 'ok');
                //add some extra days after finished competition
                $medlem->addPaidUntil(self::FORETAGSMEDLEMS_EXTRA_DAYS);
                $medlem->commit();
              } catch (Exception $e) {
                Misc::logMotiomera(" " . $i++ . " | something went wrong", 'error');
              }
            } else {
              Misc::logMotiomera(" " . $i++ . " | Member is omitted due to 0 steps |" . $medlem->getForetag()->getNamn() . ' | id: ' . $medlem->getId() . ' | ' . $medlem->getAnamn() . " | steg: $steg" . " | email: " . $medlem->getEpost(), 'warning');
            }
          } else {
            Misc::logMotiomera(" " . $i++ . " | Member is omitted due to member not in foretag | id: " . $medlem->getId() . ' | ' . $medlem->getAnamn() . " | email: " . $medlem->getEpost(), 'warning');
          }
        }
      }
      $lag_save = array();
      foreach ($save as $m) {
        //print_r($m);
        $i = 0;
        if (!isset($lag_save[$m['lag_id']]) AND ($m['lag_id'] > 0)) {
          $lag_save[$m['lag_id']] = Lag::loadById($m['lag_id']);
        }
        $sql = "INSERT INTO " . Tavling::RELATION_TABLE . " SET ";
        foreach ($m as $field => $value) {
          $i++;
          if ($i == 8) {
            $sql.= $field . " = '" . $value . "'";
          } else {
            $sql.= $field . " = '" . $value . "', ";
          }
        }
        //echo "<br />$sql";
        $db->query($sql);
      }
      if (count($lag_save) != 0) {
        Tavling::saveLagList($lag_save);
      }
    }
    Misc::logMotiomera("End foretag::saveAndEndForetagsTavling() ", 'info');
  }

  public static function loggaIn($namn, $losenord, $cookie = false) {
    global $db;
    $namn = Security::secure_postdata($namn);
    $losenord = Security::secure_postdata($losenord);

    if ($namn == "" || $losenord == "") {
      return false;
    }
    $sql = "SELECT id
				FROM " . self::classToTable(get_class()) . " 
				WHERE aNamn='$namn'";
    $id = $db->value($sql);

    if ($id == "") {
      throw new ForetagException("Felaktigt anv㭤arnamn", -10);
    }
    $foretag = Foretag::loadById($id);
    $losenordKrypterat = Security::encrypt_password($id, $losenord);

    if ($losenordKrypterat == $foretag->getLosenord()) { // Lyckad inloggning
      $sessionId = Security::generateSessionId();
      $foretag->setSessionId($sessionId);
      $foretag->commit();
      $_SESSION["mm_foretag_aid"] = $id;
      $_SESSION["mm_foretag_sid"] = $sessionId;

      if ($cookie) {
        setcookie("mm_foretag_aid", $id, time() + 60 * 60 * 24 * 30, "/");
        setcookie("mm_foretag_Sid", $sessionId, time() + 60 * 60 * 24 * 30, "/");
      }
      return true;
    } else {
      throw new ForetagException("Felaktigt l??ord", -5);
    }
  }

  public static function getInloggad() {

    if (empty($_SESSION["mm_foretag_aid"]) && empty($_SESSION["mm_foretag_sid"]) && !empty($_COOKIE["mm_foretag_aid"]) && !empty($_COOKIE["mm_foretag_sid"])) { // f??ker h㬴a fr䬠cookie
      $_SESSION["mm_foretag_aid"] = $_COOKIE["mm_foretag_aid"];
      $_SESSION["mm_foretag_sid"] = $_COOKIE["mm_foretag_sid"];
    }

    if (!empty($_SESSION["mm_foretag_aid"])) {
      try {
        $foretag = Foretag::loadById($_SESSION["mm_foretag_aid"]);

        if ($foretag->getSessionId() == $_SESSION["mm_foretag_sid"]) {
          return $foretag;
        } else {
          return false;
        }
      } catch (Exception $e) {
        return false;
      }
    } else {
      return false;
    }
  }

  // PRIVATE FUNCTIONS //////////////////////////////////////

  private function skapaANamn() {
    global $db;
    $namn = array(
        "skalbagge",
        "mygga",
        "fluga",
        "galt",
        "puma",
        "trana",
        "duva",
        "delfin",
        "aborre",
        "lax",
        "lejon",
        "tiger",
        "apa",
        "katt",
        "zebra",
        "igelkott",
        "koala",
        "svan",
        "elefant",
        "ko",
        "sparv",
        "get"
    );
    do {
      $forslag = $namn[mt_rand(0, count($namn) - 1)] . mt_rand(10, 99) . $namn[mt_rand(0, count($namn) - 1)];
      $sql = "SELECT count(*) FROM " . self::classToTable(get_class()) . " WHERE aNamn = '$forslag'";
      $count = $db->value($sql);
    } while ($count != 0);
    return $forslag;
  }

  /**
   * This function returns true for active contests including those that have yet to start
   * optionally submit days to add, e.g if yuo want to show a page even after the contest is over
   *
   * slutdatum is fetched from db and then added 24h since slutdatum is defined as i.e "Sun, 01 Aug 2010 00:00:00 GMT" but the contest stops at "23:59:59"
   * 2h is also added as a precaution for summertime, execution time and safety
   *
   * @param int $offset 
   * @return boolean
   * @author Aller Internet, Kristian Erendi
   */
  public function aktivTavling($offset = 0) {
    $secondsperday = 24 * 60 * 60;
    $slutdatum = $this->getSlutdatumUnix() + $secondsperday + (2 * 60 * 60);  //add 2h as a precaution for summertime, execution time and safety
    $compare = $slutdatum + ($offset * $secondsperday);
    //echo "offset: ". $offset . "<br/>";
    //echo "slutdatum: " . $slutdatum . "<br/>";
    //echo "compare: ". $compare . "<br/>";
    //echo "time: ". time() . "<br/>";
    if ($compare > time()) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Function startNewContestSameAsLast
   * Sets the startdate to next monday
   * Example:   startNewContestSameAsLast  (  )
   */
  public function startNewContestSameAsLast() {
    $stamp = strtotime("next Monday");
    // die($date);
    $date = date('Y-m-d', $stamp);
    $this->setStartdatum($date);
    $this->commit();
  }

  /**
   * Function startNewContestNewTeams
   * Starts a new contest with new teams
   * Example:    bool startNewContestNewTeams  (  )
   */
  public function startNewContestNewTeams() {
    $this->genereraLag();
  }

  /**
   * Function unsetAllLag
   * Unsets all company lag
   * Example:   bool unsetAllLag  ( int \$first, string \$second  )
   */
  public function unsetAllLag() {
    Security::demand(FORETAG, $this);
    $lag = $this->listLag();
    foreach ($lag as $thislag) {
      $thislag->delete();
    }
  }

  // PUBLIC FUNCTIONS ///////////////////////////////////////

  public function getStegSnittByDay($day) {
    global $db;
    $tmstp = (time() - ($day * -86400));
    $datum = date("Y-m-d", $tmstp);

    if ($datum < $this->getStartDatum()) {
      return 0;
      break;
    } else {
      $slut = $datum;
    }
    $sql = "
			SELECT sum(a.steg) as steg 
			FROM " . Steg::TABLE . " a, " . Foretag::KEY_TABLE . " b
			WHERE a.medlem_id = b.medlem_id
			AND b.foretag_id = " . $this->getId() . "
			AND a.datum = '" . $slut . "'
		";

    // echo $sql;
    $steg = $db->value($sql);
    $medlemmar = $this->countMedlemmar();

    if ($steg != 0 && $medlemmar != 0) {
      return round($steg / $medlemmar);
    } else {
      return 0;
    }
  }

  public function getStegTotal($datum = null) {
    global $db, $foretag_stegtotal_cache;


    if (!isset($foretag_stegtotal_cache)) {
      $foretag_stegtotal_cache = array();
    }

    if (!$datum && isset($foretag_stegtotal_cache[$this->getStartdatum() . "-" . $this->getSlutdatum()])) {
      if (isset($foretag_stegtotal_cache[$this->getStartdatum() . "-" . $this->getSlutdatum()][$this->getId()])) {
        return $foretag_stegtotal_cache[$this->getStartdatum() . "-" . $this->getSlutdatum()][$this->getId()];
      } else {
        return 0;
      }
    } else {
      $sql = "
				SELECT b.foretag_id, sum(a.steg) as steg
				FROM " . Foretag::KEY_TABLE . " b, " . Steg::TABLE . " a
				WHERE a.medlem_id = b.medlem_id
				" . ($datum == null ? " AND a.datum >= '" . $this->getStartdatum() . "'
				AND a.datum <= '" . $this->getSlutdatum() . "' GROUP BY b.foretag_id
			" : " AND a.datum = '" . $datum . "' GROUP BY b.foretag_id");

      $res = $db->query($sql);

      if (!$datum) {

        $foretag_stegtotal_cache[$this->getStartdatum() . "-" . $this->getSlutdatum()] = array();

        while ($r = mysql_fetch_array($res)) {
          $foretag_stegtotal_cache[$this->getStartdatum() . "-" . $this->getSlutdatum()][$r["foretag_id"]] = $r["steg"];
        }

        return $foretag_stegtotal_cache[$this->getStartdatum() . "-" . $this->getSlutdatum()][$this->getId()];
      } else {
        while ($r = mysql_fetch_array($res)) {
          if ($r["foretag_id"] == $this->getId()) {
            return $r["steg"];
          }
        }

        unset($res);
      }
    }
  }

  public function getAntalLag() {
    global $db;
    $sql = "SELECT count(*) FROM " . Lag::TABLE . " WHERE foretag_id = " . $this->getId();
    return $db->value($sql);
  }

  public function isGiltig() {

    if (strtotime($this->getGiltig()) < strtotime(date("Y-m-d")))
      return false;
    else
      return true;
  }

  public function isAnstalld(Medlem $medlem) {
    global $db;
    $sql = "SELECT count(*) FROM " . self::KEY_TABLE . " WHERE foretag_id = " . $this->getId() . " AND medlem_id = " . $medlem->getId();
    $value = $db->value($sql);
    return ($value == 0) ? false : true;
  }

  public function genereraLag() {
    global $db;
    $lag = $this->listLag();
    foreach ($lag as $thislag) {
      $thislag->delete();
    }
    $nycklar = $this->listNycklar();
    $antalAnstallda = count($nycklar);
    $medlemmar = array();
    foreach ($nycklar as $nyckel) {
      $medlemmar[] = $nyckel["nyckel"];
    }
    $antalAnstallda = count($medlemmar);

    if ($antalAnstallda < 10) {
      $antalLag = 1;
    } else
    if ($antalAnstallda == 10) { // lite specialfall, det blir tv㟬ag med fem personer i varje vid tio anst㫬da
      $antalLag = 2;
    } else
    if ($antalAnstallda < 591) {
      $antalLag = ceil($antalAnstallda / 10);
    } else {
      $antalLag = 59;
    }
    $anstalldaPerLag = ($antalLag > 0) ? ($antalAnstallda / $antalLag) : 0;
    $lag = array();
    for ($i = 0; $i < $antalLag; $i++) {
      for ($j = ($i * floor($anstalldaPerLag)); $j < ($i * floor($anstalldaPerLag) + floor($anstalldaPerLag)); $j++) {
        $lag[$i][] = $medlemmar[$j];
      }
    }

    if ($antalLag > 0) {
      $rest = $antalAnstallda - (floor($anstalldaPerLag) * $antalLag);
      $j = 0;
      for ($i = (floor($anstalldaPerLag) * $antalLag); $i < $antalAnstallda; $i++) {
        $lag[$j][] = $medlemmar[$i];
        $j++;
      }
      $lagnamnList = LagNamn::listAll();
      $lagkeys = array_rand($lagnamnList, count($lag));

      if (count($lagkeys) == 1) {
        $lagkeys = array(
            $lagkeys
        );
      }
    }
    $i = 0;
    foreach ($lag as $lagtemp) {
      $lagnamn = $lagnamnList[$lagkeys[$i]];
      $namn = $lagnamn->getNamn();
      $bild = $lagnamn->getImgO();
      $lag = $this->skapaLag($lagnamn->getNamn(), $bild);
      $id = $lag->getId();
      $sql = "UPDATE " . self::KEY_TABLE . " SET lag_id = $id WHERE nyckel in (";
      foreach ($lagtemp as $nyckel) {
        $sql.= "'" . $nyckel . "',";
      }
      $sql = substr($sql, 0, -1);
      $sql.= ")";
      $db->nonquery($sql);
      $i++;
    }
  }

  public function slumpaLagnamn() {
    $letters = "abcdefghijklmnopqrstuvwxyz";
    $namn = "";
    for ($i = 0; $i < 10; $i++) {
      $namn.= $letters[mt_rand(0, strlen($letters) - 1)];
    }
    return $namn;
  }

  public function skapaLag($namn, Bild $bild) {
    $lag = new Lag($this, $namn, $bild);
    $this->lag = null;
    return $lag;
  }

  public function arMedI($medlem) {
    global $USER, $db;
    $sql = "select count(*) FROM " . self::KEY_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND foretag_id = " . $this->getId();
    $value = $db->value($sql);

    if ($value > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function gaMedI($nyckel, $medlem = null) {
    global $db, $USER;

    if (!$medlem) {
      Security::demand(USER);
      $medlem = $USER;
    }
    $sql = "SELECT count(*) FROM " . self::KEY_TABLE . " WHERE medlem_id is null AND foretag_id = " . $this->getId() . " AND nyckel = '" . Security::secure_data($nyckel) . "'";
    $value = $db->value($sql);

    if ($value == 0)
      throw new ForetagException("Ogiltig nyckel", -6);
    $sql = "UPDATE " . self::KEY_TABLE . " SET medlem_id = " . $medlem->getId() . ", datum = '" . date("Y-m-d H:i:s") . "' WHERE nyckel = '" . Security::secure_data($nyckel) . "'";
    $db->query($sql);
    $sql = "SELECT lag_id FROM " . self::KEY_TABLE . " WHERE nyckel = '$nyckel'";
    $lid = $db->value($sql);

    if ($lid != "") {
      $lag = Lag::loadById($lid);
      $lag->addMedlem($medlem);
    }

    //$medlem->setPaidUntilByForetag(date("Y-m-d", strtotime($this->getStartdatum()) + (60*60*24*self::TAVLINGSPERIOD_DAGAR)));
    //days until tavlingstart (if used after tavlingsstart they still get self::TAVLINGSPERIOD_DAGAR days

    $extradays = ceil((strtotime($this->getStartdatum()) - time()) / (60 * 60 * 24));

    if ($extradays < 0)
      $extradays = 0;
    $medlem->setPaidUntilByForetag(self::TAVLINGSPERIOD_MEDLEMS_DAGAR + $extradays);
    $medlem->setLevelId(1);
    $medlem->commit();
  }

  public function gaUr($medlem) {

    if ((Security::authorized(FORETAG, $this)) or (Security::authorized(USER, Medlem::loadById($medlem)))) {
      global $db;
      $sql = "UPDATE " . self::KEY_TABLE . " SET lag_id=null, datum=null, medlem_id=null WHERE medlem_id = " . $medlem . " AND foretag_id = " . $this->getId();
      $db->query($sql);

      //remove days payed by foretag (days removed = days until foretagstavling is over)
      //potential issue : if self::TAVLINGSPERIOD_DAGAR is changed during a TAVLING and a user is removed subscriptiontime might be lost

      $slutdatum = date('Y-m-d', strtotime($this->getStartdatum()) + (self::TAVLINGSPERIOD_MEDLEMS_DAGAR * 60 * 60 * 24));
      $daydiff = floor((strtotime($slutdatum) - time()) / (60 * 60 * 24));
      $medlemObj = Medlem::LoadById($medlem);

      if ($daydiff < 0)
        $daydiff = 0;
      $medlemObj->setPaidUntilByForetag($daydiff * -1);
      $medlemObj->commit();
    }
  }

  public function getMembersWithoutLag() {
    $withoutLag = 0;
    $medlemmar = $this->listMedlemmar();
    foreach ($medlemmar as $medlem) {
      $lag = $medlem->getLag();

      if (!isset($lag))
        ++$withoutLag;
      unset($lag);
    }
    return $withoutLag;
  }

  /**
   * Sends the email receipt, depending on order type different mails are sent
   * Splitted and moved here by krillo 100119 
   */
  public function sendEmailReciept($orderTyp, $refId) {
    $email = new MMSmarty();
    $orderItemList = array();
    $orderItemList = Order::listOrderDataByRefId($refId); //can be more than one order row
    $orderList = array();
    $orderList["foretagLosen"] = $this->getTempLosenord();
    $orderList["companyName"] = $this->getNamn();
    $orderList["foretagANamn"] = $this->getANamn();
    $orderList["startDatum"] = $this->getStartdatum();
    $orderList["pro_order"] = false;
    $orderList["payerCompanyName"] = $this->getPayerCompanyName();    
    $orderList["payerName"] = $this->getPayerName();
    $orderList["payerAddress"] = $this->getPayerAddress();
    $orderList["payerCo"] = $this->getPayerCo();
    $orderList["payerZipCode"] = $this->getPayerZipCode();
    $orderList["payerCity"] = $this->getPayerCity();
    $orderList["payerEmail"] = $this->getPayerEmail();
    $orderList["payerPhone"] = $this->getPayerPhone();
    $orderList["payerMobile"] = $this->getPayerMobile();
    $orderList["payerCountry"] = $this->getPayerCountry();
    $orderList["reciveCompanyName"] = $this->getReciverCompanyName();
    $orderList["reciverName"] = $this->getReciverName();
    $orderList["reciverAddress"] = $this->getReciverAddress();
    $orderList["reciverCo"] = $this->getReciverCo();
    $orderList["reciverZipCode"] = $this->getReciverZipCode();
    $orderList["reciverCity"] = $this->getReciverCity();
    $orderList["reciverEmail"] = $this->getReciverEmail();
    $orderList["reciverPhone"] = $this->getReciverPhone();
    $orderList["reciverMobile"] = $this->getReciverMobile();
    $orderList["reciverCountry"] = $this->getReciverCountry();

    $order = Order::loadByForetagId($this->getId());    //get the first order line
    $orderList["refId"] = $order->getRefId();
    $orderList["items"] = $order->getItems();
    $orderList["orderId"] = $order->getOrderId();
    $orderList["date"] = $order->getDate();
    $orderList["price"] = $order->getPrice();
    $orderList["quantity"] = $order->getAntal();
    $orderList["item"] = $order->getItem();
    $orderList["magazineId"] = $order->getMagazineId();
    $orderList["payment"] = $order->getPayment();
    $orderList["sum"] = $order->getSum();
    $orderList["sumMoms"] = $order->getSumMoms();
    $orderList["typ"] = $order->getTyp();
    $orderList["id"] = $order->getId();
    $email->assign("orderList", $orderList);
    $email->assign("orderItemList", $orderItemList);
    switch ($orderTyp) {
      case ("foretag"):
        $subject = "Kvitto - företag";
        $body = $email->fetch('epostkvittoforetag.tpl');
        break;
      case ("foretag_tillagg"):
        $subject = "Kvitto - tilläggsbeställning";
        $body = $email->fetch('epostkvittoforetag_tillagg.tpl');
        break;
      case ("foretag_again"):
        $subject = "Kvitto - fortsatt tävling";
        $body = $email->fetch('epostkvittoforetag_again.tpl');
        break;
      default :
        Order::logEmailSend(false, $subject, 'Felaktigt ordertyp inget epostkvitto skickat:  | ' . $orderList["reciveCompanyName"] . ' | ' . $orderList["reciverEmail"]);
        throw new OrderException("Felaktigt ordertyp inget epostkvitto skickat: " . $order, -9);
        break;
    }
    Misc::sendEmail($orderList["reciverEmail"], null, $subject, $body);
  }

  /**
   * Returns a twodimensional assoc array with foretag_id as key and all order_ids as the second array
   * The search uses all types "foretag", "foretag_tillagg", "foretag_again"
   * Optionally submit foretagid, orderstatus, refid, if 0 then all foretag-orders are returned 
   * 
   * @param int $foretagId
   * @param int $refid
   * @param int $orderStatus
   * @return array
   *
   * added by krillo 20100122
   */
  public static function getForetagIdsByOrder($foretagId = 0, $orderStatus = 0, $refid = 0) {
    $orderData = Order::getOrdersByForetagId($foretagId, $orderStatus, $refid);
    $returnArr = array();
    foreach ($orderData as $key => $value) {
      if (!array_key_exists($value['foretag_id'], $returnArr)) {
        $returnArr[$value['foretag_id']] = array($key);
      } else {
        $returnArr[$value['foretag_id']][] = $key;
      }
    }
    return $returnArr;
  }

  /**
   * Create a faktura file, put it on the ftp area
   */
  public function createFakturaFile($refId) {
    //$fileNamePrefix = 'fak';
    $prefix = '';
    $middlefix = 'FAK';
    $filnamn = $this->setFilnamnAuto($prefix, 'txt', 'faktura', $middlefix);
    $lokalFil = FORETAGSFAKTURA_LOCAL_PATH . "/" . $filnamn;

    if (file_exists($lokalFil)) {
      Misc::logMotiomera("Couldn't create or save order faktura file: " . $lokalFil, 'ERROR');
      throw new OrderException(" ERROR - Couldn't create or save order faktura file: " . $lokalFil, -10);
    } else {
      $orderItems = Order::listOrderDataByRefId($refId);
      $orderRefCode = $orderItems[0]['orderRefCode'];
      $msg = "FAKTURA \n\n";
      $msg .= $this->getPayerCompanyName() . "\n";
      $msg .= $this->getPayerName() . "  (". $this->getPayerEmail() . " " . $this->getPayerPhone() . ")\n\n";      

      $msg .= $this->getPayerCo() . "\n";
      $msg .= $this->getPayerAddress() . "\n";
      $msg .= $this->getPayerZipCode() . "  " . $this->getPayerCity() . "\n";
      $msg .= $this->getPayerCountry() . "\n\n";
      
      $msg .= "Fakturadatum: " . $orderItems[0]['skapadDatum'] . " \n\n";
      
      $msg .= "Artiklar: \n";
      $msg .= "Kostnadsställe/ref/kod: " . $orderRefCode . "\n";
      foreach ($orderItems as $orderItem) {
        $msg .= $orderItem['item'] . "    " . $orderItem['antal'] . "    " . $orderItem['price'] . "\n";
        $orderIdArray[] = $orderItem['id'];
      }
      $msg .= "\nSumma: " .$orderItem['sum'] . " Kr \n";
      $msg .= "Summa: " .$orderItem['sumMoms'] . " Kr ink moms\n\n\n";

      $fd = fopen($lokalFil, "a");
      if ($fd != false) {
        fwrite($fd, $msg);
        fclose($fd);
        Misc::logMotiomera("Created faktura-file for " . $this->getReciverCompanyName() . ",  " . $lokalFil . ", foretagId =  " . $this->getId() . ", orderids = " . implode(' ', $orderIdArray), 'OK');
        //update the orderrows with faktura filename        
        foreach ($orderItems as $orderItem) {
          $order = Order::loadById($orderItem['id']);
          $order->setFilnamnFaktura($filnamn);
          $order->commit();
        }
        return true;
      } else {
        Misc::logMotiomera("Unable to create faktura-file for " . $this->getReciverCompanyName() . ",  " . $lokalFil . ", foretagId =  " . $this->getId() . ", orderids = " . implode(' ', $orderIdArray), 'ERROR');
        return false;
      }
    }
  }


  /**
   * Gets all orders in status 30 for this company and creates a textfile for each company 
   * Lift the order rows to status 40
   * Run from cronscript no inparams and no return, it outputs messages
   * Moved and changed from Order krillo 100125
   */
  public static function skapaFiler() {
    Misc::logMotiomera("Start: Foretag::skapaFiler(),  Create files for orders in status 30, lift to status 40", 'info');
    $foretagIds = Foretag::getForetagIdsByOrder(0, 30, 0);
    foreach ($foretagIds as $foretagId => $orderIdArray) {
      try {
        $foretag = Foretag::loadById($foretagId);
        $foretag->createOrderPDF($orderIdArray);
      } catch (Exception $e) {
        Misc::logMotiomera("Problems loading foretag_id $foretagId", 'error');
      }
    }
    Misc::logMotiomera("End: Foretag::skapaFiler()", 'info');
  }

  /**
   * Creates order PDF file and saves to disk
   * Lifts all the order line the statuses to 40  
   * The temporary pass is written to the file and then deleted from db. The temp pass and the real are the same.
   * If order rows are found without "typ = foretag" i.e "typ = foretag_tillagg" then another tavlingsansvarig page is used  
   * 
   * @param array $orderIdArray
   * @return boolean 
   */
  private function createOrderPDF($orderIdArray) {
    global $SETTINGS;
    global $db;
    $kundnummer = $this->getKundnummer();
    $foretagsnamn = $this->getReciverCompanyName();
    $co = ($this->getReciverCo());
    if($co != ''){
      $foretagsnamn = $foretagsnamn . "\nc/o " . $co;
    }    
    $losenord = $this->getTempLosenord();
    $namn = ($this->getReciverName());
    $adress = ($this->getReciverAddress());
    $zip = ($this->getReciverZipCode());
    $city = ($this->getReciverCity());
    $country = ($this->getReciverCountry());
    $startdatum = $this->getStartdatum();
    $anamn = $this->getANamn();

    $deltagare = 0;
    $stegraknare = 0;
    $typeForetag = false;
    $typeTillagg = false;
    $fileNamePrefix = '';
    $articlesNSum = '';
    $sumMoms = '';
    $ordId = '';
    //iterate all the order rows
    foreach ($orderIdArray as $orderId) {
      $order = Order::loadById($orderId);
      $articlesNSum .= "\n" . $order->getAntal() . ' ' . $order->getItem() . ' ' . $order->getPrice() ." ex moms";
      $sumMoms = $order->getSumMoms();
      $ordId = $orderId;
      switch ($order->getCampaignId()) {
        case 'RE03':
          $deltagare += $order->getAntal();
          $stegraknare += $order->getAntal();
          break;
        case 'RE04':
          $deltagare += $order->getAntal();
          break;
      }
      if ($order->getTyp() == 'foretag' or $order->getTyp() == 'foretag_again') {
        $typeForetag = true;
      }
      if ($order->getTyp() == 'foretag_tillagg') {
        $typeTillagg = true;
      }
    }
    $filnamn = $this->setFilnamnAuto($fileNamePrefix, 'pdf');
    
    $payerCompanyName = $this->getPayerCompanyName();
    $payerCo = $this->getPayerCo();
    if($payerCo != ''){
      $payerCompanyName = $payerCompanyName . "\nc/o " . $payerCo;
    }      
    
    //PDF preface
    $pdf = new PDF();
    $a = array(
        'FULLNAME' => $namn,
        'COMPANY' => $foretagsnamn,        
        'ADDRESS' => $adress,
        'ZIPCODE' => $zip,
        'CITY' => $city,
        'COUNTRY' => $country,
        'EMAIL' => $this->getReciverEmail(),
        'PHONE' => $this->getReciverPhone(),
        'STARTDATE' => $startdatum,
        'CONTESTERS' => $deltagare,
        'COUNT' => $stegraknare,
        'FILENAME' => $filnamn,
        'ORDERID' => $ordId,
        
        'fak-name' => $this->getPayerName(),
        'fak-companyname' => $payerCompanyName,        
        'fak-adress' => $this->getPayerAddress(),
        'fak-zip' => $this->getPayerZipCode(),
        'fak-city' => $this->getPayerCity(),
        'fak-country' => $this->getPayerCountry(),
        'fak-email' => $this->getPayerEmail(),
        'fak-phone' => $this->getPayerPhone(),
        
        'articlesNSum' => $articlesNSum . "\n" . 'Totalsumma: ' . $sumMoms . ' ink moms' ,
    );
    $pdf->PagePreface($a);

    //PDF tavlingsansvarig
    $filter = array(
        '[STARTDATE]' => $startdatum,
        '[USERNAME]' => $anamn,
        '[PASSWORD]' => $losenord,
    );
    $customerInfo = array(
        'COMPANY' => $foretagsnamn,
        'CUSTOMERNO' => $kundnummer,
        'ORDERID'  => $ordId,
        'CONTENDERS' => $deltagare,
        'PEDOMETERS' => $stegraknare,
    );

    //used only for foretag_tillagg 
    $filter_tillagg = array(
        '[CUSTOMER]' => $foretagsnamn,
        '[CUSTOMERNO]' => $kundnummer,
        '[ORDERID]'  => $ordId,        
        '[STEPCOUNTERS]' => $stegraknare,
        '[ADDITIONALCOUNTER]' => $deltagare,
        '[STARTDATE]' => $startdatum,
    );

    if ($typeForetag) {
      $pdf->PageCoach($filter, $customerInfo);
    } elseif ($typeTillagg) {
      $pdf->PageAddition($filter_tillagg);
      $fileNamePrefix = 'tillagg';
    }
    //PDF participant letters
    $sql = "select nyckel from mm_foretagsnycklar where order_id in (" . implode(",", $orderIdArray) . ")";
    $keys = $db->valuesAsArray($sql);
    $i = 1;
    foreach ($keys as $key) {
      $filter = array(
          '[USERCODE]' => $key,
          '[STARTDATE]' => $startdatum,
      );
      $pdf->PageParticipant($filter, $i++);
    }

    //close and write PDF document to disk
    
    $lokalFil = FORETAGSFIL_LOCAL_PATH . "/" . $filnamn;
    $pdf->Output($lokalFil);
    if (!file_exists($lokalFil)) {
      Misc::logMotiomera("Couldn't create or save order PDF file: " . $localFile, 'error');
      throw new ForetagException(" ERROR - Couldn't create or save order PDF file: " . $localFile, -10);
    } else {
      Misc::logMotiomera("Created file for " . $this->getReciverCompanyName() . ",  " . $lokalFil . ", foretagId =  " . $this->getId() . ", orderids = " . implode(' ', $orderIdArray), 'ok');
      //set the passw again, not tillaggsbestallning, it is stored in temp just because otherwise we had not been able to print it in the letter
      if ($typeForetag) {
        $this->setLosenord($losenord);
        $this->setTempLosenord(NULL);
        $this->commit();
      }
      //update order status on all order lines            
      foreach ($orderIdArray as $orderId) {
        $order = Order::loadById($orderId);
        $order->setOrderStatus(Order::ORDERSTATUS_PSW_FILE);
        $order->setFilnamn($filnamn);
        $order->commit();
        Misc::logMotiomera("Updated status to " . Order::ORDERSTATUS_PSW_FILE . ", orderid = " . $orderId, 'ok');
      }
      return true;
    }
  }

  /**
   * Generates keys and puts into teams if parameter: $skiplag is false or omitted
   * Returns array of the creted keys
   * Changes krillo 20100201 
   */
  public function generateNycklar($antal, $skiplag = false, $orderId) {
    $generatedKeys = array();
    $letters = "ACDEFGHJKLMNPQRSTUVWXY345679";
    for ($i = 0; $i < $antal; $i++) {
      $key = "";
      for ($j = 0; $j < 8; $j++) {
        $key.= $letters[mt_rand(0, strlen($letters) - 1)];
      }
      if ($this->nyckelAvalible($key)) {
        $this->addNyckel($key, $orderId);
        $generatedKeys[] = $key;
      } else {
        $i--;
      }
    }
    if (!$skiplag) {
      $this->genereraLag();
    }
    return $generatedKeys;
  }

  /**
   * Checks in the db if key is available
   * @return true or false
   */
  private function nyckelAvalible($key) {
    global $db;
    $sql = "SELECT count(*) FROM " . self::KEY_TABLE . " WHERE nyckel = '" . Security::secure_data($key) . "'";
    $count = $db->value($sql);
    if ($count > 0)
      return false;
    else
      return true;
  }

  /**
   * Writes a keyarray to the db.
   * @param array $keys
   * @param int $order_id
   * krillo 100202
   */
  public function addKeys($keys, $order_id = null) {
    global $db;
    foreach ($keys as $key) {
      $sql = "INSERT INTO " . self::KEY_TABLE . " (foretag_id, nyckel, order_id) values(" . $this->getId() . ", '" . Security::secure_data($key) . "', " . $order_id . " )";
      $db->query($sql);
    }
  }

  /**
   * Writes a key to the db.
   *
   * @param string $key
   * @param int $order_id
   */
  private function addNyckel($key, $order_id = null) {
    global $db;
    $sql = "INSERT INTO " . self::KEY_TABLE . " (foretag_id, nyckel, order_id) values(" . $this->getId() . ", '" . Security::secure_data($key) . "', " . $order_id . " )";
    $db->query($sql);
    $id = $db->getInsertedId();
    $this->nycklar[$id] = array(
        "nyckel" => $key
    );
  }

  /**
   * This function creates a filname with safe letters  
   * The format is yymmdd_id_name.txt like 090616_1910_KaptenAB.txt
   * Test if filename allready exists on disk then create a new name
   * The function takes an optional parameter that is incorporated in the filenem
   * 
   * Change: 2012-03-13 Krillo
   * This is also used to create faktura files - called from Order
   * Added $type as input param
   * 
   * @param string $prefix
   * @param string $fileExt
   * @param string $type  default 'order'
   * @return string the filename
   */
  public function setFilnamnAuto($prefix = '', $fileExt = 'pdf', $type = 'order', $middlefix = '') {
    if (!empty($prefix)) {
      $prefix = $prefix . '_';
    }
    $letters = "a b c d e f g h i j k l m n o p q r s t u v w x y z 0 1 2 3 4 5 6 7 8 9 _";
    $letters = explode(" ", $letters);
    $namn = strtolower($this->getNamn());
    $namn = str_replace("å", "a", $namn);
    $namn = str_replace("ä", "a", $namn);
    $namn = str_replace("ö", "o", $namn);
    $namn = str_replace(" ", "_", $namn);
    $nyttNamn = "";
    for ($i = 0; $i < strlen($namn); $i++) {
      $b = substr($namn, $i, 1);
      if (in_array($b, $letters))
        $nyttNamn.= $b;
    }
    if ($prefix != '') {
      $prefix = $prefix . "_";
    }    
    if ($middlefix != '') {
      $middlefix = $middlefix . "_";
    }

    switch ($type) {
      case 'order':
        $path = FORETAGSFIL_LOCAL_PATH . "/";
        break;
      case 'faktura':
        $path = FORETAGSFAKTURA_LOCAL_PATH . "/";
        break;
      case 'member':
        $path = MEDLEMSFIL_LOCAL_PATH . "/";
        break;
      default:
        $path = FORETAGSFIL_LOCAL_PATH . "/";        
        break;
    }

    $filnamn = date("ymd") . "_" . $this->getId() . "_" . $prefix . $middlefix . $nyttNamn . ".$fileExt";
    $i = 0;
    while (file_exists($path . $filnamn)) {
      $filnamn = date("ymd") . "_" . $this->getId() . "_" . $prefix . $middlefix . $i . "_" . $nyttNamn . ".$fileExt";
      $i++;
    }
    return $filnamn;
  }

  /**
   * Uploads order files in status 40 on the FTP
   * Lift the order rows to status 50 on success
   */
  public static function uploadOrderFilesFTP() {
    Misc::logMotiomera("Start: Foretag::uploadOrderFilesFTP()", 'info');
    $filenames = Order::getFilesToUpload();
    foreach ($filenames as $file) {
      $localFile = FORETAGSFIL_LOCAL_PATH . "/" . $file;
      $serverFile = FORETAGSFIL_REMOTE_PATH . "/" . $file;
      if (Foretag::uploadFilesFTP('order', $localFile, $serverFile, true)) {
        if (Order::updateOrdersByFilename($file) == 1) {
          Misc::logMotiomera("File put successfully on ftp, status 50  " . $localFile, 'ok');
        } else {
          Misc::logMotiomera("File put successfully on ftp, FAIL TO UPDATE orderstatus, still 40", 'error');
        }
      }
    }
    Misc::logMotiomera("End: Foretag::uploadOrderFilesFTP()", 'info');
  }
  /**
   * Uploads order fktura files in status 50 on the FTP
   * Lift the order rows to status 60 on success
   */
  public static function uploadOrderFakturaFilesFTP() {
    Misc::logMotiomera("Start: Foretag::uploadOrderFakturaFilesFTP()", 'info');
    $filenames = Order::getFakturaFilesToUpload();
    foreach ($filenames as $file) {
      $localFile = FORETAGSFAKTURA_LOCAL_PATH . "/" . $file;
      $serverFile = FORETAGSFIL_REMOTE_PATH . "/" . $file;
      if (Foretag::uploadFilesFTP('order', $localFile, $serverFile, true)) {
        if (Order::updateOrdersByFilenameGeneric($file, 'filnamnfaktura', 50, 60) == 1) {
          Misc::logMotiomera("Faktura file put successfully on ftp, status 60  " . $localFile, 'ok');
        } else {
          Misc::logMotiomera("Faktura file put successfully on ftp, FAIL TO UPDATE orderstatus, still 50", 'error');
        }
      }
    }
    Misc::logMotiomera("End: Foretag::uploadOrderFilesFTP()", 'info');
  }

  /**
   * Puts files on the FTP 
   * returns true or false depending on success
   *
   * @param string $type
   * @param string $localFile
   * @param string $serverFile
   * @param boolean $runAsCron - if true then echo messages (for cron logging)
   * @return boolean $sucess
   */
  public static function uploadFilesFTP($type, $localFile, $serverFile, $runAsCron = false) {
    $handle = fopen($localFile, "r");
    $contents = fread($handle, filesize($localFile));
    fclose($handle);

    $conn_id = ftp_connect(FTP_HOST);
    if (!$conn_login = ftp_login($conn_id, FTP_USER, FTP_PASS)) {
      if ($runAsCron) {
        Misc::logMotiomera("$type - Could not log onto the FTP " . FTP_HOST . "  - throwing exception", 'error');
      } else {
        Misc::logMotiomera("Could not log onto the FTP " . FTP_HOST . "  - throwing exception", "error");
      }
      throw new ForetagException("Could not log onto the FTP", -6);
    }
    ftp_pasv($conn_id, true);
    if (ftp_put($conn_id, $serverFile, $localFile, FTP_BINARY)) {
      $sucess = true;
      if ($runAsCron) {
        Misc::logMotiomera("File put successfully on ftp " . $localFile, "ok");
      } else {
        Misc::logMotiomera("File put successfully on ftp " . $localFile, "ok");
      }
    } else {
      $sucess = false;
      if ($runAsCron) {
        Misc::logMotiomera("Failed to upload file to ftp " . $localFile, "error");
      } else {
        Misc::logMotiomera("Failed to upload file to ftp " . $localFile, "error");
      }
    }
    ftp_close($conn_id);
    return $sucess;
  }

  /**
   * Creates a reclamation PDF and puts it on the FTP
   * Returns true if operation succeds
   * @param int $nbr
   * @return boolean   
   */
  public function sendReclamation($nbr) {
    $foretag_id = $this->getId();
    try {
      $fileName = $this->createReclamationPDF($nbr);
      $localFile = FORETAGSFIL_LOCAL_PATH . "/" . $fileName;
      $serverFile = FORETAGSFIL_REMOTE_PATH . "/" . $fileName;
      if (Foretag::uploadFilesFTP('reclamation', $localFile, $serverFile)) {
        Misc::logMotiomera("Reclamation order, $nbr pedomerters,  foretag_id: $foretag_id", 'ok');
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      Misc::logMotiomera("Problem with reclamation order, $nbr pedomerters,  foretag_id: $foretag_id " . $e, 'error');
      return false;
    }
  }

  /**
   * Creates a reclamation PDF and saves it on disk
   * It returns only the filename not the whole path
   * 
   * @param int $nbr number of pedometers
   * @return string $fileName  
   */
  private function createReclamationPDF($nbr) {
    $fileName = $this->setFilnamnAuto('REKL', 'pdf');
    $foretagsnamn = $this->getReciverCompanyName();
    $co = ($this->getReciverCo());
    if($co != ''){
      $foretagsnamn = $foretagsnamn . "\nc/o " . $co;
    }       
    $pdf = new PDF();
    $a = array(
        'FULLNAME' => $this->getPayerName(),
        'COMPANY' => $foretagsnamn,
        'ADDRESS' => $this->getReciverAddress(),
        'ZIPCODE' => $this->getReciverZipCode(),
        'CITY' => $this->getReciverCity(),
        'COUNTRY' => $this->getPayerCountry(),
        'STARTDATE' => $this->getStartdatum(),
        'CONTESTERS' => 0,
        'COUNT' => $nbr,
        'FILENAME' => $fileName,        
    );
    $pdf->PagePreface($a);
    $filter = array(
        '[CUSTOMER]' => $this->getReciverCompanyName(),
        '[CUSTOMERNO]' => $this->getKundnummer(),

        '[STEPCOUNTERS]' => $nbr,
    );
    $pdf->PageDoa($filter);
    $localFile = FORETAGSFIL_LOCAL_PATH . "/" . $fileName;

    $pdf->Output($localFile);
    return $fileName;
  }

  // SETTERS & GETTERS //////////////////////////////////////

  public function getSumReclamations() {
    return Reclamation::sumReclByForetag($this->id);
  }

  public function getStegIndex() {
    if ($this->countMedlemmar() == 0) {
      return 0;
    }

    if ($this->getSlutDatum() >= date("Y-m-d")) {
      $slut = date("Y-m-d");
    } else {
      $slut = $this->getSlutDatum();
    }

    if ($slut == $this->getStartDatum()) {
      $dagar = 1;
    } else {
      $dagar = (Misc::getDagarMellanTvaDatum($this->getStartDatum(), $slut) + 1);
    }
    $return = "";

    $stegtotal = $this->getStegTotal();
    if ($stegtotal != 0 && $dagar != 0) {
      $return = round($stegtotal / $this->countMedlemmar() / $dagar);
    }
    return $return;
  }

  public function getTopplistaLag($limit = 10, $devidebymedlem = false) {
    global $db;

    if ($limit != false) {
      $limit = " LIMIT $limit";
    }
    $sql = "SELECT lag_id, sum(steg) AS steg
				FROM mm_steg a, mm_foretagsnycklar b  
				WHERE b.foretag_id = " . $this->getId() . " " . "AND b.medlem_id = a.medlem_id " . "AND a.datum >= '" . $this->getStartDatum() . "' " . "AND a.datum <= '" . $this->getSlutDatum() . "' " . "GROUP BY lag_id" . $limit;
    $res = $db->query($sql);
    $topplista = array();

    if ($devidebymedlem == true) {
      $lista = array();
      while ($row = mysql_fetch_array($res)) {

        if ($devidebymedlem == true) {

          if (!empty($row['lag_id'])) {
            $medlemmar = Lag::loadById($row['lag_id'])->listMedlemmar();
          }
        } else {
          $medlemmar = "";
        }

        if (!empty($row['lag_id'])) {
          $lista[] = array(
              "steg" => ($row['steg'] / count($medlemmar)),
              "id" => $row["lag_id"],
              "medlemmar" => $medlemmar
          );
          unset($medlemmar);
        }
      }

      if (count($lista) > 0) {
        array_multisort($lista, SORT_DESC);

        if (count($lista) > 10 && $limit == 10) {
          $lista = array_slice($lista, 0, 10);
        }
      }
      foreach ($lista as $lag) {
        $topplista[$lag['id']] = Lag::loadById($lag['id']);
      }
    } else {
      while ($row = mysql_fetch_assoc($res)) {

        if ($row["lag_id"] != null) {
          $topplista[] = Lag::loadById($row["lag_id"]);
        }
      }
    }
    return $topplista;
  }

  public static function getTopplistaForetag($limit = null) {
    global $db;
    $l = ""; //limiter


    if ($limit != null) {
      $l = " LIMIT $limit";
    }
    $sql = "SELECT sum(steg) AS steg, a.id FROM mm_foretag a, mm_foretagsnycklar b, mm_steg c 
			WHERE c.medlem_id = b.medlem_id
			AND a.id = b.foretag_id 
			GROUP BY a.id ORDER BY steg DESC" . $l;
    $foretagList = $db->valuesAsArray($sql);

    //$foretagList = Foretag::listAll();
    $topplista = array();
    foreach ($foretagList as $row) {

      if ($row['id'] != 0)
        $topplista[] = Foretag::loadById($row['id']);
    }

    //ksort($topplista);
    $topplista = array_reverse($topplista);

    /* $topplista2 = array();
      foreach($topplista as $pos){
      foreach($pos as $ftag)
      $topplista2[] = $ftag;
      } */
    return $topplista;
  }

  public function getId() {
    return $this->id;
  }

  public function getNamn() {
    return $this->namn;
  }

  public function getEpost() {
    return $this->epost;
  }

  public function getKanal() {
    return $this->kanal;
  }

  public function getCompAffCode() {
    return $this->compAffCode;
  }

  public function getIsValid() {
    return $this->isValid;
  }

  public function getKommunId() {
    return $this->kommun_id;
  }

  public function getKommun() {
    if (!$this->kommun)
      $this->kommun = Kommun::loadById($this->kommun_id);
    return $this->kommun;
  }

  public function getANamn() {
    return $this->aNamn;
  }

  public function getLosenord() {
    return $this->losenord;
  }

  /**
   * temporarily store the password two way crypto
   * created by krillo 090709
   */
  public function getTempLosenord() {
    return base64_decode($this->tmpLosenord);
  }

  public function getSessionId() {
    return $this->sessionId;
  }

  public function getGiltig() {
    return $this->giltig;
  }

  public function getStartdatum() {
    return $this->startdatum;
  }

  /**
   * returns the slutdatum in seconds (unix timestamp),  which is calculated from the startdate
   *
   * @return int
   * @author Aller Internet, Kristian Erendi
   */
  public function getSlutdatumUnix() {
    $this->slutdatumUnix = strtotime($this->startdatum) + (60 * 60 * 24 * (self::TAVLINGSPERIOD_DAGAR));
    return $this->slutdatumUnix;
  }

  /**
   * returns the slutdatum as a date (2010-08-01),  which is calculated from the startdate
   *
   * @return string 
   * @author Aller Internet, Kristian Erendi
   */
  public function getSlutdatum() {
    $this->slutdatum = date("Y-m-d", $this->getSlutdatumUnix());
    return $this->slutdatum;
  }

  
  /**
   * return true or false if company has an ongoing competition
   * @author Kristian Erendi, Reptilo 2012-05-05
   */
  public function isActiveCompetition() {
    $today = date("Y-m-d");
    if ($this->getStartdatum() <= $today && $today <= $this->getSlutdatum()) {
      return true;
    } else {
      return false;
    }
  }  

  /**
   * return different CSS-classes if company has an ongoing competition
   * This is to aid to Smarty tempate
   * 
   * @author Kristian Erendi, Reptilo 2012-05-05
   */  
  public function isActiveCompetitionCSS() {
    if ($this->isActiveCompetition()) {
      return "mmGreen";
    } else {
      return "mmLightGrey";
    }
  }  
  
  
  /**
   * Function getCurrentTavlingId
   * returns the competition id that this company is currently active in
   * Example:  getCurrentTavlingId  (   )
   */
  public function getCurrentTavlingId() {
    return $this->currenttavlingsid;
  }

  /**
   * Loads an order object and get its orderstatus.
   * If an object is unloadable the function returns a 0
   * 
   * added by krillo 20090304
   */
  public function getOrderStatus() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderStatus = $order->getOrderStatus();
      return $this->orderStatus;
    } catch (Exception $e) {
      return "0";
    }
  }

  /**
   * Loads an order object and get the order date.
   * If an object is unloadable the function returns a 0
   * 
   * added by krillo 20090304
   */
  public function getOrderDatum() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderDatum = $order->getSkapadDatum();
      return $this->orderDatum;
    } catch (Exception $e) {
      return "";
    }
  }

  /**
   * Loads an order object and get order amount.
   * If an object is unloadable the function returns nothing
   * 
   * added by krillo 20090304
   */
  public function getOrderAntal() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderAntal = $order->getAntal();
      return $this->orderAntal;
    } catch (Exception $e) {
      return "";
    }
  }

  /**
   * Loads an order object and get order sum.
   * If an object is unloadable the function returns nothing
   * 
   * added by krillo 20090304
   */
  public function getOrderSum() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderSum = $order->getSum();
      return $this->orderSum;
    } catch (Exception $e) {
      return "";
    }
  }

  /**
   * Loads an order object and get order payer name.
   * If an object is unloadable the function returns nothing
   * 
   * added by krillo 20090304
   */
  public function getOrderName() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderName = $order->getPayerName();
      return $this->orderName;
    } catch (Exception $e) {
      return "";
    }
  }

  /**
   * Loads an order object and get order payer address.
   * If an object is unloadable the function returns nothing
   * 
   * added by krillo 20090304
   */
  public function getOrderAddress() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderAddress = $order->getPayerAddress();
      return $this->orderAddress;
    } catch (Exception $e) {
      return "";
    }
  }

  /**
   * Loads an order object and get order payer zipcode.
   * If an object is unloadable the function returns nothing
   * 
   * added by krillo 20090304
   */
  public function getOrderZipCode() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderZipCode = $order->getPayerZipCode();
      return $this->orderZipCode;
    } catch (Exception $e) {
      return "";
    }
  }

  /**
   * Loads an order object and get order payer city.
   * If an object is unloadable the function returns nothing
   * 
   * added by krillo 20090304
   */
  public function getOrderCity() {
    try {
      $order = Order::loadByForetagId($this->getId());
      $this->orderCity = $order->getPayerCity();
      return $this->orderCity;
    } catch (Exception $e) {
      return "";
    }
  }

  /**
   * Added by Krillo 090609
   * All foretag info fields copied from order
   */
  public function getKundnummer() {
    return $this->kundnummer;
  }

  public function getCompanyName() {
    return htmlspecialchars_decode($this->companyName);
  }
  public function getPayerCompanyName() {
      return htmlspecialchars_decode($this->payerCompanyName);
  }

  public function getPayerName() {
    return $this->payerName;
  }

  public function getPayerFName() {
    return $this->payerFName;
  }

  public function getPayerLName() {
    return $this->payerLName;
  }

  public function getPayerAddress() {
    return $this->payerAddress;
  }

  public function getPayerCo() {
    return $this->payerCo;
  }

  public function getPayerZipCode() {
    return $this->payerZipCode;
  }

  public function getPayerCity() {
    return $this->payerCity;
  }

  public function getPayerEmail() {
    return $this->payerEmail;
  }

  public function getPayerPhone() {
    return $this->payerPhone;
  }

  public function getPayerMobile() {
    return $this->payerMobile;
  }

  public function getPayerCountry() {
    return $this->payerCountry;
  }

  public function getReciverCompanyName() {
    return htmlspecialchars_decode($this->reciverCompanyName);
  }

  public function getReciverName() {
    return $this->reciverName;
  }

  public function getReciverAddress() {
    return $this->reciverAddress;
  }

  public function getReciverCo() {
    return $this->reciverCo;
  }

  public function getReciverZipCode() {
    return $this->reciverZipCode;
  }

  public function getReciverCity() {
    return $this->reciverCity;
  }

  public function getReciverEmail() {
    return $this->reciverEmail;
  }

  public function getReciverPhone() {
    return $this->reciverPhone;
  }

  public function getReciverMobile() {
    return $this->reciverMobile;
  }

  public function getReciverCountry() {
    return $this->reciverCountry;
  }

  public function getUpdatedDate() {
    return $this->updated_date;
  }

  public function getCreatedDate() {
    return $this->created_date;
  }

  public function getOrderId() {
    return $this->orderId;
  }

  /**
   * Function setCurrentTavlingsId
   * Setting tavlings id for f??ag
   * Example:    setCurrentTavlingsId  ( 0973250 )
   */
  public function setCurrentTavlingsId($id) {
    $this->currenttavlingsid = $id;
  }

  public function listNycklar($free = false) {
    if (!$this->nycklar) {
      global $db;
      $sql = "SELECT * FROM " . self::KEY_TABLE . " WHERE foretag_id = " . $this->getId();

      if ($free)
        $sql.= " AND medlem_id is null";
      $res = $db->query($sql);
      $result = array();
      while ($data = mysql_fetch_assoc($res)) {
        $result[$data["id"]]["nyckel"] = $data["nyckel"];
        $result[$data["id"]]["medlem_id"] = $data["medlem_id"];
      }
      $this->nycklar = $result;
    }
    return $this->nycklar;
  }

  public function listMedlemmar() {
    if (!$this->medlemmar) {
      global $db;
      $sql = "SELECT medlem_id FROM " . self::KEY_TABLE . " WHERE foretag_id = " . $this->getId() . " and medlem_id is not null";
      $ids = $db->valuesAsArray($sql);
      $this->medlemmar = Medlem::listByIds($ids);
    }
    return $this->medlemmar;
  }

  public function countMedlemmar() {
    global $db, $foretag_countmedlemmar_cache;
    if (!isset($foretag_countmedlemmar_cache)) {
      $foretag_countmedlemmar_cache = array();
    } elseif (isset($foretag_countmedlemmar_cache[$this->getId()])) {
      return $foretag_countmedlemmar_cache[$this->getId()];
    } else {
      return 0;
    }
    $sql = "SELECT foretag_id,count(medlem_id) as antal FROM " . self::KEY_TABLE . " WHERE medlem_id is not null GROUP BY foretag_id";
    $res = $db->query($sql);
    while ($r = mysql_fetch_array($res)) {
      $foretag_countmedlemmar_cache[$r["foretag_id"]] = $r["antal"];
    }
    return $foretag_countmedlemmar_cache[$this->getId()];
  }

  public function listLag() {
    if (!$this->lag)
      $this->lag = Lag::listByForetag($this);
    //print_r($this->lag);
    return $this->lag;
  }

  public function setNamn($namn) {
    //		if($this->id){
    //			Security::demand(ADMIN);
    //		}
    /* 		global $db;
      $sql = "SELECT count(*) from " . self::classToTable(get_class()) . " WHERE namn = '" . Security::secure_data($namn) . "'";
      if($this->getId()) $sql .= " AND id <> " . $this->getId();
      if($db->value($sql) > 0)
      throw new ForetagException("F??agsnamnet 㰠upptaget", -3);
     */
    $this->namn = $namn;
  }

  public function setKommunId($kommun_id) {
    if ($this->id)
      Security::demand(FORETAG, $this);
    $this->kommun_id = $kommun_id;
    $this->kommun = null;
  }

  public function setKommun(Kommun $kommun) {

    if ($this->id)
      Security::demand(FORETAG, $this);
    $this->kommun_id = $kommun->getId();
    $this->kommun = $kommun;
  }

  public function setMedlem($medlem) {
    ;

    // används ej
    Security::demand(FORETAG);
    Security::demand(USER);
    global $USER;

    if ($medlem->getId() != $USER->getId())
      Security::demand(ADMIN);

    if (!Misc::isint($medlem_id))
      throw new ForetagException('$medlem_id m䲴e vara ett heltal', -2);
    $this->medlem_id = $medlem_id;
    $this->medlem = null;
  }

  public function setANamn($aNamn) {

    if ($this->getId())
      Security::demand(ADMIN);
    global $db;
    $sql = "SELECT count(*) from " . self::classToTable(get_class()) . " WHERE aNamn = '" . Security::secure_data($aNamn) . "'";

    if ($this->getId())
      $sql.= " AND id <> " . $this->getId();

    if ($db->value($sql) > 0)
      throw new ForetagException("Anv㭤arnamnet 㰠upptaget", -4);
    $this->aNamn = $aNamn;
  }

  public function setEpost($epost) {
    $this->epost = $epost;
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

  public function setIsValid($isValid) {
    $this->isValid = $isValid;
  }

  public function setLosenord($losenord) {
    if (!$this->id)
      $this->commit();
    $this->losenord = Security::encrypt_password($this->id, $losenord);
  }

  /**
   * temporarily store the password two way crypto
   * created by krillo 090709
   */
  public function setTempLosenord($tmpLosenord) {
    if (!$this->id)
      $this->commit();
    $this->tmpLosenord = base64_encode($tmpLosenord);
  }

  public function setSessionId($id) {
    $this->sessionId = $id;
  }

  public function setGiltig($datum) {
    Security::demand(FORETAG, $this);

    if (!Misc::isDate($datum, "Y-m-d"))
      throw new ForetagException('$datum har ett felaktigt format', -8);
    $this->giltig = $datum;
  }

  public function setStartdatum($datum) {

    //		Security::demand(FORETAG, $this);
    //		if(!Misc::isDate($datum, "Y-m-d"))
    //			throw new ForetagException('$datum har ett felaktigt format', -11);

    $this->startdatum = $datum;
  }

  /**
   * Added by Krillo 090609
   * All foretag info fields copied from order
   * 
   */
  public function setKundnummer($kundnummer) {
    $this->kundnummer = $kundnummer;
  }

  public function setCompanyName($name) {
    $this->companyName = $name;
  }
  public function setPayerCompanyName($name) {
    $this->payerCompanyName = $name;
  }
  public function setPayerName($payerName) {
    $this->payerName = $payerName;
  }

  public function setPayerFName($payerName) {
    $this->payerFName = $payerName;
  }

  public function setPayerLName($payerName) {
    $this->payerLName = $payerName;
  }

  public function setPayerAddress($payerAddress) {
    $this->payerAddress = $payerAddress;
  }

  public function setPayerCo($payerCo) {
    $this->payerCo = $payerCo;
  }

  public function setPayerZipCode($payerZipCode) {
    $this->payerZipCode = $payerZipCode;
  }

  public function setPayerCity($payerCity) {
    $this->payerCity = $payerCity;
  }

  public function setPayerEmail($payerEmail) {
    $this->payerEmail = $payerEmail;
  }

  public function setPayerPhone($payerPhone) {
    $this->payerPhone = $payerPhone;
  }

  public function setPayerMobile($payerMobile) {
    $this->payerMobile = $payerMobile;
  }

  public function setPayerCountry($payerCountry) {
    $this->payerCountry = $payerCountry;
  }

  public function setReciverCompanyName($name) {
    $this->reciverCompanyName = $name;
  }

  public function setReciverName($reciverName) {
    $this->reciverName = $reciverName;
  }

  public function setReciverAddress($reciverAddress) {
    $this->reciverAddress = $reciverAddress;
  }

  public function setReciverCo($reciverCo) {
    $this->reciverCo = $reciverCo;
  }

  public function setReciverZipCode($reciverZipCode) {
    $this->reciverZipCode = $reciverZipCode;
  }

  public function setReciverCity($reciverCity) {
    $this->reciverCity = $reciverCity;
  }

  public function setReciverEmail($reciverEmail) {
    $this->reciverEmail = $reciverEmail;
  }

  public function setReciverPhone($reciverPhone) {
    $this->reciverPhone = $reciverPhone;
  }

  public function setReciverMobile($reciverMobile) {
    $this->reciverMobile = $reciverMobile;
  }

  public function setReciverCountry($reciverCountry) {
    $this->reciverCountry = $reciverCountry;
  }

  //do not use this from code, it is autoupdated in the db
  public function setUpdatedDate($arg) {
    $this->updated_date = $arg;
  }

  public function setCreatedDate() {
    $this->created_date = date('Y-m-d H:i:s');
  }

  public function setOrderId($arg) {
    $this->orderId = $arg;
  }

  public function getCreationDate() {

    // echo '!'.$this->creationDate.'!';
    return $this->creationDate;
  }

  public function setCreationDate($date) {
    $this->creationDate = $date;
  }

  public function getAntalAnstallda() {
    $nycklar = $this->listNycklar();
    return count($nycklar);
  }

}

class ForetagException extends Exception {
  
}

?>
