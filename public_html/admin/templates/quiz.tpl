<h1>Quiz: {$kommun->getNamn()}</h1>

<h2>Frågor</h2>
<p>
	<a href="{$urlHandler->getUrl(QuizFraga, URL_ADMIN_CREATE, $kommun->getId())}">Ny fråga</a>
</p>
<table border="0" cellpadding="0" cellspacing="0">
{foreach from=$fragor item=fraga}
<tr>
	<td style="padding-right: 30px;"><a href="{$urlHandler->getUrl(QuizFraga, URL_ADMIN_EDIT, $fraga->getId())}">{$fraga->getFraga()}</a></td>
	<td style="padding-right: 20px;"><a href="{$urlHandler->getUrl(QuizFraga, URL_ADMIN_DELETE, $fraga->getId())}" onclick="{jsConfirm msg="Vill du verkligen ta bort den här frågan?"}">Ta bort</a></td>
	<td>{if !$fraga->getRattSvar()}
<span style="color: #800">Denna fråga har inget rätt svar markerat.</span>
{/if}</td>
{/foreach}
</table>

<p>
	<a href="{$urlHandler->getUrl(Kommun, URL_EDIT, $kommun->getId())}">Tillbaks</a>
</p>