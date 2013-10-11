{*<h1>Företagstävling</h1>
{$tabs->printTabBox()*}
{include_php file='../pages/foretagstavling_code.php'}


{*literal}
  <script>
    jQuery(function($) {
      $("#tabs").tabs({
        event: "mouseover"
      });
    });</script>
  {/literal}  
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Pågående företagstävling</a></li>
    <li><a href="#tabs-2">All time high</a></li>
  </ul>
  <div id="tabs-1">
    {php}
      //global $SETTINGS;
      //$file = $SETTINGS["url"].'/tabroot/foretag/company_contest.php';
      //$includes = file_get_contents($file);
      //print($includes);


  <div class="mmAlbumBoxTop">
    <h3 class="mmWhite BoxTitle">Steg sen tävlingsstart</h3>
  </div>
  <div class="mmRightMinSidaBox">
    <table width="155" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>&nbsp;</td>
        <td><b>Medlem</b></td>
        <td><b>Steg</b></td>
      </tr>
      {foreach name=steglista from=$topplista->getTopplista(100,$medlem) item=placering}
        {if $placering.placering == 101}
          {assign var=tomrad value=1}
        {/if}
        {if $placering.placering > 100 && $tomrad == 0}
          {assign var=tomrad value=1}
          <tr><td>&nbsp;</td></tr>
        {/if}
        <tr  {if $smarty.foreach.steglista.index % 2}{else}class="odd"{/if}>
          <td>{$placering.placering}.</td>
          <td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()}</strong>{else}{$placering.medlem->getANamn()}{/if}</a></td>
          <td>{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
        </tr>
      {/foreach}
    </table>
    <br />
    <a href="{$urlHandler->getUrl(Foretagstavling, URL_VIEW)}">Företagstopplistor <img src="/img/icons/ArrowCircleBlue.gif" alt="Steg sen start" /></a>
  </div>
  


    {/php} 



  </div>
  <div id="tabs-2">
    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
  </div>
</div>
*}