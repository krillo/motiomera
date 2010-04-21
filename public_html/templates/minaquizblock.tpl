
	<div class="mmQuizBoxTop">
		<h3 class="mmWhite BoxTitle">{if !$isProfil}Mina {/if}Quiz</h3>
	</div>
	<div class="mmQuizBoxBg">
		<div class="mmQuizBoxBgQuiz">
			{if $quizblock == false && $isProfil}
				{if $hasQuiz}
				<span><br />&nbsp;&nbsp;Inga obesvarade Quiz.</span>
				{else}
				<span><br />&nbsp;&nbsp;Inga Quiz.</span>
				{/if}
			{else}
			<table cellspacing="2" border="0">
				{foreach from=$quizblock item=quiz}
				<tr>
					<td>
						<h3><a href="{if $isProfil}{$urlHandler->getUrl(MinaQuiz, URL_VIEW, $quiz.id)}{else}{$urlHandler->getUrl(MinaQuiz, URL_EDIT, $quiz.id)}{/if}">{$quiz.namn|truncate:25}</a></h3>
						{if $isProfil}{$quiz.antal_fragor} {if $quiz.antal_fragor == 1}fråga{else}frågor{/if} kvar{else}<em>Klicka f&ouml;r att &auml;ndra</em>{/if}<br />
					</td>
				</tr>
				<tr></tr>
				{/foreach}
			</table>
			{/if}
		</div>

		<div class="mmAlbumBoxBgAlbumLank">
			{if $isProfil}
				<a href="{$urlHandler->getUrl(MinaQuiz, URL_LIST, $medlem->getId())}">Se alla quiz</a>
				<a href="{$urlHandler->getUrl(MinaQuiz, URL_LIST, $medlem->getId())}"><img src="/img/icons/ArrowCircleBlue.gif" alt="" /></a><br/>
			{else}
				<a href="{$urlHandler->getUrl(MinaQuiz, URL_LIST)}">Se alla quiz</a>
				<a href="{$urlHandler->getUrl(MinaQuiz, URL_LIST, $medlem->getId())}"><img src="/img/icons/ArrowCircleBlue.gif" alt="" /></a><br/>
				<a href="{$urlHandler->getUrl(MinaQuiz, URL_CREATE)}">Skapa nytt quiz</a>
				<a href="{$urlHandler->getUrl(MinaQuiz, URL_CREATE)}"><img src="/img/icons/ArrowCircleBlue.gif" alt="" /></a>
			{/if}
		</div>
		<br clear="all" />
	</div>
{include file="highslide_controlbar.tpl"}

