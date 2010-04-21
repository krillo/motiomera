<?php
/**
* Class and Function List:
* Function list:
* - getFileContents()
* Classes list:
* - Popup
*/

class Popup
{
	const POPUP_PATH = "/popup/pages";
	
	public static function getFileContents($file, $requireUser)
	{
		
		if ($requireUser) {
			
			if (!Security::authorized(USER, null)) {
				return null;
			}
		}
		$file = ROOT . self::POPUP_PATH . "/" . $file;

		// sparar innehållet i filen $file till $buf
		;
		ob_start();
		include ($file);
		$buf = ob_get_contents();
		ob_end_clean();
		return str_replace("'", "\'", str_replace("\n", "", $buf));
	}
}
?>
