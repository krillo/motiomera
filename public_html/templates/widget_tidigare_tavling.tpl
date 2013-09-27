<div class="clear"></div>
{if $tavlingArray}
  <div class="mmAlbumBoxTop">
    <h3 class="BoxTitle">Tidigare avslutade tävlingar</h3>
  </div>
  <div class="mmRightMinSidaBox">
    <div id="mmFinishedComp">
      {section name=record loop=$tavlingArray}
        <a href="/pages/tavlingsres.php?id={$tavlingArray[record].medlem_id}&tid={$tavlingArray[record].tavlings_id}">
          Resultatet {$tavlingArray[record].stop_datum|date_format:"%Y-%m-%d"}</a> <br/>
        {/section}
    </div>
  </div>
  <br/>
{/if}
<div class="clear"></div>