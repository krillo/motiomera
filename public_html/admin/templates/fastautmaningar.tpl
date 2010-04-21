<p>
	<a href="/admin/pages/fastautmaningar_route.php"><h4>Skapa en Svensk rutt.</h4></a>
</p>
<p>
	<a href="/admin/pages/fastautmaningar_route.php?abroad"><h4>Skapa en utländsk rutt.</h4></a>
</p>

<h3>Tillgängliga rutter</h3>
<ul>
	{foreach from=$fastaUtmaningar item=utmaning}
		<li>
			
			{$utmaning.namn} <a href="{$urlHandler->getUrl("FastaUtmaningar", URL_ADMIN_DELETE, $utmaning.id)}" class="remove">Tabort</a>
		</li>
	{/foreach}
</ul>