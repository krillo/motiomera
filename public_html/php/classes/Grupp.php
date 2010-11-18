<?php
/**
 * Klubb
 *
 */
class Grupp extends Mobject
{	
	protected $id; // int	
	protected $namn; // string	
	protected $skapad; // string	
	protected $start; // string	
	protected $skapare_id; // int	
	protected $skapare; // Medlem	
	protected $publik; // int	
	protected $medlemmar = array(); // Array: Medlem	
	protected $antalMedlemmar; // int	
	protected $anslagstavla_id; // int	
	protected $anslagstavla; // Anslagstavla	
	protected $stegTotal; // int	
	protected $fields = array(
		"namn" => "str",
		"skapad" => "str",
		"start" => "str",
		"skapare_id" => "int",
		"anslagstavla_id" => "int",
		"publik" => "int"
	);
	const MIN_LENGTH_NAMN = 4;
	const RELATION_TABLE = "mm_medlemIGrupp";
	const INVITE_TABLE = "mm_gruppinbjudan";
	const TABLE = "mm_grupp";

	// Felkoder
	// -1  $typ måste vara "grupp" eller "lag"
	// -2  $namn är för kort
	// -3  $skapad är inte ett giltigt datum
	// -4  $id måste vara ett heltal
	// -5  $medlem är redan med i gruppen
	// -7  Inga rättigheter
	// -8  Okänd medlemsstatus
	// -9  $medlem har redan ansökt om medlemskap
	// -10 Gruppens ägare måste vara medlem i gruppen
	// -11 E-postadressen finns redan
	// -12 Namnet är upptaget
	// -13 Felaktig inbjudan
	// -14 Denna e-post har redan blivit inbjuden till den här klubben
	// -15 Ogiltigt datum

	
	public function __construct($namn, $publik, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			global $USER;
			$this->setNamn($namn);
			$this->setSkapare($USER);
			$this->setSkapad(date("Y-m-d"));
			$this->setPublik($publik);
			$this->anslagstavla_id = 0;
			$this->commit();
			$this->joinGrupp($USER); // Ägaren går automatiskt med i gruppen
			$this->setAnslagstavla(new Anslagstavla($this->id, 0));
			$this->commit();
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, true);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function listInbjudningsbaraGrupper(Medlem $medlem)
	{
		global $db, $USER;
		
		if (isset($USER)) {
			$sql = "SELECT id FROM " . self::TABLE . " WHERE skapare_id = " . $USER->getId() . " AND id NOT IN (SELECT grupp_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId() . ")";
			return self::listByIds($db->valuesAsArray($sql));
		}
	}
	
	public static function decodeInvite($invite)
	{
		global $db;
		$code = base64_decode(urldecode($invite));
		$sql = "SELECT grupp_id, epost FROM " . self::INVITE_TABLE . " WHERE id = '" . $code . "'";
		$row = $db->row($sql);
		
		if (!$row) throw new GruppException("Felaktig inbjudan", -13);
		return array(
			"grupp_id" => $row["grupp_id"],
			"epost" => $row["epost"],
			"id" => $code
		);
	}
	
	public static function clearInvite($inv)
	{
		global $db;
		$sql = "DELETE FROM " . self::INVITE_TABLE . " WHERE id = '" . $inv["id"] . "'";
		$db->nonquery($sql);
	}
	
	public static function settleInvite($inv, Medlem $medlem)
	{
		global $db;
		$src = self::decodeInvite($inv);
		$sql = "INSERT INTO " . self::RELATION_TABLE . " (medlem_id, grupp_id, datum, godkannd_skapare, godkannd_medlem, ignorerad) values (" . $medlem->getId() . ", " . $src["grupp_id"] . ", '" . date("Y-m-d H:i:s") . "', 1, 1, 0)";
		$db->nonquery($sql);
		$medlem->setEpostBekraftad(1);
		$medlem->commit();
		self::clearInvite($src);
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function loadByIds($ids)
	{
		return parent::loadByIds($ids, get_class());
	}
	
	public static function listByIds($ids)
	{
		return parent::listByIds(get_class() , $ids);
	}
	
	public static function listByMedlem(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT grupp_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND godkannd_medlem = 1 AND godkannd_skapare = 1";
		return self::listByIds($db->valuesAsArray($sql));
	}
	
	public static function ledigtNamn($namn)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::classToTable(get_class()) . " WHERE namn = '$namn'";
		return ($db->value($sql) == "0") ? true : false;
	}

	// PRIVATE FUNCTIONS //////////////////////////////////////
	
	private function settleGaMedIGrupp(Medlem $medlem)
	{
		global $db;
		$sql = "UPDATE " . self::RELATION_TABLE . " SET godkannd_skapare = 1, godkannd_medlem = 1 WHERE grupp_id = " . $this->getId() . " AND medlem_id = " . $medlem->getId();
		$db->nonquery($sql);
		new FeedItem("gattmedigrupp", null, $medlem, $this);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function getStegTotal()
	{
		
		if (!$this->stegTotal) {
			$this->stegTotal = Steg::getStegTotalGrupp($this);
		}
		return $this->stegTotal;
	}
	
	public function getStegSnittByDay($day)
	{
		global $db;
		$tmstp = (time() - ($day * -86400));
		$datum = date("Y-m-d", $tmstp);
		$slut = $datum;
		$sql = "
			SELECT sum(a.steg) as steg 
			FROM " . Steg::TABLE . " a, " . self::TABLE . " b, " . self::RELATION_TABLE . " c
			WHERE a.medlem_id = c.medlem_id
			AND b.id = ". $this->getId() ." 
			AND b.id = c.grupp_id
			AND a.datum = '" . $slut . "'
		";
		// echo $sql;
		$steg = $db->value($sql);
		$medlemmar = count($this->listMedlemmar());
		
		if ($steg != 0 && $medlemmar != 0 && $slut > $this->getStart()) {
			return round($steg / $medlemmar);
		} else {
			return 0;
		}
	}
	
	public function bjudInEpost($epost)
	{
		global $db, $SETTINGS, $urlHandler;
		$sql = "DELETE FROM " . self::INVITE_TABLE . " WHERE epost = '$epost' AND grupp_id = " . $this->getId();
		$db->nonquery($sql);
		
		if (!Medlem::ledigEpost($epost)) throw new GruppException("E-postadressen finns redan", $epost." finns redan som registrerad användare");
		$code = Security::generateCode(10);
		$sql = "INSERT INTO " . self::INVITE_TABLE . " values ('" . $code . "', " . $this->getId() . ", '$epost', '" . date("Y-m-d H:i:s") . "');";
		$db->nonquery($sql);
		
		if ($this->getSkapare()->getAtkomst() == "alla") {
			$medlemLink = '<a href="' . substr($SETTINGS["url"], 0, -1) . $urlHandler->getUrl("Medlem", URL_VIEW, $this->getSkapare()->getId()) . '">' . $this->getSkapare()->getANamn() . '</a>';
		} else {
			$medlemLink = $this->getSkapare()->getANamn();
		}

		// ej HTML längre:
		$medlemLink = $this->getSkapare()->getANamn();
		$subject = $this->getSkapare()->getANamn() . " har bjudit in dig till Motiomera.se!";
		$body = "MotioMera-medlemmen " . $this->getSkapare()->getANamn() . " har bjudit in dig till att gå med i sin klubb " . $this->getNamn() . " med startdatum " . $this->getStart() . ". MotioMera är en stegtävling och det är gratis att gå med.

För att bli medlem i MotioMera och gå med i klubben, klicka på länken nedan eller kopiera den och klistra in den i adressfältet i din webbläsare:

" . $SETTINGS["url"] . $urlHandler->getUrl("Medlem", URL_INVITE, $this->encodeInvite($code));

		/*$body = $medlemLink . " har bjudin in dig till att gå med i sin klubb, " . $this->getNamn() . " som startades ". date("Y-m-d") .'.
		
		För att bli medlem och gå med i klubben, klicka på länken nedan eller kopiera den och klistra in den i adressfältet i din webbläsare:
		
		'. substr($SETTINGS["url"], 0, -1) . $urlHandler->getUrl("Medlem", URL_INVITE, $this->encodeInvite($code));*/
		try {
			Misc::sendEmail($epost, null, $subject, $body);
		}
		catch(MiscException $e) {
			$sql = "DELETE FROM " . self::INVITE_TABLE . " WHERE epost = '$epost' AND grupp_id = " . $this->getId();
			$db->nonquery($sql);
			throw $e;
		}
		return true;
	}
	
	public function encodeInvite($code)
	{
		return urlencode(base64_encode($code));
	}
	
	public function medlemSedan(Meldem $medlem)
	{
		global $db;
		$sql = "SELECT datum FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId() . " && medlem_id = " . $medlem->getId();
		return $db->value($sql);
	}
	
	public function commit()
	{
		global $USER, $ADMIN;
		
		if ($this->getSkapareId() != $USER->getId() && !isset($ADMIN)) {
			throw new UserException("Ej tillåtet", "Endast gruppens skapare kan redigera gruppen");
		}
		parent::commit();
	}
	
	public function acceptRequest($medlem)
	{
		global $urlHandler;
		$this->settleGaMedIGrupp($medlem);
		$subject = "Grattis!";
		$msg = "<p>Ditt medlemsskap i klubben " . $this->getNamn() ." har nu godkänts av klubbens grundare. <a href=\"".$urlHandler->getUrl('Grupp', URL_VIEW, $this->getId())."\">Klicka här</a> för att gå till klubben sida.</p>Hälsn MotioMera-teamet ";
		$internmail = new MotiomeraMail($subject, $msg, $this->getSkapareId() , $medlem->getId() , date("Y-m-d H:i:s") , 0, 0, 1);
	}
	
	public function denyRequest($medlem)
	{
		global $USER, $db;
		
		if ($USER->getId() != $this->getSkapareId()) {
			throw new GruppException('Inloggad medlem är inte gruppens skapare');
		}
		
		$sql = "UPDATE " . self::RELATION_TABLE . " SET ignorerad = 1 WHERE grupp_id = " . $this->getId() . " AND medlem_id = " . $medlem->getId();
		$db->nonquery($sql);
		
		$subject = "Hejsan";
		$msg = "<p>Tyvärr har din ansökan om medlemsskap i klubben " . $this->getNamn() . " inte godkänts av klubbens grundare.</p>Hälsn MotioMera-teamet";
		$internmail = new MotiomeraMail($subject, $msg, $this->getSkapareId() , $medlem->getId() , date("Y-m-d H:i:s") , 0, 0, 1);
	}
	
	public function isValidGroupId($id)
	{
		$grp = parent::lister(get_class() , "id", $id);
		
		if (count($grp) > 0) return true;
		else return false;
	}
	
	/**
	 * Function unignore
	 * Makes a previously ignored applicant a member of the group
	 * $param Medlem $medlem
	 */
	public function unignore($medlem)
	{
		global $db, $USER;
		
		// Only owner of the club can do this
		if($this->getSkapareId() == $USER->getId()) {
			
			$sql = "UPDATE " . self::RELATION_TABLE . " SET ignorerad = 0 WHERE grupp_id = " . $this->getId() . " AND medlem_id = " . $medlem->getId();
			$db->nonquery($sql);
			
			$this->acceptRequest($medlem);
		}
		
	}
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function listPublic()
	{
		return parent::lister(get_class() , "publik", "1");
	}
	
	public static function listJoinedGroups(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT grupp_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND godkannd_medlem = 1 AND godkannd_skapare = 1";
		$ids = $db->valuesAsArray($sql);
		return self::listByIds($ids);
	}
	
	public static function listCreatedGroups(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE skapare_id = " . $medlem->getId();
		$ids = $db->valuesAsArray($sql);
		return self::listByIds($ids);
	}
	
	public function removeMailInvites()
	{
		global $db;
		$sql = "DELETE FROM " . self::INVITE_TABLE . " WHERE grupp_id = '" . $this->getId() . "'";
		$db->nonquery($sql);
	}
	
	private function deleteGroup()
	{
		$this->removeMailInvites();
		Anslagstavla::deleteAllGruppPosts($this);
		FeedItem::deleteAllGruppFeedItems($this);

		//medlemskap i kluben (mm_medlemIGrupp)
		global $db;
		$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId();
		$db->query($sql);
		parent::delete();
	}
	
	public function delete()
	{
		global $USER;		
		if (!Security::authorized(ADMIN)) {			
			if (isset($USER)) {				
				if ($USER->getId() != $this->getSkapareId()) throw new GruppException("Ej tillåtet, endast gruppens ägare kan radera gruppen", -30);
				else {
					self::deleteGroup();
				}
			}
		} else {
			self::deleteGroup();
		}
	}

	// SETTERS & GETTERS ////////////////////////////////////////
	
	public function getNamn()
	{
		return $this->namn;
	}
	
	public function getSkapad()
	{
		return $this->skapad;
	}
	
	public function getStart()
	{
		
		if ($this->start == "" || $this->start == "0000-00-00") return null;
		else return $this->start;
	}
	
	public function getSkapareId()
	{
		return $this->skapare_id;
	}
	
	public function getSkapare()
	{
		if (!$this->skapare) $this->skapare = Medlem::loadById($this->skapare_id);
		return $this->skapare;
	}
	
	public function getAntalMedlemmar()
	{		
		if (empty($this->antalMedlemmar)) {
			global $db;
			$sql = "SELECT count(*) FROM " . self::RELATION_TABLE . " WHERE godkannd_medlem = 1 AND godkannd_skapare = 1 AND grupp_id = " . $this->getId();
			return $db->value($sql);
		}
		return $this->antalMedlemmar;
	}
	
	public function listMedlemmar($alla = false)
	{		
		if ($this->medlemmar == null || $alla) {
			global $db;
			$sql = "SELECT medlem_id FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId();
			
			if (!$alla) {
				$sql.= " AND godkannd_skapare = 1 AND godkannd_medlem = 1";
			}
			$ids = $db->valuesAsArray($sql);
			$this->medlemmar = Medlem::listByIds($ids);
		}
		return $this->medlemmar;
	}
	
	public function getPublik()
	{
		return $this->publik;
	}
	
	public function getAnslagstavlaId()
	{
		return $this->anslagstavla_id;
	}
	
	public function getAnslagstavla()
	{		
		if (empty($this->anslagstavla)) {
			$this->anslagstavla = Anslagstavla::loadById($this->getAnslagstavlaId());
		}
		return $this->anslagstavla;
	}
	
	public function setNamn($namn)
	{		
		if (strlen($namn) < self::MIN_LENGTH_NAMN) throw new GruppException('$namn är för kort', -2);
		
		if (!self::ledigtNamn($namn)) throw new GruppException('Namnet är upptaget', -12);
		$this->namn = $namn;
	}
	
	public function setSkapad($skapad)
	{		
		if (!Misc::isDate($skapad, "Y-m-d")) throw new GruppException('$skapad är inte ett giltigt datum', -3);
		$this->skapad = $skapad;
	}
	
	public function setStart($start)
	{		
		if (empty($start)) {
			$start = date('Y-m-d');
		}		
		if (!Misc::isDate($start, "Y-m-d")) {
			throw new GruppException('Ogiltigt datum: ' . $start, -15);
		}
		$this->start = $start;
	}
	
	public function setSkapareId($id)
	{
		
		if (!Misc::isInt($id)) throw new GruppException('$id måste vara ett heltal', -4);
		$this->skapare_id = $id;
		unset($this->skapare);
	}
	
	public function setSkapare(Medlem $medlem)
	{
		$this->skapare = $medlem;
		$this->skapare_id = $medlem->getId();
	}
	
	public function setPublik($status)
	{
		$this->publik = ($status) ? 1 : 0;
	}
	
	public function setAnslagstavlaId($id)
	{		
		if (!Misc::isInt($id)) throw new GruppException('$id måste vara ett heltal', -4);
		// TODO: lägg in kontroll så att man ej kan byta anslagstavla
		$this->anslagstavla_id = $id;
	}
	
	public function setAnslagstavla($anslagstavla)
	{
		// TODO: lägg in kontroll så att man ej kan byta anslagstavla om den redan finns
		$this->anslagstavla = $anslagstavla;
		$this->setAnslagstavlaId($this->anslagstavla->getId());
	}
	
	public static function listAllNames()
	{
		global $db;
		$sql = "SELECT id, namn FROM " . self::classToTable(get_class());
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_assoc($res)) {
			$result[$data["id"]] = $data["namn"];
		}
		return $result;
	}
	
	public static function listBySkapare($medlem)
	{
		global $db;
		$sql = "SELECT id FROM " . self::classToTable(get_class()) . " WHERE skapare_id = " . $medlem->getId();
		$ids = $db->valuesAsArray($sql);
		return self::loadByIds($ids);
	}
	
	public function joinGrupp(Medlem $medlem, $ownMsg = null)
	{
		global $db, $urlHandler;
		$medlemStatus = $this->medlemStatus($medlem);
		
		if ($medlemStatus != "notmember" && $medlemStatus != "requestignored") throw new GruppException('$medlem har redan ansökt om medlemskap', -9);
		$status = ($medlem->getId() == $this->getSkapareId()) ? 1 : 0;
		$sql = "INSERT INTO " . self::RELATION_TABLE . " (medlem_id, grupp_id, datum, godkannd_skapare, godkannd_medlem) values (
			" . $medlem->getId() . ",
			" . $this->getId() . ",
			'" . date("Y-m-d H:i:s") . "',
			$status,
			1
		)";
		$db->nonquery($sql);
		$subject = "Har ansökt medlemsskap";
		$msg = "<p>" . $medlem->getAnamn() . " har ansökt om medlemsskap i din klubb " . $this->getNamn() . ". Du kan godkänna eller neka ansökan på <a href=\"" . $urlHandler->getUrl('Grupp', URL_EDIT, $this->getId()) . "\">Hantera Grupp</a></p>";
		
		if (isset($ownMsg)) {
			$msg.= $ownMsg;
		}
		
		if ($medlem->getId() != $this->getSkapareId()) {
			$internmail = new MotiomeraMail($subject, $msg, $medlem->getId() , $this->getSkapareId() , date("Y-m-d H:i:s") , 0, 0, 1);
		}
	}
	
	public function invite(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT id FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId() . " AND medlem_id = " . $medlem->getId();
		
		if ($db->value($sql) != "") throw new GruppException('$medlem är redan med i grupp', -5);
		$sql = "INSERT INTO " . self::RELATION_TABLE . " (medlem_id, grupp_id, datum, godkannd_skapare, godkannd_medlem) values (
			" . $medlem->getId() . ",
			" . $this->getId() . ",
			'" . date("Y-m-d H:i:s") . "',
			1,
			0
		)";
		$db->nonquery($sql);
		$msg = "Du har blivit inbjuden till klubben " . $this->getNamn() . ". \n";
		$msg.= '<a href="/actions/answerinvite.php?gid=' . $this->getId() . '&amp;do=accept">Gå med i ' . $this->getNamn() . '</a>';
		new MotiomeraMail("Inbjudan till klubben " . $this->getNamn() , $msg, $this->getSkapare()->getId() , $medlem->getId() , date("Y-m-d H:i:s") , 0, 0, 1);
	}
	
	public function inviteByEmail($epostadresser)
	{
		$epostadresser = explode(',', $epostadresser);
		$count = 0;
		foreach($epostadresser as $epostadress) {
			$epostadress = trim($epostadress);
			
			if ($this->bjudInEpost($epostadress)) {
				$count++;
			}
		}
		return $count ? $count : false;
	}
	
	public function medlemStatus($medlem)
	{
		global $db;
		$sql = "SELECT * FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND grupp_id = " . $this->getId();
		$row = $db->row($sql);
		
		if ($row == null) {
			return "notmember";
		} else 
		if ($row["godkannd_medlem"] == 1 && $row["godkannd_skapare"] == 1) {
			return "ismember";
		} else 
		if ($row["godkannd_medlem"] == 1 && $row["godkannd_skapare"] == 0) {
			return "requested";
		} else 
		if ($row["godkannd_medlem"] == 0 && $row["godkannd_skapare"] == 1) {
			return "invited";
		} else 
		if ($row["godkannd_medlem"] == 0 && $row["godkannd_skapare"] == 1 && $row["ignorerad"] == 1) {
			return "inviteignored";
		} else 
		if ($row["godkannd_medlem"] == 1 && $row["godkannd_skapare"] == 0 && $row["ignorerad"] == 1) {
			return "requestignored";
		} else {
			throw new GruppException("Okänd medlemsstatus", -8);
		}
	}
	
	public static function flushMemberFromGroups(Medlem $medlem)
	{
		
		if ((Security::authorized(ADMIN)) or (Security::authorized(USER, $medlem))) {
			global $db;
			$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId();
			$db->nonquery($sql);
		}
	}
	
	public function leaveGrupp($medlem = null)
	{
		global $USER, $db;
		
		if ($medlem == null) {
			Security::demand(USER);
			$medlem = $USER;
		} else {
			Security::demand(USER, $this->getSkapare());
		}
		
		if ($medlem->getId() == $this->getSkapare()->getId()) throw new GruppException("Gruppens ägare måste vara medlem i gruppen", -10);
		$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND grupp_id = " . $this->getId() . " LIMIT 1";
		$db->nonquery($sql);
		new FeedItem("lamnatgrupp", null, $medlem, $this);
	}
	
	public function isInvitable(Medlem $medlem)
	{
		$status = $this->medlemStatus($medlem);
		
		if ($status == "notmember" || $status == "requestignored") return true;
		else return false;
	}
	
	public function isRequestable(Medlem $medlem)
	{
		$status = $this->medlemStatus($medlem);
		
		if ($status == "notmember" || $status == "inviteignored") return true;
		else return false;
	}
	
	public function isMember(Medlem $medlem)
	{
		$status = $this->medlemStatus($medlem);
		
		if ($status == "ismember") return true;
		else return false;
	}
	
	public function listInvitable()
	{
		global $db;
		$sql = "SELECT medlem_id FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId();
		$ids = $db->valuesAsArray($sql);
		
		if (count($ids) > 0) return Medlem::listMedlemmarNotIn($ids);
		else return null;
	}
	
	public function listRequests()
	{
		global $db;
		$sql = "SELECT medlem_id FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId() . " AND godkannd_skapare = 0 AND godkannd_medlem = 1 AND ignorerad = 0";
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_array($res)) {
			try {
				$result[$data["medlem_id"]] = Medlem::loadById($data["medlem_id"]);
			}
			catch(Exception $e) {

				// medlemmen finns ej, ignorera
				
			}
		}
		return $result;
	}
	
	public function listInvited()
	{
		global $db;
		$sql = "SELECT medlem_id FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId() . " AND godkannd_skapare = 1 AND godkannd_medlem = 0 AND ignorerad = 0";
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_array($res)) {
			try {
				$result[$data["medlem_id"]] = Medlem::loadById($data["medlem_id"]);
			}
			catch(Exception $e) {

				// medlemmen finns ej, ignorera
				
			}
		}
		return $result;
	}
	
	public function listIgnored()
	{
		global $db;
		$sql = "SELECT medlem_id FROM " . self::RELATION_TABLE . " WHERE grupp_id = " . $this->getId() . " AND godkannd_skapare = 0 AND ignorerad = 1";
		$ids = $db->valuesAsArray($sql);
		
		if (count($ids) > 0) return Medlem::listMedlemmarById($ids);
		else return null;
	}
	
	public static function listInvites(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT grupp_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId() . " AND godkannd_medlem = 0 AND ignorerad = 0";
		$res = $db->query($sql);
		$result = array();
		while ($data = mysql_fetch_array($res)) {
			$result[$data["grupp_id"]] = Grupp::loadById($data["grupp_id"]);
		}
		return $result;
	}
	
	public function acceptInvite(Medlem $medlem)
	{
		$this->settleGaMedIGrupp($medlem);
	}
	
	public function denyInvite(Medlem $medlem)
	{
		global $db;
		$sql = "UPDATE " . self::RELATION_TABLE . " SET ignorerad = 1 WHERE medlem_id = " . $medlem->getId() . " AND grupp_id = " . $this->getId();
		$db->nonquery($sql);
	}
}

class GruppException extends UserException
{
}
?>
