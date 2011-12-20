{if isset($debugSmarty) && isset($_GET.debugSmarty)}
	{debug}
{/if}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{*}<title>{if isset($pagetitle)}{$pagetitle} &mdash; {/if}{$pagename}</title>{*}
  <title>Stegtävling för företag</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Motiomera" />
	<meta name="keywords" content="Motiomera" />
	<link rel="stylesheet" href="/css/motiomera.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
        {if isset($USER) or isset($ADMIN) or isset($FORETAG)}
	{if !NO_INTERNET || GOOGLEMAPS_OVERRIDE_NO_INTERNET}
	{if $urlChecker->getFileName() eq 'minsida.php' or 'fastautmaningar.php'}
		<script type="text/javascript" src="http://www.google.com/jsapi?key={$GOOGLEMAPS_APIKEY}"></script>
	{/if}
	{/if}
        {/if}
	{foreach from=$urlChecker->getJsPackage() item=file}
		<script type="text/javascript" src="{$file}"></script>
	{/foreach}
	<script type="text/javascript">
		{literal}
		var mmPopup;
		// remove the registerOverlay call to disable the controlbar
		hs.registerOverlay(
			{
				thumbnailId: null,
				overlayId: 'controlbar',
				position: 'top right',
				hideOnMouseOut: true
			}
		);
		hs.showCredits = false;
		hs.graphicsDir = '/js/highslide/graphics/';
		hs.outlineType = 'rounded-white';
		// Tell Highslide to use the thumbnails title for captions
		hs.captionEval = 'this.thumb.title';
		{/literal}
	</script>


{literal}
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28332028-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
{/literal}
</head>	
<body id="body" class="{$BROWSER} {$currentPage}">
<div id="mmPage">
	<div id="mmWrapTop"></div>
	<div id="mmWrapMiddle">
		<div id="mmBannerArea" class="mmFlash">

			<div id="mmBannerTop">
        {*}
				{if !NO_INTERNET}
				  {php} include($_SERVER["DOCUMENT_ROOT"]."/pages/doubleclick_tile1.php"); {/php} 				  
				{/if}
        {*}
			</div>

			<div id="mmBannerSmall">
				<div class="mmBannerSmallInside">
        {*}
				{if !NO_INTERNET}
				<script type="text/javascript">
				<!--
					document.write('<scr' + 'ipt type="text/javascript" src="http://tvguiden.se/js/allerskvinna.js"></scr' + 'ipt>');
				-->
				</script>
				{/if}
        {*}
				</div>
			</div>
		</div>
		
		<div id="mmHeader">
			<div id="mmLogo">
				<a href="/"><img src="/img/framework/mmLogo.gif" alt="Motiomera" /></a>
			</div>
			<div class="noprint">
				{if isset($USER) or isset($ADMIN) or isset($FORETAG)}
				<div id="mmCommunityToolbar">
					{if !isset($inAdmin) && !isset($FORETAG) && $USER->getOlastaMail() > 0}
					
						{if $USER->getOlastaMail() > 1}
							<a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}"><img src="/img/icons/MailUnreadIcon_greenBG.gif" alt="{$USER->getOlastaMail()} olästa mail" /></a> <a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}">{$USER->getOlastaMail()} olästa mail</a>
						{else}
							<a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}"><img src="/img/icons/MailUnreadIcon_greenBG.gif" alt="1 oläst mail" /></a> <a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}">1 oläst mail</a>
						{/if}
					
					{/if}
					<br/><br/>
					{if $adressbok}
						{if $adressbok->listForfragningar()|@count > 0}
							<a href="{$urlHandler->getUrl(Adressbok, URL_VIEW, 3)}"><img src="/img/icons/AdressbokAddIcon.gif" alt="Vänner" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl(Adressbok, URL_VIEW, 3)}">{$adressbok->listForfragningar()|@count} {$adressbok->listForfragningar()|@count|mm_countable:"ny vän":"nya vänner"}</a>
						{/if}
					{/if}
				</div>
				<div id="mmInloggad">
					{if isset($inAdmin)}
						Välkommen <strong>{$ADMIN->getANamn()}</strong><br />
						<a href="/admin/actions/logout.php">Logga ut</a>
					{elseif isset($FORETAG)}
						Välkommen <strong>{$FORETAG->getNamn()}</strong><br />
						<a href="/actions/logout.php">Logga ut</a>
					{else}
						Välkommen <strong>{$USER->getANamn()}!</strong><br />
						<a href="/actions/logout.php">Logga ut</a>
					{/if}
				</div>
				{elseif isset($tavlingsresultatsidan) && isset($medlem)}
				<div id="mmCommunityToolbar">
				</div>
				<div id="mmInloggad">
					Välkommen <strong>{$medlem->getANamn()}!</strong><br />
					<a href="/">Till startsidan</a>
				</div>
				{else}
				<div id="mmLoggaIn" class="mmFontWhite">
					<form action="/actions/login.php" method="post">
						<table width="290" cellspacing="1" cellpadding="0" border="0">
							<tr>
								<td class="mmLoggaInTitle">E-postadress: </td>
								<td><input name="username" id="username" value="" class="mmTextField" size="17" type="text" maxlength="96" tabindex="1" /></td>
								<td class="mmLoggaInCheckbox"><input type="checkbox" id="autologin" name="autologin" value="on" tabindex="3" /> <label for="autologin">Kom ihåg mig</label></td>
							 </tr>
							 <tr>
								 <td class="mmLoggaInTitle">L&ouml;senord: </td>
								 <td><input name="password" id="password" value="" size="17" class="mmTextField" type="password" maxlength="96" tabindex="2"/></td>
								 <td><input type="hidden" name="login" value="Login"/><input type="image" src="/img/icons/LoggaInIcon.gif" alt="Logga in" tabindex="4" /></td>
							 </tr>
						</table>
					</form>
				</div>
				{/if}
				<div id="mmMaBraLogo">
				{if isset($USER) || isset($FORETAG)}
					{foreach from=$helpers item=helper}
						<a href="javascript:;" onclick="mm_rapportera_show_help({$helper->getId()},{$helper->getSizeX()},{$helper->getSizeY()},'topleft')"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" class="mmFloatRight" /></a>
					{/foreach}
				{/if}
					{*}<a href="javascript:;" onclick="rapportera_show_help(1,480,200,'topleft')"><img src="/img/icons/FaqCircleGreen.gif" alt="Hjälp" class="mmPositionRelative mmFloatRight" /></a>{*}
					{*}
          <a href="http://www.mabra.com/" title="Gå till Mabra.com">
						<span>En tj&auml;nst fr&aring;n tidningen</span>
						<img src="/img/framework/MaBraLogo.gif" alt="M&aring; Bra" />
					</a>
					{*}
					

				</div>
				{if !isset($USER) && !isset($ADMIN) && !isset($FORETAG)}
				<div id="mmMenuTop">
          <a href="/pages/skapaforetag.php" title="stegtävling">FÖR FÖRETAG</a> |
          <a href="/pages/blimedlem.php" title="stegräknare">BLI MEDLEM</a> |
					<a href="/pages/glomtlosen.php">GL&Ouml;MT L&Ouml;SENORDET?</a>			
				</div>
				{/if}
			</div>
		</div>
		
		{if !isset($doldMeny)}
		<div id="mmColumnLeft">
			<div id="mmMenuLeft">
				<ul>
				{if isset($inAdmin) && isset($ADMIN)}
				{$urlChecker->getFileName()}
					<li><a href="{$urlHandler->getUrl(Kommun, URL_ADMIN_LIST)}">KOMMUNER</a></li>
					<li><a href="{$urlHandler->getUrl(Aktivitet, URL_ADMIN_LIST)}">AKTIVITETER</a></li>
					<li><a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">FÖRETAG</a></li>
					<li><a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">ORDER</a></li>		
					<li><a href="{$urlHandler->getUrl(Visningsbild, URL_ADMIN_LIST)}">VISNINGSBILDER</a></li>
					<li><a href="{$urlHandler->getUrl(Avatar, URL_ADMIN_LIST)}">AVATARER</a></li>
					<li><a href="{$urlHandler->getUrl(ProfilData, URL_ADMIN_LIST)}">PROFILINFORMATION</a></li>
					<li><a href="{$urlHandler->getUrl(Paminnelser, URL_ADMIN_LIST)}">PÅMINNELSER</a></li>	
					<li><a href="{$urlHandler->getUrl(TextEditor, URL_ADMIN_LIST)}">TEXTHANTERING</a></li>
					<li><a href="{$urlHandler->getUrl(Help, URL_ADMIN_LIST)}">HJÄLPRUTOR</a></li>
					<li><a href="{$urlHandler->getUrl(KontrolleraBilder, URL_ADMIN_LIST)}">BILDKONTROLL</a></li>
					<li><a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}">MEDLEMMAR</a></li>
					{if $ADMIN->isTyp(ADMIN)}
					<li><a href="{$urlHandler->getUrl(Level, URL_ADMIN_LIST)}">MEDLEMSNIVÅER</a></li> 
					<li><a href="{$urlHandler->getUrl(LagNamn, URL_ADMIN_LIST)}">LAGNAMN</a></li> 
					<li><a href="/admin/pages/tavling.php">TÄVLING</a></li>
					<li><a href="/admin/pages/proquiz.php">PROQUIZ</a></li>
					<li><a href="{$urlHandler->getUrl(FastaUtmaningar, URL_ADMIN_LIST)}">FASTA RUTTER</a></li>
					<li><a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_LIST)}">KOMMUNDIALEKTER</a></li>
					{/if}
					{if $ADMIN->isTyp(SUPERADMIN)} 					
					<li><a href="{$urlHandler->getUrl(Admin, URL_ADMIN_LIST)}">ADMINISTRATÖRER</a></li>
          <li><a href="{$urlHandler->getUrl(MergeOrder, URL_ADMIN_MERGE)}">SLÅ IHOP ORDRAR</a></li>  					
					<li><a href="{$urlHandler->getUrl(Admin, URL_VIEW)}">DEBUG</a></li>
					<li><a href="/php/memcache.php">MEMCACHED (inlogg)</a></li>
					{/if}
					<hr/>
					<hr/>
				{/if}
	
					<li><a {if $urlChecker->getMarkedMenu() eq "HEM"} class="mmMarkedMenu"{/if} href="/">HEM</a></li>
					{if isset($FORETAG)}
						<li><a {if $urlChecker->getMarkedMenu() eq "HANTERA FÖRETAG"} class="mmMarkedMenu"{/if} href="{$urlHandler->getUrl(Foretag, URL_EDIT, $FORETAG->getId())}">HANTERA F&Ouml;RETAG</a></li>
					{/if}
					{if isset($USER)}
					<li class="mmMenuBG"><a{if $urlChecker->getMarkedMenu() eq "MIN SIDA"} class="mmMarkedMenu"{/if} href="/pages/minsida.php">MIN SIDA</a><img src="/img/ftag/minsida_icon.gif" class="mmMarginLeft5" alt="" /></li>
					{assign var=foretag value=$USER->getForetag(true)}
					{if $USER->getForetagsnyckel(true)}
						{if isset($foretag) && $foretag->aktivTavling(1)}
							{assign var=lag value=$USER->getLag()}
							{if $lag!= null}
							<li class="mmMenuBG"><a{if $urlChecker->getMarkedMenu() eq "MITT LAG"} class="mmMarkedMenu"{/if} href="{$urlHandler->getUrl(Lag, URL_VIEW, $lag->getId())}">MITT LAG</a><img src="/img/ftag/mittlag_icon.gif" class="mmMarginLeft5" alt="" /></li>
							{/if}
							{assign var=foretag value=$USER->getForetag()}
							<li class="mmMenuBG"><a{if $urlChecker->getMarkedMenu() eq "MITT FÖRETAG"} class="mmMarkedMenu"{/if} href="{$urlHandler->getUrl(Foretag, URL_VIEW, $foretag->getId())}">MITT F&Ouml;RETAG</a><img src="/img/ftag/mittforetag_icon.gif" class="mmMarginLeft2" alt="" /></li>
							<li class="mmMenuBG"><a{if $urlChecker->getMarkedMenu() eq "TOPPLISTOR"} class="mmMarkedMenu"{/if} href="{$urlHandler->getUrl(Foretagstavling, URL_VIEW)}">F&Ouml;RETAGST&Auml;VLING</a></li>
						{/if}
					{/if}

					<li><a{if $urlChecker->getMarkedMenu() eq "MOTIOMERAMAIL"} class="mmMarkedMenu"{/if} href="/pages/mail.php">MOTIOMERAMAIL</a></li>
					<li><a{if $urlChecker->getMarkedMenu() eq "FOTOALBUM"} class="mmMarkedMenu"{/if} href="/pages/fotoalbum.php">FOTOALBUM</a></li>
					{if isset($USER) && $sajtDelarObj->medlemHasAccess($USER,'minaQuiz')}
					<li><a{if $urlChecker->getMarkedMenu() eq "QUIZ"} class="mmMarkedMenu"{/if} href="/pages/minaquiz.php">MINA QUIZ</a></li>
					{/if}
					<li><a{if $urlChecker->getMarkedMenu() eq "MINA VÄNNER"} class="mmMarkedMenu"{/if} href="/pages/adressbok.php">MINA VÄNNER</a></li>
					<li><a{if $urlChecker->getMarkedMenu() eq "KLUBBAR"} class="mmMarkedMenu"{/if} href="/pages/klubbar.php">KLUBBAR</a></li>
					<li><a{if $urlChecker->getMarkedMenu() eq "INSTÄLLNINGAR"} class="mmMarkedMenu"{/if} href="/pages/installningar.php">INST&Auml;LLNINGAR</a></li>

					{/if}
					<li><a{if $urlChecker->getMarkedMenu() eq "OM MOTIOMERA"} class="mmMarkedMenu"{/if} href="/pages/ommotiomera.php" class="utLoggadMenuVal">OM MOTIOMERA</a></li>
					<li><a{if $urlChecker->getMarkedMenu() eq "VANLIGA FRÅGOR"} class="mmMarkedMenu"{/if} href="/pages/vanligafragor.php" class="utLoggadMenuVal">VANLIGA FR&Aring;GOR</a></li>
					{*}<li><a{if $urlChecker->getMarkedMenu() eq "TÄVLINGAR"} class="mmMarkedMenu"{/if} href="/pages/tavlingar.php" class="utLoggadMenuVal">T&Auml;VLINGAR</a></li>{*}
					<li><a{if $urlChecker->getMarkedMenu() eq "KOMMUNJAKTEN"} class="mmMarkedMenu"{/if} href="/pages/kommunjakten.php" class="utLoggadMenuVal">KOMMUNJAKTEN</a></li>
					{if isset($FORETAG)}
					<li><a{if $urlChecker->getMarkedMenu() eq "FÖR FÖRETAG"} class="mmMarkedMenu"{/if} href="{$urlHandler->getUrl(Foretag, URL_EDIT, $FORETAG->getId())}" class="utLoggadMenuVal" title="om stegtävling">F&Ouml;R F&Ouml;RETAG</a></li>
					{else}
					<li><a{if $urlChecker->getMarkedMenu() eq "FÖR FÖRETAG"} class="mmMarkedMenu"{/if} href="/pages/for_foretag.php" class="utLoggadMenuVal" title="om stegtävling">F&Ouml;R F&Ouml;RETAG</a></li>
					{/if}

				</ul>
			</div>
      {if isset($USER)}
        {* puff from rss feed *}
        <div class="rss-sidebar">
          {foreach from=$rss item=puff name=rss}
            {if  $smarty.foreach.rss.iteration < 2 }
                <span class="mmh2 mmOrange rss-sidebar">mabra.com</span>
                <div style="float:left;" class="">
                {$puff.imageurl}
                </div>
                <span class="mmh2 mmBlue">{$puff.title}</span><br />
                {$puff.excerpt}
                <div class="mmTextAlignRight">
                  <a href="{$puff.link}" target="_blank">Läs mer <img src="/img/icons/ArrowsBlue.gif" class="mmVerticalAlignMiddle" style="padding-top:4px;" alt="Läs mer" /></a>
                </div>
            {/if}
          {/foreach}
        </div>
      {/if}
		</div>
		{/if}
