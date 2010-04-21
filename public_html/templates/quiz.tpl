<script type="text/javascript">
	var current_question_index = 0;
	var all_questions = [];
	var all_alternatives = [];
	var fraga1;
	var fraga2;
	var fraga3;
	var fraga4;
	var count = 0;
	var next_enabled = true;
	var quizEnded = false;
	var kommun_namn = '{$kommun_namn}';
	{foreach from=$questions_and_answers key=k item=v}
		var question_id = "{$v[0]}";
		var question_text = "{$v[1]}";
		var question_r_answer = "{$v[2]}";
		var question_is_pro = "{$v[4]}";
		questions = new Array(question_id, question_text, question_r_answer, question_is_pro);
		all_questions[count] = questions;
		
		var count_alt = 0;
		var the_ans = [];
		{foreach from=$v[3] key=k2 item=v2}
			var alt_id = "{$v2[0]}";
			var alt_text = "{$v2[1]}";
			one_ans = new Array(alt_id, alt_text);
			the_ans[count_alt] = one_ans;
			count_alt++;
		{/foreach}
		all_alternatives[count] = the_ans;	
		count++;
	{/foreach}
	var numberOfQuestions = count;
</script>

{if $questions_and_answers|@count > 0}
<!-- START mmColumnRightQuiz -->
	
	<div id="mmColumnRightQuiz">
	
	
	
	{if $kommunbild}
		{assign var=storbild value=$kommunbild->getBild()}
		{assign var=bild value=$kommunbild->getFramsidebild()}
		{if $bild}
		<div id="mmQuizKommunBild">
			<a href="{$kommunbild->getBildUrl()}" onclick="return hs.expand(this)"><img src="{$bild->getUrl()}" alt="" class="mmQuizKommunImg" /></a><br />
		</div>
		{/if}
	{/if}
	
	<div class="mmGrayLineRight"></div>


	
	</div>

	<!-- END mmColumnRightQuiz -->
	<div class="mmh1 mmMarginBottom">
		KommunQuiz för {$kommun->getNamn()}
	</div>

	<div class="mmMarginLeft10">


		<div id="start_quiz_div">
			Kommunquizen best&aring;r av {$antalVanligafragor} fr&aring;gor relaterade till kommunen.
			Du har 60 sekunder p&aring; dig att svara p&aring; varje fr&aring;ga.
			R&auml;tt svar meddelas direkt efter att du har svarat p&aring; en fr&aring;ga.

			{if $antalProfragor}
			<br /><br />
			Du som har PRO-medlemskap får dessutom svara på {$antalProfragor} st kluriga
			bonusfrågor av almänbildande karaktär.
			{/if}	
			<br /><br />
			<a href="javascript:;" onclick="startQuiz()"><img src="/img/icons/starta_quiz.gif" alt="Starta quizen!" /></a>

		</div>
	</div>
	
	
	<div id="quiz_running" class="mmDisplayNone">
	
		
		<div class="mmFontBold12">Fr&aring;ga <span id="fraga_nr">0</span> av {$antalFragor}</div><br />
		<h3 id="rubrik_fraga">&nbsp;</h3>
		<br />
		<div id="text_fraga">
			&nbsp;
		</div>
		<br /><br />
		<div id="radio_div">&nbsp;</div>


		<br />
		<div id="quiz_ratta_div"><a href="javascript:;" onclick="validateAnswer()"><img src="/img/icons/quiz_ratta.gif" alt="R&auml;tta" /></a></div>
		
		<div id="time_elapsed" class="hidden_div">
			<div class="time_is_up">
				<div>Tiden &auml;r ute!</div>
				<div class="js_close" onclick="timeIsUpClose()">Se r&auml;tt svar</div>
			</div>
		</div>
		
		<div id="must_select_answer" class="hidden_div">
			<div class="time_is_up">
				<div>Du m&aring;ste v&auml;lja ett svar!</div>
				<div class="js_close" onclick="youMustSelectClose()">St&auml;ng</div>
			</div>
		</div>
		
		<div id="right_answer_div" class="hidden_div">

			<div class="time_is_up">
				<div class="mmQuizAnswer" id="mmQuizAnswer"><b><span id="ratt_eller_fel">&nbsp;</span>R&auml;tt svar var <span id="rans">&nbsp;</span></b></div><br/>
				<div class="" onclick="rightAnswerViewed()"><div class="mmQuizNasta">N&auml;sta fr&aring;ga &raquo;</div></div>
			</div>
		</div>

		
		<br/><br/>
		<div id="quiz_timer">
			<h3>Du har <span id="seconds_elapsed">60</span> sekunder kvar</h3>
		</div>


	</div>
	
		
	<div id="quiz_ended" class="hidden_div">
	</div>
	<!-- END mmColumnMiddleQuiz -->

	<br class="mmClearBoth" />

		
{/if}


<script type="text/javascript">
	{literal}
		function dhtmlLoadScript(url){
			var e = document.createElement("script");
			e.src = url;
			e.type="text/javascript";
			document.getElementsByTagName("head")[0].appendChild(e); 
		}
		
		onload = function(){ 
			dhtmlLoadScript(quizScript);
		}
		var arr_length = all_questions.length;
	{/literal}
</script>
