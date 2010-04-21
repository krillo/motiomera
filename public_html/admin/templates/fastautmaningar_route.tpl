{if $created}
	<h1>Du har nu skapat en fast rutt.</h1>
{/if}
<script type="text/javascript" charset="utf-8">
	{literal}
	$(document).ready(function()
	{
		fastaUtmaningar();
	});
	{/literal}
	{if !isset($_GET.abroad)}
		{literal}
			google.load('maps', '2.x');
			google.setOnLoadCallback(function()
			{
				fastaUtmaningarGmap();
			});
		{/literal}
	{/if}
</script>
<form action="{$urlHandler->getUrl("FastaUtmaningar", URL_ADMIN_CREATE)}" enctype="multipart/form-data" method="POST">
	{if isset($_GET.abroad)}<input type="hidden" name="abroad" value="true" id="abroad">{/if}
	<div id="routeMap">
		<div id="map">
			{if isset($_GET.abroad)}<img src="/files/gmaps/worldmap.gif" alt="" />{/if}			
		</div>
		<p>
			<h3>Tillgängliga rutter</h3>
			<ul>
				{foreach from=$fastaUtmaningar item=utmaning}
					<li>
						<a href="{$urlHandler->getUrl("FastaUtmaningar", URL_ADMIN_DELETE, $utmaning.id)}" class="remove">Tabort</a>
						{$utmaning.namn}
					</li>
				{/foreach}
			</ul>
		</p>
	</div>
	<div id="routeContainer">
		{if isset($_GET.abroad)}
			<h2>Utlandsruttens namn</h2>
			<p>
				<strong>Bild för utlandsrutten: </strong><input type="file" name="some_name" value="" id="some_name">
			</p>
		{else}
			<h2>Ruttens namn</h2>
		{/if}
		<input type="text" name="regionName" value="Skriv in namnet på rutten här" id="regionName">
	</div>
	<p><input type="submit" disabled="disabled" value="Skicka in rutten"></p>
</form>