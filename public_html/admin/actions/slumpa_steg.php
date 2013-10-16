<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
Security::demand(ADMIN);
!empty($_REQUEST['mm_id']) ? $mm_id = addslashes($_REQUEST['mm_id']) : $mm_id = '';
echo "Steg slumpas ut till användare: $mm_id";
if (!empty($mm_id)) {
  Misc::setTestData($mm_id);
} else {
  echo '<h3>Det saknas paramatrar!</h3>';
}