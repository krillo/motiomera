<div class="mmh1 mmMarginBottom">Uppdatera quiz</div><br />
<script language="javascript">
{literal}
	$(document).ready(function(){minaQuizAndra()});
{/literal}
</script>
<form action="{$urlHandler->getUrl(ProQuiz, URL_ADMIN_SAVE, $id)}" method="post" onsubmit="return motiomera_validateSettingsForm(this);">

	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th>Namn</th>
			<td><input type="text" name="namn" value="{$quiz->getNamn()}" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Frågor</th>
			<td id="fragor">
				{assign var=count value=1}
				{foreach from=$fragor item=fraga name=fragor}
				<table border="0" id="fraga_{$fraga.id}">
					<tr>
						<td>Fråga #{$count++}:</td>
						<td>Svar #1: <input type="radio" name="ratt_svar[{$fraga.id}]" value="1"{if $fraga.ratt_svar == 1} checked="checked"{/if} /></td>
					</tr>
					<tr>
						<td><textarea name="fraga[{$fraga.id}]" rows="7" id="fraga">{$fraga.fraga}</textarea></td>
						<td>
							<input type="text" name="svar_1[{$fraga.id}]" id="svar_1" value="{$fraga.svar_1}"><br />
							<br />
							Svar #2: <input type="radio" name="ratt_svar[{$fraga.id}]" value="2"{if $fraga.ratt_svar == 2} checked="checked"{/if}  /><br />
							<input type="text" name="svar_2[{$fraga.id}]" id="svar_2" value="{$fraga.svar_2}"><br />
							<br />
							Svar #3: <input type="radio" name="ratt_svar[{$fraga.id}]" value="3"{if $fraga.ratt_svar == 3} checked="checked"{/if}  /><br />
							<input type="text" name="svar_3[{$fraga.id}]" id="svar_3" value="{$fraga.svar_3}"><br />
						</td>
					</tr>
					<tr><td colspan="2"><hr /></td></tr>
				</table>
				{/foreach}
				<table border="0" id="fraga_0" class="question hide">
					<tr>
						<td>Fråga #{$count}:</td>
						<td>Svar #1: <input type="radio" name="ratt_svar[new_1]" value="1" checked="checked" /></td>
					</tr>
					<tr>
						<td><textarea name="fraga[new_1]" rows="7" id="fraga"></textarea></td>
						<td>
							<input type="text" name="svar_1[new_1]" id="svar_1"><br />
							<br />
							Svar #2: <input type="radio" name="ratt_svar[new_1]" value="2" /><br />
							<input type="text" name="svar_2[new_1]" id="svar_2"><br />
							<br />
							Svar #3: <input type="radio" name="ratt_svar[new_1]" value="3" /><br />
							<input type="text" name="svar_3[new_1]" id="svar_3"><br />
						</td>
					</tr>
					<tr><td colspan="2"><hr /></td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<th></th>
			<td><br/><input class="mmFloatRight" type="image" src="/img/icons/UppdateraQuizIcon.gif" alt="Uppdatera quiz" /><a class="addQuestion"><img src="/img/icons/addQuizQuestion.gif" alt="Lägg till en fråga" /></a></td>
		</tr>
	</table>
</form>
<div class="mmClearBoth"></div>
