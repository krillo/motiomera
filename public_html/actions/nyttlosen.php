<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

try{
	Medlem::nyttLosen($_POST["epost"]);
}catch(MedlemException $e){
	if($e->getCode() == -17){
		throw new UserException("Felaktig e-postadress", "E-postadressen är inte knuten till något konto.");
	}

}

throw new UserException("Lösenord skickat", "Ett nytt lösenord har skapats och skickats till den angivna e-postadressen.");

?>