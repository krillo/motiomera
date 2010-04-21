<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";


include '../php/libs/php-ofc-library/open-flash-chart.php';


$medlemGraf = array();
$mabraGraf = array();

if($_GET["id"] > 0) {
	$medlem = Medlem::loadById($_GET["id"]);
}
else {
	$medlem = $USER;
}

$max_steg = 0;


$y_labels = array();







// generate some random data
srand((double)microtime()*1000000);

$bar = new bar_outline( 100, '#00ADDF', '#4AABCB' );

$data = array();

$veckodagar = array("Måndag","Tisdag","Onsdag","Torsdag","Fredag","Lördag","Söndag");

$x_labels = array();

for($i=-50;$i<=0;$i++){

	$steg = Steg::getTotalStegByDay($i,$medlem);

	if($steg >= 20000){
		$flaghigher = 40000;
	}

	$bar->data[] = $steg?intVal($steg):0;
	
	$steg>$max_steg?$max_steg=$steg:"";

	$x_labels[] = date("Y-m-d",strtotime(abs($i) . " days ago"));

}

if($bar->data[50] > 0) {
	
	
	$bar->data = array_slice($bar->data,1,50);
	$x_labels = array_slice($x_labels,1,50);
}
else {

	$bar->data = array_slice($bar->data,0,50);
	$x_labels = array_slice($x_labels,0,50);

}



$g = new graph();

if($flaghigher == 40000){
	$g->set_bg_image( '/img/guld_silver_bg500.gif', 'right', 'bottom' );
	$g->set_y_max( 40000 );
	$g->y_label_steps( 4 );
}else{
	$g->set_bg_image( '/img/20000_guld_silver_bg500.gif', 'right', 'bottom' );
	$g->set_y_max( 20000 );
	$g->y_label_steps( 2 );
}

$g->set_x_labels( $x_labels );
$g->set_x_label_style( 8, "#00000",2,3);
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
