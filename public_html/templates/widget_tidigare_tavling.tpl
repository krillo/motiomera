{if $tavlingArray}
	<div class="mmAlbumBoxTop">
		<h3 class="BoxTitle">Tidigare tävlingar</h3>
	</div>
	<div class="mmRightMinSidaBox">
    <div id="_mmFinishedComp" style="float:left;width:387px;">
      {section name=record loop=$tavlingArray}
        <a href="http://www.motiomera.se/pages/tavlingsres.php?id={$tavlingArray[record].medlem_id}&tid={$tavlingArray[record].tavlings_id}">
        Resultatet {$tavlingArray[record].stop_datum|date_format:"%Y-%m-%d"}</a> <br/>
      {/section}
    </div>
  </div>
	<br/>
{/if}