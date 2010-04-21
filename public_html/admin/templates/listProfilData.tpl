<h1>Detaljerad Profilinformation</h1>
{if $ADMIN->isTyp(SUPERADMIN)}
<p>
	<a href="{$urlHandler->getUrl(ProfilData, URL_ADMIN_CREATE)}">Skapa ny detaljerad profilinformation</a>
</p>
{/if}
<p>
{foreach from=$listProfilData item=thisProfilData}

<a href="{$urlHandler->getUrl(ProfilData, URL_ADMIN_EDIT, $thisProfilData->getId())}">{$thisProfilData->getNamn()}</a><br />

{/foreach}
</p>
