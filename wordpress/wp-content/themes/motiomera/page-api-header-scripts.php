<?php
/**
 * Template Name: Motiomera-api-header
 * Use this page to get get scripts and css to include in the header och the html document
 */

!empty($_REQUEST['page']) ? $page = $_REQUEST['page'] : $page = '';
//if($page == 'krillo'){
  print('<link id="' . $page . '" >');
//}
wp_head();
