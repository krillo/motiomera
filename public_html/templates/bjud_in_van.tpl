{if !empty($sendMails)}
	<h1>Vi har nu skickat ut dina inbjudningar!</h1>
	<h4>Vi skickade en inbjudan till</h4>
	<ul>
		{foreach from=$sendMails item=email key=key}
			<li>{$email}</li>
		{/foreach}
	</ul>
{/if}
{if !empty($faultyMails) || !empty($mailExistsAsMember) || !empty($mailExistsAsMember) || !empty($mailExistsAsTip)}
	<h1>Det var det vissa inbjudningar vi inte kunde skicka ut</h1>
{/if}
{if !empty($faultyMails)}
	<h4>Dessa adresserna är inte fungerande epost-adresser</h4>
	<ul>
		{foreach from=$faultyMails item=email key=key}
			<li>{$email}</li>
		{/foreach}
	</ul>
{/if}
{if !empty($mailExistsAsMember)}
	<h4>Dessa personerna finns redan som medlemmar på Motiomera. <i>Klicka på deras namn för att se deras profil.</i></h4>
	<ul>
		{foreach from=$mailExistsAsMember item=user key=key}
			<li><a href="/pages/profil.php?mid={$user.id}">{$user.username} - {$user.email}</a></li>
		{/foreach}
	</ul>
{/if}
{if !empty($mailExistsAsTip)}
	<h4>Dessa personerna har redan blivit inbjudna till Motiomera.</h4>
	<ul>
		{foreach from=$mailExistsAsTip item=email key=key}
			<li>{$email}</li>
		{/foreach}
	</ul>
{/if}
<hr />
<p>
	<a href="/pages/adressbok.php?tab=3">Klicka här för att bjuda in mer vänner.</a>
</p>