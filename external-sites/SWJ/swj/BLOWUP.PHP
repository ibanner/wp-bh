<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<?php
$fn=$_GET['fn'];
$pn=$_GET['pn'];
$language=$_GET['language'];
$link=mysql_connect("localhost","bh","qty79x") or die("could not connect");
@mysql_select_db("bh") or die("could not select database");
$q='select caption,lcaption from captions where picture='.$pn.' and language='.$language;
//print $q;
$results=mysql_query($q);
$fileName= mysql_result($results,0,"lcaption");
$caption=mysql_result($results,0,"caption");
$fd=fopen($fileName,"r");
$contents=fread($fd,filesize($fileName));
fclose($fd);
$c=str_replace('<br>','--',$caption);
print'<title>'.$c.'</title>
<style type="text/css">
<!--';
$size=getimagesize($fn);
$w=$size[0];
$h=$size[1];
$w1=$w+20;
$w2=760-$w1-10;
$h15=$h+15;
print'div#pic {position:absolute;left:10px;top:10px;width:'.$w.'px;height:'.$h.'px; }';
if($w>$h){
	print'div#txt{position:absolute;left:px;top:'.$h15.'px;width:'.$w.'px;}';}
else{
	print'div#txt{position:absolute;left:'.$w1.'px;top:10px;width:'.$w2.'px;}';
}
print'-->
</style>
</head>
<body bgcolor=#ffeecf>';
$size=getimagesize($fn);
$dim=$size[3];
print'<div id="pic"><img src="'.$fn.'" " ' .$dim.'"  border="0" alt=""></div>';
print'<div id="txt">'.$contents.'</div>';
?>
</body>
</html>
