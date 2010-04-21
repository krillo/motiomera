<h1>Administrationssida för {if isset($foretaget)}{$foretaget->getNamn()}{else}Nytt företag{/if}</h1>

<p> 
	Er företagstävling har avslutats. Hoppas att ni har haft roligt, och att kontoret nu har fler rosiga kinder!<br/>
<br/>
	Sugen på att fortsätta?<br/>
	Vill du skapa en ny tävling nu på en gång, klicka nedan. Tävlingen kommer att starta nästkommande måndag! Resultaten från förra tävlingen nollställs då och ni börjar om på nytt (varje enskild användare behåller dock sina steg på Min sida). Välj om du vill ha samma deltagare som sist eller ändra antalet deltagare i tävlingen.<br/>
<br/>
	<a href="{$urlHandler->getUrl('Foretag', URL_NEW_CONTEST, $foretaget->getId())}">Starta ny tävling med samma deltagare (precis som förut är det möjligt att ändra lagindelningen i administrationsgränssnittet)</a>.<br/>
<br/>
	<a href="/pages/skapaforetag.php">Starta ny tävling och ändra antalet deltagare</a>.<br/>
</p>


