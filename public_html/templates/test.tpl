hej hej



{literal}



<script type="text/javascript">


function visa(){

	document.getElementById("testBox").style.display = "none";

	var html = 'tjena<form action="" method="post"><select name="test"><option value="1">11Etst</option><option value="1">Etst</option></select></form>';

	document.getElementById("testBox").style.display = "block";

	document.getElementById("testBox").innerHTML = html;
	mmPopup.show(500, 500);	
	mmPopup.setContent(html);


}


</script>

{/literal}

<a href="#" onclick="visa();">klick</a>

<div id="testBox"></div>