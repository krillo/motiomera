		<div class="mmClearBoth"></div>
		<div class="mmGrayLineBottom"></div>
	
		<div id="mmFooter">

			<div id="mmFooterRight">
				<a href="#" onclick="motiomera_rapportera(); return false;">Uppmärksamma oss på denna sida</a>
			</div>
			<a href="#" onclick="motiomera_kontakt(); return false;">Kontakta oss</a>  |  <a href="http://www.integritetspolicy.se/#cookies" rel="external">Om Cookies</a>  |  <a href="http://www.integritetspolicy.se" rel="external">Integritetspolicy</a>  |
			<a href="http://www.mabra.com/Pren____2633.aspx" rel="external">Om Allers f&ouml;rlag</a>  |  <a href="http://www.mabra.com/Default____33627.aspx" rel="external">Press</a>  |  <a href="/pages/sitemap.html">Sitemap</a><br />
	&copy; Copyright Allers f&ouml;rlag 2008 - {$smarty.now|date_format:"%Y"}  | Ansvarig utgivare: Liselotte St&aring;lberg
	
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

</div>
{if $DEBUG == true}
Querycount: {$querycount}
{/if}
</body>
</html>