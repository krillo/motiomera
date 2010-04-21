<h1 class="mmMarginTop5">Kontakta oss</h1>

<form action="javascript: motiomera_kontakta_send()" method="post">
<input type="hidden" name="sida" id="sida" value="{$HTTP_REFERER}" />
<input type="hidden" name="browser" id="user_browser" value="{$HTTP_USER_AGENT}" />
<div>
Du är välkommen att kontakta oss via e-post, brev eller fax – ange ditt meddelande nedan.
Innan du kontaktar oss kan du titta i <a href="/pages/vanligafragor.php">Vanliga frågor</a> för att se om du hittar svaret på din fråga där.
<p/>
För personlig kundservice och teknisk support:<br/>
Ring 042-44 430 31 vardagar 08.00-15.00
<p/>
Allers förlag<br/>
MåBra Kundservice<br/>
251 85 Helsingborg<br/>
Fax: 042-17 37 40
</div>
<p/>
<b>Meddelande:</b>
<textarea name="beskrivning" id="beskrivning" cols="40" rows="8" class="mmWidth90Procent" ></textarea>

<div>
	{if $user_id}
		<b>Avsändare:</b> {$USER->getANamn()}<br/>
		<b>Epost:</b> {$USER->getEpost()}<br/>
		<input type="hidden" name="user_id" id="user_id" value="{$USER->getid()}" />
		<input type="hidden" name="user_namn" id="user_namn" value="{$USER->getANamn()}" />
		<input type="hidden" name="user_epost" id="user_epost" value="{$USER->getEpost()}" />
	{else}
		<b>Namn:</b><br/><input type="text" name="user_namn" id="user_namn" value="{$namn}" /><br/>
		<b>E-post:</b><br><input type="text" name="user_epost" id="user_epost" value="{$epost}" /><br/>
		<input type="hidden" name="user_id" id="user_id" value="Ej inloggad" />
	{/if}
<p/>

<input type="submit" value="Skicka" />

</div>

</form>
