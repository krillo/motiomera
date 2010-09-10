<?php

/**
 * This class handles Rss
 *
 * Felkoder
 * -1 Kan inte instansiera klass
 * -2 RSS-filen kunde inte läsas
 * -3 Cache-filen är inte skrivbar
 */
class RSSHandler{
	//const RSS_URL = "http://www.allersforlag.se/Templates/rss20____51287.aspx";
  const RSS_URL = "http://mabra.com/feed";
	const CACHE_PATH = "/files/rsscache";
	const CRON_PATH = "/cron";
	const IMG_WIDTH = 126;
	const IMG_HEIGHT = 95;
	const MAX_ARTICLES = 3;


	
	public function __construct(){
		throw new RSSHandlerException("Kan inte instansiera klass", -1);
	}


	/**
	 * Function getLatestRowsInRss
	 * Gets the latest rows from rss
	 * Example:
	 *      getLatestRowsInRss  ( http://motiomera.se/rss.xml )
	 */	
	public static function getRssAsArray($url, $amount = 2){
		$file = self::getRssFlow($url);
		
		if (!self::isRss($file)) {
			return false;
		}
		$xml = simplexml_load_string($file);
		$arr = array();

		// print_r($xml);
		foreach($xml as $node) {
			$i = 0;
			foreach($node->item as $f) {
				
				if ($f->title) {

					// print_r($f);
					// die();

					$arr[] = array(
						'title' => $f->title,
						'description' => $f->description,
						'pubDate' => date('Y-m-d H:i:s', strtotime($f->pubDate)) ,
						'link' => $f->link,
						'commentsLink' => $f->comments
					);
					$i++;
					
					if ($i == $amount) {
						//print_r($arr);
						//die();
						return $arr;
					}
				}
			}
		}
		return $xml;
	}


	/**
	 * Function isRss
	 * Checks if xml=rss2.0
	 * Example:
	 *      isRss( http://motiomera.se/rss.xml )
	 */	
	public function isRss($feedxml){
		if (!empty($feedxml)) {
			try {
				$feed = @new SimpleXMLElement($feedxml);
			}
			catch (Exception $e) {
			}
			
			if ($feed->channel->item) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	/**
	 * Function isAtom
	 * Checks if xml=atom
	 * Example:
	 *      isRss( http://motiomera.se/rss.xml )
	 */
	
	public static function isAtom($feedxml){
		$feed = @new SimpleXMLElement($feedxml);
		
		if ($feed->entry) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function getRssFlow
	 * Gets an rss flow
	 * Example:
	 *      getRssFlow  ( 'http:/motiomera.se/rss.xml' )
	 */
	public static function getRssFlow($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}


	public static function fetchFront(){
		global $SETTINGS;
		$rss_cache = array();
		
		if (file_exists(ROOT . self::CACHE_PATH . "/rss.php")) {
			include (ROOT . self::CACHE_PATH . "/rss.php");
		} else {
			include (ROOT . self::CRON_PATH . "/cacherss.php");
			
			if (!file_exists(ROOT . self::CACHE_PATH . "/rss.php")) {
				Misc::sendEmail($SETTINGS["debug_mail"], $SETTINGS["email"], "Debug meddelande frÃ¥n motiomera", E_ALL);
				echo "Laddningen misslyckades! Vi arbetar med att l&ouml;sa problemet.";
			} else {
				echo '<script languge="javascript">window.location="' . $_SERVER['REQUEST_URI'] . '";</script>';
			}
			exit;
		}
		return $rss_cache;
	}
	

  public static function refreshCache(){
    Misc::logMotiomera("Start RSSHandler::refreshCache() ", 'info');
		$rss = new lastRSS;
		$rss->cache_dir = '';
		$rss->cache_time = 0;
		$rss->CDATA = "content";

    /*
		if (file_exists(ROOT . self::CACHE_PATH . "/rss.php")) {
			include (ROOT . self::CACHE_PATH . "/rss.php");
		}
     */
    
     //krillo
    echo "************* refreshCache lastRSS: ";
    print_r($rss);

		if ($rs = $rss->get(self::RSS_URL)) {

           //krillo
          echo "************* refreshCache : inne i loopen   if (rs = rss->get(self::RSS_URL) \n";



			$cache = "<?php \n\n // Skapad " . date("Y-m-d H:i:s") . "\n\n" . '$rss_cache = array();' . "\n";
			$i = 0;


           //krillo
          echo "************* refreshCache : param rs \n";
          print_r($rs);


			foreach($rs["items"] as $row) {
				if ($i < self::MAX_ARTICLES) {

          //krillo
          echo "****************** refreshCache item $i loopen ****************\n";
          print_r($row);


					$tmp = split("<br>", $row["description"]);
					$tmp2 = split('"', $tmp[1]);
					$img = $tmp2[1];
					$tmpsource = array(
						" ",
						"å",
						"ä",
						"ö",
						"Å",
						"Ä",
						"Ö"
					);
					$tmpreplace = array(
						"%20",
						"%E5",
						"%E4",
						"%F6",
						"%C5",
						"%C4",
						"%D6"
					);
					$img = str_replace($tmpsource, $tmpreplace, $img);
					$cache.= '$rss_cache[' . $i . ']["title"] = "' . htmlspecialchars($row["title"]) . '";' . "\n";

					//remove &nbsp;
					$tmp[0] = str_replace("&nbsp;", "", $tmp[0]);
					$cache.= '$rss_cache[' . $i . ']["description"] = "' . htmlspecialchars(strip_tags($tmp[0])) . '";' . "\n";
					$cache.= '$rss_cache[' . $i . ']["img"] = "' . addslashes($img) . '";' . "\n";
					$cache.= '$rss_cache[' . $i . ']["link"] = "' . (isset($row["link"]) ? $row["link"] : 'default.html') . '";' . "\n";

          //krillo
					echo "rawurlencode(img): " . rawurlencode($img).'<br>';
					
					if (!isset($rss_cache) || $img != $rss_cache[$i]["img"]) { // hämta bild

            //krillo
						echo "hämta bild";

						
						if ($f = curl_init($img)) {
							curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($f, CURLOPT_HEADER, 0);
							$bild = curl_exec($f);
						}
						$fil = fopen(ROOT . self::CACHE_PATH . "/$i.jpg", 'w');
						fwrite($fil, $bild);
						fclose($fil);
						$bild = new Bild(null, ROOT . self::CACHE_PATH . "/$i.jpg");
						$bild->resize(self::IMG_WIDTH, self::IMG_HEIGHT);
						$image = imagecreatetruecolor(126, 95);
						$bg = imagecreatefromjpeg(ROOT . self::CACHE_PATH . "/$i.jpg");
						$overlay = imagecreatefrompng(ROOT . self::CACHE_PATH . "/mask.png");
						imagecopy($image, $bg, 0, 0, 0, 0, 126, 95);
						imagecopy($image, $overlay, 0, 0, 0, 0, 126, 95);
						imagejpeg($image, ROOT . self::CACHE_PATH . "/$i.jpg", 90);
					}
				}
				$i++;
			}
			$cache.= "?>";
			
			if (!$file = @fopen(ROOT . self::CACHE_PATH . "/rss.php", 'w')) {
        Misc::logMotiomera("Cache-filen is not writable ", 'error');
				throw new RSSHandlerException("Cache-filen är inte skrivbar", -3);
			}
			fwrite($file, $cache);
			fclose($file);
		} else {
      Misc::logMotiomera("Could not get the RSS from " . self::RSS_URL , 'error');
    }
    Misc::logMotiomera("End RSSHandler::refreshCache() ", 'info');
	}
  
}

class RSSHandlerException extends Exception{
}

?>
