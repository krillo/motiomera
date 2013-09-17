{if !isset($isProfil) && isset($startKommun) && isset($slutmal)}
<div id="info-area">
    {if isset($lagnamn) && isset($foretagnamn)}
      <div class="mmStegOverText" style="float:left;height:20px;width:595px;">Du tävlar just nu för lag <a href="{$urlHandler->getUrl("Lag", URL_VIEW, $lagid)}">{$lagnamn}</a> från {$foretagnamn}{if isset($tavlingstart)}. Tävlingen startar om {$tavlingstart} dagar{/if}</div>
    {/if}
  
  Du började i &nbsp;
  <a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $startKommun->getUrlNamn())}"><strong>{$startKommun->getNamn()}</strong>&nbsp;</a>
  och har nu gått genom&nbsp;{$antalKommuner}&nbsp;kommuner.
  {if $totalKmKvar < 1}
    Du har kommit fram till ditt mål: &nbsp;<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $slutmal->getUrlNamn())}"><strong>{$slutmal->getNamn()}</strong></a>&nbsp;!{else}
    Det är {*}<strong>{$antalKommunerKvar}</strong> kommuner och {*}<strong>{$totalKmKvar|nice_tal}</strong> km kvar till ditt slutmål <a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $slutmal->getUrlNamn())}"><strong>{$slutmal->getNamn()}</strong></a>.
  {/if}
</div>    
  <div style="clear:both;"></div>
{/if}


{if $sajtDelarObj->medlemHasAccess($medlem,"minSidaGmaps")}
  <script type="text/javascript">
    google.load("maps", "2");
    {literal}
    google.setOnLoadCallback(function()
    {
      stegGrafikGmaps();
    });
    {/literal}
  </script>




  {if isset($rssFeed)}
    <div id="mmRssFeeds" class="gmaps">
      <strong>
        Senaste blogginlägget:
      </strong>
      ({$rssFeed.pubDate})
      <br />
      <a href="{$urlHandler->getUrl('RssFlow', URL_VIEW, $medlem->getId())}">{$rssFeed.title}</a><br />
      {$rssFeed.description|truncate:110}
      <br />
    </div>
  {/if}
  <div id="map-container">
    <div id="map">
      {if $nastaKommun neq null}
        <var class="metadata" title="directions-from">{$currentKommun->getNamn()}, Sweden</var>
        <var class="metadata" title="directions-to">{$nastaKommun->getNamn()}, Sweden</var>
        <var class="metadata" title="directions-procent-completed">{$avatarEndPosProcent}</var>
        <var class="metadata" title="directions-from-image">/files/kommunbilder/{$currentThumb}</var>
        <var class="metadata" title="directions-to-image">/files/kommunbilder/{$nastaThumb}</var>
      {else}
        <var class="metadata" title="directions-from">{$currentKommun->getNamn()}, sweden</var>
      {/if}
      <var class="metadata" title="directions-avatar">{$avatarUrl}</var>
      <var class="metadata" title="directions-map-type">G_PHYSICAL_MAP</var>
    </div>
    <div id="loader"></div>
  </div>




{else}
  <div id="mmStegGrafikStartkommunKontainer">
    <div id="mmStegGrafikStartkommun">
    {if $currentKommunBild}<a href="{$currentKommunBild->getUrl()}" class="highslide" onclick="return hs.expand(this)"><img src="../files/kommunbilder/{$currentThumb}" alt="" width="48" class="mmImgBorderGray" /></a>{/if}

  </div>
  {assign var=avatar value=$medlem->getAvatar()}
  <div id="mmStegGrafikAvatar" style="background:url({$avatar->getUrl()});height:40px;width:40px;left:20px;top:{if !isset($isProfil)}75{else}75{/if}px;">
  </div>
  <div id="mmStegGrafikTextNu">
    <div class="mmh3 mmGreen">Nu</div>
    <a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $currentKommun->getUrlNamn())}">{$currentKommun->getNamn()}</a>
  </div>

  <div id="mmStegGrafikSlutkommun">
  {if $nastaKommunBild}<a href="{$nastaKommunBild->getUrl()}" class="highslide" onclick="return hs.expand(this)"><img src="../files/kommunbilder/{$nastaThumb}" alt="" width="48" class="mmImgBorderGray" /></a>{/if}
</div>
<div id="mmStegGrafikTextMal">
  <div class="mmh3 mmGreen">{if $nastaKommun}P&aring; v&auml;g till{/if}</div>
  {if $nastaKommun}
    <a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $nastaKommun->getUrlNamn())}">{$nastaKommun->getNamn()}</a><br />{if $rutt->getKmTillNasta() eq 0}&lt;1{else}{$rutt->getKmTillNasta()}{/if} km kvar
  {else}
    {if !isset($medlem)}
      <a href="{$urlHandler->getUrl(Kommun, URL_CHOOSE)}">Välj nytt mål</a>
    {/if}
  {/if}
</div>


<div id="mmStegGrafikTotalt">
  <b>TOTALT</b><br />
  {$stegtotal|nice_tal} steg<br />
{if $sajtDelarObj->medlemHasAccess($medlem,"minSidaCalories")}{$caltotal} kcal{/if}
</div>

<div id="mmStegGrafikDetaljeradRapport">
  <div class="mmh4 mmGreen">
    {if isset($medlem)}
      <a href="{$urlHandler->getUrl(Rutt, URL_VIEW, $medlem->getId())}&amp;tab=1">
      {else}
        <a href="{$urlHandler->getUrl(Rutt, URL_VIEW)}&amp;tab=1">
        {/if}
        Detaljerad<br/>Rapport
      </a>
  </div>
</div>


<div id="mmStegGrafik" style="background: url('/img/minsida/mmStegGrafik{$currentKommun->getId()%4}.gif');"></div>
{if isset($rssFeed)}
  <div id="mmRssFeeds">
    <strong>
      Senaste blogginlägget:
    </strong>
    ({$rssFeed.pubDate})
    <br />
    <a href="{$urlHandler->getUrl('RssFlow', URL_VIEW, $medlem->getId())}">{$rssFeed.title}</a><br />
    {$rssFeed.description|truncate:110}
    <br />
  </div>
{/if}
</div>




<script type="text/javascript">
  <!--
  var end = {$avatarEndPos};

  {if !isset($isProfil)}
    var y = 75;
  {else}
    var y = 75;
  {/if}
  {literal}

moved = 0;
var x = 25;

function move_avatar()
{
  mmsga = document.getElementById("mmStegGrafikAvatar");
  moved ++;

  not = false;
  if(moved <= 7)
  {
    x += 8;
  }
  else if(moved <= 18)
  {
    x += 5;
    y -= 5;
  }
  else if(moved <= 22)
  {
    x += 8;
    y -= 6;
  }
  else if(moved <= 27)
  {
    x += 8;
    y += 2;
  }
  else if(moved <= 33)
  {
    y += 5;
  }
  else if(moved <= 35)
  {
    y += 5;
    x -= 5;
  }
  else if(moved <= 41)
  {
    y += 8;
    x += 1;
  }
  else if(moved <= 48)
  {
    y -= 1;
    x += 8;
  }
  else if(moved <= 50)
  {
    y -= 8;
    x -= 1;
  }
  else if(moved <= 57)
  {
    y -= 6;
    x -= 2;
  }
  else if(moved <= 62) {

    y -= 8;
    x += 6;
  }

  else if(moved <= 64) {

    y -= 0;
    x += 12;
  }

  else if(moved <= 67) {

    y += 6;
    x += 8;
  }
  else if(moved <= 74) {

    y += 8;
    x += 0;
  }
  else if(moved <= 79) {

    y += 0;
    x += 6;
  }
  else if(moved <= 86) {

    y -= 8;
    x += 8;
  }

  else if(moved <= 92) {

    y -= 0;
    x += 6;
  }
  else if(moved <= 93) {

    y += 3;
    x += 12;
  }
  else if(moved <= 98) {

    y += 8;
    x += 0;
  }
  else if(moved <= 102) {

    y += 4;
    x -= 8;
  }
  else if(moved <= 104) {

    y += 4;
    x -= 8;
  }
  else if(moved <= 106) {

    y += 6;
    x += 0;
  }
  else if(moved <= 108) {

    y += 4;
    x += 2;
  }
  else if(moved <= 110) {

    y += 0;
    x += 8;
  }
  else if(moved <= 112) {

    y -= 5;
    x += 5;
  }
  else if(moved <= 116) {

    y -= 6;
    x += 6;
  }
  else if(moved <= 123) {

    y += 0;
    x += 12;
  }
  else if(moved <= 124) {

    y -= 2;
    x += 0;
  }
  else if(moved <= 125) {

    y += 2;
    x += 0;
  }
  else if(moved <= 126) {

    y -= 2;
    x += 0;
  }
  else if(moved <= 127) {

    y += 2;
    x += 0;
  }
  else if(moved <= 128) {

    y -= 2;
    x += 0;
  }
  else if(moved <= 129) {

    y += 2;
    x += 0;
  }
  else {

    not = true;
  }

  if(!not) {
    mmsga.style.left = x + "px";
    mmsga.style.top = y + "px";

    if(moved < end) {
      window.setTimeout("move_avatar()",100);
    }
    else {



    }

  }
}
if(end > 0) {
  window.setTimeout("move_avatar()",2000);
}

  {/literal}

    -->
</script>
{/if}



  
<div id="report-area">
  {if !isset($isProfil)}
    <a class="report-button" href="#" id="mm-report-steps"><img src="/img/design12/report.png" class="report" alt=""/>Rapportera dina steg!</a>
    <a class="report-button route" href="{$urlHandler->getUrl(Rutt, URL_VIEW, $medlem->getId())}" ><img src="/img/design12/route.png" class="route_x" alt=""/>Välj en rutt!</a>
    {/if}
  <div id="report-data">
    <b>TOTALT</b><br />
    {$stegtotal|nice_tal} steg<br />
  {if $sajtDelarObj->medlemHasAccess($medlem,"minSidaCalories")}{$caltotal|nice_tal} kcal{/if}
</div>
{if isset($medlem)}
  <a class="report-button graph" href="{$urlHandler->getUrl(Rutt, URL_VIEW, $medlem->getId())}&amp;tab=1"><img src="/img/design12/graph.png" alt=""/>Detaljerad Rapport</a>
  {else}
  <a class="report-button graph" href="{$urlHandler->getUrl(Rutt, URL_VIEW)}&amp;tab=1"><img src="/img/design12/graph.png" alt=""/>Detaljerad Rapport</a>
  {/if}				
</div>    