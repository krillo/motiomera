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

<div class="mmTotaltAntalSteg"><strong>Totalt:</strong> {$foretagArray[0].foretag_steg_tot|nice_tal} steg</div>
	<div class="mmKlubbarAvatarTop"><img src="{if $foretagCustomBild!=null}{$foretagCustomBild}{else}/img/icons/AvatarKlubbTop.gif{/if}" alt="" /></div>
		<div class="mmh2">{$foretagArray[0].foretag_namn}</div>
		<strong>Startdatum för företagstävling: {$foretagArray[0].start_datum|nice_date:"j F Y"}</strong>
		<br /><br />

{*include file="positionerlag.tpl"*}
<div style="margin-top:20px;"> </div>
<br />
<div class="mmFloatLeft mmBlueBoxContainer tavlingsresult-mid-width">
  <div class="mmBlueBoxWideTop tavlingsresult-mid-width">
    <h3 class="BoxTitle">Slutresultat inom lagen</h3>
  </div>
  <div class="tavlingsresult-mid">
    {foreach name=outer item=lag from=$allaLag}
      <div class="tavlingsresult tavlingsresult-width  {if $smarty.foreach.outer.index % 2}{else}margin-right{/if}">
        <div class="mmAlbumBoxTop tavlingsresult-width">
          <h3 class="BoxTitle">{$lag.0.lag_namn}</h3>
        </div>
        <div class="mmRightMinSidaBox tavlingsresult-width">
          <div class="mmRightMinSidaBox-submenu"><strong>Antal steg under tävlingen</strong></div>
          <table width="" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td>&nbsp;</td>
              <td><strong>Medlem</strong></td>
              <td width="40"><strong>Steg</strong></td>
            </tr>
            {foreach key=key item=lagitem from=$lag name=inner}
              <tr {if $smarty.foreach.inner.index % 2}{else}class="odd"{/if}>
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
  
	<div class="mmAlbumBoxTop tavlingsresult-width">
		<h3 class="BoxTitle">Lagtoppen</h3>
	</div>
	<div class="mmRightMinSidaBox tavlingsresult-width">
		<div class="mmRightMinSidaBox-submenu"><strong>Snitt per deltagare och lag</strong></div>	
		<table width="160" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>&nbsp;</td>
				<td><strong>Medlem</strong></td>
				<td width="40"><strong>Steg</strong></td>
			</tr>
			{foreach name=outer item=lag from=$foretagLagArray}
			<tr {if $smarty.foreach.outer.index % 2}{else}class="odd"{/if}>
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
	
	<div class="mmAlbumBoxTop tavlingsresult-width">
		<h3 class="BoxTitle">Deltagartoppen</h3>
	</div>
	<div class="mmRightMinSidaBox tavlingsresult-width">
		<div class="mmRightMinSidaBox-submenu"><strong>Antal steg under tävlingen</strong></div>
		<table width="160" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>&nbsp;</td>
				<td><strong>Medlem</strong></td>
				<td width="40"><strong>Steg</strong></td>
			</tr>
        {foreach name=outer item=memb from=$allCompMembArray}
				<tr {if $smarty.foreach.outer.index % 2}{else}class="odd"{/if}>
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









<div class="mmBlueBoxWideTop">
	<h3 class="mmFontWhite BoxTitle">Slutresultat för alla deltagare i företagstävlingen {$eventDescription}</h3>
</div>
<div class="mmBlueBoxWideBg tavlingsresult-width-max">
  
  
{* This is an example of the smarty loop for all members *}
{*foreach name=outer item=memb from=$allMembArray}
  <hr />
    {$memb.medlem_namn}<br />
    {$memb.medlem_id}<br />
    {$memb.steg}<br />   
{/foreach*}  
				<div class="tavlingsresult margin-right">
					<div class="mmAlbumBoxTop tavlingsresult-width">
						<h3 class="BoxTitle">Deltagartoppen</h3>
					</div>
					<div class="mmRightMinSidaBox tavlingsresult-width">
            <div class="mmRightMinSidaBox-submenu"><strong>Antal steg under tävlingen</strong></div>
						<table width="160" cellpadding="0" cellspacing="0" border="0">
							<tr>
							  <td>&nbsp;</td>
							  <td><strong>Medlem</strong></td>
							  <td width="40"><strong>Steg</strong></td>
							</tr>
							{foreach name=outer item=memb from=$allMembArray}
								<tr {if $smarty.foreach.outer.index % 2}{else}class="odd"{/if}>
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
				<div class="tavlingsresult margin-right">
					<div class="mmAlbumBoxTop  tavlingsresult-width">
						<h3 class="BoxTitle">Lagtoppen</h3>
					</div>
					<div class="mmRightMinSidaBox  tavlingsresult-width">
            <div class="mmRightMinSidaBox-submenu"><strong>Snitt per deltagare och lag</strong></div>
						<table width="160" cellpadding="0" cellspacing="0" border="0">
							<tr>
							  <td>&nbsp;</td>
							  <td><strong>Lag</strong></td>
							  <td width="40"><strong>Steg</strong></td>
							</tr>
							{foreach name=outer item=lag from=$lagArray}
								<tr {if $smarty.foreach.outer.index % 2}{else}class="odd"{/if}>
									<td>{$smarty.foreach.outer.iteration}.</td>
									<td><a href="{$urlHandler->getUrl("Lag", URL_VIEW, $lag.lag_id)}">{$lag.lag_namn} från {$lag.foretag_namn}</a></td>
									<td>{$lag.steg_medel|nice_tal_1}</td>
								</tr>
							{/foreach}
						</table>
					</div>
				</div>
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
				<div class="tavlingsresult">
					<div class="mmAlbumBoxTop tavlingsresult-width">
						<h3 class="BoxTitle">F&ouml;retagstoppen</h3>
					</div>
					<div class="mmRightMinSidaBox tavlingsresult-width">
            <div class="mmRightMinSidaBox-submenu"><strong>Dagligt snitt per företag och deltagare</strong></div>
						<table width="160" cellpadding="0" cellspacing="0" border="0">
							<tr>
							  <td>&nbsp;</td>
							  <td><strong>Medlem</strong></td>
							  <td width="40"><strong>Steg</strong></td>
							</tr>
							{foreach name=outer item=comp from=$allForetagArray}
								<tr {if $smarty.foreach.outer.index % 2}{else}class="odd"{/if}>
									<td>{$smarty.foreach.outer.iteration}.</td>
									<td>{$comp.foretag_namn}</td>
									<td>{$comp.foretag_steg_medel|nice_tal_1}</td>
								</tr>
							{/foreach}
						</table>
					</div>
				</div>
     </div>
<div class="mmClearBoth"></div>