<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<script language="JavaScript">
function openwindow(p){

a1=p.indexOf(",w=");
p1=p.substr(0,a1);
q=p.substr(a1+3,3);
r=p.substr(a1+9,3);
//alert(p1);
//alert(q);
//alert(r);
bu="blowup.php?"+p1;
//alert(bu);
p3="toolbar=0, location=0, directories=0, status=0,  scrollbars=no, resizable=no, copyhistory=no,width=" + q+",height="+r;
window.open(bu,"Enlargement",p3)
}
</script>

	<LINK REL=StyleSheet HREF="msstyle.css" TYPE="text/css" >
<style type="text/css">
<!--
div#logo		{position:absolute;left:31px;top:20px; }
div#t2			{position:absolute;left:170px;top:20px;height:55px;background:#631dba;width:610px;}
div#t3			{position:absolute;left:170px;top:21px;height:53px;background:#993366;width:610px;font-family: monotype corsiva; font-size: 28px; font-weight: 600; color: #FFeecf;  padding-left: 10px;}
div#watermark	{position:absolute;left:171px;top:76px;width:630px;height:1000px; vertical-align: top; }
div#txt			{position:absolute;left:181px;top:260px;width:420px;font-family:verdana;font-size:12px;}
div#swj			{font-family: monotype corsiva; font-size: 24px; font-weight: 600;position:absolute; left:0px;top:30;width:160px;color:#993366;text-align:center;}
div#headlines	{position:absolute;left:0px;top:260;width:170px;color:#993366;text-align:center;}
div#lowertext	{position:absolute;left:20px;top:240px;width:130px;text-align:center;}
<?php
 //create CSS for frame side pics and captions
$country=$_GET['country'];
$language=$_GET['language'];
$places=$_GET['places'];
$link=mysql_connect("localhost","bh","qty79x") or die("could not connect");
@mysql_select_db("bh") or die("could not select database");
if(!isset($language)){$language=1;}
$tR0=480;
$R0=630;
$tL0=1130;
$q='select * from pics where place='.$places;
$results=mysql_query($q);
//$x=pe($q);
$nrows=mysql_num_rows($results);
for($i=0;$i<$nrows;$i++){

	$picname= mysql_result($results,$i,"picname");
	$serno= mysql_result($results,$i,"serno");
	$linkto= mysql_result($results,$i,"link");
	$position= mysql_result($results,$i,"position");
	$q='select caption from captions where picture='.$serno.' and language='.$language;
	$results2=mysql_query($q);
	$caption= mysql_result($results2,0,"caption");
	$l=round((strlen($caption)/25+1)*17);
	$pname[$i]=$picname;
	$cp[$i]=$caption;
	$pn[$i]=$serno;
	$lnk[$i]=$linkto;
	$size=getimagesize($picname);
	$w=$size[0];
	$ww[$i]=$w;
	$h=$size[1];
	$hh[$i]=$h;
	if($w>$h){
		$nw[$i]=120;
		$nh[$i]=round(120*$h/$w);
	}
	else{
		$nh[$i]=120;
		$nw[$i]=round(120*$w/$h);
	}

	$l=round((strlen($caption)/25+1)*17);
	$hf=round($nh[$i]);
	if($position=='r'){
		$hr=$tR0+$hf+5;
		$lop=$R0+(780-$R0)/2-$nw[$i]/2;
		$top=$tR0;
		$lp=630;
		$ht=$tR0+$hf+5;
		$tR0=round($tR0+$hf+$l+10);}
	else{
		$lop=170/2-$nw[$i]/2;
		$hL=$tL0+$hf+5;
		$top=$tL0;
		$lp=10;
		$ht=$tL0+$hf+5;
		$tL0=round($tL0+$hf+$l+10);
	}
	print'
	div#pic'.$i.	'{ position:absolute;left:'.$lop.'px;top:'.$top.'px;width:'.$nw[$i].'px;height:'.$nh[$i].'px; }
	div#caption'.$i.'{ position:absolute;left:'.$lp.'px;top:'.$ht.'px;width:150px;font-family: Arial; font-size: 12px; font-weight: 400;text-align: center; }';
}
//Lines on sides
$hc1=$tR0+38;
$hc=$tR0+100-480;
print'div#rightline	{position:absolute;left:610px;top:480px;width:1px;height:'.$hc.'px;background: #993366}';
print'div#leftline	{position:absolute;left:170px;top:60px;width:1px;height:'.$hc1.'px;background: #993366}';
print'
-->
</style>';

$places=$_GET['places'];
$country=$_GET['country'];
$q="Select Placename from places where place=".$places;
$results=mysql_query($q);
$x=pe($q);
	$placename= mysql_result($results,0,"placename");
print'<title>Synagogues Without Jews--'.$placename.'</title>
</head>
<body bgcolor="#ffeecf">';
function pe ($sqlQ){
	$e=mysql_errno();
	if($e<>0)
		print $sqlQ . '<br>' . $e. ':  ' .mysql_error();
	return $e;
}
$country=$_GET['country'];
$language=$_GET['language'];
if(strlen($language)==0){
	$language=1;}
$places=$_GET['places'];
$q='select * from places where place='.$places;
$results=mysql_query($q);
$placename= mysql_result($results,0,"Placename");
$wm= mysql_result($results,0,"watermark");
$q='select maintext from txt where place='.$places.' and language='.$language;
//print $q;
$x=pe($q);
$results2=mysql_query($q);
$maintext= mysql_result($results2,0,"maintext");
//Display LOGO
print'<div id=logo><img src="logo-new.jpg" width="98" height="198" border="0" alt=""></div> ';
// Dispaly Heading
print'<div id="t2"></div><div id="t3">'.$placename.'</div> <div id="leftline"></div><div id="rightline"></div>';
//display links
print'<div id="links"></div>';
//Dispaly watermark
//print'<div id="watermark"><img src="'.$wm.'" width="430" height="283" border="0" alt=""></div>';
print'<div id="watermark"><img src="'.$wm.'" width="610" height="406" border="0" alt=""></div>';
//Dispaly main text
print'<div id="txt">';
print'<div class=Section1>';
//$filename="texts/mondovi Eng.txt";
$fd=fopen($maintext,"r");
$contents=fread($fd,filesize($maintext));
fclose($fd);
print $contents;
print'</div></div>';
//Display side pictures with frames and captions
for($i=0;$i<$nrows;$i++){
	$nh30=$nh[$i]+30;
	print'<div id="pic'.$i.'">';
	$h=$hh[$i]+20;
	$w=760;
	if($ww[$i]>$hh[$i]){
		$w=$ww[$i]+20;
		$h=500;}
	$p1="fn=".$pname[$i]."&language=".$language."&pn=".$pn[$i];
	$px="'".$p1.',w='.$w.',h='.$h."'";
	print'<img src="'.$pname[$i].'" width="'.$nw[$i].'" height="'.$nh[$i].'" border="0" onclick="javascript:openwindow('.$px.')">';
	print'</div>';
	print'<div id="caption'.$i.'">';
	if($lnk[$i]==0){print $cp[$i];}
	else{print'<a href="general.php?places='.$lnk[$i].'&language='.$language.'">'.$cp[$i].'</a>';}
	print'</div>';
}
print'<div id="lowertext"><font size="-1">
This site<br>';
//<div id="swj">Synagogues<br>Without Jews</div><br><br><br>
print'<font face="Monotype Corsiva" size="+2" color="#993366"><b>Synagogues<br>Without Jews</b></font>
<br><br>
is based partly on the book
of the same name by
Rivka and Ben-Zion Dorfman<br><br>
ART&nbsp;and&nbsp;ARCHITECTURE
of SYNAGOGUES in the
COUNTRIES of CENTRAL EUROPE<br>';
$q='select * from country';
$results=mysql_query($q);
$x=pe($q);
for($i=0;$i<7;$i++){
	$country= mysql_result($results,$i,"country");
	$serno= mysql_result($results,$i,"serno");
	$places= mysql_result($results,$i,"places");

	print '<a href="country.php?country='.$serno.'&places='.$places.'">'.$country.'</a><br>';
}
print'<br><br>
and the<br><br>
HISTORIES<br> of their RESPECTIVE<br>
COMMUNITIES<br><br>



We are grateful to <a href="http://www.bh.org.il">Beth&nbsp;Hatefutsot</a> for generously hosting our site.<br><br>
Who we are:<br><br>
<a href="general.php?places=10&language=1">Synagogue Art Research</a><br><br><br>
Book:<br><br>
<a href="general.php?places=11&language=1">Synagogues Without Jews</a><br>
Buy at: <a href="http://www.jewishpub.org/product.php?isbn=0827606923">JPS</a> or <a href="http://www.amazon.com/exec/obidos/tg/detail/-/0827606923/qid=1086274503/sr=1-1/ref=sr_1_1/102-2281292-0540158?v=glance&s=books
">Amazon</a><br><br>
Special pages<br>
<a href="general.php?places=12&language=1">Preface</a><br>
<a href="general.php?places=9&language=1">Our team</a><br>
<a href="general.php?places=15&language=1">What&acute;s new?</a><br>
<a href="general.php?places=16&language=1">How can I help?</a><br><br>
<br>Related sites:<br>
<font size="-2"><a href="http://www.jewishpub.org">JEWISH PUBLICATION SOCIETY</a></font><br>
<a href="http://www.yad-vashem.org.il/">YAD VASHEM</a></font>
</div>';



?>
</body>
</html>
