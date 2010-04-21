<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - loadByMedlem()
* - addKontakt()
* - removeKontakt()
* - removeAllMedlemKontakter()
* - isKontakt()
* - listForfragningar()
* - clearForfragningar()
* - getMedlem()
* - listKontakterId()
* - listKontakter()
* - setMedlem()
* Classes list:
* - Adressbok
* - AdressbokException extends Exception
*/

class Adressbok
{
	
	protected $medlem;
	
	protected $kontakter = array();
	
	protected $kontakter_id = array();
	const RELATION_TABLE = "mm_kontakt";

	// Felkoder
	// -1 $medlem finns redan bland kontakter

	// -2 Du kan inte läga till dig själv

	const FF_OLASTA = 8;
	const FF_LASTA = 16;
	const FF_ALLA = 32;
	
	public function __construct(Medlem $medlem)
	{
		$this->setMedlem($medlem);
	}

	// STATIC FUNCTIONS ///////////////////////////////////////
	
	public static function loadByMedlem(Medlem $medlem)
	{
		return new Adressbok($medlem);
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function addKontakt(Medlem $medlem)
	{
		global $db;
		
		if ($this->isKontakt($medlem)) throw new AdressbokException('$medlem finns redan bland kontakter', -1);
		
		if ($this->getMedlem()->getId() == $medlem->getId()) throw new AdressbokException('Du kan inte läga till dig själv', -2);
		$sql = "INSERT INTO " . self::RELATION_TABLE . " (medlem_id, kontakt_id, datum) values (" . $this->getMedlem()->getId() . ", " . $medlem->getId() . ", '" . date('Y-m-d H:i:s') . "')";
		$db->nonquery($sql);
		new FeedItem("lagttilliadressbok", array(
			$medlem->getId() ,
			$medlem->getANamn()
		) , $this->getMedlem());
	}
	
	public function removeKontakt(Medlem $medlem)
	{
		global $db;
		Security::demand(USER, $this->getMedlem());
		$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $this->getMedlem()->getId() . " AND kontakt_id = " . $medlem->getId();
		$db->nonquery($sql);
	}
	
	public function removeAllMedlemKontakter(Medlem $medlem)
	{
		global $db;
		
		if ((Security::authorized(ADMIN)) or (Security::authorized(USER, $medlem))) {
			$sql = "DELETE FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $medlem->getId() . " OR kontakt_id = " . $medlem->getId();
			$db->nonquery($sql);
		}
	}
	
	public function isKontakt(Medlem $medlem)
	{
		global $db;
		$sql = "SELECT count(*) FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $this->getMedlem()->getId() . " AND kontakt_id = " . $medlem->getId();
		
		if ($db->value($sql) != 0) return true;
		else return false;
	}
	
	public function listForfragningar($typ = self::FF_OLASTA)
	{
		global $db;
		$sql = "SELECT medlem_id FROM " . self::RELATION_TABLE . " WHERE kontakt_id = " . $this->getMedlem()->getId() . " AND medlem_id NOT IN (SELECT kontakt_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $this->getMedlem()->getId() . ")";
		
		if ($typ == self::FF_OLASTA) $sql.= " AND last = 0";
		else 
		if ($typ == self::FF_LASTA) $sql.= " AND last = 1";
		return Medlem::listByIds($db->valuesAsArray($sql));
	}
	
	public function clearForfragningar()
	{
		global $db;
		$sql = "UPDATE " . self::RELATION_TABLE . " SET last = 1 WHERE kontakt_id = " . $this->getMedlem()->getId();
		$db->nonquery($sql);
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getMedlem()
	{
		return $this->medlem;
	}
	
	public function listKontakterId()
	{
		
		if (count($this->kontakter_id) == 0) {
			global $db;
			$sql = "SELECT kontakt_id FROM " . self::RELATION_TABLE . " WHERE medlem_id = " . $this->getMedlem()->getId();
			$this->kontakter_id = $db->valuesAsArray($sql);
		}
		return $this->kontakter_id;
	}
	
	public function listKontakter()
	{
		
		if (count($this->kontakter) == 0) {
			foreach($this->listKontakterId() as $kontakt_id) {
				try {
					$this->kontakter[] = Medlem::loadById($kontakt_id);
				}
				catch(Exception $e) {

					// medlemmen finns inte, ignorera
					
				}
			}
		}
		return $this->kontakter;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
	}
}

class AdressbokException extends Exception
{
}
?>
