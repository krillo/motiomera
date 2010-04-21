<h1>Välj en kommun att gå till</h1>
<div id="mapdiv" class="mmFloatLeft">

</div>

<script type="text/javascript">
	var map = new FusionMaps("/FusionMaps/C_FCMap_{$lan_1}.swf", "Map1Id", "300", "300", "0", "0");
	// 	var map = new FusionMaps("/FusionMaps/C_FCMap_VastraGotalandslan.swf", "Map1Id", "300", "300", "0", "0");
	map.setDataURL("/maps/valj_kommun.php?z=0");
	map.render("mapdiv");
</script> 

{if $lan_2}

<div id="mapdiv2" class="mmFloatLeft">

</div>

<script type="text/javascript">
	var map = new FusionMaps("/FusionMaps/C_FCMap_{$lan_2}.swf", "Map2Id", "300", "300", "0", "0");
	// 	var map = new FusionMaps("/FusionMaps/C_FCMap_VastraGotalandslan.swf", "Map1Id", "300", "300", "0", "0");
	map.setDataURL("/maps/valj_kommun.php?z=0");
	map.render("mapdiv2");
</script> 

{/if}