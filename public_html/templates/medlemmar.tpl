<h1>Medlemmar</h1>

<form action="" method="post" onsubmit="motiomera_sok_medlem(this.sokord.value, false); return false;">


	<input type="text" name="sokord" onkeyup="motiomera_sok_medlem(this.value, false);" />
	<input type="submit" value="Sök" />
	<img src="/img/framework/loading.gif" id="motiomera_sok_medlem_loading" class="mmLoading" alt="Laddar" />

</form>

<div id="motiomera_sok_medlem_resultat"></div>