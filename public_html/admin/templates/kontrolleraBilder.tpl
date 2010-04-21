<h1>Kontrollera Bilder</h1>
<div style="margin-bottom:8px;">
	<b>Icke godk&auml;nda bilder</b>
</div>

<form method="POST" action="/admin/actions/approvebild.php" class="margin0">
{foreach from=$fotoalbumbilder key=k item=v name=foo}
<input type="hidden" name="bild_{$smarty.foreach.foo.iteration}" value="{$v.id}" />
{/foreach}
<input type="hidden" name="nrofpictures" value="{$smarty.foreach.foo.iteration}" />
<input type="hidden" name="approve_type" value="1" />
<div style="margin-bottom:8px;">
	<input type="submit" value="Godk&auml;nn alla" />
</div>
</form>

{foreach from=$fotoalbumbilder key=k item=v name=foo}

{if $smarty.foreach.foo.iteration is odd}
<div style="padding:4px;background-color:#66cc99;width:550px;">
{else}
<div style="padding:4px;background-color:#cccc99;width:550px;">
{/if}
	<div  class="mmFloatLeft" style="width:80px;">
		<a id="thumb" href="/admin/actions/visafotoalbumbild.php?id={$v.id}&storlek=stor" class="highslide" onclick="return hs.expand(this)">
			<img 
				src="/admin/actions/visafotoalbumbild.php?id={$v.id}&storlek=mini" 
				alt="{$v.namn}" 
				title="{$v.namn}" 
				width="{$v.bredd_mini}"
				height="{$v.hojd_mini}" 
				border="0" />
		</a>
	</div>
	<div  class="mmFloatLeft mmWidthHundra">
		<div><b>Anv&auml;ndare:</b></div>
		<div>
			<a target="blank" href="{$urlHandler->getUrl(Medlem, URL_VIEW, $v.medlem_id)}">{$v.aNamn}</a>
		</div>
	</div>
	<div  class="mmFloatLeft mmWidthHundra">
		<div><b>Namn:</b></div>
		<div>{$v.namn}</div>
	</div>
	<div  class="mmFloatLeft mmWidthEttTvaNollPixlar">
		<div><b>Beskrivning:</b></div>
		<div>{$v.beskrivning}</div>
	</div>
	<div  class="mmFloatLeft" style="width:80px;">
	<form method="POST" action="/admin/actions/approvebild.php" class="margin0">
		<input type="hidden" name="approve_type" value="1" />
		<input type="hidden" name="bild_1" value="{$v.id}" />
		<input type="hidden" name="nrofpictures" value="1" />
		<input type="submit" value="Godk&auml;nn" />
	</form>
	</div>
	<div  class="mmFloatLeft mmWidthSexNollPixlar">
	<form method="POST" action="/admin/actions/approvebild.php" class="margin0">
		<input type="hidden" name="approve_type" value="0" />
		<input type="hidden" name="bild_1" value="{$v.id}" />
		<input type="hidden" name="nrofpictures" value="1" />
		<input type="submit" value="Ta bort" />
	</form>
	</div>
	<div class="mmClearBoth"></div>
</div>		
{/foreach}