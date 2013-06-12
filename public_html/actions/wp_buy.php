<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
if (!isset($_POST) or empty($_POST)) {
  throw new UserException('Felaktigt anrop', 'Sättet att anropa denna sida var felaktig försök igen här: <a href="' . $urlHandler->getUrl('Medlem', URL_CREATE) . '">Bli Medlem</a>');
}
print_r($_REQUEST);

