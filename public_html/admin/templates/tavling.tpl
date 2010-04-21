<h1>Tävling</h1>
<form action="/admin/pages/tavling.php" method="post">

<strong>Antal steg: </strong>
<select name="antal_steg">
{html_options values=$steg output=$steg selected=$antalSteg_sel}
</select><br />

<strong>Antal medlemmar: </strong>
<select name="antal_medlemmar">
{html_options values=$antalMedlemmar output=$antalMedlemmar selected=$antalMedlemmar_sel}
</select><br />

<strong>Procent Pro: </strong>
<select name="procent_pro">
{html_options values=$percentTimes output=$percent selected=$percent_sel}
</select> % <br />


<strong>Tidsperiod: </strong> 
<select name="startTid">
{html_options values=$dates output=$dates selected=$startTid_sel}
</select>
<select name="slutTid">
{html_options values=$dates output=$dates selected=$slutTid_sel})
</select>
<input type="submit" value="Hämta" name="submit" />
</form>
<br />

{if $userArray}
<a href="{$fileUrl}">Hämta lista</a>
<table>
<tr>
	<td>
		nr
	</td>
	<td>
		id
	</td>
	<td>
		Användarnamn
	</td>
	<td>
		epost
	</td>
	<td>
		Antal steg
	</td>
</tr>

{foreach from=$userArray item=user name=userLoop}
<tr>
	<td class="mmList1">
		{$smarty.foreach.userLoop.iteration}
	</td>
	<td class="mmList2">
		{$user.id}
	</td>
	<td class="mmList1">
		<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $user.id)}">{$user.aNamn}</a>
	</td>
	<td class="mmList2">
		<a href="mailto:{$user.epost}">{$user.epost}</a>
	</td>
	<td class="mmList1">
		{$user.steg}
	</td>
	<td class="mmList2">
		{if $user.levelId eq 1}Pro{else}Gratis{/if}
	</td>
</tr>
{/foreach}
</table>
<p>
Html-kod att klistra in på textsidan där vinnarna presenteras:<br />
{$html}
</p>
{else}
Inga Träffar!
{/if}
