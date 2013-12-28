<script type="text/javascript">
  jQuery(document).ready(function($) {

    var mm_id = $("#mm_id").html();
    if (mm_id !== undefined) {
      //Kommuner
      var data = {mm_id: mm_id};
      $.post('/ajax/includes/display_kommuner.php', data, function(response) {
        $("#kommuner").append(response);
        var hash = location.hash;
        hashHandler(hash);
      });
    }

    function hashHandler(hash) {
      if (hash !== '') {
        hash = hash.replace('#', '');
        if ($("#" + hash).length) {  //does selector exist on page
          $('html, body').animate({
            scrollTop: $("#" + hash).offset().top - 110
          }, 1000);
          return true;
        }
      }
    }

  });
</script>  
<div id="kommuner"></div>

