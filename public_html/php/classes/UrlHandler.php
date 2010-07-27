<?php

/**
* Class and Function List:
* Function list:
* - redirect()
* - getUrl()
* - back()
* Classes list:
* - UrlHandler
* - UrlHandlerException extends Exception
*/
/*

Hjälpklass för att generera URL:er till gemensamma metoder för klasser

Syntax:

Argument skickas in som en string, int eller en array med flera värden.
UrlHandler söker sedan igenom url:en efter tecknet '*' och ett index. *0 ersätts med det första inskickade värdet, *1 med det andra osv.
För att ange standardvärden används "[värde]" och dessa måste komma sist i url:en.
Man kan även göra sub-anrop utan argument §Klass.KONSTANT§ eller med argument §Klass.KONSTANT|*0§

Exempel 1: Argument som värde

Värden:		"Grupp", URL_DELETE, 2
URL: 		"/actions/delete.php?table=grupp&id=%&redirect=[/grupper.php]"
Resultat:	/actions/delete.php?table=grupp&id=2&redirect=/grupper.php

Exempel 2: Argument som array

Värden:		"Grupp", URL_DELETE, array(2, "/index.php")
URL: 		"/actions/delete.php?table=grupp&id=*0&redirect=[/grupper.php]"
Resultat:	/actions/delete.php?table=grupp&id=2&redirect=/index.php

Exempel 3: Sub-anrop

Värden: 	"Grupp", URL_SAVE, 5
URL: 		"/actions/save.php?table=grupp&id=*0&redirect=[§Grupp.URL_VIEW|*0§]"
Resultat:    /actions/save.php?table=grupp&id=5&redirect=%2Fpages%2Fgrupp.php%3Fid%3D2

Smarty

Såhär skriver du ut en url via smarty:
{$urlHandler->getUrl(Medlem, URL_CREATE)}
{$urlHandler->getUrl(Grupp, URL_DELETE, $grupp->getId())}


*/

class UrlHandler
{
	
	protected $urls = array(
		"Site" => array(
			"admin" => "/admin/",
			"login" => "/pages/login.php",
			"logout" => "/actions/logout.php?who=*0",
		) ,
		"Admin" => array(
			URL_VIEW => "/admin/pages/installningar.php",
			URL_ADMIN_LIST => "/admin/pages/listadmins.php",
			URL_ADMIN_CREATE => "/admin/pages/editadmin.php?created=*0",
			URL_ADMIN_EDIT => "/admin/pages/editadmin.php?id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=admin&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=admin&id=*0"
		) ,
		"RssFlow" => array(
			URL_VIEW => "/pages/minblogg.php?mid=*0",
		) ,
		"Medlem" => array(
			URL_EDIT => "/pages/installningar.php?tab=*0",
			URL_VIEW => "/pages/profil.php?mid=*0",
			URL_VIEW_OWN => "/pages/minsida.php",
			URL_CREATE => "/pages/blimedlem.php",
			URL_SAVE => "/actions/save.php?table=medlem&id=*0",
			URL_INVITE => "/pages/blimedlem.php?inv=*0",
			URL_DELETE => "/actions/delete.php?table=medlem&id=*0",
			URL_ADMIN_LIST => "/admin/pages/medlemmar.php",
			URL_ADMIN_EDIT => "/admin/pages/medlem.php?id=*0",
			URL_ADMIN_EDIT_PASS => "/admin/pages/medlem.php?id=*0&passmsg=*1",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=medlem&id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=medlem&id=*0",
			URL_NYTTLOSEN => "/actions/nyttlosen.php",
			URL_BUY => "/pages/bestall.php?id=*0",
			URL_BLOCK_MEMBER => "/actions/blockuser.php?bmid=*0",
			URL_RESET_STEG => "/actions/delete.php?table=verifieraallasteg"
		) ,
		"FastaUtmaningar" => array(
			URL_ADMIN_CREATE => "/admin/actions/save.php?table=fastautmaningar",
			URL_ADMIN_LIST => "/admin/pages/fastautmaningar.php",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=fastautmaningar&rid=*0",
			URL_SAVE => "/actions/save.php?table=fastrutt&rid=*0",
			URL_SWITCH => "/actions/save.php?table=fastrutt&mid=*0",
			URL_ADMIN_SAVE => "/admin/pages/fastautmaningar.php?created",
		) ,
		"Grupp" => array(
			URL_EDIT => "/pages/editklubb.php?id=*0",
			URL_VIEW => "/pages/klubb.php?id=*0",
			URL_CREATE => "/pages/editklubb.php",
			URL_DELETE => "/actions/delete.php?table=grupp&id=*0",
			URL_SAVE => "/actions/save.php?table=grupp&id=*0",
			URL_LIST => "/pages/klubbar.php",
			URL_KICK => "/actions/kick.php?table=grupp&gid=%0&mid=*1",
			URL_EXT_INVITE => "/actions/inviteemail.php",
			URL_INVITE => "/actions/invite.php",
		) ,
		"Lag" => array(
			URL_CREATE => "/pages/editlag.php?fid=*0",
			URL_EDIT => "/pages/editlag.php?lid=*0",
			URL_VIEW => "/pages/lag.php?lid=*0",
			URL_SAVE => "/actions/save.php?table=lag&id=*0",
			URL_ADMIN_SAVE => "/actions/save.php?table=lag&fid=*0",
			URL_DELETE => "/actions/delete.php?table=lag&id=*0",
			URL_RANDOMIZE_TEAMS => "/actions/save.php?table=randomteams&fid=*0",
		) ,
		"LagNamn" => array(
			URL_CREATE => "/admin/pages/editlagnamn.php",
			URL_EDIT => "/admin/pages/editlagnamn.php?lid=*0",
			URL_VIEW => "/admin/pages/lagnamn.php?lid=*0",
			URL_SAVE => "/admin/actions/save.php?table=lagnamn",
			URL_DELETE => "/admin/actions/delete.php?table=lagnamn&id=*0",
			URL_ADMIN_LIST => "/admin/pages/lagnamn.php"
		) ,
		"MinaSteg" => array(
			URL_VIEW => "/pages/stegbydatum.php?datum=*0",
			URL_LIST => "/pages/rapport.php"
		) ,
		"MinaQuiz" => array(
			URL_LIST => "/pages/minaquiz.php?id=*0",
			URL_CREATE => "/pages/minaquizskapa.php",
			URL_EDIT => "/pages/minaquizandra.php?id=*0",
			URL_VIEW => "/pages/minaquizvisa.php?id=*0",
			URL_DELETE => "/actions/delete.php?table=minaquiz&id=*0",
			URL_SAVE => "/actions/save.php?table=minaquiz&qid=*0"
		) ,
		"MinaQuizFragor" => array(
			URL_DELETE => "/actions/delete.php?table=minaquizfragor&qid=*0&fid=*0",
		) ,
		"Steg" => array(
			URL_EDIT => "/pages/steg.php?sid=*0",
			URL_CREATE => "/pages/steg.php",
			URL_DELETE => "/actions/delete.php?table=steg&id=*0&redirect=[/pages/minsida.php]",
			URL_SAVE => "/actions/save.php?table=steg&id=*0",
			URL_LIST => "/pages/rapport.php",
		) ,
		"Kommun" => array(
			URL_CREATE => "/admin/pages/editkommun.php",
			URL_SAVE => "/admin/actions/save.php?table=kommun&id=*0",
			URL_EDIT => "/admin/pages/editkommun.php?kid=*0",
			URL_VIEW => "/kommun/*0/",
			URL_ADMIN_LIST => "/admin/pages/kommuner.php",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=kommun&id=*0",
			URL_CHOOSE => "/pages/valjKommun.php",
		) ,
		"Rutt" => array(
			URL_VIEW => "/pages/rapport.php?id=*0",
			URL_EDIT => "/pages/valj_rutt.php"
		) ,
		"Mal" => array(
			URL_ADMIN_LIST => "/admin/pages/listmal.php",
			URL_ADMIN_EDIT => "/admin/pages/mal.php?id=*0",
			URL_ADMIN_CREATE => "/admin/pages/mal.php",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=mal&id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=mal&id=*0"
		) ,
		"MalManager" => array(
			URL_SAVE => "/actions/save.php?table=malmanager",
			URL_DELETE => "/actions/delete.php?table=malmanager",
			URL_VIEW => "/pages/rapport.php"
		) ,
		"Kommunavstand" => array(
			URL_DELETE => "/admin/actions/delete.php?table=kommunavstand&id=*0&target=*1&redirect=§Kommun.URL_EDIT|*0§",
			URL_SAVE => "/admin/actions/save.php?table=kommunavstand&id=*0&redirect=§Kommun.URL_EDIT|*0§"
		) ,
		"Stracka" => array(
			URL_CREATE => "/pages/minsida.php",
			URL_SAVE => "/actions/save.php?table=stracka&id=*0",
			URL_DELETE => "/actions/delete.php?table=stracka&id=*0"
		) ,
		"AnslagstavlaRad" => array(
			URL_SAVE => "/actions/save.php?table=anslagstavlarad",
		) ,
		"Foretag" => array(
			URL_NEW_CONTEST => '/actions/save.php?table=newcontest&fid=*0',
			URL_ADMIN_LIST => "/admin/pages/listforetag.php?search=&field=id&limit=20&offset=0&showValid=true",
			URL_ADMIN_EDIT => "/admin/pages/editforetag.php?fid=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=foretag",
			URL_LIST => "/pages/listforetag.php",
			URL_EDIT => "/pages/editforetag.php?fid=*0&tab=*1",
			URL_SAVE => "/actions/save.php?table=foretag&id=*0",
			URL_CREATE => "/pages/editforetag.php",
			URL_VIEW => "/pages/foretag.php?fid=*0",
			URL_DELETE => "/actions/delete.php?table=foretag&id=*0",
			URL_DELETE_ALL => "/actions/delete.php?table=alla_lag&id=*0",
			URL_GENERATE_KEYS => "/actions/generatekeys.php",
			URL_REMOVE_USER => "/actions/save.php?table=gaurlag&fid=*0&id=*1",
      URL_RECLAMATION => "/actions/reclamation.php?table=foretag&fid=*0&id=*1",			
		) ,
		"Order" => array(
			URL_SEND => "/actions/sendorder.php",
			URL_ADMIN_ORDER_LIST => "/admin/pages/listorder.php?search=&field=id&limit=20&offset=0&showValid=true",		
		) ,		
		"Foretagstavling" => array(
			URL_VIEW => "/pages/foretagstavling.php",
		) ,
		"Visningsbild" => array(
			URL_ADMIN_LIST => "/admin/pages/visningsbilder.php",
			URL_ADMIN_SAVE => "/admin/actions/upload.php?do=visningsbild",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=visningsbild&id=*0",
			URL_LIST => "/pages/installningar.php?tab=1",
			URL_SET_DEFAULT => "/admin/actions/setstandard.php?id=*0",
			URL_SAVE => "/actions/save.php?table=visningsbild&id=*0"
		) ,
		"CustomVisningsbild" => array(
			URL_EDIT => "/pages/installningar.php?tab=1",
			URL_SAVE => "/actions/upload.php?do=customvisningsbild",
			URL_DELETE => "/actions/delete.php?table=customvisningsbild&id=*0",
			URL_ADMIN_LIST => "/admin/pages/customvisningsbilder.php",
			URL_ADMIN_APPROVE => "/admin/actions/approve.php?do=customvisningsbild&id=*0"
		) ,
		"CustomLagbild" => array(
			URL_SAVE => "/actions/upload.php?do=customlagbild&lagid=*0",
		) ,
		"CustomForetagsbild" => array(
			URL_SAVE => "/actions/upload.php?do=customforetagsbild",
			URL_ADMIN_SAVE => "/actions/upload.php?do=customforetagsbild&fid=*0",
		) ,
		"Kommunbild" => array(
			URL_SAVE => "/admin/actions/save.php?table=kommunbild&id=*0",
			URL_DELETE => "/admin/actions/delete.php?table=kommunbild&id=*0",
			URL_VIEW => "/pages/bild.php?typ=kommunbild&id=*0",
		) ,
		"Kommunvapen" => array(
			URL_SAVE => "/admin/actions/upload.php?do=kommunvapen&kid=*0",
			URL_DELETE => "/admin/actions/delete.php?table=kommunvapen&id=*0"
		) ,
		"Kommunkarta" => array(
			URL_SAVE => "/admin/actions/upload.php?do=kommunkarta&kid=*0",
			URL_DELETE => "/admin/actions/delete.php?table=kommunkarta&id=*0"
		) ,
		"Avatar" => array(
			URL_ADMIN_LIST => "/admin/pages/avatarer.php",
			URL_ADMIN_SAVE => "/admin/actions/upload.php?do=avatar",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=avatar&id=*0"
		) ,
		"Quiz" => array(
			URL_VIEW => "/kommun/*0/quiz/",

			/*URL_LIST=>			"/pages/kommunquiz.php",*/
			URL_LIST => "/pages/kommunjakten.php",
		) ,
		"KontrolleraBilder" => array(
			URL_ADMIN_LIST => "/admin/pages/kontrollerabilder.php",
		) ,
		"TextEditor" => array(
			URL_ADMIN_LIST => "/admin/pages/texteditor.php",
			URL_ADMIN_CREATE => "/admin/pages/texteditor_edit.php",
			URL_ADMIN_EDIT => "/admin/pages/texteditor_edit.php?id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=texteditor&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=texteditor&id=*0"
		) ,
		"Paminnelser" => array(
			URL_ADMIN_LIST => "/admin/pages/paminnelser.php",
		) ,
		"PaminnelseSQL" => array(
			URL_ADMIN_EDIT => "/admin/pages/paminnelser_andrasql.php?qid=*0",
			URL_ADMIN_CREATE => "/admin/pages/paminnelser_skapasql.php",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=paminnelse_sql&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=paminnelse_sql&id=*0",
			'TrialRun' => "/admin/pages/paminnelser_provkorsql.php?qid=*0",
			'TrialRunFake' => "/admin/pages/paminnelser_provkorsql.php?fake=1&qid=*0",
		) ,
		"PaminnelseMeddelande" => array(
			URL_ADMIN_EDIT => "/admin/pages/paminnelser_andramedd.php?mid=*0",
			URL_ADMIN_CREATE => "/admin/pages/paminnelser_skapamedd.php",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=paminnelse_meddelanden&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=paminnelse_meddelanden&id=*0",
		) ,
		"ProfilData" => array(
			URL_ADMIN_LIST => "/admin/pages/profildata.php",
			URL_ADMIN_CREATE => "/admin/pages/profildata_edit.php",
			URL_ADMIN_EDIT => "/admin/pages/profildata_edit.php?id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=profildata&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=profildata&id=*0"
		) ,
		"ProfilDataVal" => array(
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=profildataval&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=profildataval&id=*0"
		) ,
		"QuizFraga" => array(
			URL_ADMIN_LIST => "/admin/pages/quiz.php?kid=*0",
			URL_ADMIN_CREATE => "/admin/pages/editquiz.php?kid=*0",
			URL_ADMIN_EDIT => "/admin/pages/editquiz.php?id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=quizfraga&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=quizfraga&id=*0",
		) ,
		"QuizAlternativ" => array(
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=quizalternativ",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=quizalternativ&id=*0",
		) ,
		"ProQuiz" => array(
			URL_ADMIN_LIST => "/admin/pages/proquiz.php?id=*0",
			URL_ADMIN_CREATE => "/admin/pages/proquizskapa.php",
			URL_ADMIN_EDIT => "/admin/pages/proquizandra.php?id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=minaquiz&id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=minaquiz&qid=*0"
		) ,
		"Adressbok" => array(
			URL_VIEW => "/pages/adressbok.php?tab=*0",
			URL_SAVE => "/actions/save.php?table=adressbok&mid=*0",
			URL_DELETE => "/actions/delete.php?table=adressbok&mid=*0"
		) ,
		"Aktivitet" => array(
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=aktivitet",
			URL_ADMIN_LIST => "/admin/pages/aktiviteter.php",
			URL_ADMIN_EDIT => "/admin/pages/aktiviteter.php?id=*0",
		) ,
		"MergeOrder" => array(
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=mergeorder",
			URL_ADMIN_MERGE => "/admin/pages/mergeorder.php?status=*0",
		) ,		
		"Fotoalbum" => array(
			URL_LIST => "/pages/fotoalbum.php?mid=*0",
			URL_VIEW => "/pages/fotoalbumvisa.php?fid=*0",
			URL_CREATE => "/pages/fotoalbumskapa.php",
			URL_EDIT => "/pages/fotoalbumandra.php?fid=*0",
			URL_DELETE => "/pages/fotoalbumtabort.php?fid=*0",
			URL_SAVE => "/actions/save.php?table=fotoalbum&fid=*0",
		) ,
		"FotoalbumBild" => array(
			URL_VIEW => "/pages/fotoalbumvisabild.php?id=*0",
			URL_CREATE => "/pages/fotoalbumbildladdaupp.php?fid=*0",
			URL_SAVE => "/actions/upload.php?do=fotoalbumbild&fid=*0",
			URL_DELETE => "/actions/delete.php?table=fotoalbumbild&id=*0",
		) ,
		"MotiomeraMail" => array(
			URL_CREATE => "/pages/mail.php?do=send&mid=*0",
			URL_VIEW => "/pages/mail.php?do=*0",
			URL_LIST => "/pages/mail.php?do=inbox&folder_id=*1",
			URL_EDIT => "/pages/mail.php?do=manage_folders",
			URL_SAVE => "/actions/skickameddelande.php?mid=*0",
		) ,
		"InternMail" => array(
			URL_CREATE => "/pages/mail.php?do=send&mid=*0",
			URL_VIEW => "/pages/mail.php?do=inbox",
			URL_SAVE => "/actions/skickameddelande.php?mid=*0",
		) ,
		"MailSentItems" => array(
			URL_VIEW => "/pages/mail.php?do=outbox",
		) ,
		"MailManageFolder" => array(
			URL_VIEW => "/pages/mail.php?do=manage_folders",
		) ,
		"KontaktMail" => array(
			URL_SEND => "/actions/sendmail.pgp",
		) ,
		"Help" => array(
			URL_ADMIN_LIST => "/admin/pages/helpers.php",
			URL_ADMIN_CREATE => "/admin/pages/help_edit.php",
			URL_ADMIN_EDIT => "/admin/pages/help_edit.php?id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=help&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=help&id=*0"
		) ,
		"Topplista" => array(
			URL_LIST => "/pages/topplistor.php?id=*0&klubb=*1",
			URL_VIEW => "/pages/topplista.php?lista=*0"
		) ,
		"Kommunjakten" => array(
			URL_VIEW => "/kommunjakten/*0",
		) ,
		"Level" => array(
			URL_ADMIN_LIST => "/admin/pages/listLevels.php",
			URL_ADMIN_CREATE => "/admin/pages/editLevel.php",
			URL_ADMIN_EDIT => "/admin/pages/editLevel.php?id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=level&id=*0",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=level&id=*0",
			URL_SET_DEFAULT => "/admin/actions/save.php?table=level&action=default&id=*0",
		) ,
		"SajtDelar" => array(
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=sajtdelar&args=*0"
		) ,
		"Kommundialekt" => array(
			URL_CREATE => "/pages/laggtilldialekt.php?kid=*0",
			URL_SAVE => "/actions/save.php?table=kommundialekt",
			URL_ADMIN_CREATE => "/admin/pages/editkommundialekt.php?kid=*0",
			URL_ADMIN_EDIT => "/admin/pages/editkommundialekt.php?id=*0",
			URL_ADMIN_SAVE => "/admin/actions/save.php?table=kommundialekt",
			URL_ADMIN_DELETE => "/admin/actions/delete.php?table=kommundialekt&id=*0",
			URL_ADMIN_LIST => "/admin/pages/listkommunfiler.php",
			URL_ADMIN_APPROVE => "/admin/actions/approve.php?do=kommundialekt&mod=approve&id=*0",
			URL_ADMIN_UNAPPROVE => "/admin/actions/approve.php?do=kommundialekt&mod=unapprove&id=*0",
		) ,
	);
	
	public function redirect($class, $action, $args = null)
	{
		header("Location: " . $this->getUrl($class, $action, $args, false));
		exit;
	}
	
	public function getUrl($class, $action, $args = null, $amp = true)
	{
		
		if (defined($action)) {
			$action = constant($action);
		}
		$url = $this->urls[$class][$action];
		$index = 0;
		while (strpos($url, '§') !== false) { // Ersätt alla §§ med anrop

			$start = strpos($url, '§');
			$stop = strpos($url, '§', $start + 1);
			$query = substr($url, $start + 2, $stop - $start - 2);
			$dot = strpos($query, ".");
			$subclass = substr($query, 0, $dot);
			$next = strpos($query, "|");
			
			if ($next !== false) { // om argument skickatas med

				$argindex = substr($query, $next + 2);
				$subconst = substr($query, $dot + 1, $next - $dot - 1);
				$subarg = (is_array($args)) ? urlencode($args[$argindex]) : urlencode($args);
			} else {
				$subconst = substr($query, $dot + 1);
				$subarg = null;
			}
			$suburl = urlencode($this->getUrl($subclass, $subconst, $subarg));
			$url = substr($url, 0, $start) . $suburl . substr($url, $stop + 2);
		}
		while (strpos($url, '*') !== false) { // Ersätt alla *i med matchande argument

			$pos = strpos($url, '*');
			$nr = substr($url, $pos + 1, 1);
			$value = (is_array($args)) ? urlencode($args[$nr]) : urlencode($args);
			$url = substr($url, 0, $pos) . $value . substr($url, $pos + 2);
			$index++;
		}
		while (strpos($url, '[') !== false) { // Ersätt alla [] med värden

			$start = strpos($url, '[');
			$stop = strpos($url, ']');
			
			if (($index > 0 && !is_array($args)) || (count($args) > $index + 1)) { // Om inget argument finns används standardvärdet

				$url = substr($url, 0, $start) . substr($url, $start + 1, $stop - $start - 1) . substr($url, $stop + 1);
			} else {
				$value = (is_array($args)) ? urlencode($args[$index]) : urlencode($args);
				$url = substr($url, 0, $start) . $value . substr($url, $stop + 1);
				$index++;
			}
		}
		
		if ($amp) $url = str_replace("&", "&amp;", $url);
		return $url;
	}
	
	public function back()
	{
		header("Location: " . $_SERVER["HTTP_REFERER"]);
		exit;
	}
}

class UrlHandlerException extends Exception
{
}
?>
