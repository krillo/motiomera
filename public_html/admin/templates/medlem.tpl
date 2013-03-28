{literal}
<script type="text/javascript">
  jQuery(document).ready(function($){   

   $("#cahngeanamn").click(function(event) {
     event.preventDefault();
     var data = {
       mm_id:       $('#mm_id').attr('value'),
       anamn:       $('#anamn').attr('value')
     };    
     $.ajax({
       type: "POST",
       url: "http://motiomera.dev/ajax/actions/changeanamn.php",
       dataType: 'json',
       data: data,
       success: function(data){
         $('#anamn-action').html(data.msg);
       }
     });
     return false;
   });


        
});
</script>        
{/literal}

<h1>Hantera medlem</h1>
<p><a href="{$urlHandler->getUrl(Medlem, URL_ADMIN_LIST)}">&laquo; Tillbaka</a></p>
<form action="{$urlHandler->getUrl(Medlem, URL_ADMIN_SAVE)}" method="post">
  <input type="hidden" value="{$medlem->getId()}" name="medlem_id" id="mm_id"/>
  <table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
    <tr>
      <th>Användarnamn</th>
      <td><input type="text" id="anamn" name="anamn" value="{$medlem->getANamn()}"  size="25"/></td>
      <td><input type="button" id="cahngeanamn" name="cahngeanamn" value="Byt alias"  /></td>
      <td id="anamn-action"></td>
    </tr>
    <tr>
      <th>Namn</th>
      <td class="mmRawText">{$medlem->getFNamn()} {$medlem->getENamn()}</td>
    </tr>
    <tr>
      <th>E-post</th>
      <!-- td class="mmRawText"><a href="mailto:{*$medlem->getEpost()*}">{*$medlem->getEpost()*}</a></td-->
      <td><input type="text" name="epost" value="{$medlem->getEpost()}"  size="25"/></td>
      <!--td><input type="submit" name="setEmail" value="Spara epost" /></td-->		
    </tr>    
    <tr>
      <th>Status</th>
      <td class="mmRawText">{if $medlem->getEpostBekraftad() == 1}Aktiverad{else}<span class="mmRed">Ej aktiverad</span>&nbsp;<input type="submit" name="aktivera" value="Aktivera" /> &nbsp; Nyckel:&nbsp;  {$medlem->getForetagsnyckel_temp()}{/if}</td>
    </tr>
    <tr>
      <th>Konto skapat</th>
      <td class="mmRawText">{$medlem->getSkapad()|substr:0:16}&nbsp;</td>
    </tr>
    <tr>
      <th>Senast inloggad</th>
      <td class="mmRawText">{$medlem->getSenastInloggad()}&nbsp;</td>
    </tr>
    <tr>
      <th>Betalt t.o.m.</th>
      <td><input type="text" name="paidUntil" value="{$medlem->getPaidUntil()}" /></td>
    </tr>    
    <tr>
      <th>Kommun</th>
      <td class="mmRawText">{assign var=kommun value=$medlem->getKommun()}{$kommun->getNamn()}</td>
    </tr>
    <tr>
      <th>Medlemsnivå</th>
      <td>{mm_html_options name=levelId options=$opt_levels selected=$sel_level}</td>
    </tr>
    <tr>
      <th>Företag</th>
      <td >
        <a href="/admin/pages/listorder.php?search=&field=id&limit=40&offset=0&showValid=true&foretagid={$medlem->getForetagsId()}" style="text-decoration: underline; color: blue;">{$medlem->getForetagsNamn()}</a>
      </td>
    </tr>  
    <tr>
      <th>Tävlingsdatum</th>
      <td class="{$medlem->isActiveCompetitionCSS()}">        
        {$medlem->getForetagStartdatum()} - {$medlem->getForetagSlutdatum()}
      </td> 
    </tr>       
    <tr>
      <th>Lösenord</th>
      <!-- td><input type="submit" name="sendPassword" value="Skicka nytt lösenord" /></td-->
      <td>
        <input type="text" name="newpassword" value="" /><span class="mmRed">  {$passmsg}</span>
      </td>
    </tr>
  </table>
  <input type="submit" value="Spara" />
</form>

<br />
<br />
<br />
<br />
<form action="{$urlHandler->getUrl(Medlem, URL_ADMIN_DELETE, $medlem->getId())}" method="post">

  <input type="submit" name="tabort" value="Ta bort medlem" onclick="var q = confirm('Är du säker på att du vill ta bort den här medlemmen?'); return q;" />



</form>
