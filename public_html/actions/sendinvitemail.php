<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
	if ( isset($_POST['email']) )
	{
		$sendMails = array();
		$faultyMails = array();
		$mailExistsAsMember = array();
		$mailExistsAsTip = array();
		$maxBatchSize = 5;
		$currentBatchCount = 0;
		foreach ($_POST['email'] as $email)
		{
			$count = 0;
			
			if (!empty($email) && Misc::isEmail($email))
			{
				$count = $db->allValuesAsArray('select id, anamn as username from mm_medlem where epost = "'.mysql_real_escape_string(trim($email)).'" limit 1;');
				if ( empty($count) )
				{
					$count = $db->nonquery('select * from mm_inbjudningar where epost = "'.mysql_real_escape_string($email).'" limit 1;');
					if ( $count == 0 )
					{
						$sendMails[] = $email;
						
					}
					else
					{
						$mailExistsAsTip[] =  $email;
					}
				}
				else
				{
					foreach ($count as $key => $value)
					{
						$mailExistsAsMember[] = $arrayName = array('email' => $email, 'id' => $count[$key]['id'], 'username' => $count[$key]['username']);
					}
				}
			}
			else if(!empty($email))
			{
				$faultyMails[] = $email;
			}

			if ($currentBatchCount > 5)
			{
				break;
			}
			$currentBatchCount++;
		}
		$subject = 'Inbjudan från '.$USER->getFNamn();
		$message = 'Din vän '.$USER->getFNamn().' har bjudit in dig till MotioMera - Sveriges roligaste motionstävling. MotioMera är en tjänst för dig som vill röra på dig - och samtidigt ha kul!

Gå till http://www.motiomera.se och bli medlem.
Vi ses på MotioMera!

MotioMera - Sveriges roligaste stegtävling
En tjänst från tidningen MåBra
www.motiomera.se';

		foreach ($sendMails as $key => $email)
		{
			if (@!Misc::sendEmail($email, $SETTINGS["email"], $subject, $message))
			{
				unset($sendMails[$key]);
				$faultyMails[] = $email;
			}
			else
			{
				$db->nonquery('INSERT INTO mm_inbjudningar (medlem_id,epost) VALUES ('.$USER->getId().',"'.$email.'");');
			}
		}

		$smarty = new MMSmarty;
		$smarty->assign('sent', true);
		$smarty->assign('sendMails', $sendMails);
		$smarty->assign('faultyMails', $faultyMails);
		$smarty->assign('mailExistsAsMember', $mailExistsAsMember);
		$smarty->assign('mailExistsAsTip', $mailExistsAsTip);

		$smarty->display('bjud_in_van.tpl');
	}
?>