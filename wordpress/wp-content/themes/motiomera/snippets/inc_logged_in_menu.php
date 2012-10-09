<?php
//echo $_SERVER["DOCUMENT_ROOT"] . "../public_html/php/init.php";
//require_once($_SERVER["DOCUMENT_ROOT"] . "../public_html/php/init.php");  

global $USER;
//print_r($USER);
/*
$USER = Medlem::getInloggad();
if (!$USER) {
  unset($USER);
}
 * 
 */
//global $mmStatus;
//print_r($mmStatus);

?>

<div id="logged-in-menu">
      <ul>
        <a href="#" id="logged-in-friend"><li>Du har en vänförfrågan</li></a>
        <a href="/pages/mail.php?do=inbox" id="logged-in-email"><li>Motiomeramail</li></a>
        <a href="/actions/logout.php" id="logged-in-logout"><li>Logga ut</li></a>
      </ul>
    </div>




<!--

            {if isset($USER) or isset($ADMIN) or isset($FORETAG)}
              <div id="mmCommunityToolbar">
                {if !isset($inAdmin) && !isset($FORETAG) && $USER->getOlastaMail() > 0}

                  {if $USER->getOlastaMail() > 1}
                    <a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}"><img src="/img/icons/MailUnreadIcon_greenBG.gif" alt="{$USER->getOlastaMail()} olästa mail" /></a> <a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}">{$USER->getOlastaMail()} olästa mail</a>
                  {else}
                    <a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}"><img src="/img/icons/MailUnreadIcon_greenBG.gif" alt="1 oläst mail" /></a> <a href="{$urlHandler->getUrl("InternMail", URL_VIEW)}">1 oläst mail</a>
                  {/if}

                {/if}
                <br/><br/>
                {if $adressbok}
                  {if $adressbok->listForfragningar()|@count > 0}
                    <a href="{$urlHandler->getUrl(Adressbok, URL_VIEW, 3)}"><img src="/img/icons/AdressbokAddIcon.gif" alt="Vänner" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl(Adressbok, URL_VIEW, 3)}">{$adressbok->listForfragningar()|@count} {$adressbok->listForfragningar()|@count|mm_countable:"ny vän":"nya vänner"}</a>
                  {/if}

-->