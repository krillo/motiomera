<h1>{if isset($admin)}{$admin->getAnamn()}{else}Ny administratör{/if}</h1>
{if isset($admin) && $admin->getId() != $ADMIN->getId()}
<p>
<a href="{$urlHandler->getUrl(Admin, URL_ADMIN_DELETE, $admin->getId())}" onclick="{jsConfirm msg="Är du säker på att du vill ta bort den här administratören?"}">Ta bort</a>
</p>
{/if}
<form action="{$urlHandler->getUrl(Admin, URL_ADMIN_SAVE, $adminId)}" method="post" onsubmit="motiomeraValidateAdminForm(anamninput.value , losenordinput.value)">

	<p>
		Användarnamn:<br />
		<input type="text" name="anamn" id="anamninput" value="{if isset($admin)}{$admin->getANamn()}{/if}" />
	</p>
	<p>
		Lösenord:<br />
		<input type="text" name="losenord" id="losenordinput" onkeyup="motiomeraCheckLosen(this.value)" />&nbsp;<span id="pass_validate"></span>
	</p>
	<p>
		Behörighet:<br />
		{mm_html_options name=typ options=$opt_typ selected=$sel_typ}
	</p>
	<p>
		<input type="submit" value="Spara" />
	</p>
</form>
<div id="form_validate">{if $created eq 'false'}Du angav inget namn, eller så validerar inte lösenordet.<br />
Lösenordet ska innehålla totalt 8 tecken varav minst en ska vara en siffra.{/if}</div>