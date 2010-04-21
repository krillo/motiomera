<div class="mmh1 mmMarginBottom">Uppdatera quiz</div><br />
<script language="javascript">
{literal}
	$(document).ready(function(){minaQuizAndra()});
{/literal}
</script>
<form action="{$urlHandler->getUrl(MinaQuiz, URL_SAVE, $id)}" method="post" onsubmit="return motiomera_validateSettingsForm(this);">

	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th>Namn</th>
			<td><input type="text" name="namn" value="{$quiz->getNamn()}" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Tillträde</th>
			<td>
				<input type="radio" name="tilltrade" value="alla" class="mmWidthTvaNollPixlar" {if $quiz->getTilltrade() == "alla"}checked{/if} /> Alla<br />
				{if $grupper != null || $foretag != null}
					<input type="radio" name="tilltrade" value="vissa" class="mmWidthTvaNollPixlar" {if $quiz->getTilltrade() == "vissa"}checked{/if} /> Vissa<br />
				{/if}
				<table id="privacyOptions">
					<tr>
						<td class="mmTilltradeTd">
							{if $grupper != null}
								<h3>Grupper</h3>
								<input type="checkbox" name="tilltrade_grupper[]" value="alla" class="mmWidthTvaNollPixlar" {if $quiz->getTilltradeAllaGrupper() == "ja"} checked{/if}/> Alla grupper<br />
								{foreach from=$grupper item=grupp}
									<input type="checkbox" name="tilltrade_grupper[]" value="{$grupp->getId()}" class="mmWidthTvaNollPixlar" {if $quiz->harGruppTilltrade($grupp->getId(), $quiz->getId())}checked{/if}/> {$grupp->getNamn()}<br />
								{/foreach}
							{/if}
							{if $foretag != null}
								<br />
								<h3>Företag</h3>
								<input type="checkbox" name="tilltrade_foretag" value="ja"class="mmWidthTvaNollPixlar"" {if $quiz->harForetagTilltrade()}checked{/if} /> {$foretag->getNamn()}<br />
							{/if}
						</td>
					</tr>
				</table>
			</td>
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
			<td>
				<br/>
				<input class="mmFloatRight autoWidth" type="image" src="/img/icons/MinaQuizSparaIcon.gif" alt="Uppdatera quiz" />
				<a class="addQuestion"><img src="/img/icons/addQuizQuestion.gif" alt="Lägg till en fråga" /></a>
			</td>
		</tr>
	</table>
</form>
<div class="mmClearBoth"></div>