<div class="mmBlueBoxTop"><h3 class="BoxTitle">Framsteg de senaste 7 dagarna</h3></div>
<div class="mmBlueBoxBg">
	<div id="mmStegGrafikSenasteVeckan">
		<strong>Steg</strong><br />
		{$stegSenasteVeckan|nice_tal}
		{if $sajtDelarObj->medlemHasAccess($medlem,"minSidaCalories")} <br />{$calstegSenasteVeckan|nice_tal} kcal{/if}
		<br />
		<br />
		<br />
		<strong>Snitt</strong><br />
		{$stegsnitt|nice_tal}
		{if $sajtDelarObj->medlemHasAccess($medlem,"minSidaCalories")}<br />{$calstegsnitt|nice_tal} kcal{/if}
	</div>
	<div class="mmFlash">
		{$graf}
	</div>
	
	<br />
	{if $medaljLimitReached}
	<div style="float: right; width: 250px;">
		<b>Grattis!</b><br />
		<img src="{$medaljBild}" align="left" style="margin-right: 5px;" />
		Du har registrerat mer än {$medaljLimit} poäng denna vecka och har fått {$medaljNamn}.<br />
		Du har också kvalificerat dig till veckans utlottning. (OBS gäller bara under vår- och höstkampanjen!
	</div>
	{/if}	
	<div class="mmGuldNivaInfo"></div>Guldnivå
	<div class="mmClearLeft"></div><br />
	<div class="mmSilverNivaInfo"></div> Silvernivå
	<div class="mmClearLeft"></div>

	<br />
</div>

<div class="mmBlueBoxBottom"></div>
<div class="mmClearLeft"></div>