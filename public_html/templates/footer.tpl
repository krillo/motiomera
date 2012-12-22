		<div class="mmClearBoth"></div>
	</div>
</div>

<div id="motiomera_popup_overlay"></div>
<div id="motiomera_popup_shadow"></div>
<div id="motiomera_popup">
	<div id="motiomera_popup_close"></div>
	<div id="motiomera_popup_content"></div>
</div>

{php}
//print_r($GLOBALS);
global $SETTINGS;
$footer = file_get_contents($SETTINGS["url"].'/api-footer/');
print($footer);
{/php}
