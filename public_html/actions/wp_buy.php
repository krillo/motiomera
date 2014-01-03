<?php
// Krillo 2014-01-03
// This file is actually not used since jquery.mmwp.buy.js catches the submit and redirects it to either "/actions/payson_foretag.php" or "/actions/payson_privat.php"
//
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
if (!isset($_POST) or empty($_POST)) {
  throw new UserException('Felaktigt anrop', 'Sättet att anropa denna sida var felaktig försök igen här: <a href="/">Bli Medlem</a>');
}
print_r($_REQUEST);

