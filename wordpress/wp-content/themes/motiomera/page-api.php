<?php

/**
 * Template Name: Motiomera-api
 * Use this page to get get snippets from wp
 */
!empty($_REQUEST['snippet']) ? $snippet = addslashes($_REQUEST['snippet']) : $snippet = '';

switch ($snippet) {
  case 'inc_fb_root':
    print (file_get_contents(dirname(__FILE__)."/snippets/inc_fb_root.php"));
    break;
  default:
    break;
}

