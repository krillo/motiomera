<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - __getEmptyObject()
* - listByMedlem()
* - listAsArray()
* - listUncheckedAsArray()
* - loadById()
* - rowById()
* - loadMedlemsBildblock()
* - loadGruppsBildblock()
* - loadLagsBildblock()
* - loadForetagsBildblock()
* - processRowsBildblock()
* - delete()
* - isAgare()
* - getBredd()
* - getHojd()
* - getId()
* - getFotoalbumId()
* - setId()
* - getNamn()
* - getBeskrivning()
* - getMedlemId()
* - getMedlem()
* - setMedlemId()
* - setMedlem()
* - getApproved()
* - setApproved()
* Classes list:
* - FotoalbumBild extends Bild
* - FotoalbumBildException extends Exception
*/

class FotoalbumBild extends Bild
{
	
	protected $medlem;
	protected $fotoalbum;
	protected $fotoalbum_id;
	protected $id;				// int
	protected $medlem_id; 		// int
	protected $namn;			// string
	protected $beskrivning;		// string
	protected $hojd_stor;
	protected $bredd_stor;
	protected $hojd_liten;
	protected $bredd_liten;
	protected $hojd_mini;
	protected $bredd_mini;
	protected $approved;
	
	const TABLE = 'mm_fotoalbumbild';

	// Felkoder
	// -1 Filen är inte en bild (som vi kan läsa)/okänt filformat
	// -2 Båda argumenten får inte vara null

	
	public function __construct($source = null, $filename = null, $fotoalbum_id, $dummy_object = NULL)
	{
		
		global $USER, $SETTINGS;
		
		if ($source) {
			parent::__construct($source);

			// Kolla om det är en riktig bild
			$data = @getimagesize($source["tmp_name"]);
			
			if ($data == false) {
				throw new FotoalbumException("Okänt filformat", -1);
			} else {

				// Skapa bild-id till denna bild
				global $db;

				// Släng in i databasen
				$db->nonquery("	INSERT INTO
									mm_fotoalbumbild
									(
										fotoalbum_id, medlem_id,
										tillagd
									)
								VALUES
									(
										'" . $fotoalbum_id . "', '" . $USER->getId() . "',
										'" . date("Y-m-d H:i:s") . "'
									)
				");
				$bild_id = $db->getInsertedId();

				// Resize'a till mini-bild (och spara alltid som jpg)
				$this->resizeNyFil(FOTOALBUM_PATH . "/" . $bild_id . "_mini.jpg", $SETTINGS["fotoalbum_bredd_mini"], $SETTINGS["fotoalbum_hojd_mini"]);

				// Resize'a till thumbnail (och spara alltid som jpg)
				$this->resizeNyFil(FOTOALBUM_PATH . "/" . $bild_id . "_liten.jpg", $SETTINGS["fotoalbum_bredd_liten"], $SETTINGS["fotoalbum_hojd_liten"]);

				// Resize'a till stor bild (och spara alltid som jpg)
				$this->resizeNyFil(FOTOALBUM_PATH . "/" . $bild_id . "_stor.jpg", $SETTINGS["fotoalbum_bredd_stor"], $SETTINGS["fotoalbum_hojd_stor"]);

				// Spara orignalet, ifall vi vill Šndra storlekarna fšr liten och stor i framtiden
				move_uploaded_file($source["tmp_name"], FOTOALBUM_PATH . "/original/" . $bild_id);

				// Hämta storlek på lilla och stora bilden..
				$mini = new Bild(null, FOTOALBUM_PATH . "/" . $bild_id . "_mini.jpg");
				$liten = new Bild(null, FOTOALBUM_PATH . "/" . $bild_id . "_liten.jpg");
				$stor = new Bild(null, FOTOALBUM_PATH . "/" . $bild_id . "_stor.jpg");

				// .. och uppdatera databasen/bilden
				$db->nonquery("	UPDATE
									mm_fotoalbumbild
								SET
									bredd_stor = '" . $stor->getBredd() . "',
									hojd_stor = '" . $stor->getHojd() . "',
									bredd_liten = '" . $liten->getBredd() . "',
									hojd_liten = '" . $liten->getHojd() . "',
									bredd_mini = '" . $mini->getBredd() . "',
									hojd_mini = '" . $mini->getHojd() . "'
								WHERE
									id = '" . $bild_id . "'
				");
				$this->setId($bild_id);
			}
		} elseif ($filename) {
			parent::__construct(null, FOTOALBUM_PATH . "/" . $filename);
		}
	}
	
	// STATISKA FUNKTIONER ////////////////////////////////////////
	
	public static function listByMedlem(Medlem $medlem)
	{
		return parent::lister(get_class() , "medlem_id", $medlem->getId());
	}
	
	public static function listAsArray(Medlem $medlem, $fotoalbum_id, $order_by = "namn ASC")
	{
		global $db;
		$medlem_id = $medlem->getId();
		$sql = $db->query("	SELECT
								*
							FROM
								mm_fotoalbumbild
							WHERE
								medlem_id = '" . $medlem_id . "'
									AND
								fotoalbum_id = '" . $fotoalbum_id . "'
							ORDER BY
								$order_by
		");
		$bilder = array();
		while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			foreach ($row as $field => $value) {
				$row[$field] = stripslashes($value);
			}
			$bilder[] = $row;
		}
		return $bilder;
	}
	
	public static function listUncheckedAsArray()
	{
		global $db;
		$sql = $db->query("	SELECT
								A.*, M.aNamn
							FROM
								mm_fotoalbumbild AS A, mm_medlem AS M
							WHERE
								A.approved = 0 AND A.medlem_id = M.id
							ORDER BY
								A.tillagd ASC
							LIMIT 0,20
		");
		$bilder = array();
		while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			$bilder[] = $row;
		}
		return $bilder;
	}
	
	public static function loadById($id)
	{
		$bild = new FotoalbumBild(null, null, 0);
		$row = self::rowById($id);
		foreach($row as $key => $value) {
			$bild->$key = $value;
		}
		return $bild;
	}
	
	public static function rowById($id)
	{
		global $db;
		return $db->row("SELECT * FROM mm_fotoalbumbild WHERE id = '" . Security::secure_data($id) . "'");
	}
	
	public static function loadMedlemsBildblock(Medlem $medlem, $antal = 20)
	{
		global $db;
		$bilder = array();
		$lowest_id = 0;
		$medlem_id = $medlem->getId();

		// hämta 10 senaste bilderna
		$sql = $db->query("	SELECT
								*
							FROM
								mm_fotoalbumbild
							WHERE
								medlem_id = '" . $medlem_id . "'
							ORDER BY
								id DESC
		");
		list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock(($antal / 2) , $lowest_id, $bilder, $sql);

		// hämta 10 slumpade bilder
		
		if (count($bilder) == ($antal / 2)) {

			// det finns iallafall 10 bilder, så försök hitta några till
			$sql = $db->query("	SELECT
									*
								FROM
									mm_fotoalbumbild
								WHERE
									medlem_id = '" . $medlem_id . "'
										AND
									id < $lowest_id
								ORDER BY
									RAND()
			");
			list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock($antal, $lowest_id, $bilder, $sql);
		}
		
		if (count($bilder) > 0) {

			// slumpa ordningen
			shuffle($bilder);
			return $bilder;
		} else {
			return false;
		}
	}
	
	public static function loadGruppsBildblock(Grupp $grupp, $antal = 20)
	{
		global $db, $USER;
		
		$lowest_id = 0;
		$bilder = array();
		$grupp_id = $grupp->getId();

		// hämta 10 senaste bilderna (baserat på fotoalbum som specifikt gett tillstånd till denna grupp att se bilderna)
		$sql = $db->query("	SELECT
								*, mm_fotoalbumbild.id AS id
							FROM
								mm_fotoalbum, mm_fotoalbumGruppAcl, mm_fotoalbumbild
							WHERE
								mm_fotoalbum.id = mm_fotoalbumGruppAcl.fotoalbum_id
									AND
								mm_fotoalbum.id = mm_fotoalbumbild.fotoalbum_id
									AND
								mm_fotoalbumGruppAcl.grupp_id = $grupp_id
							ORDER BY
								mm_fotoalbumbild.id DESC
		");

		list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock(($antal / 2) , $lowest_id, $bilder, $sql);
		// hittade vi 10 bilder som hade gett specifik access till gruppen?
		
		if (count($bilder) < ($antal / 2)) {

			// om inte, hämta fler bilder ifrån människor som är medlemmar i gruppen OCH gett tillgång till "alla"
			$sql = $db->query("	SELECT
								mm_fotoalbum.*, mm_fotoalbumbild.*,
								mm_medlem.fnamn, mm_medlem.anamn, mm_medlem.enamn, mm_medlem.avatar_filename, 
								mm_fotoalbumbild.id AS id
								FROM
									mm_fotoalbum, mm_fotoalbumbild, mm_medlem, mm_medlemIGrupp
								WHERE
									mm_fotoalbumbild.fotoalbum_id = mm_fotoalbum.id
										AND
									mm_fotoalbum.medlem_id = mm_medlem.id
										AND
									(
										mm_fotoalbum.tilltrade = 'alla'
											OR
										mm_fotoalbum.tilltrade_alla_grupper = 'ja'
									)
										AND
									mm_medlem.id = mm_medlemIGrupp.medlem_id
										AND
									mm_medlemIGrupp.grupp_id = $grupp_id
										AND
									mm_medlemIGrupp.godkannd_medlem = 1
										AND
									mm_medlemIGrupp.godkannd_skapare = 1
								ORDER BY
									mm_fotoalbumbild.id DESC
			");
			list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock(($antal / 2) , $lowest_id, $bilder, $sql);
		}

		// hämta 10 slumpade bilder
		
		if (count($bilder) == ($antal / 2)) {

			// det finns iallafall 10 bilder, så försök hitta några till (slumpade)
			$sql = $db->query("	SELECT
									*, mm_fotoalbumbild.id AS id
								FROM
									mm_fotoalbum, mm_fotoalbumGruppAcl, mm_fotoalbumbild
								WHERE
									mm_fotoalbumbild.fotoalbum_id = mm_fotoalbum.id
										AND
									mm_fotoalbum.id = mm_fotoalbumGruppAcl.fotoalbum_id
										AND
									mm_fotoalbumGruppAcl.grupp_id = $grupp_id
										AND
									mm_fotoalbumbild.id < $lowest_id
								ORDER BY
									RAND()
			");
			list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock($antal, $lowest_id, $bilder, $sql);

			// hämta slumpade bilder på tilltrade = alla
			
			if (count($bilder) < $antal) {

				// det finns iallafall 10 bilder, så försök hitta några till (slumpade)
				//	*, mm_fotoalbumbild.id AS id

				$sql = $db->query("	SELECT
								mm_fotoalbum.*, mm_fotoalbumbild.*,
								mm_medlem.fnamn, mm_medlem.anamn, mm_medlem.enamn, mm_medlem.avatar_filename, 
								mm_fotoalbumbild.id AS id
									FROM
										mm_fotoalbum, mm_fotoalbumbild, mm_medlem, mm_medlemIGrupp
									WHERE
										mm_fotoalbumbild.fotoalbum_id = mm_fotoalbum.id
											AND
										mm_fotoalbum.medlem_id = mm_medlem.id
											AND
										(
											mm_fotoalbum.tilltrade = 'alla'
												OR
											mm_fotoalbum.tilltrade_alla_grupper = 'ja'
										)
											AND
										mm_medlem.id = mm_medlemIGrupp.medlem_id
											AND
										mm_medlemIGrupp.grupp_id = $grupp_id
											AND
										mm_medlemIGrupp.godkannd_medlem = 1
											AND
										mm_medlemIGrupp.godkannd_skapare = 1
											AND
										mm_fotoalbumbild.id < $lowest_id
									ORDER BY
										RAND()
				");
				list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock($antal, $lowest_id, $bilder, $sql);
			}
		}
		
		if (count($bilder) > 0) {

			// slumpa ordningen
			shuffle($bilder);
			return $bilder;
		} else {
			return false;
		}
	}
	
	public static function loadLagsBildblock(Lag $lag, $antal = 20)
	{
		global $db, $USER;
		$lowest_id = 0;
		$bilder = array();
		$lag_id = $lag->getId();

		// hämta bilder från människor som är medlemmar i laget OCH gett tillgång till "alla" eller "alla grupper"
		//*, mm_fotoalbumbild.id AS id

		$sql = $db->query("	SELECT
								mm_fotoalbum.*, mm_fotoalbumbild.*,
								mm_medlem.fnamn, mm_medlem.anamn, mm_medlem.enamn, mm_medlem.avatar_filename, 
								mm_fotoalbumbild.id AS id
								FROM
									mm_fotoalbum, mm_fotoalbumbild, mm_medlem, mm_foretagsnycklar
								WHERE
									mm_fotoalbumbild.fotoalbum_id = mm_fotoalbum.id
										AND
									mm_fotoalbum.medlem_id = mm_medlem.id
										AND
									(
										mm_fotoalbum.tilltrade = 'alla'
											OR
										mm_fotoalbum.tilltrade_alla_grupper = 'ja'
									)
										AND
									mm_medlem.id = mm_foretagsnycklar.medlem_id
										AND
									mm_foretagsnycklar.lag_id = $lag_id
								ORDER BY
									mm_fotoalbumbild.id DESC
			");
		list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock($antal, $lowest_id, $bilder, $sql);
		
		if (count($bilder) > 0) {
			// slumpa ordningen
			shuffle($bilder);
			return $bilder;
		} else {
			return false;
		}
	}
	
	public static function loadForetagsBildblock(Foretag $foretag, $antal = 20)
	{
		global $db, $USER;
		$lowest_id = 0;
		$bilder = array();
		$foretag_id = $foretag->getId();

		// hämta 10 senaste bilderna (baserat på fotoalbum som specifikt gett tillstånd till företaget att se bilderna)
		//	*, mm_fotoalbumbild.id AS id

		$sql = $db->query("	SELECT
								mm_fotoalbum.*, mm_fotoalbumbild.*,
								mm_medlem.fnamn, mm_medlem.anamn, mm_medlem.enamn, mm_medlem.avatar_filename, 
								mm_fotoalbumbild.id AS id
							FROM
								mm_fotoalbum, mm_fotoalbumbild, mm_medlem, mm_foretagsnycklar
							WHERE
								mm_fotoalbumbild.fotoalbum_id = mm_fotoalbum.id
									AND
								mm_fotoalbum.medlem_id = mm_medlem.id
									AND
								mm_foretagsnycklar.medlem_id = mm_medlem.id
									AND
								mm_foretagsnycklar.foretag_id = $foretag_id
									AND
								(
									mm_fotoalbum.tilltrade_foretag = 'ja'
										OR
									mm_fotoalbum.tilltrade = 'alla'
								)
							ORDER BY
								mm_fotoalbumbild.id DESC
		");
		list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock(($antal / 2) , $lowest_id, $bilder, $sql);

		// hämta 10 slumpade bilder
		
		if (count($bilder) == ($antal / 2)) {

			// det finns iallafall 10 bilder, så försök hitta några till (slumpade)
			$sql = $db->query("	SELECT
								mm_fotoalbum.*, mm_fotoalbumbild.*,
								mm_medlem.fnamn, mm_medlem.anamn, mm_medlem.enamn, mm_medlem.avatar_filename, 
								mm_fotoalbumbild.id AS id
								FROM
									mm_fotoalbum, mm_fotoalbumbild, mm_medlem, mm_foretagsnycklar
								WHERE
									mm_fotoalbumbild.fotoalbum_id = mm_fotoalbum.id
										AND
									mm_fotoalbum.medlem_id = mm_medlem.id
										AND
									mm_foretagsnycklar.medlem_id = mm_medlem.id
										AND
									mm_foretagsnycklar.foretag_id = $foretag_id
										AND
									(
										mm_fotoalbum.tilltrade_foretag = 'ja'
											OR
										mm_fotoalbum.tilltrade = 'alla'
									)
										AND
									mm_fotoalbumbild.id < $lowest_id
								ORDER BY
										RAND()
			");
			list($lowest_id, $bilder) = FotoalbumBild::processRowsBildblock($antal, $lowest_id, $bilder, $sql);
		}
		
		if (count($bilder) > 0) {

			// slumpa ordningen
			shuffle($bilder);
			return $bilder;
		} else {
			return false;
		}
	}
	
	public function getRelationTagId()
	{
		$tagg = Tagg::loadByObjectId($this->getId(),self::TABLE);
		if (!empty($tagg)) {
			return $tagg->getTagId();
		} else {
			return false;
		}
	}
	
	public static function processRowsBildblock($antal, $lowest_id, $bilder, $sql)
	{
		global $USER;
		$ok = false;
		while ($row = mysql_fetch_array($sql, MYSQL_ASSOC)) {

			// inte alla bilder har ett fotoalbum (användaren gick inte igenom "namnge bilder"-processen)
			
			if ($row["fotoalbum_id"] > 0) {
				// kolla så att besökaren har tillgång till fotoalbumet som bilden är i
				$fotoalbum = Fotoalbum::loadById($row["fotoalbum_id"]);
				$ok = false;
				
				if (Security::authorized(USER) == false) {

					// icke inloggad besökare
					
					if ($fotoalbum->getTilltrade() == "alla") {
						$ok = true;
					}
				} else {

					// inloggad användare
					
					if ($fotoalbum->harMedlemTilltrade($USER)) {
						$ok = true;
					}
				}
				
				if ($ok == true) {
					$bild = new FotoalbumBild(null, null, 0);
					foreach($row as $key => $value) {
						$bild->$key = $value;
					}
					
					if ($bild->beskrivning == "") $bild->beskrivning = $bild->namn;
					$bilder[] = $bild;
					
					if ($row["id"] < $lowest_id || $lowest_id == 0) {
						$lowest_id = $row["id"];
					}
				}
				
				if (count($bilder) == $antal) {

					// vi har alla bilder vi vill ha
					break;
				}
			}
		}
		return array(
			$lowest_id,
			$bilder
		);
	}

	// PUBLIKA FUNKTIONER ///////////////////////////////////////
	
	public function delete()
	{
		Security::demand(USER, $this->getMedlem());
		$res1 = @unlink(FOTOALBUM_PATH . "/original/" . $this->id);
		$res2 = @unlink(FOTOALBUM_PATH . "/" . $this->id . "_liten.jpg");
		$res3 = @unlink(FOTOALBUM_PATH . "/" . $this->id . "_stor.jpg");
		
		if (true ||  ($res1 == true && $res2 == true && $res3 == true)) {

			// plocka endast bort ifrån databasen om vi lyckades plocka bort alla filer - RÄTTELSE: detta görs alltid! (möjligen tillfälligt)
			global $db;
			$db->nonquery("DELETE FROM mm_fotoalbumbild WHERE id = '" . $this->id . "'");
		}
	}
	
	public function isAgare()
	{
		global $USER;
		
		if ($USER->getId() == $this->medlem_id) {
			return true;
		} else {
			return false;
		}
	}

	// SETTERS & GETTERS //////////////////////////////////////
	
	public function getBredd($storlek = "stor")
	{
		$var = "bredd_$storlek";
		return $this->$var;
	}
	
	public function getHojd($storlek = "stor")
	{
		$var = "hojd_$storlek";
		return $this->$var;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getFotoalbumId()
	{
		return $this->fotoalbum_id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function getNamn()
	{
		return strip_tags(stripslashes($this->namn));
	}
	
	
	public function getBeskrivningNinja()
	{
		global $urlHandler;
		if ($this->getRelationTagId()) {
			$kommun = Kommun::loadById($this->getRelationTagId());
			$tagg = "<br /><strong>Taggar: </strong><a href='".$urlHandler->getUrl('Kommun', URL_VIEW, $kommun->getNamn())."' title='".$kommun->getNamn()."'>".$kommun->getNamn()."</a>";
		}
		return strip_tags(stripslashes($this->beskrivning)).$tagg;
	}
	
	public function getBeskrivning()
	{
		return strip_tags(stripslashes($this->beskrivning));
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
	
	public function setMedlemId($id)
	{
		$this->medlem_id = $id;
		$this->medlem = null;
	}
	
	public function setMedlem(Medlem $medlem)
	{
		$this->medlem = $medlem;
		$this->medlem_id = $medlem->getId();
	}
	
	public function getApproved()
	{
		return $this->approved;
	}
	
	public function setApproved($approved)
	{
		global $db;
		
		if ($approved == 0) {
			$res1 = @unlink(FOTOALBUM_PATH . "/original/" . $this->id);
			$res2 = @unlink(FOTOALBUM_PATH . "/" . $this->id . "_liten.jpg");
			$res3 = @unlink(FOTOALBUM_PATH . "/" . $this->id . "_stor.jpg");
			$db->nonquery("DELETE FROM mm_fotoalbumbild WHERE id = '" . $this->id . "'");
		} else {
			$sql = "UPDATE	mm_fotoalbumbild SET approved = 1 WHERE id = " . $this->id;
			$db->nonquery($sql);
		}
	}
}

class FotoalbumBildException extends Exception
{
}
?>
