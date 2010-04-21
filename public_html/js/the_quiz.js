var answer_interval;	
var elapsed_time = 0;		
var result_array = [];
//var action_link = "/pages/quiz_result.php?";
var action_link = "/actions/save_quiz.php?";
var nr_of_right_answers = 0;
var temporary_elapsed_time;
var waiting_for_close_time_is_up = false;

action_link += "kommun_namn="+kommun_namn;
		
		function quiz_clicked_option(what, value) {
			
			q_the_alts = document.getElementsByName("the_alt_img");
						
			for(i=0;i<q_the_alts.length;i++) {
				
				q_the_alts[i].src = '/img/icons/quiz_svarsbox.gif';

				//q_the_alts[i].parentNode.innerHTML = "";
			}
			
			what.src = '/img/icons/quiz_svarsbox_check.gif';
			
			
			v = document.getElementById("the_alt");
			v.value = value;
		}
		


		
		
		function loadQuizQuestion(){
			setDataValue('seconds_elapsed', MAX_QUIZ_SECONDS);
			current_nr = parseInt(current_question_index) + 1;
			setDataValue('fraga_nr', current_nr);
			var quest_arr = all_questions[current_question_index];
			fraga1 = quest_arr[0];
			fraga2 = quest_arr[1];
			fraga3 = quest_arr[2];
			fraga4 = quest_arr[4];
			if (fraga2.indexOf('|') > 0) {
				fragesats = fraga2.split('|');
				fraga2 = fragesats[1];
				setDataValue('rubrik_fraga', fragesats[0]);
			} else {
				setDataValue('rubrik_fraga', ' ');
			}
			
			setDataValue('text_fraga', fraga2);
			var radio_div = getById('radio_div');
			
			var ans_alt = all_alternatives[current_question_index];
			var the_radios = "";
			
			for(var j, x, i = ans_alt.length; i; j = parseInt(Math.random() * i), x = ans_alt[--i], ans_alt[i] = ans_alt[j], ans_alt[j] = x);

			for(x=0;x<ans_alt.length;x++){
				var one_alt = ans_alt[x];
				//the_radios += '<input type="radio" name="the_alt" value="' + one_alt[0] + '" />' + one_alt[1] + '<br>';
				//the_radios += '<div  class="mmFloatLeft" style="margin-right:10px;margin-bottom:10px;vertical-align:middle;" onclick="quiz_clicked_option(this)"><img src="/img/icons/quiz_svarsbox.gif" class="mmMarginRight10" /><input type="radio" name="the_alt" class="hide" value="' + one_alt[0] + '" /></div><br/>' + one_alt[1] + '<div style="clear:both;"></div>';
				the_radios += '<img src="/img/icons/quiz_svarsbox.gif" name="the_alt_img" onclick="quiz_clicked_option(this,\'' + one_alt[0] + '\')" class="mmMarginRight10" style="display:block;" /><div class="mmQuizAlternativeText">' + one_alt[1] + '</div><div class="mmClearLeft"></div><br/>';
			}
			the_radios += '<input type="hidden" id="the_alt" name="the_alt" />';
			radio_div.innerHTML = the_radios;
			current_question_index++;
			answer_interval = setInterval("startCountDown()", 1000);
		}

		function startCountDown(){
			if(elapsed_time == MAX_QUIZ_SECONDS){
				waiting_for_close_time_is_up = true;
				clearInterval(answer_interval);
				elapsed_time = 0;
				hideDiv('quiz_ratta_div');
				hideDiv('quiz_timer');
				setDataValue('seconds_elapsed', MAX_QUIZ_SECONDS);
				showDiv('time_elapsed');
				return;
			}
			elapsed_time = elapsed_time + 1;
			setDataValue('seconds_elapsed', MAX_QUIZ_SECONDS - elapsed_time);
		}

		function timeIsUpClose(){
			viewRAnswer(0);
			hideDiv('time_elapsed');
			//showDiv('quiz_running');
		}

		function rightAnswerViewed(){
			hideDiv('right_answer_div');
			showDiv('quiz_ratta_div');
			showDiv('quiz_timer');
			loadNext();
		}

		function youMustSelectClose(){
			elapsed_time = temporary_elapsed_time;
			answer_interval = setInterval("startCountDown()", 1000);
			hideDiv('must_select_answer');
			showDiv('quiz_ratta_div');
			showDiv('quiz_timer');
		}

		function viewRAnswer(chosen){
			if(current_question_index == numberOfQuestions){
				quizEnded = true;
			}
			clearInterval(answer_interval);
			elapsed_time = 0;
			next_enabled = false;
			hideDiv('quiz_ratta_div');
			hideDiv('quiz_timer');
			showDiv('right_answer_div');
			
			if(chosen == fraga3){
				setDataValue('ratt_eller_fel', "Bra! ");
				document.getElementById('mmQuizAnswer').style.color = "#4A9422";
			}
			else{
				setDataValue('ratt_eller_fel', "Fel! ");
				document.getElementById('mmQuizAnswer').style.color = "red";
			}
			setDataValue('rans', getAltTextById(fraga3));
			//setTimeout ("loadNext()", 3000);
			result_array[(current_question_index-1)] = new Array(fraga1, chosen, fraga3, fraga4);
		}
	
		function validateAnswer(){
			if(next_enabled && !waiting_for_close_time_is_up){
				var chosen = "";
				v = document.getElementById("the_alt");
				chosen = v.value;
				
				if (chosen == "") {
					temporary_elapsed_time = elapsed_time;
					clearInterval(answer_interval);
					hideDiv('quiz_ratta_div');
					hideDiv('quiz_timer');
					showDiv('must_select_answer');
					return;
				}
				else{
					viewRAnswer(chosen);
				}
			}
		}	

		function quizEnd(){
			hideDiv('quiz_ratta_div');
			hideDiv('quiz_timer');
			showDiv('quiz_ended');
			var nr_of_questions = result_array.length;
			action_link += "&nr_of_questions=" + nr_of_questions;
			for(i=0;i<result_array.length;i++){
				var thearr = result_array[i];
				var frid = thearr[0];
				var chid = thearr[1];
				var rid = thearr[2];
				var isPro = all_questions[i][3];
				action_link += "&frid" + i + "=" + frid;
				action_link += "&chid" + i + "=" + chid;
				action_link += "&isPro" + i + "=" + isPro;
				if(rid == chid){
					nr_of_right_answers++;
				}
			}
			location.href = action_link;
		}

		function loadNext(){
			hideDiv('right_answer_div');
			if(quizEnded){
				quizEnd();
			}
			else{
				waiting_for_close_time_is_up = false;
				loadQuizQuestion();
				next_enabled = true;
			}
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

		
		

		function startQuiz(){
			setDataValue('seconds_elapsed', MAX_QUIZ_SECONDS);
			loadQuizQuestion();
			document.getElementById('start_quiz_div').style.display = 'none';
			document.getElementById('quiz_running').style.display = 'block';
		}