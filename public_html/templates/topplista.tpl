<form action="" method="post">
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>Län</th>
			<td>{mm_html_options name=lan options=$opt_lan selected=$sel_lan onchange="if(this.value!='')getById('kommunRow').style.display='block';"}</td>
		</tr>
		<tr>
			<th>Kommun</th>
			<td>
				<span id="mmKommunValjLan">Välj ett län</span>
				{foreach from=$lanKommuner item=kommun}
				<div id="mmTopplistaKommun" class="mmTopplistaKommun">
					{mm_html_options name=kommun_id options=$kommun.opt}
				</div>
				{/foreach}
			</td>
		</tr>
		<tr>
			<th>Kön</th>
			<td>{mm_html_options name=kon options=$opt_kon selected=$sel_kon}</td>
		</tr>
		<tr>
			<th>Födelseår</th>
			<td>{mm_html_options name=fodelsearFran options=$opt_fodelsear selected=$sel_fodelsear} till {mm_html_options name=fodelsearTill options=$opt_fodelsear selected=$sel_fodelsear}</td>
		</tr>
		{foreach from=$profilData item=attribut}
		<tr>
			<th>{$attribut.namn}</th>
			<td>{mm_html_options name=$attribut.formId options=$attribut.opt selected=$attribut.sel}</td>
		</tr>
		{/foreach}
		<tr>
			<td></td>
			<td><input type="submit" value="Filtrera"></td>
		</tr>
	</table>
</form>

<h3 class="mmMarginTop">Resultat</h3>

<table border="0" cellpadding="0" cellspacing="0" width="200">
	{foreach from=$topplista->getTopplista() item=placering key=key}
	<tr>
		<td>#{$key+1}</td>
		<td><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $placering.medlem->getId())}">{$placering.medlem->getANamn()}</a></td>
		<td class="mmTextAlignRight">{$placering.steg|nice_tal}</td>
	</tr>
	{foreachelse}
	<tr>
		<td>Inga medlemmar matchade sökningen</td>
	</tr>
	{/foreach}
</table>