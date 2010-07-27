<?php

/**
 * Medlemsklassen sköter allt som har ankytning till webbplatsens medlemmar.
 *
 * Felmeddelanden:
 * -1  Förnamnet är för kort
 * -2  Efternamnet är för kort
 * -3  Ogiltig e-postadress
 * -4  Lösenordet är för kort
 * -5  Felaktigt lösenord
 * -6  E-postadressen är upptagen
 * -7  Användarnamnet är upptaget
 * -8  Ej behörig
 * -9  Kan inte ändra användarnamn
 * -10 $atkomst har ett felaktigt värde
 * -11 $kon måste ha värdet "man" eller "kvinna"
 * -12 Användarnamnet är för kort
 * -13 E-postadressen kunde inte hittas
 * -14 $ar har ett felaktigt värde
 * -15 Kontot ej aktiverat
 * -16 E-postadressen kunde inte hittas
 * -17 Inget konto är knuten till e-postadressen
 * -18 $datum har ett felaktigt format
 * -19 Medlemskap har gått ut
 * -20 $datum har ett felaktigt format
 * -21 Användarnamn kunde inte hittas
 * -22 php/settings.php - $SETTINGS["default_adminmail"] ej satt
 */
class Medlem extends Mobject
{
	
	protected $id; // int
	protected $fNamn; // string
	protected $eNamn; // string
	protected $aNamn; // string
	protected $kon; // enum: 'man', 'kvinna'
	protected $fodelsear; // int
	protected $kommun_id; // int
	protected $kommun; // object: Kommun
	protected $browser; // string
	protected $ip; // ip nummer
	protected $beskrivning; // string
	protected $epost; // string
	protected $epostBekraftad; // string
	protected $losenord; // string (encrypted)
	protected $senastInloggad; // datetime
	protected $skapad; // datetime
	protected $sessionId; // string
	protected $admin; // int
	protected $foretag; // object: Foretag
	protected $lag; // object: Lag
	protected $visningsbild_filename; // string
	protected $visningsbild;
	protected $avatar_filename;
	protected $avatar;
	protected $atkomst; // string
	protected $customerId; // int
	protected $userOnStaticRoute;
	/** true/false */
	protected $fastrutt_id;
	/** int */	
	protected $justNuKommun_id; // int	
	protected $currentMal; // object: Mal	
	protected $prenumerationer; // array
	protected $olastaMail; // int
	protected $paidUntil; // string
	protected $pokalStart; // string
	protected $levelId; // int
	protected $status; // string
	protected $block_mail; // string /** block motiomeramails from none friends */
	protected $rssUrl; //string
	protected $mAffCode; //string
	protected $foretagsnyckel_temp; //string
	
	protected $fields = array(
		"fNamn" => "str",
		"eNamn" => "str",
		"aNamn" => "str",
		"kon" => "str",
		"fodelsear" => "int",
		"kommun_id" => "int",
		"beskrivning" => "str",
		"epost" => "str",
		"epostBekraftad" => "str",
		"losenord" => "str",
		"senastInloggad" => "str",
		"skapad" => "str",
		"visningsbild_filename" => "str",
		"avatar_filename" => "str",
		"sessionId" => "str",
		"admin" => "str",
		"atkomst" => "str",
		"block_mail" => "str",
		"customerId" => "int",
		"userOnStaticRoute" => "str",
		"fastrutt_id" => "int",
		"justNuKommun_id" => "int",
		"olastaMail" => "int",
		"paidUntil" => "str",
		"pokalStart" => "str",
		"browser" => "str",
		"ip" => "str",
		"levelId" => "int",
		"status" => "str",
		"rssUrl" => "str",
 		"mAffCode" => "str",		
 		"foretagsnyckel_temp" => "str",
	);
	const GET_PREN_URL = "http://mabra.allers.dropit.se/Templates/UserService____51240.aspx?key=aorkmfdfgge74hd8h2vd7&get=Subscriptions&customerid=";
	const MIN_LENGTH_ANAMN = 3;
	const MIN_LENGTH_FNAMN = 2;
	const MIN_LENGTH_ENAMN = 2;
	const MIN_LENGTH_LOSEN = 4;
	const MAX_LENGTH_FNAMN = 40;
	const MAX_LENGTH_ENAMN = 40;
	const MAX_LENGTH_ANAMN = 20;
	const MAX_LENGTH_AFFCODE = 20;	
	const AUTO_LOGOUT = 20;
	const RSS_CACHE_TABLE = "mm_userrsscache";
	const PREN_TABLE = "mm_prenumeration";
	const PROFILDATAVAL_TABLE = "mm_medlemprofildataval";
	const PROFILDATATEXT_TABLE = "mm_medlemprofildatatext";
	const PROFILDATA_TABLE = "mm_profildata";
	const PROFILVAL_TABLE = "mm_profildataval";
	const TABLE = "mm_medlem";


	public function __construct($epost, $anamn, Kommun $kommun, $kon, $fnamn, $enamn, $kontotyp, $mAffCode,  $dummy_object = false)
	{
		
		if (!$dummy_object) {
			$this->setANamn($anamn);
			$this->setKon($kon);
			$this->setFNamn($fnamn);
			$this->setENamn($enamn);
			$this->setKommun($kommun);
			$this->setMAffCode($mAffCode);			

			// SQL för att kolla om epostadressen finns registrerad
			$sql = "SELECT epost FROM " . $this->getTable() . " " . "WHERE epost = '" . Security::secure_data($epost) . "' ";
			global $db;
			
			if ($db->value($sql) != "") {

				// Kolla om den registrerade epostadressen är verifierad.
				$sqlUnverif = "SELECT id FROM " . $this->getTable() . " " . "WHERE epost = '" . Security::secure_data($epost) . "' " . "AND epostBekraftad != 1";
				$reggadMedlem = $db->value($sqlUnverif);
				
				if ($reggadMedlem) {

					// Ta bort den ickeverifierade medlemmen
					$db->nonquery('DELETE FROM ' . $this->getTable() . ' ' . 'WHERE id = ' . $reggadMedlem);
				} else {

					// Kasta fel om att ett aktivt konto är reggat med epostadressen
					throw new MedlemException("E-postadressen är upptagen", -6);
				}
			}
			$this->setEpost($epost);
			$this->setOlastaMail(0);
			$this->setFastRuttId(0);
			$this->setSkapad();
			$this->setMotiomeraMailBlock('false');
			$this->setAtkomst('alla');
			$this->setUserOnStaticRoute('false');
			$this->setJustNuKommunId($kommun->getId());
			$this->setBrowser();
			$this->setIpNr();
			$this->setLevelId(0);
			
#			AVMARKERAT eftersom trial inte ska sätta något datum
#			if ($kontotyp == "trial") {
#				$this->addPaidUntil(92);
#			}
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, Kommun::__getEmptyObject() , null, null, null, null, null, true);
	}
	
	public function confirm($losenord)
	{
		$this->setLosenord($losenord); // sparar objektet för att generera id-nummer

		new Stracka($this->getKommun() , $this);

		// skapa första/default fotoalbum
		new Fotoalbum(array(
			"namn" => "Mina bilder",
			"beskrivning" => "",
			"tilltrade" => "alla"
		) , $this);
		new FeedItem("valkommen", null, $this);
	}
	
	public function sendWelcomeMail()
	{

		//no mails means a new member
		$usermails = MotiomeraMail::listByReceiver($this->getId());
		
		if (count($usermails) == 0) {
			global $SETTINGS;
			$subject = 'Välkommen till Motiomera.se !';
			$msg = 'Vi här på Motiomera.se hoppas att du kommer att tycka om tjänsten. Klicka <a href="' . $SETTINGS["url"] . 'pages/valkommen.php' . '">här</a> för att komma till vår <a href="' . $SETTINGS["url"] . 'pages/valkommen.php' . '">välkomstsida</a>.';
			$adminMedlem = Medlem::loadByUsername($SETTINGS["default_adminmail"]);
			$internmail = new MotiomeraMail($subject, $msg, $adminMedlem->getId() , $this->getId() , date("Y-m-d H:i:s") , 0, 0);
		}
	}
	
	public function addPaidUntil($dagar)
	{
		$dag = 60 * 60 * 24;
		
		if ($this->paidUntil == "" || strtotime($this->paidUntil) < time()) {
			$this->paidUntil = date("Y-m-d", time() + ($dagar * $dag));
		} else {
			$this->paidUntil = date("Y-m-d", strtotime($this->paidUntil) + ($dagar * $dag));
		}
	}

	// PRIVATE FUNCTIONS
	
	private function setLosenP($losen)
	{
		$this->losenord = Security::encrypt_password($this->id, $losen);
	}

	// PUBLIC FUNCTIONS
	
	public function listMedaljer()
	{
		return Sammanstallning::listMedaljer($this);
	}
	
	public function handleOrder(Order $order)
	{
		
		if ($order->getExpired()) {
			return false;
		}
		$campaignCodes = Order::getCampaignCodes("medlem");
		$dagar = $campaignCodes[$order->getCampaignId() ]["dagar"];
		$level = $campaignCodes[$order->getCampaignId() ]["levelid"];
		$order->setExpired(true);
		$order->commit();
		$this->addPaidUntil($dagar);
		$this->setLevelId($level);
		$this->commit();
		
		if ($this->getEpostBekraftad() == 0) {
			$this->sendActivationEmail();
		}

		//		Misc::sendMail($this->getEpost(), null, "Tack för din beställning!", $message);
		
	}
	
	public function addStaticRouteToUser($fastrutt_id)
	{
		$this->removeToCurrentPos($this->getId());
		//Stracka::deleteUserStrackor($this);
		$this->setFastRutt($fastrutt_id);
	}
	
	/**
	 * Function removeToCurrentPos
	 * 
	 * Removes the rutt to current pos
	 *
	 * Example:
	 *      removeToCurrentPos  ( $id )
	 */
	public function removeToCurrentPos($id)
	{
		if ($id == $this->getId()) {
			$currentKommun = $this->getCurrentKommun();
			$ruttObj = new Rutt($this);
			$rutt = $ruttObj->getRutt();
			$curPos = false;
			foreach($rutt as $id => $stracka) {
				$kommun = $stracka["Kommun"];
				$thisKm = $stracka["ThisKm"];
				$stracka_id = $stracka["id"];
				if ($id == $ruttObj->getCurrentIndex()) {
					$curPos = true;
					
					if ($thisKm > 0) {
						continue;
					}
				} elseif (!$curPos) {
					continue;
				}
				$kommun = $stracka["Kommun"];
				$stracka = Stracka::loadById($stracka_id);
				$stracka->delete();
			}
		}
	}
	
	public function removeStaticRouteForUser($id,$completed=false)
	{
		
		if ($id == $this->getId()) {

			// check if international challenge
			
			$currentKommun = $this->getCurrentKommun();
			$ruttObj = new Rutt($this);
			$rutt = $ruttObj->getRutt();
			$curPos = false;
			$lastNonAbroad = false;
			foreach($rutt as $id => $stracka) {
				$kommun = $stracka["Kommun"];
				$thisKm = $stracka["ThisKm"];
				$stracka_id = $stracka["id"];

				if ($curPos === false) {
					// if this isn't the current kommun, and it's not abroad, we'll save it as the lastNonAbroad kommun
					if ($kommun->getAbroad() == "false") {
						$lastNonAbroad = $kommun;
					}
				}

				if ($id == $ruttObj->getCurrentIndex()) { // we're now at the current kommun
					$curPos = true;

					if ($kommun->getAbroad() == "false" || $thisKm > 0) { // if it's not abroad, and not just a "jump to" kommun
						if(!$completed) {
							$stracka = Stracka::loadById($stracka_id);
							$stracka->setStatic(0);
							$stracka->commit();
						}

						continue;
					}
				} elseif (!$curPos) { // we haven't reached the current kommun yet
					if(!$curPos && $stracka["fastRutt"] == 1) { // this is part of the ended static route, and so it will be changed to non-static...
						if(!$completed) { // ...if it's not a completed static route
							$stracka = Stracka::loadById($stracka_id);
							$stracka->setStatic(0);
							$stracka->commit();
						}
					}
					elseif($stracka["fastRutt"] == 1 && !$completed) { // if this static route isn't completed, it's changed to non-static

						$stracka = Stracka::loadById($stracka_id);
						$stracka->setStatic(0);
						$stracka->commit();
					}
					else { // this looks a bit weird, it's here for historic reasons
					}
					continue;
				}
				$kommun = $stracka["Kommun"];
				$stracka = Stracka::loadById($stracka_id);
				$stracka->delete();
			}

			if ($lastNonAbroad != false && $this->getCurrentKommun()->getId() != $lastNonAbroad->getId()) {
				$strackaObj = new Stracka($lastNonAbroad, $this);
				$strackaObj->setTempStatus(false);
				$strackaObj->commit();
			}
	
			$this->setUserOnStaticRoute('false');
			$this->setFastRuttId(0);
			$this->commit();
		}
	}
	
	public function setFastRutt($fastrutt_id)
	{
		global $db;
		$sql = "SELECT kommunTill_id FROM " . Rutt::TABLE . " " . "WHERE fastRutt_id = $fastrutt_id ORDER BY id ASC";
		$rutt = $db->allValuesAsArray($sql);

		//print_r($rutt);
		foreach($rutt as $row) {
			$kommun = Kommun::loadById($row['kommunTill_id']);
			new Stracka($kommun, $this, 1);
		}
		$this->approveTempStrackor();
		$this->setUserOnStaticRoute('true');
		$this->setFastRuttId($fastrutt_id);
		$this->commit();
	}
	
	public function setStaticRuttDone($id)
	{
		Rutt::setStaticRouteDoneForUser($this, $id);
		$this->removeStaticRouteForUser($this->getId(),true);
	}
	
	public function uppdateraRutt($nykommun = false)
	{
		$gammalKommunId = $this->getJustNuKommunId();
		$rutt = new Rutt($this);
		$kommun = $rutt->getCurrentKommun();
		
		if (!$kommun) {
			$kommun = $this->getKommun(); // Om medlem inte kommit till någon kommun ännu väljs startkommunen

			
		}
		$this->setJustNuKommunId($kommun->getId());
		$this->commit();
		$malManager = new MalManager($this);
		$malManager->updateMal();
		
		if ($gammalKommunId != $this->getJustNuKommunId()) { // kommit fram till kommun

			$this->lasSteg();
			new FeedItem("komframtillkommun", null, $this, null, $kommun);
			$nykommun = true;
		}
	}
	
	public function setStartKommun($kommun_id)
	{
		
		if ($this->getStegTotal() == 0) {
			$this->resetRoute();
		}
		$kommun = Kommun::loadById($kommun_id);
		new Stracka($kommun, $this);
		$this->approveTempStrackor();
		$this->commit();
	}
	
	public function resetRoute()
	{
		global $db;
		$sql = "DELETE FROM mm_stracka WHERE medlem_id=" . $this->getId();
		$db->nonquery($sql);
	}
	
	public function lasSteg()
	{
		$stegList = Steg::listByMedlem($this);
		foreach($stegList as $steg) {
			
			if (!$steg->getLast()) {
				$steg->setLast(true);
				$steg->commit();
			}
		}
	}
	
	public function getForetagsnyckel($activeOnly = false)
	{
		global $db;
		$sql = "SELECT foretag_id, nyckel FROM " . Foretag::KEY_TABLE . " WHERE medlem_id = " . $this->getId() . " ORDER BY datum DESC LIMIT 1";
		$result = $db->row($sql);

		$nyckel = $result["nyckel"];
		$id = $result["foretag_id"];
		
		if($id) {
		
			$foretag = Foretag::loadById($id);
		
			if($activeOnly) {

				// check to make sure that the contest isn't over (+1 day to allow for mondays)
				if($foretag->aktivTavling("+1")) {
					return $nyckel;
				}
				else {
					return null;
				}
			}
			else {
				return $nyckel;
			}
		}
		else {
			return null;
		}
	}
	
	public function synlig()
	{
		global $USER;
		
		switch ($this->getAtkomst()) {
		case "alla":
			return true;
			break;

		case "medlem":
			
			if (isset($USER)) {
				return true;
			}
			break;

		case "adressbok":
			
			if (isset($USER) && $this->inAdressbok($USER)) {
				return true;
			}
			break;

		case "foretag":
			
			if (isset($USER) && $this->getForetag() && $this->getForetag()->isAnstalld($USER)) {
				return true;
			}
			break;
		}
		return false;
	}
	
	public function isInloggad()
	{
		
		if (strtotime($this->getSenastInloggad()) > time() - (self::AUTO_LOGOUT * 60)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getSuccessfullQuizzes()
	{
		global $db;
		$sql = "SELECT K.namn FROM mm_kommun AS K, mm_quizsuccess AS Q WHERE Q.medlem_id = " . $this->id . " AND K.id = Q.kommun_id";
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_array($res, MYSQL_ASSOC)) {
			$result[] = $data['namn'];
		}
		return $result;
	}
	
	public function getAntalSuccessfullQuizzes()
	{
		global $db;
		$sql = "SELECT count(*) FROM " . Quiz::TABLE . " WHERE medlem_id = " . $this->getId();
		return $db->value($sql);
	}
	
	public function getUsersThatHasMeAsContact($from_id)
	{
		global $db;
		
		if ($from_id == 0) {
			$sql = "SELECT M.id, M.aNamn FROM mm_kontakt AS MMC, mm_medlem AS M WHERE MMC.kontakt_id = " . $this->getId() . " AND MMC.medlem_id = M.id";
		} else {
			$sql = "SELECT M.id, M.aNamn FROM mm_kontakt AS MMC, mm_medlem AS M WHERE MMC.kontakt_id = " . $this->getId() . " AND MMC.medlem_id != " . $from_id . " AND MMC.medlem_id = M.id";
		}
		$result = array();
		$res = $db->query($sql);
		while ($data = mysql_fetch_array($res, MYSQL_ASSOC)) {
			$result[$data['id']] = $data['aNamn'];
		}
		return $result;

		/*
		$sql = "SELECT count(*) FROM mm_kontakt WHERE medlem_id = " . $this->getId() . " AND kontakt_id = " . $medlem->getId();
		if($db->value($sql) != 0)
		return true;
		else
		return false;
		*/

		//$arr[0]['id'] = '1';
		//$arr[0]['name'] = 'gigi';

		
	}
	
	public function inAdressbok(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT count(*) FROM mm_kontakt WHERE medlem_id = " . $this->getId() . " AND kontakt_id = " . $medlem->getId();
		
		if ($db->value($sql) != 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function awaitingVisningsbildApproval()
	{
		$customVisningsbild = $this->getCustomVisningsbild();
		
		if ($customVisningsbild && !$customVisningsbild->isApproved()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getLatestKommun()
	{
		return Stracka::getLatestKommunByMedlem($this);
	}
	
	public function getCurrentKommun()
	{
		$rutt = new Rutt($this);
		return $rutt->getCurrentKommun();
	}
	
	public function loggaUt()
	{
		$this->setSessionId("");
		$this->commit();
		session_destroy();
		setcookie("mm_mid", null, 0, "/");
		setcookie("mm_sid", null, 0, "/");
	}
	
	public function joinGrupp($grupp, $ownMsg = null)
	{
		$grupp->joinGrupp($this, $ownMsg);
	}
	
	public function getStegToNextMal()
	{
		return Steg::kmToSteg($this->getCurrentMal()->getAvstand()) - ($this->getStegTotal() - Mal::getUsedSteg($this));
	}
	
	public function listBySokord($sokord, $notIn = null)
	{
		Security::demand(USER);
		global $db, $USER;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE id <> " . $USER->getId() . " AND epostBekraftad = 1 AND atkomst IN ('alla', 'medlem') AND (aNamn LIKE '%" . Security::secure_data($sokord) . "%' OR fNamn LIKE '%" . Security::secure_data($sokord) . "%' OR eNamn LIKE '%$sokord%')";
		
		if ($notIn && count($notIn) > 0) {
			$sql.= " AND id NOT IN (" . implode(",", $notIn) . ")";
		}
		$ids = $db->valuesAsArray($sql);
		return parent::listByIds(get_class() , $ids);
	}

	// SETTERS & GETTERS
	
	/**
	 * Function getRssFeed
	 *
	 * Returns an array with users rssFeed
	 *
	 * Example:
	 *      getRssFeed  ( 15 ) returns 15 latest entrys in users rss feed defaults at 2
	 */
	
	public function getRssFeed($amount = 1)
	{
		return RSSHandler::getRssAsArray($this->getRssUrl() , $amount);
	}
	/**
	 * Function getLatestCachedRss
	 *
	 * Gets the latest cached rss from user
	 *
	 * Example:
	 *      getLatestCachedRss  (  )
	 */
	
	public function getLatestCachedRss($timestamp = false)
	{
		global $db;
		$stamp = null;
		
		if ($timestamp == true) {
			$stamp = "title, description, link, UNIX_TIMESTAMP(pubDate) as pubDate";
		} else {
			$stamp = "*";
		}
		$sql = "SELECT " . $stamp . " FROM " . self::RSS_CACHE_TABLE . " 
			WHERE medlem_id = " . $this->getId() . " 
			ORDER BY pubDate DESC 
			LIMIT 1";
		return $db->row($sql);
	}
	
	/**
	 * Function getForetagsnyckel_temp
	 *
	 * Gets users foretagsnyckel_temp (used for temporary storage from signup to confirmation)
	 *
	 * Example:
	 *      getForetagsnyckel_temp  (   )
	 */
	
	public function getForetagsnyckel_temp()
	{
		return $this->foretagsnyckel_temp;
	}
	
	/**
	 * Function setForetagsnyckel_temp
	 *
	 * Sets the users foretagsnyckel_temp (used for temporary storage from signup to confirmation)
	 *
	 * Example:
	 *      setForetagsnyckel_temp  ( $id  )
	 */
	
	public function setForetagsnyckel_temp($nyckel)
	{
		return $this->foretagsnyckel_temp = $nyckel;
	}
	
	
	/**
	 * Function getRssUrl
	 *
	 * Gets users rss url
	 *
	 * Example:
	 *      getRssUrl  (   )
	 */
	
	public function getRssUrl()
	{
		return $this->rssUrl;
	}
	/**
	 * Function setRssUrl
	 *
	 * Sets users rss url
	 *
	 * Example:
	 *      setRssUrl  ( motiomera.se/rss.xml )
	 */
	
	public function setRssUrl($url)
	{
		if (!$this->rssUrl or $url != $this->rssUrl) {
			$this->rssCacheFlush();
		}
		$this->rssUrl = $url;
	}
	
	/**
	 * Function rssCacheFlush
	 * 
	 * Flush the rss cache for user
	 *
	 * Example:
	 *      rssCacheFlush  (  )
	 */
	public function rssCacheFlush()
	{
		global $db;
		$sql = "DELETE FROM " . self::RSS_CACHE_TABLE . " 
			WHERE medlem_id = " . $this->getId();
		$db->nonquery($sql);
	}
	
	public function getPaidUntil()
	{
		return $this->paidUntil;
	}
	
	public function getPokalStart()
	{
		return ($this->pokalStart == "0000-00-00" || $this->pokalStart == "") ? null : $this->pokalStart;
	}
	
	public function getOlastaMail()
	{
		return $this->olastaMail;
	}
	
	public function getCurrentMal()
	{
		
		if (!$this->currentMal) {
			$this->currentMal = Mal::loadCurrentByMedlem($this);
		}
		return $this->currentMal;
	}
	
	public function getFNamn()
	{
		return stripslashes($this->fNamn);
	}
	
	public function getENamn()
	{
		return stripslashes($this->eNamn);
	}
	
	public function getANamn()
	{
		return strip_tags($this->aNamn, '<p><a>');
	}
	
	public function getLevelId()
	{
		return $this->levelId;
	}
	
	public function getLevel()
	{
		
		if ($this->levelId > 0) {
			try {
				return Level::loadById($this->levelId);
			}
			catch(Exception $e) {

				// the level didn't exist, return default instead
				return Level::getDefault();
			}
		}
	}
	
	public function getKon()
	{
		return $this->kon;
	}
	
	public function getFodelsear()
	{
		return $this->fodelsear;
	}
	
	public function getKommunId()
	{
		return $this->kommun_id;
	}
	
	public function getKommun()
	{
		
		if (!$this->kommun) {
			
			if ($this->getKommunId()) {
				$this->kommun = Kommun::loadById($this->kommun_id);
			} else {
				$this->kommun = null;
			}
		}
		return $this->kommun;
	}
	
	public function getBeskrivning()
	{
		return strip_tags($this->beskrivning, '<p><a>');
	}
	
	public function getEpost()
	{
		return $this->epost;
	}
	
	public function getEpostBekraftad()
	{
		return $this->epostBekraftad;
	}
	
	public function getLosenord()
	{
		return $this->losenord;
	}
	
	public function getSenastInloggad()
	{
		return $this->senastInloggad;
	}
	
	public function getSkapad()
	{
		return $this->skapad;
	}
	
	public function getSkapadDateOnly()
	{
		return Misc::getDateFromDateTime($this->skapad);
	}
	
	public function getSessionId()
	{
		return $this->sessionId;
	}
	
	public function getAdmin()
	{
		return $this->admin;
	}
	
	public function getMAffCode()
	{
		return $this->mAffCode;
	}	
	
	public function getAtkomst()
	{
		return $this->atkomst;
	}
	
	public function getCustomerId()
	{
		return $this->customerId;
	}
	
	public function getJustNuKommunId()
	{
		return $this->justNuKommun_id;
	}
	
	/**
	 * Function getJustNuKommunId
	 * 
	 * Returns name of current kommun
	 *
	 * Example:
	 *      getJustNuKommunId  (  )
	 */
	public function getJustNuKommunNamn()
	{
		if($this->getJustNuKommunId()){
			$kommun = Kommun::loadByid($this->getJustNuKommunId());
			return $kommun->getNamn();
		} else {
			return false;
		} 
	}
	
	public function getTotalStegByDay($day = 0)
	{
		return Steg::getTotalStegByDay($day, $this);
	}
	
	public function getStegTotal($start = null, $stop = null)
	{
		return Steg::getStegTotal($this, $start, $stop);
	}
	
	public static function getStegTotalForMedlemId($medlem_id, $start = null, $stop = null)
	{
		return Steg::getStegTotalForMedlemId($medlem_id, $start, $stop);
	}

	
	public function getStegIndex($foretag = false){
		if(!$foretag) {
			$foretag = $this->getForetag();
		}		
		if ($foretag) {			
			if ($foretag->getSlutDatum() > date("Y-m-d")) {
				$slut = date("Y-m-d");
			} else {
				$slut = $foretag->getSlutDatum();
			}
			$dagar = (Misc::getDagarMellanTvaDatum($foretag->getStartDatum() , $slut) + 1);
			if ($dagar>0):
				return round($this->getStegTotal($foretag->getStartDatum() , $slut) / $dagar);
			else:
				return 0;
			endif;
		} else {
			return 0;
		}
	}
	
	
	public static function getStegIndexForMedlemId($medlem_id, $foretag){		
		if ($foretag) {			
			if ($foretag->getSlutDatum() > date("Y-m-d")) {
				$slut = date("Y-m-d");
			} else {
				$slut = $foretag->getSlutDatum();
			}
			$dagar = (Misc::getDagarMellanTvaDatum($foretag->getStartDatum() , $slut) + 1);
			if ($dagar>0):
				return round(self::getStegTotalForMedlemId($medlem_id,$foretag->getStartDatum() , $slut) / $dagar);
			else:
				return 0;
			endif;
		} else {
			return 0;
		}
	}
	
	
	public function getStegTotalGrupp(Grupp $grupp){
		return Steg::getStegTotalGrupp($grupp, $this);
	}
	
	public function getStegTotalLag(Lag $lag)
	{
		return Steg::getStegTotalLag($lag, $this);
	}
	
	public function getVisningsbildFilename()
	{
		return $this->visningsbild_filename;
	}
	
	public function getVisningsbild()
	{
		$customVisningsbild = $this->getCustomVisningsbild(false);
		
		if ($customVisningsbild) {
			$this->visningsbild = $customVisningsbild;
		} else {
			
			if ($this->visningsbild_filename) {
				$this->visningsbild = Visningsbild::loadByFilename($this->visningsbild_filename);
			} else {
				$this->visningsbild = Visningsbild::loadStandard($this->getKon());
			}
		}
		return $this->visningsbild;
	}
	
	public function getAvatarFilename()
	{
		return $this->avatar_filename;
	}
	
	public function getAvatar()
	{
		
		if (!$this->avatar) {
			
			if ($this->avatar_filename != "") {
				try {
					$this->avatar = Avatar::loadByFilename($this->getAvatarFilename());
				}
				catch(FilException $e) {
					
					if ($e->getCode() == - 1) {
						$this->avatar = Avatar::loadStandard();
						$this->avatar_filename = "";
						$this->commit();
					}
				}
			} else $this->avatar = Avatar::loadStandard();
		}
		return $this->avatar;
	}
	
	public function getCustomVisningsbild($approved = true)
	{
		try {
			$customVisningsbild = CustomVisningsbild::loadByMedlem($this, $approved);
		}
		catch(CustomVisningsbildException $e) {
			
			if ($e->getCode() == - 3) {
				unset($customVisningsbild);
			} else {
				throw $e;
			}
		}
		
		if (isset($customVisningsbild)) {
			return $customVisningsbild;
		} else {
			return false;
		}
	}
	
	public function getProfilDataVal($profilDataId = "random")
	{
		global $db;
		
		if ($profilDataId == "random") {
			$sql = "SELECT profilDataVal_id FROM " . self::PROFILDATAVAL_TABLE . "
					WHERE
						medlem_id = '" . $this->getId() . "'
					ORDER BY
						rand(" . rand(0, time()) . ")
					LIMIT 1";
		} else {
			$sql = "SELECT profilDataVal_id FROM " . self::PROFILDATAVAL_TABLE . "
					WHERE
						medlem_id = '" . $this->getId() . "' AND profilData_id = '" . Security::secure_data($profilDataId) . "'";
		}
		return $db->value($sql);
	}
	
	public function getProfilDataText($profilDataId = "random")
	{
		global $db;
		
		if ($profilDataId == "random") {
			$sql = "SELECT profilDataText FROM " . self::PROFILDATATEXT_TABLE . "
					WHERE
						medlem_id = '" . $this->getId() . "'
					ORDER BY
						rand(" . rand(0, time()) . ")
					LIMIT 1";
		} else {
			$sql = "SELECT profilDataText FROM " . self::PROFILDATATEXT_TABLE . "
					WHERE
						medlem_id = '" . $this->getId() . "' AND profilData_id = '" . Security::secure_data($profilDataId) . "'";
		}
		return $db->value($sql);
	}
	
	public function getProfilDataValObject($profilDataId = "random")
	{
		global $db;
		$sql_fritext = "SELECT b.id, b.namn as namn, a.profilDataText as varde FROM " . self::PROFILDATATEXT_TABLE . " a, " . self::PROFILDATA_TABLE . " b WHERE a.profilData_id = b.id and a.medlem_id = " . $this->getId();
		$sql_selectval = "SELECT b.id, b.namn as namn, c.varde as varde FROM " . self::PROFILDATAVAL_TABLE . " a, " . self::PROFILDATA_TABLE . " b, " . self::PROFILVAL_TABLE . " c WHERE a.profilDataVal_id = c.id and a.profilData_id = b.id and a.medlem_id = " . $this->getId();
		$profildata_select = $db->allValuesAsArray($sql_selectval);
		$profildata_text = $db->allValuesAsArray($sql_fritext);
		
		if (count($profildata_text)) {
			foreach ($profildata_text as $key => $values) {
				$profildata_text[$key]['varde'] = utf8_decode($values['varde']);
			}
		}
		$profildata = array_merge($profildata_select, $profildata_text);
		$utf_fixed = array();
		foreach($profildata as $profildatasnutt) {
			$profildatasnutt['namn'] = ($profildatasnutt['namn']);
			$profildatasnutt['varde'] = ($profildatasnutt['varde']);
			$utf_fixed[] = $profildatasnutt;
		}
		$profildata = $utf_fixed;
		unset($utf_fixed);
		shuffle($profildata);
		
		if (count($profildata)) {
			return $profildata[0];
		} else {
			return array();
		}

		/*
		if ($this->getProfilDataText($profilDataId)):
		$text = $this->getProfilDataText($profilDataId); //ProfilDataVal::loadById($this->getProfilDataVal($profilDataId)); // Profildata är fritext
		endif;
		if ($this->getProfilDataVal($profilDataId)):
		$select = ProfilDataVal::loadById($this->getProfilDataVal($profilDataId)); // Profildata är från select
		endif;
		*/

		// FORTSÄTT HÄR
		// Du skriver om till en SQL som ska hämta alla existerande profildataIDn från båda tabellerna och slumpa ut ett val där.

		// Bör nog också skicka med en bool om vilken av de två tabellerna datan i objektet ska hämtas från

		
	}
	
	public function getBrowser()
	{
		return $this->browser;
	}
	
	public function getIpNr()
	{
		return $this->ip;
	}
	
	public function listInvites()
	{
		return Grupp::listInvites($this);
	}
	
	public function acceptInvite(Grupp $grupp)
	{
		$grupp->acceptInvite($this);
	}
	
	public function denyInvite(Grupp $grupp)
	{
		$grupp->denyInvite($this);
	}
	
	public function listSteg($order = "datum DESC, tid DESC")
	{

		// deprecated
		return Steg::listByMedlem($this, $order);
	}
	
	public function listTotalStegByDay($num)
	{
		global $db;
		$today = strtotime(date("Y-m-d"));
		$result = array();
		for ($i = 0; $i < $num; $i++) {
			$curr = count($result);
			$offset = $i * (60 * 60 * 24);
			$date = date("Y-m-d", time() - $offset);
			$sql = "SELECT SUM(steg) FROM mm_steg WHERE DATUM like '" . $date . "%' and medlem_id = " . $this->getId();
			$steg = $db->value($sql);
			
			if ($steg != 0) {
				$result[$curr]["datum"] = $date;
				$result[$curr]["steg"] = $steg;
			}
		}
		return $result;
	}
	
	public function listJoinedGroups()
	{
		return Grupp::listJoinedGroups($this);
	}
	
	public function listCreatedGroups()
	{
		return Grupp::listCreatedGroups($this);
	}
	
	public function listPrenumerationer()
	{
		
		if (!$this->prenumerationer) {
			global $db;
			$sql = "SELECT tidning FROM " . self::PREN_TABLE . " WHERE medlem_id = " . $this->getId();
			$this->prenumerationer = $db->valuesAsArray($sql);
		}
		return $this->prenumerationer;
	}
	
	public function getForetag($activeOnly = false)
	{
		if (!$this->foretag) {
			$this->foretag = Foretag::loadByMedlem($this,$activeOnly);
		}
		return $this->foretag;
	}
	
	public function getLag()
	{
		
		if (!$this->lag) {
			$this->lag = Lag::loadByMedlem($this);
		}
		return $this->lag;
	}
	
	public function getUserOnStaticRoute()
	{
		return ($this->userOnStaticRoute=="true")?true:false;
	}
	
	public function setUserOnStaticRoute($set)
	{
		$this->userOnStaticRoute = $set;
	}
	
	public function setFNamn($fNamn)
	{
		
		if (strlen($fNamn) < self::MIN_LENGTH_FNAMN) {
			throw new MedlemException("Förnamnet är för kort: $fNamn", -1);
		}
		
		if (strlen($fNamn) > self::MAX_LENGTH_FNAMN) {
			throw new MedlemException("Förnamnet är för långt: " . $fNamn, -1);
		}
		$this->fNamn = $fNamn;
	}
	
	public function setENamn($eNamn)
	{
		
		if (strlen($eNamn) < self::MIN_LENGTH_ENAMN) {
			throw new MedlemException("Efternamnet är för kort: $eNamn", -2);
		}
		
		if (strlen($eNamn) > self::MAX_LENGTH_ENAMN) {
			throw new MedlemException("Efternamnet är för långt: " . $eNamn, -2);
		}
		$this->eNamn = $eNamn;
	}
	
	public function setANamn($anamn)
	{
		
		if ($this->aNamn) {
			throw new MedlemException("Kan inte ändra användarnamn", -9);
		}
		
		if (strlen($anamn) < self::MIN_LENGTH_ANAMN) {
			throw new MedlemException("Användarnamnet är för kort", -12);
		}
		
		if (strlen($anamn) > self::MAX_LENGTH_ANAMN) {
			throw new MedlemException("Användarnamnet är för långt :" . $anamn, -12);
		}
		global $db;
		$sql = "SELECT aNamn FROM " . $this->getTable() . " WHERE anamn = '" . Security::secure_data($anamn) . "' ";
		
		if ($this->getId()) {
			$sql.= "AND id <> " . $this->getId();
		}
		
		if ($db->nonquery($sql) != 0) {
			throw new MedlemException("Användarnamnet är upptaget", -7);
		}
		$this->aNamn = $anamn;
	}
	
	public function setKon($kon)
	{
		
		if ($kon != "man" && $kon != "kvinna") {
			throw new MedlemException('$kon måste ha värdet "man" eller "kvinna"', -11);
		}
		$this->kon = $kon;
	}
	
	public function setLevelId($levelId)
	{
		$this->levelId = $levelId;
	}
	
	public function setFodelsear($ar)
	{
		
		if ($this->id) {
			Security::demand(USER, $this);
		}
		
		if (!Misc::isInt($ar) || $ar < 1900 || $ar > 2008) {
			throw new MedlemException('$ar har ett felaktigt värde', -14);
		}
		$this->fodelsear = $ar;
	}
	
	public function setKommunId($id)
	{
		
		if ($this->getId()) Security::demand(USER, $this);
		$this->kommun_id = $id;
		$this->kommun = null;
	}
	
	public function setBrowser()
	{
		$this->browser = $this->getRawCurrentBrowserVersion();
	}
	
	public function setIpNr()
	{
		$this->ip = $this->getCurrentIpNr();
	}
	
	public function setKommun(Kommun $kommun)
	{
		
		if ($this->getId()) {
			Security::demand(USER, $this);
		}
		$this->kommun = $kommun;
		$this->kommun_id = $kommun->getId();
	}
	
	public function setBeskrivning($beskrivning)
	{
		
		if ($this->getId()) Security::demand(USER, $this);
		$this->beskrivning = $beskrivning;
	}
	
	public function saveBrowserAndIp()
	{
		global $db;
		$sql = "UPDATE " . self::TABLE . " SET ip='" . $this->getCurrentIpNr() . "', browser='" . $this->getRawCurrentBrowserVersion() . "' WHERE id=" . $this->getId();
		$db->query($sql);

		// die(mysql_error()." ".$sql);
		
	}
	
	public function setEpost($epost)
	{
		
		if ($this->getId()) Security::demand(USER, $this);
		global $db;
		
		if (!Misc::isEmail($epost)) throw new MedlemException("Ogiltig e-postadress", -3);
		
		if ($this->getId()) {
			$sql = "SELECT epost FROM " . $this->getTable() . " WHERE epost = '" . Security::secure_data($epost) . "' ";
			
			if ($this->getId()) $sql.= "AND id <> " . $this->getId();
			
			if ($db->value($sql) != "") {
				throw new MedlemException("E-postadressen är upptagen", -6);
			}
		}
		$this->epost = $epost;

		// TODO: Bekräfta epost
		
	}
	
	public function setEpostBekraftad($status)
	{
		$this->epostBekraftad = $status;
	}
	
	public function setLosenord($losenord)
	{
		
		if ($this->getId()) Security::demand(USER, $this);
		
		if (strlen($losenord) < self::MIN_LENGTH_LOSEN) throw new MedlemException("Lösenordet är för kort", -4);
		
		if (!$this->getId()) $this->commit();
		$losenord_krypterat = Security::encrypt_password($this->id, $losenord);
		$this->losenord = $losenord_krypterat;
	}
	
	public function setSenastInloggad()
	{
		$this->senastInloggad = date("Y-m-d H:i:s");
	}
	
	public function setSkapad()
	{
		$this->skapad = date("Y-m-d H:i:s");
	}
	
	public function setVisningsbildFilename($filename)
	{
		Security::demand(USER, $this);
		$this->visningsbild_filename = $filename;
	}
	
	public function setAvatarFilename($filename)
	{
		Security::demand(USER, $this);
		$this->avaratar_filename = $filename;
		$this->avatar = null;
	}
	
	public function setAvatar(Avatar $avatar)
	{
		$this->avatar = $avatar;
		$this->avatar_filename = $avatar->getNamn();
	}
	
	public function setVisningsbild($visningsbild)
	{
		Security::demand(USER, $this);
		
		if (get_class($this->getVisningsbild()) == "CustomVisningsbild") $this->getVisningsbild()->delete();
		$this->setVisningsbildFilename($visningsbild->getNamn());
		$this->commit();
	}
	
	public function setSessionId($sid)
	{
		$this->sessionId = $sid;
	}
	
	public function setAdmin($admin)
	{
		Security::demand(SUPERADMIN);
		$this->admin = ($admin) ? 1 : 0;
	}
	
	public function setMAffCode($mAffCode)
	{		
		if (strlen($mAffCode) > self::MAX_LENGTH_AFFCODE) {
			$mAffCode = substr($mAffCode,0, self::MAX_LENGTH_AFFCODE -1 ); 
		}
		$this->mAffCode = $mAffCode;
	}	
		
	public function setAtkomst($atkomst)
	{
		
		if ($this->getId()) {
			Security::demand(USER, $this);
			
			if (!in_array($atkomst, array(
				"alla",
				"medlem",
				"adressbok",
				"foretag",
				"ingen"
			)) || ($this->getForetag() && $atkomst == "ingen") || (!$this->getForetag() && $atkomst == "foretag")) throw new MedlemException($atkomst . ' har ett felaktigt värde', -10);
		}
		$this->atkomst = $atkomst;
	}
	
	public function setCustomerId($id)
	{
		Security::demand(USER, $this);
		global $db;
		$this->customerId = $id;
		$sql = "DELETE FROM " . self::PREN_TABLE . " WHERE medlem_id = " . $this->getId();
		$db->nonquery($sql);
		
		if ($this->getCustomerId() != "" && !$this->prenumerationer) {
			$response = file_get_contents(self::GET_PREN_URL . $id);
			$tidningar = explode("\n", $response);
			$this->prenumeration = null;
			foreach($tidningar as $tidning) {
				
				if ($tidning != "") {
					$sql = "INSERT INTO " . self::PREN_TABLE . " (medlem_id, tidning) values(" . $this->getId() . ", '" . Security::secure_data($tidning) . "')";
					$db->nonquery($sql);
				}
			}
		}
	}
	
	public function setPaidUntil($datum)
	{
		
		if (!Misc::isDate($datum, "Y-m-d")) throw new MedlemException('$datum har ett felaktigt format', -18);
		$this->paidUntil = $datum;
	}
	
	public function setFastRuttId($id)
	{
		$this->fastrutt_id = $id;
	}
	
	public function getFastRuttId()
	{
		return $this->fastrutt_id;
	}
	
	public function setPaidUntilByForetag($days)
	{ // OBS: kräver manuell commit()

		
		if ($this->levelId == 0) {
			$this->setPaidUntil(date("Y-m-d"));
		}
		$start = (strtotime($this->paidUntil) > time()) ? strtotime($this->paidUntil) : strtotime(date('Y-m-d'));
		$this->paidUntil = date('Y-m-d', $start + $days * 24 * 60 * 60);
	}
	
	public function setPokalStart($datum)
	{
		Security::demand(USER, $this);
		
		if (!Misc::isDate($datum, "Y-m-d") && $datum != null) throw new MedlemException('$datum har ett felaktigt format', -20);
		$this->pokalStart = $datum;
	}
	
	public function setMotiomeraMailBlock($var)
	{
		/** true or false */
		
		if ($var == 'true') $this->block_mail = 'true';
		elseif ($var == 'false') $this->block_mail = 'false';
	}
	/**
	 * Function getUserAbroadId
	 *
	 * Gets the id for static route if there is one and it is abroad
	 * Oterhwise returns false
	 *
	 * Example:
	 *      getUserAbroadId  (   )
	 */
	
	public function getUserAbroadId()
	{
		global $db;
		$fastrutt_id = $this->fastrutt_id;
		
		if ($fastrutt_id != null) {
			$sql = "SELECT abroad FROM " . Rutt::FASTA_RUTTER_TABLE . " 
				WHERE id = " . $fastrutt_id;

			//echo $sql;
			
			if ($db->value($sql) == 'true') {
				return $fastrutt_id;
			} else {
				return false;
			}
		}
	}
	
	public function getMotiomeraMailBlock()
	{
		return $this->block_mail;
	}
	
	public function setJustNuKommunId($id)
	{
		$this->justNuKommun_id = $id;
	}
	
	public function setOlastaMail($olastaMail)
	{
		$this->olastaMail = $olastaMail;
	}
	
	public function recountOlastaMail()
	{
		$this->olastaMail = MotiomeraMail::getMedlemOlastaMailCount($this);
	}
	
	public function deleteAllProfilDataVal()
	{
		global $USER;
		
		if ((Security::authorized(ADMIN)) or ($USER->getId() == $this->getId())) {
			global $db;
			$sql = "DELETE FROM " . self::PROFILDATAVAL_TABLE . "
			WHERE
				medlem_id = " . $this->getId();
			$db->query($sql);
		}
	}
	
	public function removeAllSteg()
	{ //** Security check before calling this function in actions/delete

		global $db;
		$sql = 'DELETE FROM ' . Steg::TABLE . ' WHERE medlem_id = ' . $this->id;
		$db->query($sql);
	}
	
	public function removeAllStrackor()
	{ //** Security check before calling this function in actions/delete

		global $db;
		$sql = 'DELETE FROM ' . Rutt::TABLE . ' WHERE medlem_id = ' . $this->id;
		$db->query($sql);
	}
	
	public function delete()
	{
		global $USER;
		
		if ((Security::authorized(ADMIN)) or ($USER->getId() == $this->getId())) {
			Adressbok::removeAllMedlemKontakter($this);
			Anslagstavla::deleteAllMemberPosts($this);
			$this->deleteAllProfilDataVal();

			//ta bort alla mail
			MotiomeraMail::removeAllMemberMail($this);
			MotiomeraMail_Folders::deleteMemberFolders($this);

			//mal tabellen verkar oanvänd, nedanstående rad ej testad
			//MalManager::removeAllMedlemMal($this);

			Help::removeAllMedlemAvfardade($this);
			Quiz::removeAllMemberQuizresults($this);
			Fotoalbum::removeAllMedlemFolders($this);

			//lag inte i burk atm, nedanstående rad ej testad
			//Lag::removeMedlemFromAllLag($this);

			/* remove member from foretagsnycklar */

			$foretag = Foretag::loadByMedlem($this);
			
			if (isset($foretag)) {
				$foretag->gaUr($this->getId());
			}

			/* inga matchande klasser för mindre tabeller */
			global $db;
			$tables = array(
				'mm_pokal',
				'mm_medalj',
				'mm_help_medlem_noshow'
			);
			foreach($tables as $table) {
				$sql = 'DELETE FROM ' . $table . ' WHERE medlem_id = "' . $this->getId() . '"';
				$db->nonquery($sql);
			}

			//mm_order, mm_prenumeration töms ej avsiktligen
			//ta bort medlemmen ur grupper (och grupper den skapat)

			$agrupp = Grupp::listByMedlem($this);
			foreach($agrupp as $grupp) {
				
				if ($grupp->getSkapareId() == $this->getId()) {
					$grupp->delete();
				}

				/*else
				$grupp->leaveGrupp($this);*/
			}
			Grupp::flushMemberFromGroups($this);
			Stracka::deleteUserStrackor($this);
			$asteg = Steg::listByMedlem($this);
			foreach($asteg as $steg) {
				$steg->delete();
			}
			FeedItem::deleteAllMedlemFeedItems($this);
			parent::delete();
		}
	}
	
	public function setForetagsnyckel($nyckel)
	{
		global $db;
		$foretag = Foretag::loadByForetagsnyckel($nyckel);
		$foretag->gaMedI($nyckel, $this);
	}
	
	public function setProfilDataVal($profilDataId, $profilDataValId)
	{
		global $db;
		
		if ($profilDataValId == 0) {

			// Radera
			$sql = "DELETE FROM " . self::PROFILDATAVAL_TABLE . "
					WHERE
						medlem_id = '" . $this->getId() . "' AND profilData_id='" . Security::secure_data($profilDataId) . "'";
		} else {

			// Spara
			$sql = "INSERT INTO " . self::PROFILDATAVAL_TABLE . "
						(medlem_id,profilData_id,profilDataVal_id)
					VALUES
						('" . $this->getId() . "','" . Security::secure_data($profilDataId) . "','" . Security::secure_data($profilDataValId) . "')
					ON DUPLICATE KEY UPDATE
						profilDataVal_id='" . Security::secure_data($profilDataValId) . "'";
		}
		$db->query($sql);
	}
	
	public function setProfilDataText($profilDataId, $profilDataText)
	{
		global $db;
		
		if (strlen($profilDataText) == 0) {

			// Radera
			$sql = "DELETE FROM " . self::PROFILDATATEXT_TABLE . "
					WHERE
						medlem_id = '" . $this->getId() . "' AND profilData_id='" . Security::secure_data($profilDataId) . "'";
		} else {

			// Spara
			$sql = "INSERT INTO " . self::PROFILDATATEXT_TABLE . "
						(medlem_id,profilData_id,profilDataText)
					VALUES
						('" . $this->getId() . "','" . Security::secure_data($profilDataId) . "','" . Security::secure_data($profilDataText) . "')
					ON DUPLICATE KEY UPDATE
						profilDataText='" . Security::secure_data($profilDataText) . "'";
		}
		$db->query($sql);
	}
	
	public function setStatus($status)
	{
		Security::demand(USER, $this);
		$status = str_replace(array(
			"<",
			">"
		) , "", $status);
		$this->status = $status;
		new FeedItem("uppdateratstatus", $this->status, $this);
		$this->commit();
	}
	
	public function getStatus()
	{
		
		if ($this->status == "") {
			return null;
		} else {
			return (stripslashes($this->status));
		}
	}
	
	public function sendActivationEmail()
	{
		global $SETTINGS;
		$email = new MMSmarty();
		$email->assign("fnamn", $this->getFNamn());
		$email->assign("enamn", $this->getENamn());
		$email->assign("to", $this->getEpost());
		$key = urlencode(base64_encode(trim($this->getId() . "|" . $this->getEpost())));
		$email->assign("url", $SETTINGS["url"] . "actions/activate.php?q=" . $key);
		$email->assign("key", $key);
		
		if ($this->getEpostBekraftad() == 0) { // Ny användare

			$body = $email->fetch('activationemail.tpl');
		} else {

			// TODO: ny template för gammal användare
			$body = $email->fetch('activationemail.tpl');
		}
		$subject = "Välkommen till Motiomera.se!";
		Misc::sendEmail($this->getEpost() , $SETTINGS["email"], $subject, $body);
	}
	
	public function cleanTempStrackor()
	{
		Stracka::cleanTempStrackor($this);
	}
	
	public function approveTempStrackor()
	{
		Stracka::approveTempStrackor($this);
	}

	// STATIC FUNCTIONS
	
	public function setUsedTrialKonto($mail)
	{
		global $db;
		$sql = "INSERT INTO mm_gratisperiod SET  mail='" . $mail . "'";
		$db->query($sql);
	}
	
	public function usedTrialKonto($mail)
	{
		global $db;
		$sql = "SELECT * FROM mm_gratisperiod WHERE mail='" . $mail . "'";
		$db->value($sql);
		
		if ($db->value($sql) > 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function upptagenEpost($epost)
	{
		global $db;
		$sql = "SELECT epost FROM " . self::classToTable(get_class()) . " WHERE epost = '" . Security::secure_data($epost) . "' ";
		
		if (!$db->value($sql)) {
			return false;
		} else {

			//kollar om epost är aktiverat, om inte så deletar det användaren och skickar tillbaka att det är ok epost
			$sql.= "AND epostBekraftad = 0";
			
			if ($db->value($sql)) {
				$sql2 = "DELETE FROM " . self::classToTable(get_class()) . " WHERE epost = '" . Security::secure_data($epost) . "'";
				$db->query($sql2);
				return false;
			} else {
				return True;
			}
		}
	}
	
	public static function getCurrentIpNr()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
	
	public static function getRawCurrentBrowserVersion()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}
	
	public static function getCurrentBrowserVersion($conciseOutput = false)
	{
		$useragent = self::getRawCurrentBrowserVersion();
		
		if (preg_match('|MSIE ([0-9].[0-9]{1,2})|', $useragent, $matched)) {
			$browser_version = (explode(".", $matched[1]));
			$browser = 'IE' . $browser_version[0];
		} elseif (preg_match('|Opera/([0-9].[0-9]{1,2})|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'Opera' . $browser_version;
		} elseif (preg_match('|Firefox/([0-9\.]+)|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'Firefox' . $browser_version;
		} elseif (preg_match('|Version/([0-9]+\.[0-9]+\.[0-9]+) Safari|', $useragent, $matched)) {
			$browser_version = $matched[1];
			$browser = 'Safari' . $browser_version;
		} else {

			// browser not recognized!
			$browser_version = 0;
			$browser = 'Unknown';
		}
		
		if ($conciseOutput) {
			$temp = explode('.', $browser);
			return $temp[0];
		} else {
			return $browser;
		}
	}
	
	/**
   * Sets and sends a new password to the user
   */ 
	public static function nyttLosen($epost){		
		if (self::ledigEpost($epost)) throw new MedlemException("Inget konto är knuten till e-postadressen", -17);
		$pass = Security::generateCode(8);		
		$msg  = "Hejsan, \n";
    $msg .= "Du bad nyss om ett nytt lösenord för MotioMera, kommer här: $pass Hoppas att du nu ska lyckas logga in på tjänsten igen. \n\n";
    $msg .= "Om du har fler undringar kan du titta i Vanliga frågor på http://www.MotioMera.se för att se om du hittar svaret där. För personlig kundservice och teknisk support: Ring 042-444 30 25 vardagar 09.00-11.30, 13.00-15.00. \n\n";
    $msg .= "Hoppas du får fortsatt glädje av tjänsten. \n";    
    $msg .= "Med vänliga hälsningar \n";
    $msg .= "Tidningen MåBra \n";
    if(Misc::sendEmail($epost, null, "Ditt nya lösenord", $msg)){
		  $medlem = Medlem::loadByEpost($epost);
		  $medlem->setLosenP($pass);
		  $medlem->commit();
    }
	}
	
	/**
	 * This function sets a new password 
	 * it requires logged in as admin&nbsp;
	 *
	 * @param string $pass 
	 * @return void
	 * @author Aller Internet, Kristian Erendi
	 */
	public function newPassword($pass){
	  try {
      Security::demand(ADMIN);
      $this->setLosenP($pass);
      $this->commit();
	  } catch (Exception $e) {
      $e->string = "kunde inte ställa om lösenordet";
      throw $e;
	  }
	}
	
	
	
	public static function ledigtAnvandarnamn($aNamn)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::classToTable(get_class()) . " WHERE aNamn = '" . Security::secure_data($aNamn) . "'";
		
		if ($db->value($sql) > 0) return false;
		else return true;
	}
	
	public static function ledigEpost($epost)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::classToTable(get_class()) . " WHERE epost = '" . Security::secure_data($epost) . "' AND epostBekraftad = 1";
		return ($db->value($sql) == "0") ? true : false;
	}
	
	public static function listAll()
	{
		$arr = parent::lister(get_class() , null, null, "aNamn");
		$ret = array();
		foreach($arr as $medlem) {
			
			if (!empty($medlem)) {
				$ret[] = $medlem;
			}
		}
		return $ret;
	}
	
	public static function listAllInForetag()
	{
		global $db;
		$sql = "SELECT medlem_id FROM " . Foretag::KEY_TABLE . " WHERE medlem_id > 0";
		$ids = $db->valuesAsArray($sql);
		return parent::loadByIds($ids, get_class());
	}
	
	public static function listMedlemmar($offset, $limit, $sort, $search, $way)
	{
		$arr = parent::lister(get_class() , $sort, null, $sort, $offset, $limit, $search, $way);
		$ret = array();
		foreach($arr as $medlem) {
			
			if (!empty($medlem)) {
				$ret[] = $medlem;
			}
		}
		return $ret;
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function loadByIds($id)
	{
		return parent::loadByIds($id, get_class());
	}
	
	public static function loadByEpost($epost)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE epost = '" . $epost . "'";
		try {
			$medlem = Medlem::loadById($db->value($sql));
		}
		catch(MedlemException $e) {
			
			if ($e->getCode() == - 2) throw new MedlemException("E-postadressen kunde inte hittas", -16);
		}
		return $medlem;
	}
	
	public static function loadByUsername($username)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE aNamn = '" . $username . "'";
		try {
			$medlem = Medlem::loadById($db->value($sql));
		}
		catch(MedlemException $e) {
			
			if ($e->getCode() == - 2) throw new MedlemException("Användarnamnet kunde inte hittas", -21);
		}
		return $medlem;
	}
	
	public function loadByJustNuKommun(Kommun $kommun)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE justNuKommun_id = " . $kommun->getId() . " LIMIT 20";
		return self::listByIds($db->valuesAsArray($sql));
	}
	
	public static function listByIds($ids)
	{
		return parent::listByIds(get_class() , $ids);
	}
	
	public static function listMedlemmarById($ids)
	{
		return parent::listByIds(get_class() , $ids);
	}
	
	public static function listMedlemmarNotIn($ids)
	{
		return parent::listByIds(get_class() , $ids, true);
	}

	/*
	Försöker logga in med epost och lösenord.
	Om det lyckas så sparas data om vem som är inloggad, och ett Medlems-objekt returneras
	*/
	
	public static function loggaIn($epost, $losenord, $cookie = false)
	{
		global $db;
		$epost = Security::secure_postdata($epost);
		$losenord = Security::secure_postdata($losenord);
		
		if ($epost == "" || $losenord == "") return false;
		$sql = "SELECT id
				FROM " . self::classToTable(get_class()) . " 
				WHERE epost='$epost'";
		$id = $db->value($sql);
		
		if ($id == "") {
			throw new MedlemException('E-postadressen kunde inte hittas', -13);
		}
		$medlem = Medlem::loadById($id);
		
		if ($medlem->epostBekraftad == 0) throw new MedlemException('Kontot ej aktiverat', -15);
		$losenordKrypterat = Security::encrypt_password($id, $losenord);

		
		if ($losenordKrypterat == $medlem->getLosenord()) { // Lyckad inloggning

			$sessionId = self::generateSessionId();
			$medlem->setSenastInloggad();
			$medlem->setSessionId($sessionId);
			$medlem->commit();
			$_SESSION["mm_mid"] = $id;
			$_SESSION["mm_sid"] = $sessionId;
			
			if ($cookie) {
				setcookie("mm_mid", $id, time() + 60 * 60 * 24 * 30, "/");
				setcookie("mm_sid", $sessionId, time() + 60 * 60 * 24 * 30, "/");
			}

			// if levelId is set (ie, the member used to be a pro), it gets reset to zero, and an exception is thrown (which leads to to the user being redirected to the buy page)
			
			if ($medlem->getPaidUntil() < date("Y-m-d") && $medlem->getLevelId() > 0) {
				$level = $medlem->getLevelId();
				$medlem->setLevelId(0);
				$medlem->commit();
				throw new MedlemException('Medlemskap har gått ut', -19, $level);
			}
			return true;
		} else {
			throw new MedlemException("Felaktigt lösenord", -5);
		}
	}
	/**
	 *	Returnerar medlemsobjektet för den inloggade medlemmen, eller false om besökaren inte är inloggad
	 */
	
	public static function getInloggad()
	{
		
		if (empty($_SESSION["mm_mid"]) && empty($_SESSION["mm_sid"]) && !empty($_COOKIE["mm_mid"]) && !empty($_COOKIE["mm_sid"])) { // försöker hämta från cookie

			$_SESSION["mm_mid"] = $_COOKIE["mm_mid"];
			$_SESSION["mm_sid"] = $_COOKIE["mm_sid"];
		}
		
		if (!empty($_SESSION["mm_mid"])) {
			try {
				$medlem = Medlem::loadById($_SESSION["mm_mid"]);
				
				if ($medlem->getSessionId() == $_SESSION["mm_sid"]) {
					$medlem->setSenastInloggad();
					$medlem->commit();
					return $medlem;
				} else {
					return false;
				}
			}
			catch(Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public static function generateSessionId()
	{
		$letters = "abcdefghijklmnopqrstuvxyz1234567890";
		$result = "";
		for ($i = 0; $i < 80; $i++) {
			$result.= $letters[mt_rand(0, strlen($letters) - 1) ];
		}
		return $result;
	}
	/** used by activation */
	
	public static function verifyValidMemberId($id)
	{
		global $db;
		$sql = 'SELECT * FROM ' . self::TABLE . " WHERE id = " . (int)$id;
		$query = mysql_query($sql);
		return mysql_num_rows($query) > 0 ? 1 : 0;
	}
	/**
	 * Function getAntalMedlemmar
	 *
	 * Returns the number of activated users
	 *
	 * Example:
	 *      getAntalMedlemmar  (  )
	 */
	
	public function getAntalMedlemmar()
	{
		global $db;
		$sql = "SELECT * FROM " . self::TABLE . " 
			WHERE epostBekraftad = 1";
		return $db->nonquery($sql);
	}
	/** used by actions/verifymember.php */
	
	public static function verifyValidUsername($uname)
	{
		$sql = 'SELECT id FROM ' . self::TABLE . ' WHERE aNamn = "' . mysql_real_escape_string($uname) . '" LIMIT 1';
		$qry = mysql_query($sql);
		
		if (mysql_num_rows($qry) < 1) return 0;
		else {
			$target = self::loadByUsername(mysql_real_escape_string($uname));
			return $target->getId();
		}
	}
	
	public static function getMedlemmarFrontpage($antal)
	{
		$profilDatas = ProfilData::listAll();
		$count = 0;
		$valda = array();
		do {
			$count++;
			reset($profilDatas);
			foreach($profilDatas as $key => $profilData) {
				
				if (rand(0, 100) < 10 && (!in_array($profilData, $valda) || $antal > sizeof($profilDatas))) {
					$valda[] = $profilData;
					
					if (sizeof($valda) >= $antal) {
						break;
					}
				}
			}
		}
		while (sizeof($valda) < $antal && $count < 10);
		$ids = array();
		foreach($valda as $key => $profilData) {
			$id = 0;
			$alla_medlemmar = $profilData->getMedlemmar();
			
			if (sizeof($alla_medlemmar) > 0) {
				do {
					$id = $alla_medlemmar[rand(0, sizeof($alla_medlemmar) - 1) ];
				}
				while (false && in_array($id, $ids));
			}
			
			if ($id > 0) {
				$ids[] = $id;
			}
		}
		return self::listByIds($ids);
	}
	/**
	 * Function cacheRssFeeds
	 *
	 * Caches the users rss feeds
	 *
	 * Example:
	 *      cacheRssFeeds  (  )
	 */
	
	public static function cacheRssFeeds()
	{
		global $db;
		$sql = "SELECT id FROM " . self::TABLE . " 	
		WHERE rssUrl != ''";
		$userWithRss = $db->valuesAsArray($sql);

		// print_r($userWithRss);
		unset($sql);
		foreach($userWithRss as $userid) {
			
			if (!empty($userid)) {
				$medlem = Medlem::loadById($userid);
				$latestfeed = $medlem->getRssFeed();

				// print_r($latestfeed);
				foreach($latestfeed as $feed) {
					$cache = $medlem->getLatestCachedRss();

					//print_r($feed);
					//print_r($cache);

					//die();

					
					if (isset($cache) && (strtotime($feed['pubDate']) > strtotime($cache['pubDate']))) {
						$sql = "INSERT INTO " . self::RSS_CACHE_TABLE . " SET 
							medlem_id = " . $medlem->getId() . ", 
							title = '" . mysql_real_escape_string(strip_tags($feed['title'])) . "', 
							description = '" . mysql_real_escape_string(strip_tags($feed['description'], '<img><a><p><embed><object><script>')) . "', 
							pubDate = '" . $feed['pubDate'] . "', 
							link = '" . $feed['link'] . "', 
							commentsLink = '" . $feed['commentsLink'] . "'";

						// echo $sql;
						$db->nonquery($sql);
						$feed = new FeedItem('nyblogg', null, $medlem);
					}
				}
			}
		}
	}
	/**
	 * Function getTavlingMedlemmar
	 *
	 * Returns an array with aNamn and ids and steg
	 *
	 * Example:
	 *     bool getTavlingMedlemmar  ( 2008-11-23,2008-11-24,10,49000,50 )
	 */
	
	public static function getTavlingMedlemmar($startTid, $slutTid, $antal_medlemmar, $antal_steg, $procent_pro)
	{
		global $db;
		$sqlg = "SELECT a.id, a.epost, a.aNamn, a.levelId, SUM(steg) as steg FROM " . self::TABLE . " a, " . Steg::TABLE . " b  
			WHERE a.id = b.medlem_id 
			AND b.datum >= '" . $startTid . "' 
			AND b.datum <= '" . $slutTid . "' ";
		
		if ($procent_pro > 0) {
			$proAntal = ceil($antal_medlemmar * $procent_pro);
			$sqlp = "AND levelId = 1 ";
			$sqlp.= "GROUP BY a.id ";
			$sqlp.= "HAVING SUM(steg) >= '" . $antal_steg . "' ";

			// echo $sqlg.$sqlp;
			$medlemmarPro = $db->allValuesAsArray($sqlg . $sqlp);
			
			if (count($medlemmarPro) > $proAntal) {
				$medlemmarPro = Misc::shuffle_assoc($medlemmarPro, 0);

				// sort($medlemmarPro);
				//print_r($medlemmarPro);

				// die();

				$medlemmarPro = array_slice($medlemmarPro, 0, $proAntal);

				// echo $proAntal;
				
			}
			$sqlg.= "AND levelId = 0 ";
			$sqlg.= "GROUP BY a.id ";
			$sqlg.= "HAVING SUM(steg) >= '" . $antal_steg . "' ";
			$medlemmarGratis = $db->allValuesAsArray($sqlg);
			
			if (count($medlemmarGratis) > ($antal_medlemmar - $proAntal)) {
				$medlemmarGratis = Misc::shuffle_assoc($medlemmarGratis, 0);

				// sort($medlemmarGratis);
				// echo ($antal_medlemmar - $proAntal);

				$medlemmarGratis = array_slice($medlemmarGratis, 0, ($antal_medlemmar - $proAntal));
			}
			$medlemmar = array();
			foreach($medlemmarPro as $value) {
				$medlemmar[] = $value;
			}
			foreach($medlemmarGratis as $value) {
				$medlemmar[] = $value;
			}
		} else {
			$sqlg.= "GROUP BY a.id ";
			$sqlg.= "HAVING SUM(steg) >= '" . $antal_steg . "' ";
			$medlemmar = $db->allValuesAsArray($sqlg);
			
			if (count($medlemmar) > $antal_medlemmar) {
				$medlemmar = Misc::shuffle_assoc($medlemmar, 0);
				$medlemmar = array_slice($medlemmar, 0, $antal_medlemmar);
			}
		}
		return Misc::shuffle_assoc($medlemmar, 0);
	}
}

class MedlemException extends Exception
{
	
	protected $medlem_id;
	
	public function __construct($msg, $code, $medlem_id = null)
	{
		parent::__construct($msg, $code);
		$this->medlem_id = $medlem_id;
	}
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
}
?>
