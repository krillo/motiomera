<?php

function smarty_modifier_mm_countable($nr, $sing, $plu){

	if($nr == 1){
		return $sing;
	}else{
		return $plu;
	}

}

?>
