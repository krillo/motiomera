<?php
/**
 * Felkoder
 * -1 $to är inte en giltig e-postadress
 * -2 $from är inte en giltig e-postadress
 * -3 Felaktigt format
 * -4 Mailet kunde inte skickas
 */
class Misc
{

	public function __construct()
	{
		throw new Exception("Kan inte instansiera denna klass");
	}



  /**
   * Return dayname in swedish by passing weekday number
   * @param int $daynumber weekday number
   * @return string
   */
  public static function veckodag($daynumber) {
  	$dag = explode(";", "Måndag;Tisdag;Onsdag;Torsdag;Fredag;Lördag;Söndag" );
    return $dag[$daynumber-1];
  }

  /**
   * Returns monthname in swedish by passing month number
   * @param int $monthnumber
   * @return string
   */
  public static function month($monthnumber) {
    $monthnames=array(
      1 => "januari",
      2 => "februari",
      3 => "mars",
      4 => "april",
      5 => "maj",
      6 => "juni",
      7 => "juli",
      8 => "augusti",
      9 => "september",
      10 => "oktober",
      11 => "november",
      12 => "december",
    );
    return $monthnames[$monthnumber];
  }

  /**
   * Truncates an array from the end. Keeps as many elements that are submited
   * @param array $array
   * @param int $keep
   * @return array
   */
  public static function truncateArrayFromEnd(array $array, $keep){
    $fullCount = count($array);
    if($fullCount >= $keep + 1){
      for($x = $fullCount; $x >= $keep; $x--){
        unset($array[$x]);
      }
    }
  	return $array;
  }


	public static function isUtf8($string)
	{
	    return (utf8_encode(utf8_decode($string)) == $string);
	}

	public static function shuffle_assoc(&$array, $mode = 2)
	{
		$keys = array_keys($array);
		$values = array_values($array);

		if (($mode == 0) || ($mode == 1)) shuffle($values);

		if (($mode == 0) || ($mode == 2)) shuffle($keys);
		if (count($keys)>0 && count($values)>0) {
			return array_combine($keys, $values);
		} else {
			return false;
		}
	}

	public static function matchString($first, $second)
	{ //Returns true if it match


		if ($first == $second) {
			return true;
		} else {
			return false;
		}
	}

	public static function isValidId($id)
	{

		if (is_numeric($id) && floor($id) == $id && !empty($id) && $id > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function gentime()
	{ //För att mäta vad tid något tar i scriptet :)

		static $gentime;

		if ($gentime == 0) $gentime = microtime(true);
		else return (string)(microtime(true) - $gentime);
	}

	public static function get_milliseconds($remove_dot = false)
	{
		$time = microtime();
		$timearray = explode(" ", $time);
		$time = $timearray[1] + $timearray[0];

		if ($remove_dot == true) {
			$time = intval($time);
		}
		return $time;
	}

	public static function dateToTimestamp($date)
	{
		$time = explode('-', $date);
		$mktime = mktime(0, 0, 0, $time[1], $time[2], $time[0]);
		return $mktime;
	}

	public static function getManadFromDate($n)
	{
		$manader = array(
			"Januari",
			"Februari",
			"Mars",
			"April",
			"Maj",
			"Juni",
			"Juli",
			"Augusti",
			"September",
			"Oktober",
			"November",
			"December"
		);
		$date = explode('-', $n);
		return $manader[$date[1] - 1];
	}

	public static function getDateFromDateTime($n)
	{
		$date = explode(' ', $n);
		return $date[0];
	}

	public static function getCalFromSteg($steg)
	{
		return $steg * 0.05;
	}

	public static function getDagarMellanTvaDatum($startdag, $slutdag)
	{
		$start = self::dateToTimestamp($startdag);
		$slut = self::dateToTimestamp($slutdag);
		return intval(($slut - $start) / (86400));
	}

	public static function isInt($mixed)
	{
		$num = (int)$mixed;
		$num = (string)$num;

		if ($num == $mixed) return true;
		else return false;
	}

	public static function isEmail($email)
	{
		return eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $email);
	}

	public static function isDate($date, $format = "Y-m-d H:i:s")
	{
		$dateereg = "[1-2][0-9]{3}-[0-1][0-9]-[0-3][0-9]";
		$timeereg = "[0-2][0-9]:[0-5][0-9]:[0-5][0-9]";

		switch ($format) {
		case "Y-m-d":
			return eregi('^' . $dateereg . '$', $date);
			break;

		case "Y-m-d H:i:s":
			return eregi('^' . $dateereg . ' ' . $timeereg . '$', $date);
			break;

		default:
			throw new MiscException("Felaktigt format", -3);
		}
	}

	public function niceDate($time, $format)
	{

		if (date("Y-m-d") == date("Y-m-d", $time)) {
			return "Idag";
		} else
		if (date("Y-m-d", strtotime("-1 day")) == date("Y-m-d", $time)) {
			return "Igår";
		} else {
			return date($format, $steg->getDatum());
		}
	}

	public static function arrayKeyMerge($arr1, $arr2)
	{
		$result = $arr1;
		foreach($arr2 as $key => $value) {
			$result[$key] = $value;
		}
		return $result;
	}


/**
 * Sends all the emails. Loggs all to file email.log
 * Optional messages can sent to the logfile via last param logmessage
 *
 * @param string $to
 * @param string $from
 * @param string $subject
 * @param string $message
 * @param string $logMessage
 * @return boolean
 */
	public static function sendEmail($to, $from, $subject, $message, $logMessage = '') {
    global $SETTINGS;
    $tomail = explode("Bcc:", $to);
    $charset = 'UTF-8';

    if (!self::isEmail($to)) {
      self::logEmailSend(false, $subject, $logMessage . ' | To: is not a valid email adress - ' . $to);
      throw new MiscException($to . ' är inte en giltig e-postadress ' . $logMessage, -1);
    }
    if ($from != null && !self::isEmail($from)) {
      self::logEmailSend(false, $subject, $logMessage . ' | From: is not a valid email adress - ' . $from);
      throw new MiscException('$from är inte en giltig e-postadress ' . $logMessage, -2);
    }


    if ($from == null) {
      $from = "noreply motiomera <" . $SETTINGS["email"] . ">";
    }
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers.= 'Content-type: text/plain; charset=UTF-8' . "\r\n";
    $headers.= 'Content-Transfer-Encoding: 8bit' . "\r\n";
    $headers.= "From: $from \r\n";
    $headers.= "Reply-To: " . $SETTINGS["reply_to"] . "\r\n";
    $headers.= 'Bcc: noreply motiomera <noreply@motiomera.se>' . "\r\n";
    $encoded_subject = "=?$charset?B?" . base64_encode($subject) . "?=\n";
    $content = $message;

    if (!mail($tomail[0], $encoded_subject, $message, $headers)) {
      self::logEmailSend(false, $subject, $logMessage . ' | The mail could not be sent to - ' . $to);
      throw new MiscException("Mailet kunde inte skickas " . $logMessage, -4);
    }
    self::logEmailSend(true, $subject, $logMessage . ' | from: ' . $from . ' to: ' . $to);
    return true;
  }

  /**
	 * Log email sending to logfile
	 *
	 * @param boolean $success
	 * @param string_type $whichEmail
	 * @param string_type $msg
	 */
	public static function logEmailSend($success, $whichEmail, $msg){
		$success ? $status = "SUCCESS" : $status = "FAIL";
    @file_put_contents(EMAIL_SEND_LOG_FILE, date("Y-m-d H:i:s ") . ' | ' . $whichEmail . ' | '. $msg . " | "  . $status . "\n" , FILE_APPEND);
  }


  /**
   * All purpose logging to motiomera.log
   * or to another file if submitted
   *
   * @param string_type $msg
   * @param string $level
   */   
  public static function logCronMotiomera($msg){
    @file_put_contents(LOG_DIR.'/cron_motiomera.log', $msg . "\n" , FILE_APPEND);
  } 


  /**
   * All purpose logging to motiomera.log
   * Use levels: CRITICAL, DEBUG, ERROR, INFO, NOTICE, WARNING
   *
   * @param string_type $msg
   * @param string $level
   */
  public static function logMotiomera($msg, $level = ''){
  	$logFile = LOG_DIR."/motiomera.".date("y-m").".log";
    if(!file_exists($logFile)){
      touch($logFile);
      chmod($logFile, 777);
      chown($logFile, 'deploy');
    }
    $msg = date("Y-m-d H:i:s") . " [". strtoupper($level) ."] ".$msg ."\n";
  	$fd = fopen($logFile, "a");
  	fwrite($fd, $msg);
  	fclose($fd);
  }



	public static function curlGetHeaders($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$result = explode("\n", curl_exec($ch));
		return $result;
	}

	public static function getCurrentPage()
	{
		$current_page = $_SERVER['SCRIPT_NAME'];
		$current_page = explode('/', $current_page);
		$current_page = strtolower(str_replace('.php', '', $current_page[count($current_page) - 1]));
		return $current_page;
	}
}

class MiscException extends UserException
{

	public function __construct($msg, $code)
	{
		parent::__construct($msg, $code);
	}
}
?>
