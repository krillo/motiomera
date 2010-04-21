<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

if(!empty($_POST['type']) && $_POST['type']="all"){
	echo json_encode( Kommun::listAll(true, true));
}else{
	if(!empty($_POST['kommun_id'])){
		$surrounding = Kommun::getAngransandeKommun($_POST['kommun_id'], true);
		if ( !empty($surrounding) )
		{
			$temp = json_encode($surrounding);
			echo $temp;
		}
	}	
}
?>
