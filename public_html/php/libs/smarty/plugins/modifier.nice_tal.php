<?php

function smarty_modifier_nice_tal($tal,$decimals=false)
{	
	return number_format($tal, $decimals, ",", " ");
}

?>
