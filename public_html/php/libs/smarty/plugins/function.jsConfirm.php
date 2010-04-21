<?php

// Example
// {jsConfirm msg="Message"}

function smarty_function_jsConfirm($params, &$smarty)
{
    
    $msg = null;
    
    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'msg':
            	$msg = $_val;
            	break;
     
        }
    }

	$html = "var q=confirm('$msg');return q;";
    return $html;

}


?>