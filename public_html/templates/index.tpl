<div class="mmFlash">
  <div id="mmSplash">


    <div style="text-align:center;">
<a href="/pages/skapaforetag.php" style="text-decoration:none;" title="stegtävling"><h1 style="color:#B71416;font-size:250%;">Teambuilding på jobbet!</h1>
  <div style="text-align:left;float:left;padding-left:50px;">
    <h2 style="text-align:left;float:left;"> 
      Vår stora vårtävlingen börjar den 22/4<br><br>
      Välj mellan 5 och 8 veckors tävling<br>
      till samma pris.
    </h2>

          <div class="clear"></div>
          <p style="text-align:left;font-size:14px;">
      
     <!--       
     Fortfarande 15% rabatt!<br/><br/>
    <span style="text-decoration:line-through;color: #B71416;">169 kr &nbsp;</span> 144 kr per person<br/>
    <span style="text-decoration:line-through;color: #B71416; ">289 kr &nbsp;</span> 246 kr per person med stegräknare -->
    169 kr per person<br/>
    289 kr per person med stegräknare
  </p>
</div>        
</a>
<a href="/pages/skapaforetag.php"><img src="img/bestall_knapp.jpg" alt="Stegtävling" title="Stegtävling"/></a>
</div>

    <div class="mmGrayLineMiddle"></div>
    <div id="fb-root"></div>
    <script>
      {literal}
  (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/sv_SE/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
      {/literal}
    </script>
    <div style="color:#3B5998;font-family:lucida,tahoma,verdana,arial,sans-serif;margin-left: 20px;margin-bottom: 20px;">
      <h2 style="font-size: 21px;font-weight: bold;" >Har du sett Motiomera.se på Facebook?</h2>
      <p style="font-size: 12px;color:#3B5998;">Dela dina egna motiomerabilder på vår <a href="http://www.facebook.com/pages/MotioMera/63606043494" style="color:#3B5998;text-decoration: underline;">Facebooksida!</p></a> 
      <div class="fb-like" data-send="false" data-width="400" data-show-faces="true"></div>
    </div>       
    <div class="mmGrayLineMiddle"></div>

    <script>
      {literal}
$(function(){
  $("#slides").slides({
  effect: 'fade',
	play: 5000,
  generatePagination: false
  });
});
      {/literal}
    </script>


    <div id="slides">
      <div class="slides_container" >
        <div>
          <a href="pages/for_foretag.php"><img src="/slideshow/img/0.jpg" title="om stegtävling" /></a>
        </div>
        <div>
          <a href="pages/for_foretag.php"><img src="/slideshow/img/1.jpg" title="om stegtävling" /></a>
        </div>
        <!--div>
          <a href="pages/for_foretag.php"><img src="/slideshow/img/2.jpg" title="om stegtävling" /></a>
        </div-->
        <div>
          <a href="pages/for_foretag.php"><img src="/slideshow/img/3.jpg" title="om stegtävling" /></a>
        </div>
        <div>
          <a href="pages/for_foretag.php"><img src="/slideshow/img/4.jpg" title="om stegtävling" /></a>
        </div>
      </div>
    </div>



  </div>
</div>
<div class="mmGrayLineMiddle"></div>
<div id="mmBlueBoxMiddleWide"><div id="mmBlueBoxMiddleWideText">Senaste nytt från mabra.com:</div></div>
{foreach from=$rss item=puff name=rss}
  {if  $smarty.foreach.rss.iteration > 1 }
    <div class="mmArticleNarrowStart{if $smarty.foreach.rss.iteration < 4} mmMarginRight{/if}">
      {$puff.imageurl}
      <span class="mmh2 mmBlue">{$puff.title}</span><br />
      {$puff.excerpt}
      <div class="mmTextAlignRight"><a href="{$puff.link}" target="_blank">L&auml;s mer <img src="img/icons/ArrowsBlue.gif" class="mmVerticalAlignMiddle" alt="Läs mer" /></a></div>
    </div>
  {/if}
{/foreach}

<div class="mmClearBoth"></div>
<div class="mmGrayLineMiddle"></div>
{$texteditor_nm->getTexten()}
{*}
<img src="img/startsida/RegStegBild.jpg" alt="Registrera dina steg" />
<div id="mmRegSteg" class="mmFontWhite"><a href="default.html">Registrera dina steg h&auml;r</a></div>

<div class="mmClearBoth mmMarginTop13"></div>
<div class="mmGrayLineMiddle"></div>

<div class="mmArticleWideStart">

<img src="img/startsida/Matnyttigt.jpg" alt="Matnyttigt" align="left" class="mmMarginRight15;" />
<span class="mmh2 mmGreen">MATNYTTIGT!</span><br />
Vi ger dig inspiration till goda och nyttiga matr&auml;tter som g&aring;r snabbt att laga!
<div class="mmTextAlignRight"><a href="default.html">Recept h&auml;r</a> <a href="default.html"><img src="img/icons/ArrowCircleGreen.gif" class="mmVerticalAlignMiddle" /></a></div>

</div>
{*}
