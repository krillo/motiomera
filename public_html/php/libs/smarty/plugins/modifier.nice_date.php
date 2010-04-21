<?php

function smarty_modifier_nice_date($string, $format = "Y-m-d H:i:s", $minimum = "m", $den = false)
{

	$months = array('', 'januari', 'februari', 'mars', 'april', 'maj', 'juni', 'juli', 'augusti', 'september', 'oktober', 'november', 'december');

	$string = strtotime($string);

	$date = date("Y-m-d", $string);
  
	if(date("Y-m-d") == $date){

		if(time() - $string < 60*60 && $minimum == "m"){
			$minutes = round((time() - $string)/60) + 1;
			if($minutes == 1)
				return "1 minut sedan";
			else
				return $minutes . " minuter sedan";
		}else{
			return "idag";
		}
	}else if(date("Y-m-d", strtotime("-1 day")) == $date){
		return "igår";
	}else if(date("Y-m-d", strtotime("+1 day")) == $date){
		return "i morgon";
	}else{
		
		if(substr($format, 0 , 1) == "j"){
			$day = date("j", $string);
			if ($day == 1 || $day == 2 || $day == 31)
				$day .= ":a";
			else
				$day .= ":e";
				
			if(substr($format, 0, 3) == "j F")
				$month = $months[date("n", $string)];
			$denStr = ($den) ? "den" : "";
			return $denStr . " " . $day . " " . $month . date(substr($format, 3), $string);
		}else{
			return date($format, $string);
		}	
	}
}

?>
