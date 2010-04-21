<?php

/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listAll()
* - loadById()
* - loadByMedlem()
* - removeAllMedlemFolders()
* - listAsArray()
* - loadBilder()
* - harGruppTilltrade()
* - harMedlemTilltrade()
* - harForetagTilltrade()
* - delete()
* - isAgare()
* - addTilltradeGrupp()
* - getTilltradesGrupper()
* - getNamn()
* - getBeskrivning()
* - setTillagd()
* - getTillagd()
* - getMedlemId()
* - getMedlem()
* - setNamn()
* - getTilltrade()
* - setTilltrade()
* - getTilltradeForetag()
* - setTilltradeForetag()
* - getTilltradeAllaGrupper()
* - setTilltradeAllaGrupper()
* - setBeskrivning()
* Classes list:
* - Fotoalbum extends Mobject
* - FotoalbumException extends Exception
*/
/*
sql:

drop table if exists mm_fotoalbum;
create table mm_fotoalbum (
id int unsigned not null primary key auto_increment,
medlem_id int unsigned not null,

namn varchar(200) not null,
beskrivning text,
tilltrade enum('alla', 'vissa') not null default 'alla',
tilltrade_alla_grupper enum('ja', 'nej') not null default 'nej',
tilltrade_foretag enum('ja', 'nej') not null default 'nej',

tillagd datetime not null,

serialize blob
);

drop table if exists mm_fotoalbumGruppAcl;
create table mm_fotoalbumGruppAcl (
fotoalbum_id int unsigned not null,
grupp_id int unsigned not null,

unique index (fotoalbum_id, grupp_id)
);

drop table if exists mm_fotoalbumForetagAcl;
create table mm_fotoalbumForetagAcl (
fotoalbum_id int unsigned not null,
foretag_id int unsigned not null,

unique index (fotoalbum_id, foretag_id)
);

drop table if exists mm_fotoalbumbild;
create table mm_fotoalbumbild (
id int unsigned not null primary key auto_increment,
fotoalbum_id int unsigned not null,
medlem_id int unsigned not null,

namn varchar(200),
beskrivning text,

bredd_stor smallint unsigned not null,
hojd_stor smallint unsigned not null,
bredd_liten smallint unsigned not null,
hojd_liten smallint unsigned not null,
bredd_mini tinyint unsigned not null,
hojd_mini tinyint unsigned not null,

tillagd datetime not null,

serialize blob
);

*/

class Fotoalbum extends Mobject
{
	
	protected $medlem;
	protected $id; // int
	protected $medlem_id; // int
	protected $namn; // string
	protected $beskrivning; // string
	protected $tillagd; // datetime
	protected $tilltrade; // enum (alla/vissa)
	protected $tilltrade_foretag; // enum (ja/nej)
	protected $tilltrade_alla_grupper; // enum (ja/nej)
	
	protected $fields = array(
		"id" => "int",
		"medlem_id" => "int",
		"namn" => "str",
		"beskrivning" => "str",
		"tilltrade" => "str",
		"tilltrade_foretag" => "str",
		"tilltrade_alla_grupper" => "str",
		"tillagd" => "date",
	);

	// Felkoder
	// -1 Inget namn angivet

	
	public function __construct($data, $medlem = null, $dummy_object = false)
	{
		
		if (!$dummy_object) {
			global $USER;
			
			if ($medlem == null) Security::demand(USER);
			
			if (empty($data["namn"])) {
				throw new FotoalbumException("Inget namn angivet", -1);
			} else {

				// se om "Alla grupper" har tillgång till detta album
				$tilltrade_alla_grupper = "nej";
				
				if (isset($data["tilltrade_grupper"])) {

					//ändrat från for() av magnus 18/8
					foreach($data["tilltrade_grupper"] as $grupp => $value) {
						
						if ($grupp == "alla") {
							$tilltrade_alla_grupper = "ja";
							unset($grupp);
						}
					}

					//for($x=0;$x<count($data["tilltrade_grupp"]);$x++) {
					//	if ($data["tilltrade_grupp"][$x] == "alla") {

					//		$tilltrade_alla_grupper = "ja";

					//		unset($data["tilltrade_grupp"][$x]);

					//	}

					//}

					
				}

				// Skapa nytt fotoalbum
				// EDIT by oskar: om argument $medlem finns används detta istället för $USER (används vid skapande av ny medlem)

				$this->medlem_id = ($medlem) ? $medlem->getId() : $USER->getId();
				$this->setNamn($data["namn"]);
				$this->setBeskrivning($data["beskrivning"]);
				$this->setTilltrade($data["tilltrade"]);
				
				if (isset($data["tilltrade_foretag"])) {
					$this->setTilltradeForetag($data["tilltrade_foretag"]);
				}
				$this->setTilltradeAllaGrupper($tilltrade_alla_grupper);
				$this->setTillagd(date("Y-m-d H:i:s"));
				$this->commit();
				
				if (isset($data["tilltrade_grupper"])) {
					foreach($data["tilltrade_grupper"] as $grupp => $value) { //ändrat från for() av magnus 18/8

						$this->addTilltradeGrupp($grupp);
					}
				}
			}
		}
	}
	
	public static function __getEmptyObject()
	{
		$class = get_class();
		return new $class(null, null, true);
	}

	// STATIC FUNCTION ////////////////////////////////////////
	
	public static function listAll()
	{
		return parent::lister(get_class());
	}
	
	public static function loadById($id)
	{
		return parent::loadById($id, get_class());
	}
	
	public static function loadByMedlem(Medlem $medlem)
	{
		$medlem_id = $medlem->getId();
		return parent::lister(get_class() , "medlem_id", $medlem_id, "tillagd");
	}
	
	public static function removeAllMedlemFolders(Medlem $medlem)
	{
		$folders = self::loadByMedlem($medlem);
		foreach($folders as $folder) $folder->delete();
	}

	// Hämta alla fotoalbum
	
	public static function listAsArray(Medlem $medlem, $order_by = "namn ASC")
	{
		global $db;
		$medlem_id = $medlem->getId();
		$sql = $db->query("	SELECT
								*
							FROM
								mm_fotoalbum
							WHERE
								medlem_id = '" . $medlem_id . "'
							ORDER BY
								$order_by
		");
		$fotoalbum = array();
		while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			foreach ($row as $field => $value) {
				$row[$field] = stripslashes($value);
			}
			$fotoalbum[] = $row;
		}
		return $fotoalbum;
	}

	// Hämta alla bilder som tillhör fotoalbumet
	
	public static function loadBilder($fotoalbum_id) {
		$album = self::loadById($fotoalbum_id);
		$member = Medlem::loadById($album->medlem_id);
		if(!empty($album)) {
			return FotoalbumBild::listAsArray($member, $fotoalbum_id);
		}
		//return parent::lister("fotoalbumbild", "fotoalbum_id", $fotoalbum_id, "namn");
	}
	
	public static function harGruppTilltrade($grupp_id, $fotoalbum_id)
	{
		global $db;
		$sql = $db->query("SELECT * FROM mm_fotoalbumGruppAcl WHERE grupp_id = " . Security::secure_data($grupp_id) . " AND fotoalbum_id = " . $fotoalbum_id);
		
		if (mysql_num_rows($sql) > 0) {
			return true;
		} else {
			return false;
		}
	}

	// PUBLIC FUNCTIONS ///////////////////////////////////////
	
	public function harMedlemTilltrade($medlem)
	{
		
		if (Security::authorized(USER) == false) {

			// icke inloggad besökare
			
			if ($this->getTilltrade() == "alla") {
				return true;
			} else {
				return false;
			}
		}

		// alla har tillträde
		
		if ($this->getTilltrade() == "alla") return true;

		// ägare har självklart tilltrade till sina egna album
		
		if ($this->isAgare()) return true;

		// gå igenom användarens grupper och se om nån av dem har direkt tillträde
		$grupper = Grupp::listByMedlem($medlem);
		
		if ($grupper != null) {
			foreach($grupper as $grupp) {
				
				if ($this->harGruppTilltrade($grupp->getId() , $this->getId()) == true) {

					// tillträde via grupp
					return true;
				}
			}
		}

		// om ALLA grupper har tillgång,
		// hämta alla användarens grupper OCH ägarens grupper och se om de båda är medlemmar i samma grupp

		
		if ($this->getTilltradeAllaGrupper() == "ja") {
			$agare_grupper = Grupp::listByMedlem($this->getMedlem());
			
			if ($grupper != null && $agare_grupper != null) {
				foreach($grupper as $grupp) {
					foreach($agare_grupper as $agare_grupp) {
						
						if ($agare_grupp->getId() == $grupp->getId()) {

							// gemensam grupp
							return true;
						}
					}
				}
			}
		}

		// ta reda på om ägaren av fotoalbumet och besökaren är medlemmar i samma företag
		// samt om företagsmedlemmar har tilltrade till detta fotoalbum

		$foretag = Foretag::loadByMedlem($medlem);
		
		if ($foretag != null) {
			$foretag_id = $foretag->getId();
		}
		$medlem_foretag = Foretag::loadByMedlem($this->getMedlem());
		
		if ($medlem_foretag != null) {
			$medlem_foretag_id = $medlem_foretag->getId();
		}
		
		if ($foretag != null && $medlem_foretag != null && $foretag_id == $medlem_foretag_id && $this->harForetagTilltrade() == true) {
			return true;
		}
		return false;
	}
	
	public function harForetagTilltrade()
	{
		
		if ($this->getTilltradeForetag() == "ja") {
			return true;
		} else {
			return false;
		}
	}
	
	public function delete()
	{
		global $USER;

		//Security::demand(USER, $this->getMedlem());
		
		if (Security::authorized(ADMIN) || Security::authorized(USER, $this->getMedlem())) {

			// Leta reda på alla bilder som tillhör detta fotoalbum
			$medlem = Medlem::loadById($this->getMedlemId());
			$bilder = FotoalbumBild::listAsArray($medlem, $this->getId());
			for ($x = 0; $x < count($bilder); $x++) {

				// .. och plocka bort bild för bild
				$bild = FotoalbumBild::loadById($bilder[$x]["id"]);
				$bild->delete();
			}

			// Plocka bort själva fotoalbumet
			parent::delete();
		} else throw new SecurityException("Ej behörig", "Du har inte behörighet att ta bort bilden");
	}

	// Tar reda på om (nuvarande) användaren äger detta album
	
	public function isAgare()
	{
		global $USER;
		
		if ($USER->getId() == $this->medlem_id) {
			return true;
		} else {
			return false;
		}
	}
	
	public function addTilltradeGrupp($grupp_id)
	{
		global $db;
		$db->nonquery("INSERT INTO mm_fotoalbumGruppAcl (fotoalbum_id, grupp_id) VALUES ('" . $this->getId() . "', '" . $grupp_id . "')");
	}
	
	public function getTilltradesGrupper()
	{
		global $db;
		$grupper = array();
		$sql = $db->query("SELECT * FROM mm_fotoalbumGruppAcl WHERE fotoalbum_id = " . $this->getId());
		while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			$grupper[] = $row["grupp_id"];
		}
		
		if (count($grupper) == 0) {

			// inga grupper har tillgång till detta fotoalbum
			return null;
		} else {
			return $grupper;
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getNamn()
	{
		return strip_tags(stripslashes($this->namn));
	}
	
	public function getBeskrivning()
	{
		return strip_tags(stripslashes($this->beskrivning), '<a><p>');
	}
	
	public function setTillagd($tillagd)
	{
		$this->tillagd = $tillagd;
	}
	
	public function getTillagd()
	{
		return $this->tillagd;
	}
	
	public function getMedlemId()
	{
		return $this->medlem_id;
	}
	
	public function getMedlem()
	{
		
		if (!$this->medlem) $this->medlem = Medlem::loadById($this->getMedlemId());
		return $this->medlem;
	}
	
	public function setNamn($namn)
	{
		$this->namn = $namn;
	}
	
	public function getTilltrade()
	{
		return $this->tilltrade;
	}
	
	public function setTilltrade($tilltrade)
	{
		$this->tilltrade = $tilltrade;
	}
	
	public function getTilltradeForetag()
	{
		return $this->tilltrade_foretag;
	}
	
	public function setTilltradeForetag($tilltrade_foretag)
	{
		
		if ($tilltrade_foretag != "ja") $tilltrade_foretag = "nej";
		$this->tilltrade_foretag = $tilltrade_foretag;
	}
	
	public function getTilltradeAllaGrupper()
	{
		return $this->tilltrade_alla_grupper;
	}
	
	public function setTilltradeAllaGrupper($tilltrade_alla_grupper)
	{
		
		if ($tilltrade_alla_grupper != "ja") $tilltrade_alla_grupper = "nej";
		$this->tilltrade_alla_grupper = $tilltrade_alla_grupper;
	}
	
	public function setBeskrivning($beskrivning)
	{
		$this->beskrivning = $beskrivning;
	}
}

class FotoalbumException extends Exception
{
}
?>
