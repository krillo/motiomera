{if isset($debugSmarty) && isset($_GET.debugSmarty)}
  {debug}
{/if}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>{if isset($pagetitle)}{$pagetitle}{/if}</title>
    <!--title>Stegtävling och friskvård för företag</title-->
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="Stegtävling för företag. Motiomera är Sveriges roligaste stegtävling för alla som vill röra på sig och ha kul. Häng bara på dig stegräknaren och börja gå." />
    <meta name="keywords" content="motoimera stegtävling, stegtävling för företag, stegtävling på jobbet, stegtävling korpen, tävla med stegräknare, friskvård åt företag, avdragsgill friskvårdsaktivitet, motionera stegtävling, köp stegräknare, billig stegräknare, motionera mera, friskvård stegräknare, stegtävling omvandling, friskvårdsaktivitet, friskvårdsaktiviteter" />
    {if isset($ADMIN)}
      <link rel="shortcut icon" href="{$mm_url}/wp-content/themes/motiomera/faviconadmin.ico" type="image/x-icon" />
      <link rel="icon" href="{$mm_url}/wp-content/themes/motiomera/faviconadmin.ico" type="image/x-icon" />
    {else}
      <link rel="shortcut icon" href="{$mm_url}/wp-content/themes/motiomera/favicon.ico" type="image/x-icon" />
      <link rel="icon" href="{$mm_url}/wp-content/themes/motiomera/favicon.ico" type="image/x-icon" />      
    {/if} 
    <link rel="stylesheet" href="{$mm_url}/css/motiomera.css" type="text/css" media="screen" />
    <!--link rel="stylesheet" href="{$mm_url}/css/checkout.css" type="text/css" media="screen" /-->
    <!--link rel="stylesheet" href="{$mm_url}/css/print.css" type="text/css" media="print" /-->


    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <!--script type='text/javascript' src='{$mm_url}/wp-includes/js/jquery/jquery.js?ver=1.8.3'></script-->

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


    {if $pagetitle == 'Min sida' or  $pagetitle == 'Profilsida' or $pagetitle == 'Företagssida' or $pagetitle == 'Lagsidan' or $pagetitle == 'Detaljerad rapport'} 
      <link rel='stylesheet' id='ui-lightness-style-css'  href='{$mm_url}/wp-content/themes/motiomera/css/ui-lightness/jquery-ui-1.9.2.custom.min.css?ver=3.3.2' type='text/css' media='all' />
      <script type='text/javascript' src='{$mm_url}/wp-content/themes/motiomera/js/jquery-ui-1.9.2.custom.min.js?ver=3.3.2'></script>
      <script type='text/javascript' src='{$mm_url}/wp-content/themes/motiomera/js/jquery.flot.js?ver=3.3.2'></script>
      <script type='text/javascript' src='{$mm_url}/wp-content/themes/motiomera/js/jquery.flot.stack.js?ver=3.3.2'></script>    
      <script type="text/javascript" src="{$mm_url}/wp-content/themes/motiomera/js/jquery.mmwp.steps.js?ver=3.5.1"></script>
    {/if}
    {if $pagetitle == 'Företagstävling'} 
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
      <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    {/if}
    <link rel='stylesheet' id='wp-mm-style-css'  href='{$mm_url}/wp-content/themes/motiomera/css/wp_mm_common.css?ver=3.3.2' type='text/css' media='all' />



    {php}
      //get all scripts and css from wp
      global $SETTINGS;
      $includes = file_get_contents($SETTINGS["url"].'/api-header/?page=kapten');
      //print($includes);
    {/php} 




    <script type="text/javascript">
      {literal}
        /*
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
         */
      {/literal}
    </script>



    {if $pagetitle == 'Kvitto'} 
      {literal}    
        <script type="text/javascript">
          var fb_param = {};
          fb_param.pixel_id = '6009178513893';
          fb_param.value = '0';
          fb_param.currency = 'SEK';
          (function() {
            var fpw = document.createElement('script');
            fpw.async = true;
            fpw.src = '//connect.facebook.net/en_US/fp.js';
            var ref = document.getElementsByTagName('script')[0];
            ref.parentNode.insertBefore(fpw, ref);
          })();
        </script>
        <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6009178513893&amp;value=0&amp;currency=SEK" /></noscript>
      {/literal}    
    {/if}


    {literal}
      <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-28332028-1']);
        _gaq.push(['_trackPageview']);

        (function() {
          var ga = document.createElement('script');
          ga.type = 'text/javascript';
          ga.async = true;
          ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
          var s = document.getElementsByTagName('script')[0];
          s.parentNode.insertBefore(ga, s);
        })();
      </script>
    {/literal}
    {literal}
      <script type="text/javascript">
        jQuery(document).ready(function($) {
          var mm_id = $("#mm_id").html();
          if (mm_id !== undefined) {
            //MotiomeraMail
            var data = {mm_id: mm_id};
            $.post('/ajax/includes/mailcount.php', data, function(response) {
              $("#logged-in-email-id li").append(response);
            });

            //Mina vanner
            var data = {mm_id: mm_id};
            $.post('/ajax/includes/addresscount.php', data, function(response) {
              $("#logged-in-friend li").append(response);
            });
          }
        });
      </script>  
    {/literal}



  </head>	
  <body id="body" class="{$BROWSER} {$currentPage}">
    {if isset($USER)}
      <div id="mm_id" style="display: none;">{$USER->getId()}</div>
    {else}
      <div id="mm_id" style="display: none;">0</div>
    {/if}
    <div id="mm_url" style="display: none;">{$mm_url}</div>
    <div id="profil_id" style="display: none;">{$_GET.mid}</div>
    {php}
      //get menu from wp
      global $SETTINGS;
      $menu = file_get_contents($SETTINGS["url"].'/api-menu/');
      $fb = file_get_contents($SETTINGS["url"].'/api/?snippet=inc_fb_root');
      //$menu2 = file_get_contents($SETTINGS["url"].'/api/?snippet=logged_in_menu');
      print($fb. $menu);
      //print($menu2);
    {/php}    
    <div id="logged-in-menu">
      <ul>
        <a href="/pages/adressbok.php?tab=3" id="logged-in-friend"><li>Mina vänner</li></a>
        <a href="/pages/mail.php?do=inbox" id="logged-in-email-id" class="logged-in-email"><li><span class="">Motiomeramail</span></li></a>        
            {if isset($inAdmin)}
          <a href="/admin/actions/logout.php" id="logged-in-logout"><li>Logga ut</li></a>
            {elseif isset($FORETAG)}
          <a href="/actions/logout.php" id="logged-in-logout"><li>Logga ut</li></a>
            {else}
          <a href="/actions/logout.php" id="logged-in-logout"><li>Logga ut</li></a>
            {/if}
      </ul>
    </div>       
    <div class="mmClearBoth"></div>

    <div id="mmPage">
      <div id="mmWrapMiddle">
        {if !isset($doldMeny)}
          <div id="mmColumnLeft">
            <div id="mmMenuLeft">
              <ul>
                {if !isset($logged_id)}
                  &nbsp;
                  {php}
                    //get menu from wp
                    //global $SETTINGS;
                    //$menu = file_get_contents($SETTINGS["url"].'/api-menu/');
                    //print($menu);
                  {/php}    
                {/if}
                {if isset($inAdmin) && isset($ADMIN)}
                  {$urlChecker->getFileName()}
                  <li><a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">ORDER</a></li>		
                  <li><a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">FÖRETAG</a></li>          
                  <li><a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}">MEDLEMMAR</a></li>
                  <li><a href="/admin/pages/veckovinnare.php">VECKOVINNARE</a></li>
                  <li><a href="{$urlHandler->getUrl(TextEditor, URL_ADMIN_LIST)}">TEXTHANTERING</a></li>
                  <li><a href="{$urlHandler->getUrl(Kommun, URL_ADMIN_LIST)}">KOMMUNER</a></li>
                  <li><a href="{$urlHandler->getUrl(Aktivitet, URL_ADMIN_LIST)}">AKTIVITETER</a></li>
                  <li><a href="{$urlHandler->getUrl(Visningsbild, URL_ADMIN_LIST)}">VISNINGSBILDER</a></li>
                  <li><a href="{$urlHandler->getUrl(Avatar, URL_ADMIN_LIST)}">AVATARER</a></li>
                  <li><a href="{$urlHandler->getUrl(ProfilData, URL_ADMIN_LIST)}">PROFILINFORMATION</a></li>
                  <li><a href="{$urlHandler->getUrl(Paminnelser, URL_ADMIN_LIST)}">PÅMINNELSER</a></li>	                  
                  <li><a href="{$urlHandler->getUrl(Help, URL_ADMIN_LIST)}">HJÄLPRUTOR</a></li>
                  <li><a href="{$urlHandler->getUrl(KontrolleraBilder, URL_ADMIN_LIST)}">BILDKONTROLL</a></li>
                    {if $ADMIN->isTyp(ADMIN)}
                    <li><a href="{$urlHandler->getUrl(Level, URL_ADMIN_LIST)}">MEDLEMSNIVÅER</a></li> 
                    <li><a href="{$urlHandler->getUrl(LagNamn, URL_ADMIN_LIST)}">LAGNAMN</a></li>                     
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


                {if isset($FORETAG)}
                  <li><a   class="vinnare-menu"   href="/pages/editforetag.php?fid={$FORETAG->getId()}&tab=2">HANTERA FÖRETAG</a></li>
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
                    {if $USER->isVeckoVinnare()}
                    <li><a href="/pages/minvinst.php" class="vinnare-menu">DU ÄR VINNARE</a></li>
                    {/if}
                  <li><a{if $urlChecker->getMarkedMenu() eq "MOTIOMERAMAIL"} class="mmMarkedMenu"{/if} href="/pages/mail.php">MOTIOMERAMAIL</a></li>
                  <li><a{if $urlChecker->getMarkedMenu() eq "FOTOALBUM"} class="mmMarkedMenu"{/if} href="/pages/fotoalbum.php">FOTOALBUM</a></li>
                    {* if isset($USER) && $sajtDelarObj->medlemHasAccess($USER,'minaQuiz')}
                    <li><a{if $urlChecker->getMarkedMenu() eq "QUIZ"} class="mmMarkedMenu"{/if} href="/pages/minaquiz.php">MINA QUIZ</a></li>
                    {/if *}
                  <li><a{if $urlChecker->getMarkedMenu() eq "MINA VÄNNER"} class="mmMarkedMenu"{/if} href="/pages/adressbok.php">MINA VÄNNER</a></li>
                    {* <li><a{if $urlChecker->getMarkedMenu() eq "KLUBBAR"} class="mmMarkedMenu"{/if} href="/pages/klubbar.php">KLUBBAR</a></li>  *}
                  <li><a{if $urlChecker->getMarkedMenu() eq "INSTÄLLNINGAR"} class="mmMarkedMenu"{/if} href="/pages/installningar.php">INST&Auml;LLNINGAR</a></li>
                  <li><a{if $urlChecker->getMarkedMenu() eq "TOPPLISTOR"} class="mmMarkedMenu"{/if} href="/pages/topplistor.php">TOPPLISTOR</a></li>

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
