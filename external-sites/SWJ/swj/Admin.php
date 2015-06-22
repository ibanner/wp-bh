
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
	<script language="JavaScript">
function newCountry(r){
	//alert("hi1");
	//alert(formf);
	document.form1.g.value=r;
	document.form1.submit();
}
function submitPage(){

	document.form1.submit();
}
function newCountry2(r){
	//alert("hi2");
	//alert(formf);
	document.form2.g.value=r;
	document.form2.submit();
}

function submitPage2(){
	document.form2.g.value=22;
	document.form2.submit();
}
function newCountry3(r){
	//alert("hi3");
	document.form3.g.value=r;
	document.form3.submit();
}
function submitPage3(){
	document.form3.g.value=22;
	document.form3.submit();
}

	</script>
	<style type="text/css">
<!--
 BODY 				{ width: 720px; margin:0px; padding:0px; font-size:10px}
 div#logo			{position:absolute;left:31px;top:20px;;}
 div#heading		{position:absolute;left:170px;top:20px;width:500px;height:60px;background: #993366;}
 div#heading1		{ font-family: Century Gothic; font-size: 18px; font-weight: 400; color: #FFFFcc; background: #993366;padding-left: 10px;}
 div#heading2		{ font-family: monotype corsiva; font-size: 36px; font-weight: 600; color: #FFFFcc; background: #993366; padding-left: 10px;}

 div#watermark	  	{position:absolute;left:170px;top:80px;width:430px;height:1000px; vertical-align: top; }
 div#txt			{position:absolute;left:145px;top:280px;width:380px}
 div#linktxt		{position:absolute;left:31px;	top:225px; font-family: Arial; font-size: 12px; font-weight: 400;}
-->
</style>

</head>
<body bgcolor="#ffffdd">

<?php
function pe ($sqlQ)
{
	$e=mysql_errno();
	if($e<>0)
		print $sqlQ . '<br>' . $e. ':  ' .mysql_error();
	return $e;
}
function htmtotxt($mainText){
	if($mainText<>null){
//start of conversion from htm to txt
		$fp=fopen($mainText,"r");
		$contents=fread($fp,filesize($mainText));
		fclose($fp);
		$p=strpos($contents,"<div class=Section1>");
		$contents=substr($contents,$p);
		$p=strpos($contents,"</body>");
		$contents=substr($contents,0,$p);
		$p=strpos($mainText,".htm");
		$mainText=substr($mainText,0,$p).".txt";
		$fp=fopen($mainText,"w");
		$x=fwrite($fp,$contents);
		fclose($fp);
	}
	return $mainText;
}
	//end of conversion from htm to txt
//-----------------------------------------------Entry Page -----------------------------------------------
//print $zz;
$g=$_GET['g'];
$country=$_GET['country'];
$city=$_GET['city'];
$place=$_GET['place'];
$language=$_GET['language'];
$pn=$_GET['pn'];
$lc=$_GET['lc'];
$picname=$_GET['picname'];
$caption=$_GET['caption'];
$Lcaption=$_GET['Lcaption'];
$position=$_GET['position'];
$placename=$_GET['placename'];
$lang=$_GET['lang']; 
$mt1=$_GET['mt1'];
$wm=$_GET['wm'];
$serno=$_GET['serno'];

$link=mysql_connect("localhost","bh","qty79x") or die("could not connect");
@mysql_select_db("bh") or die("could not select database");
if($g==0){print'<body bgcolor="#ffffcc">';}else{print'<body bgcolor="white">';}

//Display LOGO
//div#links				  {position:absolute;left:0px;	top:225px;width:120px;height:1000px;background: #FFFFcc}
if($g==0 or $g>2){
	print'<div id=logo><img src="Logo-new.jpg" width="108" height="198" border="0" alt=""></div> ';

// Dispaly Heading
	//print'<div id="heading"><div id="heading2">Administration Page</div></div> ';

//Display Links
	if(!isset($place)){$place=null;}
	if(!isset($placename)){$placename=null;}
//print'<div id="links">
	print'<div id="linktxt">
	<a href="Admin.php?g=10">Page linkage</a><br>
	<a href="Admin.php?g=1&place='.$place.'&placename='.$placename.'">Picture linkage</a><br>
	<a href="Admin.php?g=21">Edit Place</a><br>
	</div>

	<div id="watermark">';
	if($g == 0){print'<img src="S119_15.jpg" border="0" alt="">';}
}
else{
	print'<table bgcolor="#ffffdd" border>
	<tr><td><a href="Admin.php?g=10">Page linkage</a></td>
	<td><a href="Admin.php?g=1&place='.$place.'&placename='.$placename.'">Picture linkage</a></td>
	<td><a href="Admin.php?g=21">Edit Place</a></td></tr></table>';
}

//-------------------------------------------------------------------Enter Pics  ----------------------------------------------------

if($g==2){//update pics table
	if($place==0){
		print'You did not choose a place.  What do you want to do?<br><a href="admin.php?g=0">Define a new place.</a>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="javascript:history.go(-1)">Return to previous page</a>';}
	else{
		$picname=substr($picname,9);
		$picname = str_replace("\\", "/", $picname);
		$picname = str_replace("//", "/",$picname);
		$Lcaption=str_replace("\\", "/", $Lcaption);
		$Lcaption=str_replace("//", "/",$Lcaption);
		$Lcaption=substr($Lcaption,7);
		//$Lcaption=htmtotxt($Lcaption);
		//print $linkat.'<br>';
		$q='insert into pics (place,picname,position,link) values ('.$place.',"'.$picname.'","'.$position.'",'.$linkat.')';
		//print $q;
		$results=mysql_query($q);
		$x=pe($q);
		$q='select max(serno)as serno from pics';
		$results=mysql_query($q);
		$picture= mysql_result($results,0,"serno");
		$q='insert into captions(place,picture,caption,lcaption,language) values ('.$place.','.$picture.',"'.$caption.'","'.$Lcaption.'",'.$language.')';
		$results=mysql_query($q);
		$x=pe($q);
	}
}
if($g>=1 and $g<10){//picture linkage
	print'<br>
	<form action="admin.php" name="form2" id="form2">
	<table  border bgcolor="#ffffcc">';
if(!isset($country)){$country=0;}
	print'
	<tr><td>Country</td><td><select name="country" onchange="newCountry2(1)">
	<option value=0>Choose a country</option>';

	$q='Select * from country order by country';
	$results=mysql_query($q);
//$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$countryN= mysql_result($results,$i,"country");
		print'<option value='.$serno;
		if($country==$serno){print' selected';}
		print'>'.$countryN.'</option>';
	}
	print'</select></td></tr>
	<tr><td>City</td><td><select name="city" onchange="newCountry2(1)">';
	print'<option value=0>Choose a city</option>';
	$q='Select * from city where country='.$country.' order by city';
	$results=mysql_query($q);
	//print $q;
	//$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$cityn= mysql_result($results,$i,"city");
		print'<option value='.$serno;
		if($city==$serno){print' selected';}
		print'>'.$cityn.'</option>';
	}
	print'</select></td></tr>';
	if(!isset($place)){$place=0;}
	print'<tr><td>Place</td><td><select name="place" >
	<option value=0>Choose a Place</option>';
	$q='Select * from places  where city='.$city.' order by placename';
	$results=mysql_query($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"place");
		$placename= mysql_result($results,$i,"placename");
		print'<option value="'.$serno.'"';
		if($place==$serno){print' selected';}
		print'>'.$placename.'</option>';
	}
	print'</select></td></tr>
		<tr><td>Language</td><td><select name="language" >';
	$q='Select * from languages  order by language';
	$results=mysql_query($q);
	//$x=pe($q);
	//if($x<>0){print'<tr><td>'.$q.'</td></tr>';}
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$language= mysql_result($results,$i,"language");
		print'<option value='.$serno;
		if($serno==1){print' selected';}
		print'>'.$language.'</option>';
	}
	print'</select></td></tr>
	<tr><td>Picture</td><td dir="rtl" align="left"><input type="file" name="picname"></td><tr>
	<tr><td align=center colspan="2">Caption (250 Characters Maximum)</td></tr>
	<tr><td align=center colspan="2"><textarea cols="40" rows="4" name="caption"></textarea></td></tr>
	<tr><td>Large Caption</td><td dir="rtl" align="left"><input type="file" name="Lcaption"></td></tr>
	<tr><td>Picture position</td><td><input type="radio" name="position" value="l">Left<input type="radio" name="position" value="r" checked>Right</td></tr>';
	$q='select place,placename from places where country='.$country;
	$results=mysql_query($q);
	$nrows=mysql_num_rows($results);
	print'<tr><td>Link to...</td><td><select name="linkat" >
	<option value=0>Choose a Place</option>';
	for($i=0;$i<$nrows;$i++){
		$linkto= mysql_result($results,$i,"place");
		$plname= mysql_result($results,$i,"placename");
		print'<option value='.$linkto.'>'.$plname.'</option>';
	}
	print'<tr><td align="center" colspan="2"><input type="submit" value="Enter Data"></td></tr>
	</table>
	<input type="hidden" name="g" value="2">
	<input type="hidden" name="placename" value="'.$placename.'">
	</form>';
}
//--------------------------------------------------  Enter Place  ------------------------------------
if($g==11){
	//print $watermark.'<br>';
	$watermark1=substr($watermark,9);
	$watermark1 = str_replace("\\", "/", $watermark1);
	$watermark1 = str_replace("//", "/", $watermark1);
	//print $watermark1.'<br>';
	//print "1-mainText=".$mainText.'<br>';
	$mainText = str_replace("\\", "/", $mainText);
	$mainText = str_replace("//", "/", $mainText);
	$mainText=substr($mainText,7);
	$mainText=htmtotxt($mainText);
	if($NewPlace==null){//update old record
		$q='update places set watermark="'.$watermark1.'" where place='.$place;
		$results=mysql_query($q);
		$x=pe($q);
		$q='update txt set maintext="'.$mainText.'" where place='.$place.' and language='.$language;
		$results=mysql_query($q);
		$x=pe($q);
	}
	else{//create new record
		$q='insert into places (country,		city,		placename,			watermark) values
								('.$country.','.$city.',"'.$NewPlace.'","'.$watermark1.'")';
		$results=mysql_query($q);
		$x=pe($q);
		$q='select * from places';
		$results=mysql_query($q);
		$nrows=mysql_num_rows($results);
		$q='insert into txt(place,language,maintext) values ('.$nrows.','.$language.',"'.$mainText.'")';
		$results=mysql_query($q);
		$x=pe($q);
	}
}
if($g>=10 and $g<20){
	print'
	<form action="admin.php" name="form1" id="form1">
	<table align="center" border bgcolor="#ffffcc">
	<tr><td colspan="2" align="center">Page Entry</td></tr>';
	if(!isset($country)){$country=0;}
	print'
	<tr><td>Country</td><td><select name="country" onchange="newCountry(10)">
	<option value=0>Choose a country</option>';
	$q='Select * from country order by country';
	$results=mysql_query($q);
	$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$countryN= mysql_result($results,$i,"country");
		print'<option value='.$serno;
		if($country==$serno){print' selected';}
		print'>'.$countryN.'</option>';
	}
	print'</select></td></tr>
	<tr><td>City</td><td><select name="city" onchange="newCountry(10)">';
	$x=isset($city);
	if($x===false){$city=0;}
	$q='Select * from city where country='.$country.' order by city';
	$results=mysql_query($q);
	$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$place= mysql_result($results,$i,"city");
		print'<option value='.$serno;
		if($city==$serno){print' selected';}
		print'>'.$place.'</option>';
	}
	print'</select></td></tr>';
	$x=isset($place);
	if($x===false){$place=0;}
	print'
	<tr><td>Place</td><td><select name="place" >
	<option value=0>Choose a Place</option>';
	$q='Select * from places  where city='.$city.' order by placename';
	$results=mysql_query($q);
	$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"place");
		$placename= mysql_result($results,$i,"placename");
		print'<option value='.$serno;
		if($place==$serno){print' selected';}
		print'>'.$placename.'</option>';
	}
	print'</select></td></tr>
	<tr><td>New Place</td><td><input type="text" name="NewPlace" size="25" maxlength="25"></td></tr>;
	<tr><td>Language</td><td><select name="language" >';
	$q='Select * from languages  order by language';
	$results=mysql_query($q);
	$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$language= mysql_result($results,$i,"language");
		print'<option value='.$serno;
		if($serno==1){print' selected';}
		print'>'.$language.'</option>';
	}
	print'</select></td></tr>
	<tr><td>Watermark</td><td><input type="file" name="watermark"></td></tr>
	<tr><td>Main text</d><td><input type="file" name="mainText"></td></tr>
	<tr><td colspan=2 align="center"><input type="submit" value="Enter Data"></tr></td>';
	print'</table>
	<input type="hidden" name="g" value="11">
	</form>';
}
//-------------------------------------------------------------  Edit Place  ------------------------------------------------------
if($g==21){print'
	<form action="admin.php" name="form3" id="form3">
	<table align="center" border bgcolor="#ffffcc">
	<tr><td colspan="2" align="center">Page Entry</td></tr>';
	$x=isset($country);
	if($x===false){$country=0;}
	print'
	<tr><td>Country</td><td><select name="country" onchange="newCountry3(21)">
	<option value=0>Choose a country</option>';
	$q='Select * from country order by country';
	$results=mysql_query($q);
//$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$countryN= mysql_result($results,$i,"country");
		print'<option value='.$serno;
		if($country==$serno){print' selected';}
		print'>'.$countryN.'</option>';
	}
	print'</select></td></tr>
	<tr><td>City</td><td><select name="city" onchange="newCountry3(21)">';
	$x=isset($city);
	if($x===false){$city=0;}
	$q='Select * from city where country='.$country.' order by city';
	$results=mysql_query($q);
	print $q;
	$x=pe($q);
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$cityN= mysql_result($results,$i,"city");
		print'<option value='.$serno;
		if($city==$serno){print' selected';}
		print'>'.$cityN.'</option>';
	}
	print'</select></td></tr>';
	if(!isset($place)){$place=0;}
	print'
	<tr><td>Place</td><td><select name="place" >
	<option value=0>Choose a Place</option>';
	$q='Select * from places  where city='.$city.' order by placename';
	$results=mysql_query($q);
	//$x=pe($q);
	//if($x<>0){print'<tr><td>'.$q.'</td></tr>';}
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"place");
		$placename= mysql_result($results,$i,"placename");
		print'<option value='.$serno;
		if($place==$serno){print' selected';}
		print'>'.$placename.'</option>';
	}
	print'</select></td></tr>
	<tr><td>Language</td><td><select name="language" >';
	$q='Select * from languages  order by language';
	$results=mysql_query($q);
	//$x=pe($q);
	//if($x<>0){print'<tr><td>'.$q.'</td></tr>';}
	$nrows=mysql_num_rows($results);
	for($i=0;$i<$nrows;$i++){
		$serno= mysql_result($results,$i,"serno");
		$language= mysql_result($results,$i,"language");
		print'<option value='.$serno;
		if($serno==1){print' selected';}
		print'>'.$language.'</option>';
	}
	print'</select></td></tr>
	<tr><td align="center" colspan="2"><input type="button" name="b1" value="Continue" onClick="submitPage3()"></td></tr>
	</table>
	<input type="hidden" name="g" value="22">
	</form>';
}
if($g==22){
	print'<form action="admin.php" name="form4" id="form4">
	<table align="center" bgcolor="#ffffdd" border>';
		$q='select * from country where serno='.$country;
	$results=mysql_query($q);
	$country= mysql_result($results,0,"country");
		$q='select * from city where serno="'.$city.'"';
	$results=mysql_query($q);
	$city= mysql_result($results,0,"city");
	$q='select * from places where place="'.$place.'"';
	$results=mysql_query($q);
	$placename= mysql_result($results,0,"placename");
	$wm= mysql_result($results,0,"watermark");
	$i=$language;
	//print $language;
	$q='select * from languages where serNo='.$language;
	$results2=mysql_query($q);
	$lang= mysql_result($results2,0,"language");
	$q='select maintext from txt where place='.$place.' and language='.$language;
	$results=mysql_query($q);
//print $q;
	$mt1=mysql_result($results,0,"maintext");
	print'<tr><td>Country</td><td>'.$country.'</td></tr>
	<tr><td>City</td><td>'.$city.'</td></tr>
	<tr><td>Place</td><td>'.$placename.'</td></tr>
	<tr><td>Language</td><td>'.$lang.'</td></tr>
	<tr><td>Original watermark</td><td>'.$wm.'</td></tr>
	<tr><td>Watermark</td><td><input type="file" name="watermark"  ></td></tr>
	<tr><td>Original Text</td><td>'.$mt1.'</td></tr>
	<tr><td>Maintext</td><td><input type="file" name=maintext></td></tr>
	<tr><td align="center" colspan="2"><input type="submit" name="Effect Change" value="Effect Change"></td></tr>
	</table>
	<input type="hidden" name="lang" value="'.$i.'">
	<input type="hidden" name="g" value="25">
	<input type="hidden" name="place" value="'.$place.'">
	<input type="hidden" name="mt1" value="'.$mt1.'">
	<input type="hidden" name="wm" value="'.$wm.'">
	</form>
	<br>
	<table align="right" border="2" bgcolor="#ffffcc">';
	$q='select * from pics where place='.$place;
	//print $q.'<br>';
	$results2=mysql_query($q);
	$nrows=mysql_num_rows($results2);
	for($i=0;$i<$nrows;$i++){
		$pn= mysql_result($results2,$i,"picname");
		$pos= mysql_result($results2,$i,"position");
		$s=mysql_result($results2,$i,"serno");
		$q2='select caption,lcaption from captions where picture='.$s.' and language='.$language;
		$results3=mysql_query($q2);
		//print $q2;
		$cap= mysql_result($results3,0,"caption");
		$lc= mysql_result($results3,0,"lcaption");
		$lc1='<br>';
		if($lc<>null){$lc1=$lc;}
		$size=getimagesize($pn);
		$w=$size[0];
		$h=$size[1];
		if($w>$h){
			$nw=120;
			$nh=round(120*$h/$w);
		}
		else{
			$nh=120;
			$nw=round(120*$w/$h);
		}
		print'
		<tr><td>Picture Name</td><td><a href="admin.php?serno='.$s.'&g=23&language='.$language.'">'.$pn.'</a></td>
		<td align="center" valign="middle" rowspan="4"><img src="'.$pn.'" height="'.$nh.'" width="'.$nw.'"></tr>
		<tr><td>Caption</td><td>'.$cap.'</td></tr>
		<tr><td>Large Caption</td><td>'.$lc1.'</td></tr>
		<tr><td>Position<td>'.$pos.'</td></tr><tr></tr>';
	}
	print'</table>';
}
if($g==23){
	$q='select * from pics where serno='.$serno;
	$results=mysql_query($q);
	$pn= mysql_result($results,0,"picname");
	$pos= mysql_result($results,0,"position");
	$place= mysql_result($results,0,"place");
	$q='select caption,lcaption from captions where picture='.$serno.' and language='.$language;
	$results=mysql_query($q);
	$cap= mysql_result($results,0,"caption");
	$lc= mysql_result($results,0,"lcaption");
	$q='select placename,city,country from places where place="'.$place.'"';
	$results2=mysql_query($q);
	$placename= mysql_result($results2,0,"placename");
	$city= mysql_result($results2,0,"city");
	$country= mysql_result($results2,0,"country");
	$q='select country from  country where serno='.$country;
	$results3=mysql_query($q);
	$countryname= mysql_result($results3,0,"country");
	$q='select city from  city where serno='.$city;
	$results4=mysql_query($q);
	$cityname= mysql_result($results4,0,"city");
	print'<form action="admin.php" name="form23" id="form23">
	<br><br>
	<table border>
	<tr><td>Country</td><td>'.$countryname.'</td></tr>
	<tr><td>City</td><td>'.$cityname.'</td></tr>
	<tr><td>Place</td><td>'.$placename.'</td></tr>
	<tr><td>Current picture</td><td>'.$pn.'</td></tr>
	<tr><td>Picture</td><td dir="rtl" align="left"><input type="file" name="picname" value='.$pn.'></td><tr>
	<tr><td align=center colspan="2">Caption (250 Characters Maximum)</td></tr>
	<tr><td align=center colspan="2"><textarea cols="40" rows="6" name="caption">'.$cap.'</textarea></td></tr>
	<tr><td>Current Large Caption</td><td>'.$lc.'</td></tr>
	<tr><td>Large Caption</td><td dir="rtl" align="left"><input type="file" name="Lcaption" value="'.$lc.'"></td></tr>
	<tr><td>Picture position</td><td><input type="radio" name="position" value="l"';
	if($pos=='l'){print 'checked ';}
	print'>Left<input type="radio" name="position" value="r"';
	if($pos=='r'){print' checked ';}
	print'>Right</td></tr>
	<tr><td align="center" colspan="2"><input type="submit" value="Enter Data"></td></tr>
	</table>
	<input type="hidden" name="g" value="24">
	<input type="hidden" name="serno" value="'.$serno.'">
	<input type="hidden" name="pn" value="'.$pn.'">
	<input type="hidden" name="lc" value="'.$lc.'">
	<input type="hidden" name="language" value="'.$language.'">
	</form>';

}
if($g==24){
	if($picname==null){$picname=$pn;}
	if($Lcaption==null){$Lcaption=$lc;}
	$picname = str_replace("\\", "/", $picname);
	$picname = str_replace("//", "/",$picname);
	$picname=substr($picname,7);
	$Lcaption=str_replace("\\", "/", $Lcaption);
	$Lcaption=str_replace("//", "/",$Lcaption);
	$Lcaption=substr($Lcaption,7);
	$q='update pics set picname="'.$picname.'",position="'.$position.'"
		where serno='.$serno;
	$results=mysql_query($q);
	$x=pe($q);

	$q='update captions set caption="'.$caption.'",lcaption="'.$Lcaption.'" where picture='.$serno.' and language='.$language;
	$results=mysql_query($q);
	$x=pe($q);
	if($x==0){print'<center><h2><font color="#0000ff" size="+2">Data updated successfully</font></h2></center>';}
	else{print'<center><h2><font color="#ff0000" size="+2">Data was not updated</font></h2></center>';}
}
if($g==25){
	if($maintext==null){$maintext=$mt1;}
	if($watermark==null){$watermark=$wm;}
	$q='update places set watermark="'.$watermark.'" where place='.$place;
	$results=mysql_query($q);
	$x=pe($q);
	$q='update txt set maintext="'.$maintext.'" where place='.$place.' and language='.$language;
	$results=mysql_query($q);
	$x=pe($q);
	if($x==0){print'<center><h2><font color="#0000ff" size="+2">Data updated successfully</font></h2></center>';}
	else{print'<center><h2><font color="#ff0000" size="+2">Data was not updated due to an error</font></h2></center>';}
}
?>
</body>
</html>
