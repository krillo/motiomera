<?php
  $ret = null;
  if(empty($_REQUEST["ret"])){
    $ret = "ERROR";
  } else {
    $ret = $_REQUEST["ret"];
  }

  switch ($res) {
    case '200':
      $mess = 'OK';
      break;
    case '400':
      $mess = 'Parametars missing';
      break;
    default:
      $mess = $ret;
      break;
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>device result</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Motiomera" />
	<meta name="keywords" content="Motiomera" />

</head>
<body id="body" >

<?php echo $mess;?>


</body>
</html>

