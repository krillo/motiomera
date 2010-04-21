<h1>Välj en visningsbild</h1>

{foreach from=$visningsbilder item=visningsbild}


<a href="#" onclick="motiomera_sparaVisningsbild('{$visningsbild->getNamn()}'); return false;"><img src="{$visningsbild->getUrl()}" alt="" /></a>


{/foreach}