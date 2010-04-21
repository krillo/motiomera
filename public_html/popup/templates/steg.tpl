<script type="text/javascript" charset="utf-8">
</script>

<h1 class="mmMarginTop5">Rapportera steg</h1>

<div id="motiomera_kalender_steg"></div>

<div id="motiomera_steg_right">
	<form action="{$urlHandler->getUrl(Steg, URL_SAVE)}" method="post" name="stegRapport" id="motiomera_form_rapporteraSteg" onsubmit="motiomera_steg_addSteg(); return false;">
	
		<div class="mmWidth320">
			<input type="submit" value="Lägg till" name="spara" class="mmFloatRight" />
			
			<input type="text" size="4" id="motiomera_steg_antal" name="antal" value="" maxlength=6/>
			<span id="motiomera_steg_enhet">steg</span> 
			<small><a href="#" title="Välj en annan aktivitet" onclick="motiomera_steg_visaAktiviteter(); return false;" id="motiomera_steg_valjAktiviteteLink">Annan aktivitet</a></small>
			<div id="aktivitetLista" class="hide">
				{mm_html_options name=steg_aid options=$opt_aktivitet selected=$sel_aktivitet}
			</div>
		</div>
		
		<table id="motiomera_steg_preview_header" class="motiomera_steg_preview_table" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th class="motiomera_steg_table_cell1">Datum</th>
				<th class="motiomera_steg_table_cell2">Aktivitet</th>
				<th class="motiomera_steg_table_cell3">Tid</th>
				<th class="motiomera_steg_table_cell3">Antal</th>
				<td class="motiomera_steg_table_cell4"></td>
			</tr>
		</table>
		<div class="motiomera_steg_preview_wrap">
			<table id="motiomera_steg_preview" class="motiomera_steg_preview_table" border="0" cellpadding="0" cellspacing="0"></table>
		</div>
		<div class="mmWidth320">
			<input type="button" onclick="return false;" value="Godkänn och spara" id="motiomera_steg_spara" class="mmFloatRight mmMarginTop" />
		</div>
	</form>
</div>
