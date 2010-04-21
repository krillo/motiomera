<script language="javascript">
{literal}
	$(document).ready(function(){minaQuiz()});
{/literal}
</script>
<div id="mmMinaQuizMainpage">
<div id="mmMinaQuizTopRight">
{if $isAgare}
	<a href="{$urlHandler->getUrl(Minaquiz, URL_CREATE)}"><img src="/img/icons/QuizNewIcon.gif" alt="Skapa ett nytt quiz" /></a> <a href="{$urlHandler->getUrl(MinaQuiz, URL_CREATE)}">Skapa ett nytt quiz</a>
	<br />
	<br />
	</div>
	<div class="mmh1 mmMarginBottom">Mina quiz</div>
{else}
	</div>
	<div class="mmh1 mmMarginBottom">Quiz tillhörande {$medlem->getANamn()}</div>
{/if}

{if count($MinaQuiz) == 0 && !$isAgare}
	<h2>Det finns inga quiz du har tillgång till</h2>
{elseif count($MinaQuiz) == 0 && $isAgare}
	<h2>Du har inte skapat något quiz än</h2>
{/if}

<br/>
{foreach from=$MinaQuiz item=quiz}

	<div class="mmBlueBoxWideTop"><h3 class="mmFontWhite BoxTitle"><a href="{if $isAgare}{$urlHandler->getUrl("MinaQuiz", URL_EDIT, $quiz.id)}{else}{$urlHandler->getUrl("MinaQuiz", URL_VIEW, $quiz.id)}{/if}">{$quiz.namn|truncate:70}</a></h3><div class="mmFontWhite mmBoxTitleTextRight">
		{if $isAgare}<a href="{$urlHandler->getUrl("MinaQuiz", URL_EDIT, $quiz.id)}">&Auml;ndra</a>&nbsp; &nbsp; &nbsp;<a href="{$urlHandler->getUrl("MinaQuiz", URL_DELETE, $quiz.id)}" onClick="return confirm('&Auml;r du s&auml;ker p&aring; att du vill ta bort quizet \'{$quiz.namn}\'?');">Ta bort</a>{else}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>{$quiz.tillagd|substr:0:10}</em>{/if}</div></div>

	<div class="mmBlueBoxWideBg">
		{if $isAgare}
			{assign var=there_are_hidden_questions value=false}
			{foreach from=$quiz.fragor item=fraga name=fragorna}
				<h3>{$smarty.foreach.fragorna.iteration}: {$fraga.fraga|truncate:80}</h3>
				({$fraga.svar_1|truncate:40}, {$fraga.svar_2|truncate:40} eller {$fraga.svar_3|truncate:40})<br />
			
				<br />
				{if $smarty.foreach.fragorna.iteration == $visa_antal_fragor && !$smarty.foreach.fragorna.last}
				{assign var=there_are_hidden_questions value=true}
				<a class="show_all_questions">Visa resten av fr&aring;gorna</a>
				<div class="hidden_questions hide">
				{/if}
			{foreachelse}
			    Detta quiz har inga frågor
			{/foreach}
			{if $there_are_hidden_questions}
				<a class="hide_hidden_questions">G&ouml;m fr&aring;gorna</a>
				</div>
			{/if}
			{assign var=i value=0}
		{else}
			<h3><a hreF="{$urlHandler->getUrl('MinaQuiz', URL_VIEW, $quiz.id)}">Svara på {$quiz.antal_fragor} {if $quiz.antal_fragor == 1}fråga{else}frågor{/if}</a></h3>
		{/if}
	</div>


	<div class="mmBlueBoxWideBottom"></div>
	<div class="mmClearBoth"></div>

{/foreach}

{include file="highslide_controlbar.tpl"}
</div>