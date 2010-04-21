		<div class="mmh1 mmMarginBottom">Skapa ett quiz</div><br />
<script language="javascript">
{literal}
	$(document).ready(function(){minaQuizSkapa()});
{/literal}
</script>
<form action="{$urlHandler->getUrl(ProQuiz, URL_ADMIN_SAVE)}" method="post" onsubmit="return mm_skapaQuizValidera(this);">

	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th>Namn</th>
			<td><input type="text" name="namn" value="" class="mmTextField" /></td>
		</tr>
	</table><br />
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th>Frågor och Svar</th>
		</tr>
		<tr>
			<td>
				<div id="fragor">
					<table border="0" id="fraga_0" class="fragor">
						<tr>
							<td>Fråga:</td>
							<td>Rätt svar:</td>
						</tr>
						<tr>
							<td><textarea name="fraga[]" rows="7" id="fraga"></textarea></td>
							<td>
								<input type="text" name="ratt_svar[]" id="ratt_svar"><br />
								<br />
								Fel svar #1:<br />
								<input type="text" name="fel_svar_1[]" id="fel_svar_1"><br />
								<br />
								Fel svar #2:<br />
								<input type="text" name="fel_svar_2[]" id="fel_svar_2"><br />							
							</td>
						</tr>
						<tr><td colspan="2"><hr /></td></tr>
					</table>
				</div>
				<a class="addQuestion">Lägg till en fråga till</a>
			</td>
		</tr>
		<tr>
			<td><br/><input type="image" src="/img/icons/MinaQuizSkapaIcon.gif" alt="Skapa quiz" /></td>
		</tr>
	</table>
</form>




<div class="mmClearBoth"></div>
