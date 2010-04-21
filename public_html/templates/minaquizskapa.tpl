<div class="mmh1 mmMarginBottom">Skapa ett quiz</div><br />
<script language="javascript">
{literal}
	$(document).ready(function(){minaQuizSkapa()});
{/literal}
</script>
<form action="{$urlHandler->getUrl(MinaQuiz, URL_SAVE)}" method="post" onsubmit="return mm_skapaQuizValidera(this);">

	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th>Namn</th>
			<td><input type="text" name="namn" value="" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Tillträde</th>
			<td>
				<input type="radio" name="tilltrade" value="alla" class="mmWidthTvaNollPixlar" checked /> Alla<br />
				{if $grupper != null || $foretag != null}
					<input type="radio" name="tilltrade" value="vissa" class="mmWidthTvaNollPixlar" /> Vissa<br />
				{/if}
				<table id="privacyOptions">
					<tr>
						<td class="mmTilltradeTd">
							{if $grupper != null}
								<h3>Grupper</h3>
								<input type="checkbox" name="tilltrade_grupper[]" value="alla" class="mmWidthTvaNollPixlar" /> Alla grupper<br />
								{foreach from=$grupper item=grupp}
									<input type="checkbox" name="tilltrade_grupper[]" value="{$grupp->getId()}" class="mmWidthTvaNollPixlar" /> {$grupp->getNamn()}<br />
								{/foreach}
							{/if}
							{if $foretag != null}
								<br />
								<h3>Företag</h3>
								<input type="checkbox" name="tilltrade_foretag" value="ja" class="mmWidthTvaNollPixlar" /> {$foretag->getNamn()}<br />
							{/if}
						</td>
					</tr>
				</table>
			</td>
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
			</td>
		</tr>	
		<tr>
			<td>
				<br/>
				<input class="mmFloatRight autoWidth" type="image" src="/img/icons/MinaQuizSparaIcon.gif" alt="Skapa quiz" />
				<a class="addQuestion"><img src="/img/icons/addQuizQuestion.gif" alt="Lägg till en fråga" /></a>
			</td>
		</tr>
	</table>
</form>
<div class="mmClearBoth"></div>