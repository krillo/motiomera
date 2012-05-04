<script type="text/javascript">
	{literal}
	$(document).ready(function()
	{
		addSorting();
	});
	{/literal}
</script>
<h1>Medlemmar</h1><br />
<form method="get" action="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}">
<input type="text" name="search" value="{$search}" />
{html_options name=field options=$searchOpt selected=$searchSel}
<input type="text" name="limit" value="{$limit}" size="4"/> 
<input type="submit" name="Sök" value="Sök" /><br />

</form>
<br />
{if $medlemmar eq null}
<div>Inga träffar!</div>
{else}
<table class="sortable" border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th>
				<a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}?sort=id&amp;way={$way}">ID</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}?sort=aNamn&amp;way={$way}">Förnamn</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}?sort=aNamn&amp;way={$way}">Efternamn</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}?sort=aNamn&amp;way={$way}">Användarnamn</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}?sort=epost&amp;way={$way}">E-post adress</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}?sort=skapad&amp;way={$way}">Skapad</a>
			</th>
			<th>
				Betald till
			</th>      
			<th>
				Profil
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}?sort=epostBekraftad&amp;way={$way}">Aktiverad</a>
			</th>
			<th>
				Redigera
			</th>
			<th>
				Företag
			</th>
			<th>
				Tävlingsdatum
			</th>      
		</tr>
	</thead>
	<tbody>
		{foreach from=$medlemmar item=medlem name=medlemmar}
		<tr>
			<td class="mmList2">
				{$medlem->getId()}
			</td>
			<td class="mmList1">
				{$medlem->getFNamn()}
			</td>
			<td class="mmList2">
				{$medlem->getENamn()}
			</td>
			<td class="mmList1">
				{$medlem->getANamn()}
			</td>
			<td class="mmList2">
				<span class="medlemListEmail">
					{$medlem->getEpost()}
				</span>
			</td>
			<td class="mmList1">
				{$medlem->getSkapadDateOnly()}
			</td>
			<td class="mmList2 {$medlem->isActiveAccountCSS()}">        
				{$medlem->getPaidUntil()}
			</td>         
			<td class="mmList2">
				<a style="text-decoration: underline; color: blue;" href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">Visa profil</a>
			</td>
			<td class="mmList1 mmRed">
			{if !$medlem->getEpostBekraftad()}Ej aktiverad{/if}
			</td>        
			<td class="mmList2">
				<a style="text-decoration: underline; color: blue;" href="{$urlHandler->getUrl(Medlem, URL_ADMIN_EDIT, $medlem->getId())}">Redigera</a>
			</td>
			<td class="mmList1">
        <a href="/admin/pages/listorder.php?search=&field=id&limit=40&offset=0&showValid=true&foretagid={$medlem->getForetagsId()}" style="text-decoration: underline; color: blue;">{$medlem->getForetagsNamn()}</a>
			</td>
			<td class="mmList2 {$medlem->isActiveCompetitionCSS()}">        
				{$medlem->getForetagStartdatum()} - {$medlem->getForetagSlutdatum()}
			</td>           
		</tr>
		{/foreach}
	</tbody>
</table>
{/if}