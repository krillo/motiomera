<a href="{$urlHandler->getUrl(Help, URL_ADMIN_LIST)}">Tillbaka till listan</a>
<h1>{if isset($help)}

<!-- TinyMCE -->
<script type="text/javascript" src="/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
{literal}
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
{/literal}
</script>
<!-- /TinyMCE -->


{$help->getNamn()}
{else}
	Ny Hjälpruta  krillo-åäö-krillo
{/if}</h1>
{if isset($help) && $ADMIN->isTyp(ADMIN)}
<p>
<a href="{$urlHandler->getUrl(Help, URL_ADMIN_DELETE, $help->getId())}" onclick="{jsConfirm msg="Är du säker på att du vill ta bort denna text?"}">Ta bort</a>
</p>
{/if}
<form action="{$urlHandler->getUrl(Help, URL_ADMIN_SAVE, $helpId)}" method="post">
{if $ADMIN->isTyp(ADMIN)}

	<p>
		Namn:<br />
		<input type="text" name="namn" value="{if isset($help)}{$help->getNamn()}{/if}" />
	</p>
	<p>
		Sida (visas i headern):<br />
		<input type="text" name="sida" value="{if isset($help)}{$help->getPage()}{/if}" /> ex. /pages/minsida.php
	</p>
	<p>
		Storlek:<br />
		<input type="text" name="sizeX" value="{if isset($help) && $help->getSizeX() > 0}{$help->getSizeX()}{else}480{/if}" /><br/>
		x<br/>
		<input type="text" name="sizeY" value="{if isset($help) && $help->getSizeY() > 0}{$help->getSizeY()}{else}200{/if}" />

	</p>
	<p>
		Tema:<br />
		{mm_html_options name=tema options=$opt_teman selected=$sel_typ}
	</p>
	<p>
		Auto:<br />
		{mm_html_options name=auto options=$opt_auto selected=$sel_auto}
	</p>
{/if}
{if isset($help)}
	<textarea id="elm1" name="texten" rows="15" cols="80">{$help->getTexten(true)}</textarea>
{/if}
	<p>
		<input type="submit" value="Spara" />
	</p>
</form>