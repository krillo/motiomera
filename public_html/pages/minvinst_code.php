<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
global $USER;
Security::demand(USER);
$yearWeek = $USER->getVeckotavlingWeek();
$year = substr($yearWeek, 0, 4); 
$week = substr($yearWeek, 5); 
?>



<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  $(function() {
    
    

    //do input validation
    var validator = $("#minvinst").validate({
      errorClass: "invalid",
      validClass: "valid",
      rules: {
        firstname: {
          required: true
        },
        lastname: {
          required: true
        },
        street1: {
          required: true
        },
        zip: {
          required: true
        },
        city: {
          required: true
        },
        phone: {
          required: true
        }       
      },  
      messages: {
        firstname: {
          required: ''
        },
        lastname: {
          required: ''
        },
        street1: {
          required: ''
        },
        zip: {
          required: ''
        },
        city: {
          required: ''
        },
        phone: {
          required: ''
        }
      },
      submitHandler:
        function(form) {
        var magid = $('input[name=magazine]:checked').val();
        if(typeof magid == 'undefined'){
          alert("Välj en tidning");
          return false;  //abort the submit
        } else{
          var magname = jQuery("label[for="+magid+"]").html();  //get the magname from the label
          var co  = jQuery("#co").val();
          var firstname  = jQuery("#firstname").val();
          var lastname  = jQuery("#lastname").val();
          var street1  = jQuery("#street1").val();
          var zip  = jQuery("#zip").val();
          var city  = jQuery("#city").val();
          var phone  = jQuery("#phone").val();
          var dataString = "id=<?php echo $USER->getId(); ?>&firstname=" + firstname + "&lastname=" + lastname + "&street1=" + street1 + "&zip=" + zip + "&city=" + city + "&phone=" + phone + "&co=" + co + "&magid=" + magid + "&magname=" + magname;
          jQuery.ajax({
            type: "POST",
            url: "/api/api_minvinst.php",
            data: dataString,
            cache: false,
            success: function(data){
              //console.log(data);
              jQuery("#minvinst-result").html(data);
            }
          });
          return false; // stop the normal submit flow
        } 
      }      
    });
    

  });
</script>   



<p style="font-size: 14px;  ">
  Du är en av vinnarna vecka <?php echo $week; ?>, <?php echo $year; ?> och var en av vinnarna i veckoutlottningen.<br/>
  Din vinst är en prenumeration från Aller media <br/><br/>

  Det du ska göra är att välja en tidning i listan, kontrollera din adress och klicka på "OK", sen fixar vi resten. Se vilka <a href="/pages/tavlingar.php">andra som också vann.</a>
</p>


<div class="clear" style="float:left;margin-top: 20px;">
  <table border="0" style="float:left;">
    <thead>
      <tr>
        <th></th>
        <th>Tidning</th>
        <th>Antal nummer</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="mmList1"><input type="radio" id="141" name="magazine" value="141" /></td> 
        <td class="mmList2"><label for="141">Allas</label></td>
        <td class="mmList1">9</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="111" name="magazine" value="111" /></td>
        <td class="mmList2"><label for="111">Allers</label></td>
        <td class="mmList1">9</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="391" name="magazine" value="391" /></td>
        <td class="mmList2"><label for="391">Allers Trädgård</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="331" name="magazine" value="331" /></td>
        <td class="mmList2"><label for="331">Antik & Auktion</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="361" name="magazine" value="361" /></td>
        <td class="mmList2"><label for="361">Bra Korsord</label></td>
        <td class="mmList1">5</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="531" name="magazine" value="531" /></td>
        <td class="mmList2"><label for="531">Café</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="541" name="magazine" value="541" /></td>
        <td class="mmList2"><label for="541">Elle</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="561" name="magazine" value="561" /></td>
        <td class="mmList2"><label for="561">Elle Interiör</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="551" name="magazine" value="551" /></td>
        <td class="mmList2"><label for="551">Elle Mat & Vin</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="321" name="magazine" value="321" /></td>
        <td class="mmList2"><label for="321">Femina</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="441" name="magazine" value="441" /></td>
        <td class="mmList2"><label for="441">Fiskejournalen</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="341" name="magazine" value="341" /></td>
        <td class="mmList2"><label for="341">FOTO</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="151" name="magazine" value="151" /></td>
        <td class="mmList2"><label for="151">Hemmets Veckotidning</label></td>
        <td class="mmList1">9</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="121" name="magazine" value="121" /></td>
        <td class="mmList2"><label for="121">Hänt Extra</label></td>
        <td class="mmList1">9</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="461" name="magazine" value="461" /></td>
        <td class="mmList2"><label for="461">Hänt Bild</label></td>
        <td class="mmList1">8</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="601" name="magazine" value="601" /></td>
        <td class="mmList2"><label for="601">Isabellas</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="431" name="magazine" value="431" /></td>
        <td class="mmList2"><label for="431">Jaktjournalen</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="591" name="magazine" value="591" /></td>
        <td class="mmList2"><label for="591">Lätt & lagom</label></td>
        <td class="mmList1">5</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="421" name="magazine" value="421" /></td>
        <td class="mmList2"><label for="421">Lätta Kryss</label></td>
        <td class="mmList1">5</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="471" name="magazine" value="471" /></td>
        <td class="mmList2"><label for="471">Magasinet Skåne</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="371" name="magazine" value="371" /></td>
        <td class="mmList2"><label for="371">Matmagasinet</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="311" name="magazine" value="311" /></td>
        <td class="mmList2"><label for="311">MåBra</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="381" name="magazine" value="381" /></td>
        <td class="mmList2"><label for="381">Mästarkryss</label></td>
        <td class="mmList1">2</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="491" name="magazine" value="491" /></td>
        <td class="mmList2"><label for="491">Nybörjarkryss</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="131" name="magazine" value="131" /></td>
        <td class="mmList2"><label for="131">Se & Hör</label></td>
        <td class="mmList1">9</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="161" name="magazine" value="161" /></td>
        <td class="mmList2"><label for="161">Svensk Damtidning</label></td>
        <td class="mmList1">9</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="501" name="magazine" value="501" /></td>
        <td class="mmList2"><label for="501">Sudoku för alla</label></td>
        <td class="mmList1">6</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="191" name="magazine" value="191" /></td>
        <td class="mmList2"><label for="191">TV-Guiden</label></td>
        <td class="mmList1">20</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="181" name="magazine" value="181" /></td>
        <td class="mmList2"><label for="181">Året Runt</label></td>
        <td class="mmList1">9</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="711" name="magazine" value="711" /></td>
        <td class="mmList2"><label for="711">Mat & Vänner</label></td>
        <td class="mmList1">4</td>
      </tr>
      <tr>
        <td class="mmList1"><input type="radio" id="731" name="magazine" value="731" /></td>
        <td class="mmList2"><label for="731">Baka</label></td>
        <td class="mmList1">4</td>
      </tr>
    </tbody>
  </table>

  <form id="minvinst">
    <ul id="minvinst-ul">   
      <li><label for="firstname">Förnamn</label><input type="text" name="firstname" id="firstname" class="" value="<?php echo $USER->getFNamn(); ?>"/></li><div class="clear"></div>
      <li><label for="lastname">Efternamn</label><input type="text" name="lastname" id="lastname" class="" value="<?php echo $USER->getENamn(); ?>"/></li><div class="clear"></div>
      <li><label for="co-toggle">c/o</label><input type="text" name="co" id="co"  class="" value="<?php echo $USER->getCo(); ?>"/></li><div class="clear"></div>                 
      <li><label for="phone">Mobil/telefon</label><input type="text" name="phone" id="phone" value="<?php echo $USER->getPhone(); ?>"/></li><div class="clear"></div>
      <li><label for="street1">Postadress</label><input type="text" name="street1" id="street1" value="<?php echo $USER->getAddress(); ?>"/></li><div class="clear"></div>
      <li><label for="zip">Postnummer</label><input type="text" name="zip" id="zip" value="<?php echo $USER->getZip(); ?>"/></li><div class="clear"></div>
      <li><label for="city">Ort</label><input type="text" name="city" id="city" value="<?php echo $USER->getCity(); ?>"/></li><div class="clear"></div>  
    </ul>

    <p style="font-size: 14px;margin:15px 0 0 20px;float:left;">Läs mer om <a href="http://www.prenumerera.se/" target="_blank">tidningarna.</a></p>
    
    <input type="submit" value="OK" style="float:left;font-size: 15px;width: 200px;height: 25px;margin:15px 0 0 20px;" id="minvinst-submit">
    
  </form>
  <div id="minvinst-result"></div>
</div>
