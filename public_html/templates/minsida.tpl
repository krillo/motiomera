<div style="overflow:hidden;">
  <div class="mmh1" style="float:left;">{$USER->getANamn()}</div> 
  <div class="mmh1 mmMarginBottom mmProfilH1" style="float:left;width:400px;padding-top: 7px;height:25px;overflow: hidden;">
    {if $selfProfile}
      <form action="" method="post" class="margin0" onsubmit="mm_saveStatus(this.status.value);
          return false;" id="mmUpdateStatusForm" style="margin-top: 2px;">
      {/if}  
      <span class="mmGray editable local-status-update">
        <span id="mmUpdateStatus">
          <input type="text" name="status" id="mmStatusField" value=""  />
          <input type="submit" name="save" value="Spara" id="save" />
          <input type="button" onclick="mm_toggleUpdateStatus(false);" name="clear" value="Avbryt" id="clear" />
          <img src="/img/icons/loadinganim.gif" alt="" id="mmStatusLoading" />
        </span>
        {if $selfProfile}
          <a href="#" onclick="mm_toggleUpdateStatus(true);
          return false;">
          {/if}
          <span id="mmMedlemStatusText">{if $medlem->getStatus()}{$medlem->getStatus()}{else}{if $selfProfile}Vad gör du just nu?{/if}{/if}</span>
          {if $selfProfile}
          </a>
        {/if}
      </span>
  </div>
  {if $selfProfile}
  </form>
{/if}
<div class="facebook-like">
  <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FMotioMera%2F63606043494&amp;send=false&amp;layout=standard&amp;width=200&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=30" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:30px;" allowTransparency="true"></iframe>  
</div>
</div>

<div class="mmClearBoth"></div>
{include file=steggrafik.tpl}

<!-- START mmColumnRightMinSida -->
{* include file="widget_userblogg.tpl" *}
<div id="mmColumnRightMinSida">
  {include file="widget_steglista.tpl"}  
  {include file="widget_tidigare_tavling.tpl"}    
  {include file="fotoalbumblock.tpl"}
</div><!-- END mmColumnRight -->

<!-- START mmColumnMiddle -->
<div style="min-height:340px;margin-bottom: 15px;float:left">
  {php}
  include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_graph.php');
  {/php}
  {php}
  include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_steps.php');
  {/php}
</div>
<div style="min-height:340px;margin-bottom: 15px;float:left">
  {include file="widget_kommunjakten.tpl"}
</div>
<div style="min-height:340px;margin-bottom: 15px;float:left">
  {include file="widget_handelser.tpl"}
</div>