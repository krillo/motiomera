<h1 class="mmMarginTop5">Anmälan</h1>

<form action="javascript: motiomera_rapportera_send()" method="post">
<div>Har du observerat något som du anser inte hör hemma på den här sidan?</div>
<p/>
<b>Beskrivning:</b><br/>
<textarea name="beskrivning" id="beskrivning" cols="40" rows="8" class="mmWidth90Procent" ></textarea>

<div>
	<b>Rapporterad sida:</b> {$HTTP_REFERER}
	<input type="hidden" name="sida" id="sida" value="{$HTTP_REFERER}" />
	<input type="hidden" name="browser" id="user_browser" value="{$HTTP_USER_AGENT}" />
	<br/>
	{if $user_id}
		<b>Anmälare:</b> {$USER->getANamn()}<br/>
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
