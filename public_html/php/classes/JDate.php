<?php

class JDate {

/**
 * Can take unix timestamp (as int), "Y-m-d", "Y-m-d H:i:s"
 * and nothing (null) as argument. Will return an array with
 * information about the week.
 *
 * @author Jonas Björk, jonas@jonasbjork.net
 */
  public static function getWeek($date = false) {
    $week = array();
    if (!$date) {
    $date = time();
    }
    if (is_int($date)) {
      $date = $date;
      $week['date'] = date("Y-m-d", $date);
      $week['date_unix'] = $date;
      $week['year'] = date("Y", $date);
    } else {
      $week['date'] = $date;
      $date = strtotime($date);
      $week['date_unix'] = $date;
      $week['year'] = date("Y", $date);      
    }
    if(date("w", $date)==1) {
      $week['monday'] = date("Y-m-d", $date);
      $week['monday_unix'] = $date;
    } else {
      $week['monday'] = date("Y-m-d", strtotime("last Monday", $date));
      $week['monday_unix'] = strtotime("last Monday", $date);
    }
    if(date("w", $date)==0) {
      $week['sunday'] = date("Y-m-d", $date);
      $week['sunday_unix'] = $date;
    } else {
      $week['sunday'] = date("Y-m-d", strtotime("next Sunday", $date));
      $week['sunday_unix'] = strtotime("next Sunday", $date);
    }
    $week['week_number'] = date("W", $date);
    return $week;
  }
   
/**
 * This function returns the week array for the requested year and week
 *
 * @param string $year 
 * @param string $week 
 * @return array weekarray
 * @author Aller Internet, Kristian Erendi
 */
  public static function getDateFromWeek($year, $week) {
   $udate = strtotime("01 January $year + $week weeks");
   return self::getWeek(date('Y-m-d', $udate));
  }
   
   
/**
 * this function adds or subtracts weeks to the submitted date
 * if no date is submitted then current time is used
 *
 * @param string $date
 * @return void
 * @author Aller Internet, Kristian Erendi
 */  
  public static function addWeek($i, $date = false) {
    if (!$date) {
      $udate = time();
    }
    if (is_int($date)) {
      $udate = $date;
    }
    if($i > 0 ){
      $i = '+'.$i;
    } 
    $date = date("Y-m-d", strtotime(date("Y-m-d", $udate) . " $i week"));
    return self::getWeek($date);
  } 
    
}
?>