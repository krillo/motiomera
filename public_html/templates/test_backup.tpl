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
<h1>Quiz</h1>
<div id="right_answer_div" class="hidden_div warn_text">
	<div>
		<b>R&auml;tt svar var <span id="rans">&nbsp;</span></b>
	</div>
	<div>&nbsp;</div>
</div>
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
<script type="text/javascript">
	var arr_length = all_questions.length;
	{literal}
	loadQuizQuestion();
	/*
	for(i=0;i<arr_length;i++){
		quest_arr = all_questions[i];
		document.write(quest_arr[0]);
		document.write("<br>");
		document.write(quest_arr[1]);
		document.write("<br>");
		document.write(quest_arr[2]);
		document.write("<br>");
		document.write("<br>");

		var ans_alt = all_alternatives[i];
		for(x=0;x<ans_alt.length;x++){
			var one_alt = ans_alt[x];
			document.write(one_alt[0]);
			document.write("<br>");
			document.write(one_alt[1]);
			document.write("<br>");
		}
		
		document.write("<br>");
		document.write("****************");
		document.write("<br>");
	}
	*/
	{/literal}

	{literal}
		function loadQuizQuestion(){
			current_nr = parseInt(current_question_index) + 1;
			setDataValue('fraga_nr', current_nr);
			var quest_arr = all_questions[current_question_index];
			fraga1 = quest_arr[0];
			fraga2 = quest_arr[1];
			fraga3 = quest_arr[2];
			setDataValue('text_fraga', fraga2);
			var radio_div = getById('radio_div');
			
			var ans_alt = all_alternatives[current_question_index];
			var the_radios = "";
			for(x=0;x<ans_alt.length;x++){
				var one_alt = ans_alt[x];
				the_radios += '<input type="radio" name="the_alt" value="' + one_alt[0] + '" />' + one_alt[1] + '<br>';
			}
			radio_div.innerHTML = the_radios;
			current_question_index++;
		}

		function validateAnswer(){
			if(next_enabled){
				var chosen = "";
				var len = document.theform.the_alt.length;
				for(i=0;i<len;i++){
					if(document.theform.the_alt[i].checked) {
						chosen = document.theform.the_alt[i].value;
					}
				}
				if (chosen == "") {
					alert("Du måste välja ett svar!");
					return;
				}
				else{
					if(current_question_index == numberOfQuestions){
						quizEnded = true;
					}
					viewRAnswer();
				}
			}
		}	

		function loadNext(){
			hideDiv('right_answer_div');
			if(quizEnded){
				alert("resume");
			}
			else{
				loadQuizQuestion();
				next_enabled = true;
			}
		}
		
		function viewRAnswer(){
			next_enabled = false;
			showDiv('right_answer_div');
			setDataValue('rans', getAltTextById(fraga3));
			setTimeout ("loadNext()", 3000);
		}

		function getAltTextById(id){
			var ans_alt = all_alternatives[current_question_index-1];
			for(x=0;x<ans_alt.length;x++){
				var one_alt = ans_alt[x];
				if(one_alt[0] == id){
					return one_alt[1];
				}
			}
			return 'empty';
		}
	{/literal}
</script>
