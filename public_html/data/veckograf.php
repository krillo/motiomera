<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";


include '../php/libs/php-ofc-library/open-flash-chart.php';


$medlemGraf = array();
$mabraGraf = array();

if(!empty($_GET['lid'])){
	$lag = Lag::loadById($_GET['lid']);
}elseif(!empty($_GET['fid'])){
	$foretag = Foretag::loadById($_GET['fid']);
}elseif(!empty($_GET['gid'])){
	$klubb = Grupp::loadById($_GET['gid']);
}else{
	if($_GET["id"] > 0) {
		$medlem = Medlem::loadById($_GET["id"]);
	}
	else {
		$medlem = $USER;
	}
}

$max_steg = 0;


$y_labels = array();







// generate some random data
srand((double)microtime()*1000000);

$bar = new bar_outline( 100, '#00ADDF', '#4AABCB' );

$data = array();

$veckodagar = array("Måndag","Tisdag","Onsdag","Torsdag","Fredag","Lördag","Söndag");

$x_labels = array();

for($i=-7;$i<=0;$i++){

	if (isset($medlem)) {

		$steg = Steg::getTotalStegByDay($i,$medlem);

	} elseif(isset($lag)) {

		$steg  = $lag->getStegSnittByDay($i);

	} elseif(isset($foretag)) {
	
		$steg = $foretag->getStegSnittByDay($i);
		
	} elseif(isset($klubb)) {
	
		$steg = $klubb->getStegSnittByDay($i);
		
	}
	if ($steg >= 20000) {
		$flaghigher = 40000;
	}
	$bar->data[] = $steg?intVal($steg):0;
	
	$steg>$max_steg?$max_steg=$steg:"";

	$x_labels[] = $veckodagar[date("N",strtotime(abs($i) . " days ago"))-1];

}

if($bar->data[7] > 0) {
	
	
	$bar->data = array_slice($bar->data,1,7);
	$x_labels = array_slice($x_labels,1,7);
}
else {

	$bar->data = array_slice($bar->data,1,7);
	$x_labels = array_slice($x_labels,1,7);

}

$x_labels = array($x_labels[0],'','',$x_labels[3],'','',$x_labels[6]);

$g = new graph();
if(isset($flaghigher) && $flaghigher == 40000){
	$g->set_bg_image( '/img/guld_silver_bg.gif', 'right', 'bottom' );
	$g->set_y_max( 40000 );
	$g->y_label_steps( 4 );
}else{
	$g->set_bg_image( '/img/20000_guld_silver_bg.gif', 'right', 'bottom' );
	$g->set_y_max( 20000 );
	$g->y_label_steps( 2 );
}

$g->set_x_labels( $x_labels );

$g->set_x_axis_steps( 0 );

//
// BAR CHART:
//
//$g->set_data( $data );
//$g->bar_filled( 50, '#9933CC', '#8010A0', 'Page views', 10 );
//
// ------------------------
//
$g->title( ' ', '{font-size: 1px;}' );
$g->data_sets[] = $bar;
echo $g->render();
?>
?>
