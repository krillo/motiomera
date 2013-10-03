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
global $SETTINGS, $USER;
$footer = file_get_contents($SETTINGS["url"].'/api/?snippet=footer&mmid='.$USER->getId() );
print($footer);
{/php}