<?php
/**
 * Description: The complete code for the graph
 * 
 * Date: 2013-09-16
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * 
 *
 */
if(!isset($heading)){
  $heading = "Framsteg de senaste ";
}
if(!isset($legend1)){
  $legend1 = "Dina steg ";
}



?>

<style>
  #mm-graph{width:520px; float:left; border-color:#666;}
  #placeholder{width:520px;height:300px;float:left}
  .xAxis{color:#2E4B00;}

  #mm-legend-area{
    width:500px;background-color: #8AA25A;
    border-radius: 0 0 10px 10px;
    border-top: 1px solid #FFFFFF;
    height: 40px;
    margin-bottom: 35px;
    padding: 10px;
    float:left;
    color:#fff;
    font-size: 13px;
  }
  #mm-legend-left{float:left;width:277px;}
  .mm-legend-container-long{float: left;width:175px;}
  .mm-legend-container{float: left;width:100px;}
  .mm-legend{float: left;height: 12px;margin-right: 10px;width: 25px;border:1px solid #fff;}
  .mm-legend-steps{background-color: #46BFE3;}
  .mm-legend-average{background-color: #306EFF;}
  .mm-legend-gold{background-color: #F2D897;}
  .mm-legend-silver{background-color: #CCC;}
  #mm-legend-right{float:right;width:190px;height:50px;}
  #mm-legend-steps{width:90px;float:left;}
  #mm-legend-average{width:90px;float:left;}


  .mmBlueBoxTop {
    background-color: #D0E0C7;
    border-radius: 10px 10px 0 0;
    height: 40px;
    margin: 0;
    padding: 0;
    width: 520px;
  }      
  .BoxTitle {
    color: #FFFFFF;
    font-family: 'Cabin Condensed',sans-serif;
    font-size: 18px;
    font-weight: normal;
    margin: 10px 0 0 15px;
    position: absolute;
  }      
  #mm-nbr-days{width:25px;}
  #mm-nbr-days-ok{padding:3px 12px;}
</style>

<div class="mmBlueBoxTop">
  <h3 class="BoxTitle"><?php echo $heading; ?><input type="text" id="mm-nbr-days" value="7" /> dagarna <input type="button" value="ok" id="mm-nbr-days-ok"></h3> 
</div>    
<div id="mm-graph">
  <div id="placeholder" ></div>
  <div id="mm-legend-area">
    <div id="mm-legend-left">
      <div class="mm-legend-container-long"><div class="mm-legend mm-legend-steps"></div><?php echo $legend1; ?></div>
      <div class="mm-legend-container"><div class="mm-legend mm-legend-gold"></div>Guldnivå</div>
      <div class="mm-legend-container-long"><div class="mm-legend mm-legend-average"></div>Snitt samtliga deltagare</div>
      <div class="mm-legend-container"><div class="mm-legend mm-legend-silver"></div>Silvernivå</div>
    </div>
    <div id="mm-legend-right">
      <div id="mm-legend-steps"></div>
      <div id="mm-legend-average"></div>
    </div>
  </div>
</div>    