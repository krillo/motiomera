<h2>Sök medlem</h2>

<form action="" method="post" onsubmit="motiomera_sok_medlem(this.sokord.value, false); return false;">


	<input type="text" name="sokord" onkeyup="motiomera_sok_medlem(this.value, false);" />
	<input type="submit" value="Sök" />
	<img src="/img/framework/loading.gif" id="motiomera_sok_medlem_loading" class="mmLoading adressLoading" alt="Laddar" />

</form>

<div id="motiomera_sok_medlem_resultat"></div>