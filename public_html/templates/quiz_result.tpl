<div id="mmColumnRightQuiz">
	
	
	
	{if $kommunbild}
		{assign var=storbild value=$kommunbild->getBild()}
		{assign var=bild value=$kommunbild->getFramsidebild()}
		{if $bild}
		<div class="mmQuizKommunBild">
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
<br />
			<div class="mmQuizAnswer mmGreen">RESULTAT</div>
			<h3>Du hade {$nr_of_rights} r&auml;tt och {$nr_of_wrongs} fel.</h3>

			
			<br/><br/>
			<a href="/pages/minsida.php"><img src="/img/icons/ArrowCircleGreen.gif" class="mmMarginRight10" /></a><a href="/pages/minsida.php">Till Min sida</a><br/>
			<a href="{$urlHandler->getUrl("Kommun",URL_VIEW,$kommunurl)}"><img src="/img/icons/ArrowCircleGreen.gif" class="mmMarginRight10" /></a><a href="{$urlHandler->getUrl("Kommun",URL_VIEW,$kommunurl)}">Till kommunsidan för {$kommunnamn}</a><br/>
			<a href="/kommunjakten/"><img src="/img/icons/ArrowCircleGreen.gif" class="mmMarginRight10" /></a><a href="/kommunjakten/">Spela fler kommunquiz</a><br/>

		</div>

	<!-- END mmColumnMiddleQuiz -->

	



