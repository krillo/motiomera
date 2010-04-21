<div class="mmFlash">{$graf}</div>
<br/>
	<div class="mmGuldNivaInfo"></div>Guldnivå
	<div class="mmClearLeft"></div><br/>
	<div class="mmSilverNivaInfo"></div> Silvernivå
	<div class="mmClearLeft"></div>
	
<h2 class="mmMarginBottom">Mina framsteg</h2>

<div id="my_chart"></div>
	
	<h2 class="mmMarginBottom">Stegkalender</h2>
	{*}<p>
		<a href="#" onclick="motiomera_steg_rapportera(); return false;">Rapportera steg popup</a>
			<a href="{$urlHandler->getUrl(Steg, URL_CREATE)}">Rapportera steg</a>
	</p>
	{*}
	
	
	<div id="motiomera_kalender_rapport"></div>
	
	<div id="mmKalenderSteg"></div>
	<script type="text/javascript">
		motiomera_visaRapportKalender({$medlem->getId()});
	</script>

