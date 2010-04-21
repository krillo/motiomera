<script type="text/javascript">
	var current_question_index = 0;
	var all_questions = [];
	var all_alternatives = [];
	var fraga1;
	var fraga2;
	var fraga3;
	var count = 0;
	var next_enabled = true;
	var quizEnded = false;
	var kommun_namn = '{$kommun_namn}';
	{foreach from=$questions_and_answers key=k item=v}
		var question_id = "{$v[0]}";
		var question_text = "{$v[1]}";
		var question_r_answer = "{$v[2]}";
		questions = new Array(question_id, question_text, question_r_answer);
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
<div>
	<h1>
		Quiz
	</h1>
</div>
<div id="quiz_running">
	<div>
		<h3>
			Du har <span id="seconds_elapsed">0</span> sekunder kvar
		</h3>
	</div>
	<div>&nbsp;</div>
	<div>
		<b>Fr&aring;ga <span id="fraga_nr">0</span></b>
	</div>
	<div id="text_fraga">
		&nbsp;
	</div>
	<div>&nbsp;</div>
	<div>
		<b>Svarsalternativ</b>
	</div>
	<form name="theform">
	<div id="radio_div">&nbsp;</div>
	</form>
	<div>&nbsp;</div>
	<div>
		<input type="button" value="Next" onclick="validateAnswer()" />
	</div>
</div>

<div id="time_elapsed" class="hidden_div">
	<div class="time_is_up">
		<div>Tiden &auml;r ute!</div>
		<div class="js_close" onclick="timeIsUpClose()">St&auml;ng</div>
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
		<div><b><span id="ratt_eller_fel">&nbsp;</span>R&auml;tt svar var <span id="rans">&nbsp;</span></b></div>
		<div class="js_close" onclick="rightAnswerViewed()">St&auml;ng</div>
	</div>
</div>

<div id="quiz_ended" class="hidden_div">
	Quiz is over
</div>
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
