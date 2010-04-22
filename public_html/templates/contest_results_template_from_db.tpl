<script type="text/javascript" src="http://www.google.com/jsapi/"></script>
<script type="text/javascript">
	{literal}
	google.load("jquery", "1.2.6");
	google.setOnLoadCallback(function()
	{
		var $username = $('#mmInloggad strong').text();
		$username = $username.substr(0,$username.length-1);
		if ($username)
		{
			$('#mmColumnMiddleWide .mmRightMinSidaBox td').each(function(index)
			{
				if ($.trim($(this).text()) === $username)
				{
					$(this).parent().addClass('mmCurrentUser');
				}
			});
		}
	});
	{/literal}
</script>


{* This is an example of the smarty loop for all members *}
{* foreach name=outer item=lag from=$allaLag}
  <hr />
  {foreach key=key item=lagitem from=$lag name=inner}
    {$key}<br/>
    {$lagitem.medlem_namn}<br />
    {$lagitem.medlem_id}<br />
    {$lagitem.steg}<br />    
  {/foreach}
{/foreach *}

<div class="mmTotaltAntalSteg"><b>Totalt:</b> {$foretagArray[0].foretag_steg_tot|nice_tal} steg</div>
	<div class="mmKlubbarAvatarTop"><img src="{if $foretagCustomBild!=null}{$foretagCustomBild}{else}/img/icons/AvatarKlubbTop.gif{/if}" alt="" /></div>
		<div class="mmh2">{$foretagArray[0].foretag_namn}</div>
		<b>Startdatum för företagstävling: {$foretagArray[0].start_datum|nice_date:"j F Y"}</b>
		<br /><br />

{*include file="positionerlag.tpl"*}
<div style="margin-top:20px;"> </div>
<br />
<div class="mmFloatLeft mmBlueBoxContainer mmWidthFyraEttNollPixlar">
  <div class="mmBlueBoxTop">
    <h3 class="mmWhite BoxTitle">Slutresultat inom lagen</h3>
  </div>
  <div class="mmBlueBoxBg">
    {foreach name=outer item=lag from=$allaLag}
      <div class="eventResults">
        <div class="mmAlbumBoxTop">
          <h3 class="mmWhite BoxTitle">{$lag.0.lag_namn}</h3>
        </div>
        <div class="mmRightMinSidaBox">
          <div class="mmHeightTvaNollPixlar"><b>Antal steg under tävlingen</b></div>
          <table width="155" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td>&nbsp;</td>
              <td><b>Medlem</b></td>
              <td><b>Steg</b></td>
            </tr>
            {foreach key=key item=lagitem from=$lag name=inner}
              <tr>
                <td>{$smarty.foreach.inner.iteration}.</td>
                <td><a href="{$urlHandler->getUrl('Medlem', URL_VIEW, $lagitem.medlem_id)}">{$lagitem.medlem_namn}</a></td>
                <td class="mmNoWrap">{$lagitem.steg|nice_tal}</td>
              </tr>                
            {/foreach}
          </table>
        </div>
      </div>
      {if $smarty.foreach.inner.index % 2 != 0}
        <div class="mmClearBoth"></div>
      {/if}
    {/foreach}
    <br class="mmClearBoth">
  </div>
  <div class="mmBlueBoxBottom"></div>
  <div class="mmClearBoth"></div>
</div>

{* This is an example of the smarty loop for lag *}
{*foreach name=outer item=lag from=$foretagLagArray}
  <hr />
    {$lag.lag_namn}<br />
    {$lag.lag_id}<br />
    {$lag.steg_medel}<br />    
{/foreach *}

<div class="mmFloatLeft mmRightSideContestBar">
	<div class="mmAlbumBoxTop">
		<h3 class="mmWhite BoxTitle">Lagtoppen</h3>
	</div>
	<div class="mmRightMinSidaBox">
		<div class="mmHeightTvaNollPixlar"><b>Snitt per deltagare och lag</b></div>	
		<table width="155" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>&nbsp;</td>
				<td><b>Medlem</b></td>
				<td><b>Steg</b></td>
			</tr>
			{foreach name=outer item=lag from=$foretagLagArray}
			<tr>
				<td>{$smarty.foreach.outer.iteration}.</td>
				<td><a href="{$urlHandler->getUrl("Lag", URL_VIEW, $lag.lag_id)}">{$lag.lag_namn}</a></td>
				<td>{$lag.steg_medel|nice_tal}</td>
			</tr>	
		{/foreach}
		</table>
	</div>
	<br />
		

{* This is an example of the smarty loop for all members in company*}
{*foreach name=outer item=memb from=$allCompMembArray}
  <hr />
    {$memb.medlem_namn}<br />
    {$memb.medlem_id}<br />
    {$memb.steg}<br />   
{/foreach*}
	
	<div class="mmAlbumBoxTop">
		<h3 class="mmWhite BoxTitle">Deltagartoppen</h3>
	</div>
	<div class="mmRightMinSidaBox">
		<div class="mmHeightTvaNollPixlar"><b>Antal steg under tävlingen</b></div>
		<table width="155" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>&nbsp;</td>
				<td><b>Medlem</b></td>
				<td><b>Steg</b></td>
			</tr>
        {foreach name=outer item=memb from=$allCompMembArray}
				<tr>
					<td>{$smarty.foreach.outer.iteration}.</td>
					<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $memb.medlem_id)}">
						{$memb.medlem_namn|truncate:11}</a>
					</td>
					<td class="mmNoWrap">
						{$memb.steg|nice_tal}
					</td>
				</tr>
			{/foreach}
		</table>
	</div>
	<br />
	{include file="bildblock.tpl"}
	<br />
</div>
<br class="mmClearBoth" />


{* This is an example of the smarty loop for all members *}
{*foreach name=outer item=memb from=$allMembArray}
  <hr />
    {$memb.medlem_namn}<br />
    {$memb.medlem_id}<br />
    {$memb.steg}<br />   
{/foreach*}

<div class="mmBlueBoxWideTop">
	<h3 class="mmFontWhite BoxTitle">Slutresultat för alla deltagare i företagstävlingen {$eventDescription}</h3>
</div>
<div class="mmBlueBoxWideBg">
	<table>
		<tr>
			<td>
				<div class="mmTopplistaRuta">
					<div class="mmAlbumBoxTop">
						<h3 class="mmWhite BoxTitle">Deltagartoppen</h3>
					</div>
					<div class="mmRightMinSidaBox">
						<strong>Antal steg under tävlingen</strong><br /><br />
						<table width="155" cellpadding="0" cellspacing="0" border="0">
							<tr>
							  <td>&nbsp;</td>
							  <td><b>Medlem</b></td>
							  <td><b>Steg</b></td>
							</tr>
							{foreach name=outer item=memb from=$allMembArray}
								<tr>
									<td>{$memb.rank}.</td>
									<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $memb.medlem_id)}">{$memb.medlem_namn|truncate:13}</a></td>
									<td>{$memb.steg|nice_tal}</td>
								</tr>
							{/foreach}
						</table>
					</div>
				</div>
				
						

{* This is an example of the smarty loop for lag *}
{*foreach name=outer item=lag from=$lagArray}
  <hr />
    {$lag.lag_namn}<br />
    {$lag.lag_id}<br />
    {$lag.steg_medel}<br />    
{/foreach *}	
				
				<div class="mmTopplistaRuta">
					<div class="mmAlbumBoxTop">
						<h3 class="mmWhite BoxTitle">Lagtoppen</h3>
					</div>
					<div class="mmRightMinSidaBox">
						<strong>Snitt per deltagare och lag</strong><br /><br />
						<table width="155" cellpadding="0" cellspacing="0" border="0">
							<tr>
							  <td>&nbsp;</td>
							  <td><b>Lag</b></td>
							  <td><b>Steg</b></td>
							</tr>
							{foreach name=outer item=lag from=$lagArray}
								<tr>
									<td>{$smarty.foreach.outer.iteration}.</td>
									<td><a href="{$urlHandler->getUrl("Lag", URL_VIEW, $lag.lag_id)}">{$lag.lag_namn} från {$lag.foretag_namn}</a></td>
									<td>{$lag.steg_medel|nice_tal}</td>
								</tr>
							{/foreach}
						</table>
					</div>
				</div>
				<div class="mmTopplistaRuta">
				
				
{* This is an example of the smarty loop for all companys *}
{*foreach name=outer item=comp from=$allForetagArray}
  <hr />
    {$comp.foretag_id}<br />
    {$comp.foretag_namn}<br />
    {$comp.foretag_steg_tot}<br /> 
    {$comp.foretag_steg_medel}<br /> 
    {$comp.start_datum}<br /> 
    {$comp.stop_datum}<br />            
{/foreach*}  				
						
					<div class="mmAlbumBoxTop">
						<h3 class="mmWhite BoxTitle">F&ouml;retagstoppen</h3>
					</div>
					<div class="mmRightMinSidaBox">
						<strong>Dagligt snitt per företag och deltagare</strong><br /><br />
						<table width="155" cellpadding="0" cellspacing="0" border="0">
							<tr>
							  <td>&nbsp;</td>
							  <td><b>Medlem</b></td>
							  <td><b>Steg</b></td>
							</tr>
							{foreach name=outer item=comp from=$allForetagArray}
								<tr>
									<td>{$smarty.foreach.outer.iteration}.</td>
									<td>{$comp.foretag_namn}</td>
									<td>{$comp.foretag_steg_medel|nice_tal}</td>
								</tr>
							{/foreach}
						</table>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
<div class="mmBlueBoxWideBottom"></div>
<div class="mmClearBoth"></div>
