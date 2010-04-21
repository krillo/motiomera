<?php /* Smarty version 2.6.19, created on 2010-02-22 09:44:34
         compiled from steg.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'mm_html_options', 'steg.tpl', 18, false),)), $this); ?>
<script type="text/javascript" charset="utf-8">
</script>

<h1 class="mmMarginTop5">Rapportera steg</h1>

<div id="motiomera_kalender_steg"></div>

<div id="motiomera_steg_right">
	<form action="<?php echo $this->_tpl_vars['urlHandler']->getUrl('Steg','URL_SAVE'); ?>
" method="post" name="stegRapport" id="motiomera_form_rapporteraSteg" onsubmit="motiomera_steg_addSteg(); return false;">
	
		<div class="mmWidth320">
			<input type="submit" value="Lägg till" name="spara" class="mmFloatRight" />
			
			<input type="text" size="4" id="motiomera_steg_antal" name="antal" value="" maxlength=6/>
			<span id="motiomera_steg_enhet">steg</span> 
			<small><a href="#" title="Välj en annan aktivitet" onclick="motiomera_steg_visaAktiviteter(); return false;" id="motiomera_steg_valjAktiviteteLink">Annan aktivitet</a></small>
			<div id="aktivitetLista" class="hide">
				<?php echo smarty_function_mm_html_options(array('name' => 'steg_aid','options' => $this->_tpl_vars['opt_aktivitet'],'selected' => $this->_tpl_vars['sel_aktivitet']), $this);?>

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