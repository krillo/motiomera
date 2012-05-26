<?php
function smarty_modifier_nice_tal_1($tal,$decimals=false){	
	return number_format($tal, $decimals, ",", "");
}
?>