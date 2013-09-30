<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

$fb = new stdClass;
$fb->loggedin = 0;
$fb->mm_id = 0;
$fb->connected = 0;
$fb->status = 0;
$fb->msg = '';
!empty($_REQUEST['email']) ? $fb->email = Security::secure_postdata($_REQUEST['email']) : $fb->email = '';
!empty($_REQUEST['fbid']) ? $fb->fbid = Security::secure_postdata($_REQUEST['fbid']) : $fb->fbid = '';
!empty($_REQUEST['type']) ? $fb->type = Security::secure_postdata($_REQUEST['type']) : $fb->type = 'login';
try {
  switch ($fb->type) {
    case 'login':
      $id = Medlem::loggaInFb($fb->fbid, $fb->email);
      if ($id > 0) {
        $fb->status = 1;
        $fb->loggedin = 1;
        $fb->mm_id = $id;
      }
      break;
    case 'connect':
      try {
        global $USER;
        Security::demand(USER);
        $fbid = $USER->getFbId();
        $email = $USER->getEpost();
        if ($fbid != '') {
          if ($fbid == $fb->fbid) {  //allready connected - do nothing
            $fb->status = 2;
            $fb->msg = "Kontot redan kopplat till Facebook";
          } else {
            $fb->status = -1;
            $fb->msg = "MotioMera-kontot är redan kopplat till ett annat Facebook-konto. Läs på frågor och svar för mer information.";
          }
        } else {  //connect this account with facebook, no matter what email is used
          $USER->setFbId($fb->fbid);
          $USER->commit();
          $fb->status = 1;
          $fb->msg = "Facebook-kopplingen är nu klar. Nästa gång du loggar in kan du använda Facebookknappen";
        }
      } catch (Exception $exc) {
        $fb->status = 0;
        $fb->msg = $exc->getTraceAsString();
        echo json_encode($fb);
        die();
      }
      /*

        $ok = Medlem::connectFb($fb->fbid, $fb->email);
        726694464
        if ($ok > 0) {
        $fb->status = 1;
        $fb->connected = 1;
        }
       * 
       */
      break;
    default:
      break;
  }
  echo json_encode($fb);
  die();
} catch (MedlemException $e) {
  if ($e->getCode() == -5) {
    $fb->status = -5;
    $fb->msg = "Felaktig inloggning";
  } else if ($e->getCode() == -15) {
    $fb->status = -15;
    $fb->msg = "Kontot ej aktiverat Du måste aktivera ditt konto för att kunna logga in";
  } else if ($e->getCode() == -19) {  //$urlHandler->redirect("Medlem", URL_BUY, $e->getMedlemId());
    $fb->status = -19;
    $fb->msg = "Redir till köpsidan";
  }
  echo json_encode($fb);
  die();
}
