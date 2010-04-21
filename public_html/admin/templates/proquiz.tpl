<script language="javascript">
{literal}
	$(document).ready(function(){minaQuiz()});
{/literal}
</script>
<div id="mmProQuizMainpage">
<div id="mmProQuizTopRight">
<a href="{$urlHandler->getUrl(ProQuiz, URL_ADMIN_CREATE)}"><img src="/img/icons/QuizNewIcon.gif" alt="Skapa ett nytt quiz" /></a> <a href="{$urlHandler->getUrl(ProQuiz, URL_ADMIN_CREATE)}">Skapa ett nytt quiz</a>
<br />
<br />
</div>
<div class="mmh1 mmMarginBottom">Pro Quiz</div>

{if count($proquiz) == 0}
	<h2>Det finns inga Pro Quiz ännu</h2>
{/if}

<br/>
{foreach from=$proquiz item=quiz}

	<div class="mmBlueBoxWideTop"><h3 class="mmFontWhite BoxTitle"><a href="{if $isAgare}{$urlHandler->getUrl(ProQuiz, URL_ADMIN_EDIT, $quiz.id)}{else}{$urlHandler->getUrl(ProQuiz, URL_ADMIN_VIEW, $quiz.id)}{/if}">{$quiz.namn}</a></h3><div class="mmFontWhite mmBoxTitleTextRight">
		{if $isAgare}<a href="{$urlHandler->getUrl(ProQuiz, URL_ADMIN_EDIT, $quiz.id)}">&Auml;ndra</a>&nbsp; &nbsp; &nbsp;<a href="{$urlHandler->getUrl(ProQuiz, URL_ADMIN_DELETE, $quiz.id)}" onClick="return confirm('&Auml;r du s&auml;ker p&aring; att du vill ta bort quizet \'{$quiz.namn}\'?');">Ta bort</a>{else}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>{$quiz.tillagd|substr:0:10}</em>{/if}</div></div>

	<div class="mmBlueBoxWideBg">
		{assign var=there_are_hidden_questions value=false}
		{foreach from=$quiz.fragor item=fraga name=fragorna}
			<h3>{$smarty.foreach.fragorna.iteration}: {$fraga.fraga}</h3>
			({$fraga.svar_1}, {$fraga.svar_2} eller {$fraga.svar_3})<br />
		
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
	</div>


	<div class="mmBlueBoxWideBottom"></div>
	<div class="mmClearBoth"></div>

{/foreach}

{include file="highslide_controlbar.tpl"}
</div>