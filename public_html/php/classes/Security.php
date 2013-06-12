<?php

/**
 * 
 * Hjälpklass för säkerhetshantering
 */

class Security {

  public static function encrypt_password($medlem_id, $losenord) {
    $newpass = "";
    $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#%&/()._-";
    for ($i = 0; $i < strlen($losenord); $i++) {
      $times = $medlem_id * (round($i / 2));
      for ($j = 0; $j < $times; $j++) {
        $newpass.= substr($letters, round(($j * $i * $i) * $medlem_id) % strlen($letters), 1);
      }
      $newpass.= substr($losenord, $i, 1);
    }
    return sha1(md5($newpass)) . sha1(md5(md5($newpass)));
  }

  public static function checkLosenStrength($pass) {

    if (Security::checkForNumber($pass) == false) {
      return "fel: Lösenordet måste innehålla siffror";
    } elseif (strlen($pass) < 8) {
      return "fel: Lösenordet är för kort";
    } elseif ($pass == "*00**41##") {
      echo "!";
    } else {
      return "ok";
    }
  }

  public static function checkForNumber($string) {
    $numbers = array(
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        0
    );
    $set = null;
    foreach ($numbers as $number) {

      if (preg_match("/" . $number . "/i", $string)) {
        $set = true;
      }
    }

    if ($set == true) {
      return true;
    } else {
      return false;
    }
  }

  public static function secure_postdata($data, $quotes = true) {

    if ($quotes) {
      $data = addslashes(htmlspecialchars($data));
    } else {
      $data = addslashes(htmlspecialchars($data, ENT_NOQUOTES));
    }
    return $data;
  }

  public static function secure_data($sql) {
    return (str_replace("\r", "", str_replace("\n\r", " \n", $sql)));
  }

  public static function escape($data, $quotes = true) {
    if ($quotes) {
      $data = mysql_real_escape_string(htmlspecialchars(htmlspecialchars_decode($data)));
    } else {
      $data = mysql_real_escape_string(htmlspecialchars(htmlspecialchars_decode($data, ENT_NOQUOTES)));
    }

    return $data;
  }

  public static function generateSessionId() {
    $letters = "abcdefghijklmnopqrstuvxyz1234567890";
    $result = "";
    for ($i = 0; $i < 80; $i++) {
      $result.= $letters[mt_rand(0, strlen($letters) - 1)];
    }
    return $result;
  }

  public function generateCode($length) {
    $letters = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
    $result = "";
    for ($i = 0; $i < $length; $i++) {
      $result.= $letters[mt_rand(0, strlen($letters) - 1)];
    }
    return $result;
  }

  public function authorized($typ, $object = null) {
    // Kollar om inloggad medlem/admin/företag har en viss behörighet.
    // $object är en instans av den medlem/admin/företag som man vill kolla är inloggad.

    global $USER, $ADMIN, $FORETAG, $adminLevels;
    $authorized = true;

    if (isset($ADMIN) && $ADMIN->isTyp(SUPERADMIN))
      return true;

    if ($typ == USER) {

      if (isset($USER)) {

        if ($object) {

          if ($USER->getId() != $object->getId())
            $authorized = false;
        }
      } else {
        $authorized = false;
      }
    } else
    if ($typ == FORETAG) {

      if (isset($ADMIN) && $ADMIN->isTyp(ADMIN)) {
        return true;
      }

      if (!isset($FORETAG) || (isset($object) && isset($FORETAG) && $FORETAG->getId() != $object->getId())) {
        $authorized = false;
      }
    } else
    if (in_array($typ, array(
                SUPERADMIN,
                ADMIN,
                MODERATOR,
                EDITOR
            ))) {

      if (!isset($ADMIN)) {
        $authorized = false;
      } else {

        if ($adminLevels[$ADMIN->getTyp()] < $adminLevels[$typ]) {
          $authorized = false;
        }
      }
    } else
    if ($typ == KOMMUN) {

      if (!isset($ADMIN)) {
        return false;
      } else {

        if ($ADMIN->getTyp() == "kommun" && strtolower($ADMIN->getANamn()) != strtolower($object->getNamn())) {
          $authorized = false;
        } else
        if ($adminLevels[$ADMIN->getTyp()] < $adminLevels[$typ]) {
          $authorized = false;
        }
      }
    } else {
      throw new SecurityException('Fel', "Ett fel uppstod när rättigheterna skulle kontrolleras");
    }
    return $authorized;
  }

  public static function demand($typ, $object = null) {
    // se authorized()
    // kastar undantag vid ogiltigt inlogg


    if (!self::authorized($typ, $object)) {
      throw new SecurityException("Denna sida kan ej visas", "Detta kan bero på att sidan är privat, att du inte loggat in eller att du blivit utloggad.");
    }
  }



  public static function getMedlemEncryptedString($medlem) {
    $steg = (($medlem->getStegTotal() * 13) % 317) + 134;
    $code = bin2hex($medlem->getANamn() . $medlem->getEpost() ^ $medlem->getENamn() . $medlem->getSkapad());

    if (strlen($code) < 10)
      $code = $steg;
    else
      $code = substr($code, strlen($code) - 10, 5) . substr($steg, 0, 5) . substr($code, strlen($code) - 5, 5);
    return $code;
  }
}



class SecurityException extends UserException {

  public function __construct($title, $msg, $backlink = null, $backlinktitle = null) {
    header('HTTP/1.1 403 Forbidden');
    parent::__construct($title, $msg);
  }
}






/**
 * The Access class
 * This is a new access handler, since the old is a bit dodgy.
 * Use this from now on!! Add whats missing when you ned it...
 * 
 * 1. Make an instance
 * 2. set the class members you want to give access to true
 * 3. hit the accessTo() function
 * 
 * @date 2013-06-12 
 * @author Kristian Erendi
 * @uri http://reptilo.se
 */
class Access {
  public $logged_in = false;              //any type of logged in
  public $admin = false;                  //is admin
  public $foretag = false;                //is foretag
  public $user = false;                   //is user
  public $user_in_foretag = false;        //user in this company. This is a constraint to the user
  public $users_in_same_company = false;  //user in the same copmany as submitted user. This is a constraint to the user 
  

  
  /**
   * 2013-06-10 Krillo
   * This need much more work... 
   * 
   * This function takes an access object
   * Returns true if conditions are met 
   * 
   */
  public function accessTo() {
   if($this->isEmpty()){
     return false;
   } 
   global $USER, $ADMIN, $FORETAG;
    $authorized = 0;
    
    //any type of logged in is ok
    if($this->logged_in){  
      if($USER || $ADMIN || $FORETAG){
        return true;
      } else {
        return false;
      }
    }
/*  
 * This code is taken from page/profile.php
 * look hat it when developing the rest
 *   
    if ($access $ADMIN) {
      $authorized = 10; //'admin';
    }
    if (!$authorized && $FORETAG && $mid) {
      if ($FORETAG->isAnstalldByMId($mid)) {
        $authorized = 5; //'foretag';
      }
    }
    if (!$authorized && $USER && $mid) {
      $authorized = 1; // logged in user 
      $usrId = $USER->getId();
      if ($usrId == $mid) {
        $authorized = 3; // same user
      } else if (Medlem::isInSameCompany($USER->getId(), $mid)) {
        $authorized = 2; // same company
      }
    }
 */
  }    
  

  
  private function isEmpty(){
    if($logged_in == false && $admin == false && $foretag == false && $user == false && $user_in_foretag == false && $users_in_same_company == false){
      return false;
    } else{
      return true;
    }
  }
  
  
}

