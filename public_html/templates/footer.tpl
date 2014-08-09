		<div class="mmClearBoth"></div>
		<div class="mmGrayLineBottom"></div>
	
		<div id="mmFooter">

			<div id="mmFooterRight">
				<!--a href="#" onclick="motiomera_rapportera(); return false;">Uppmärksamma oss på denna sida</a-->
			</div>
			<a href="mailto:gustaf@motiomera.se?subject=Kontakt%20oss%20om%20Motiomera" >Kontakta oss</a>  |  <a href="http://motiomera.se/pages/integritetspolicy.php/#cookies" rel="external">Om Cookies</a>  |  <a href="http://motiomera.se/pages/integritetspolicy.php" rel="external">Integritetspolicy</a>  |
			  <a href="/pages/sitemap.html">Sitemap</a><br />
	&copy; Copyright Motiomera 2008 - {$smarty.now|date_format:"%Y"}
	
		</div>
		<div class="mmClearBoth"></div>
		<div id="mmWrapBottom"></div>
	
	</div>
</div>

{foreach from=$helpers item=helper}
	{if isset($helper) && $helper!=0 && isset($USER)}
		{if $helper->getAuto() && $helper->is_avfardad($USER->getId()) == false}			
			<script type="text/javascript">
						mm_addOnLoad("mm_rapportera_show_help({$helper->getId()},{$helper->getSizeX()},{$helper->getSizeY()},'topleft',true)");				
			</script>		
		{/if}
	{/if}

{/foreach}


<div id="motiomera_popup_overlay"></div>
<div id="motiomera_popup_shadow"></div>
<div id="motiomera_popup">
	<div id="motiomera_popup_close"></div>
	<div id="motiomera_popup_content"></div>
</div>

{*}
{if (!isset($ADMIN)) }
  {if !NO_INTERNET}
  <div id="motiomera_banner_right" class="mmFlash">
    <div style="margin-bottom:10px;">{php} include($_SERVER["DOCUMENT_ROOT"]."/pages/doubleclick_tile14.php"); {/php}</div>
    <div style="margin-bottom:10px;">{php} include($_SERVER["DOCUMENT_ROOT"]."/pages/doubleclick_tile3.php"); {/php}</div>
    <div style="margin-bottom:10px;">{php} include($_SERVER["DOCUMENT_ROOT"]."/pages/doubleclick_tile5.php"); {/php}</div>
    <div style="margin-bottom:10px;">{php} include($_SERVER["DOCUMENT_ROOT"]."/pages/doubleclick_tile4.php"); {/php}</div>
  </div>
  {php} include($_SERVER["DOCUMENT_ROOT"]."/pages/footer_scripts.php"); {/php}
  {/if}
{/if}
{*}

<!-- /div -->
{if $DEBUG == true}
Querycount: {$querycount}
{/if}

{literal}
<script type="text/javascript">
setTimeout(function(){var a=document.createElement("script");
var b=document.getElementsByTagName('script')[0];
a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0012/6838.js?"+Math.floor(new Date().getTime()/3600000);
a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>
{/literal}
</body>
</html>