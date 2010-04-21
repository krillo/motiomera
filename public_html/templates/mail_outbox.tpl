				<table>
					<tr class="mmMailOutboxTopTr">
						<td class="mmWidthFyraNollPixlar">
							
						</td>
						<td class="mmWidthFyraNollPixlar">
							<input type="checkbox" name="select_all_mail" />
						</td>
						<td class="mmWidthEttFemNollPixlar">
							<a href="">Fr&aring;n</a>
						</td>
						<td class="mmWidthEttSexNollPixlar">
							<a href="">&Auml;mne</a>
						</td>
						<td class="mmWidthNioNollPixlar">
							<a href="">Datum</a>
						</td>
					</tr>
					{foreach from=$outbox_mails key=k item=v}
					<tr class="mmBackGroundColorCCCCNioNio">
						<td class="mmWidthFyraNollPixlar mmAlignCenter">
							<img src="/img/mail/read_mail.gif" />
						</td>
						<td class="mmWidthFyraNollPixlar">
							<input type="checkbox" name="mail_select[]" />
						</td>
						<td class="mmWidthEttFemNollPixlar">
							{if $v.aNamn|count_characters > 15}
								<a href="{$read_mail_url}{$v.id}">{$v.aNamn|substr:0:15}...</a>
							{else}
								<a href="{$read_mail_url}{$v.id}">{$v.aNamn}</a>
							{/if}
						</td>
						<td class="mmWidthEttSexNollPixlar">
							{if $v.subject|count_characters > 20}
								<a href="{$read_mail_url}{$v.id}">{$v.subject|substr:0:20}...</a>
							{else}
								<a href="{$read_mail_url}{$v.id}">{$v.subject}</a>
							{/if}
						</td>
						<td class="mmWidthNioNollPixlar">
							{$v.date_sent|substr:0:10}
						</td>
					</tr>	
					{/foreach}
				</table>