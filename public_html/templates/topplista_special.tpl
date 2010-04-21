
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>Län</th>
			<td>{mm_html_options name=lan options=$opt_lan selected=$sel_lan onchange="if(this.value!='')getById('kommunRow').style.display='block';"}</td>
		</tr>
		{*}<tr>
			<th>Kommun</th>
			<td>
				<span id="mmKommunValjLan">Välj ett län</span>
				{foreach from=$lanKommuner item=kommun}
				<div id="mmTopplistaKommun" class="mmTopplistaKommun">
					{mm_html_options name=kommun_id options=$kommun.opt}
				</div>
				{/foreach}
			</td>
		</tr>{*}
		<tr>
			<th>Kön</th>
			<td>{mm_html_options name=kon options=$opt_kon selected=$sel_kon}</td>
		</tr>
		<tr>
			<th>Födelseår</th>
			<td>{mm_html_options name=fodelsearFran options=$opt_fodelsear selected=$sel_fodelsear} till {mm_html_options name=fodelsearTill options=$opt_fodelsear selected=$sel_fodelsear}</td>
		</tr>
		{*}{foreach from=$profilData item=attribut}
		<tr>
			<th>{$attribut.namn}</th>
			<td>{mm_html_options name=$attribut.formId options=$attribut.opt selected=$attribut.sel}</td>
		</tr>
		{/foreach}
		{*}<tr>
			<td></td>
			<td><input type="button" value="Filtrera" onclick="motiomera_topplista_specialsok();" /></td>
		</tr>
	</table>


<div id="topplista_special_results"></div>