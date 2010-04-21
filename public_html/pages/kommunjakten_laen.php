<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

	$smarty = new MMSmarty();
	
	function detectUTF8($string)
	{
	        return preg_match('%(?:
	        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
	        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
	        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
	        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
	        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
	        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
	        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
	        )+%xs', $string);
	}

	function convertUrlNamn($namn){
		$from = array("aa", "ae", "oe", "AA", "AE", "OE", "Aa", "Ae", "Oe", "_");
		$to = array("å","ä","ö","Å","Ä","Ö","Å","Ä","Ö"," ");
		
		//return str_replace($from, $to, $namn);
		

		
		if(detectUTF8($namn)) {
			return urldecode($namn);
		}
		else {
			return utf8_encode(urldecode($namn));
		}
	}
	
	function getMapNamn($namn){
		$from = array("å","ä","ö","Å","Ä","Ö"," ", "Lan");
		$to = array("a", "a", "o", "A", "A", "O", "", "lan");
		
		return str_replace($from, $to, $namn);
	}

	$laen = (convertUrlNamn($_GET["laen"]));

	$smarty->assign("pagetitle", "Kommunjakten - ".$laen	);
	
	$karta = "C_FCMap_" . getMapNamn($laen);
	
	$smarty->assign("laen",$laen);
	$smarty->assign("karta",$karta);
	
	$laen_kommuner = Kommun::listByLan($laen);
	
	// kontrollera att detta är ett giltigt län
	if(sizeof($laen_kommuner) == 0) {

		exit;
	}
	
	$smarty->assign("kommuner", $laen_kommuner);

	$smarty->display('kommunjakten_laen.tpl');
	
?>