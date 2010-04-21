<?php

function smarty_modifier_stegToKm($string)
{
	return round(Steg::stegToKm($string) * 10) / 10;
}

/* vim: set expandtab: */

?>
