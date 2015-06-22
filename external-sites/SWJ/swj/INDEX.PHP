<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head><title>Synagogues Without Jews</title>
<script language="JavaScript">
function openwindow(p){
bu="blowup.php?"+p;
//alert(bu);
p3="toolbar=0, location=0, directories=0, status=0,  scrollbars=no, resizable=no, copyhistory=no,width=450,height=500";
window.open(p,"Enlargement",p3)
}
</script>
	<title>Synagogues Without Jews</title>
	<style type="text/css">
<!--
div#t2{position:absolute;left:0px;top:20px;height:32px;background:#b76683;width:780px;}
div#t3{position:absolute;left:0px;top:21px;height:30px;background:#993366;width:780px;}
div#t4{position:absolute;left:250px;top:550px;height:1000px;background:#993366;width:1px;}
div#pic1{position:absolute;left:250px;top:52px;}
div#headlines{position:absolute;left:0px;top:60;width:250px;color:#993366;text-align:center;}
div#maintext{position:absolute;left:270px;top:590px;width:510;}
div#swj{ font-family: monotype corsiva; font-size: 28px; font-weight: 600;position:absolute; left:0px;top:60;width:250px;color:#993366;text-align:center;}
div#logo{position:absolute;left:71px;top:1280px; }
div#sidebar{position:absolute;left:0px;top:600px;width:250px;height:1400px;background:#FFE0A3;}
div#lowertext{position:absolute;left:20px;top:630px;width:220px;}
 -->
 </style>
</head>
<body bgcolor=#ffeecf>
<?php
function pe ($sqlQ)
{
	$e=mysql_errno();
	if($e<>0)
		print $sqlQ . '<br>' . $e. ':  ' .mysql_error();
	return $e;
}
if(!isset($language) or $language==null){$language=1;}
$p1="'blowup2.php?fn=Special pages/001 Home/01_N234-5-Sienna-lamps.jpg&language=1&pn=11'";
$p2="'blowup2.php?fn=Special pages/001 Home/02_S119-24-Craoatia Subotice-upper-Ar.jpg&language=1&pn=12'";
$p3="'blowup2.php?fn=Special pages/001 Home/03_N125-27 Rhodes int bimah and gal.jpg&language=1&pn=13'";
$p4="'blowup2.php?fn=Special pages/001 Home/04_N402-25-Austria Graz-syn-dome peak.jpg&language=1&pn=14'";
$p5="'blowup2.php?fn=Special pages/001 Home/05_N9-19-Czechia-Trebic-vu-across-ri.jpg&language=1&pn=15'";
$p6="'blowup2.php?fn=Special pages/001 Home/06_N96-35-Slovakia-B-Stiavnice-tn-vu-.jpg&language=1&pn=16'";
$p7="'blowup2.php?fn=Special pages/001 Home/07_N70-20-Hungary-Apostag-syn-fr-cem.jpg&language=1&pn=17'";
$p8="'blowup2.php?fn=Special pages/001 Home/08_Nx-28-002-SAR-Yad-Vshm-exhibn.jpg&language=1&pn=18'";
$p9="'blowup2.php?fn=Special pages/001 Home/09_N347-21-003SWJ-Atlanta-c.jpg&language=1&pn=19'";
$p10="'blowup2.php?fn=Special pages/001 Home/10_N219-27-004preface-Italy.jpg&language=1&pn=20'";
$p11="'blowup2.php?fn=Special pages/001 Home/11_N185-29-005swj-chap-Pecovska cem.jpg&language=1&pn=21'";
$p12="'blowup2.php?fn=Special pages/001 Home/12_N88-14-006ourteam-Praha-.jpg&language=1&pn=22'";
$p13="'blowup2.php?fn=Special pages/001 Home/13_D1376-008-Whats-new-Ruz.jpg&language=1&pn=23'";
$p14="'blowup2.php?fn=Special pages/001 Home/14_D1026-Trebic-HowHelp.jpg&language=1&pn=24'";
$p15="'blowup2.php?fn=Special pages/001 Home/15_N65-6-Baja-Aron-top.jpg&language=1&pn=25'";

print'
<div id=t2></div>
<div id=t3></div>
<div id="pic1"><img src="Special pages/001 Home/Front-jacket-upper-cover-43.jpg" width="610" height="533" border="0" alt=""></div>
<div id="maintext">
<TABLE width="510" cellpadding="5">
<tr><td colspan="3"><font color="#993366" face="Monotype Corsiva"><h1>Synagogue Art in Jewish Life</h1><br></font></td></tr>
<tr><td colspan="3"><font face="Verdana" size="-1">For centuries, Jews populated the
villages, towns and cities of continental Europe until the Holocaust destroyed
their communities and their lives. They no longer live in the thousands of villages,
towns and cities of their former prominent presence, but the houses, shops,
factories and community institutions that the Jewish culture had created remain
there, ownerless and unguarded.<br></font></td></tr>
<tr>
<td align="center">
<img src="Special pages/001 Home/01_N234-5-Sienna-lamps.jpg" width="120" height="85" border="0" alt="" onclick="javascript:openwindow('.$p1.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/02_S119-24-Craoatia Subotice-upper-Ar.jpg" width="120" height="75" border="0" alt="" onclick="javascript:openwindow('.$p2.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/03_N125-27 Rhodes int bimah and gal.jpg" width="120" height="77" border="0" alt=""onclick="javascript:openwindow('.$p3.')"></a></td>
</tr>
<tr>
<td align="center"><a href="country.php?country=6&places=21&language='.$language.'">ITALY</a></td>
<td align="center"><a href="country.php?country=2&places=18&language='.$language.'">CROATIA-SERBIA</a></td>
<td align="center"><a href="country.php?country=4&places=19&language='.$language.'">GREECE</a></td>
</tr>
<tr><td colspan="3"><font face="Verdana" size="-1">
Surviving synagogue buildings and cemeteries are often the only structures that are of recognizably Jewish
origin. The synagogues were the places most fully charged with specific Jewish
identity. Their art and architecture constitute a record of what was
esthetically important to the communities that built and used them. Synagogue
art must therefore be documented, remembered and preserved as a major visual
element of Jewish history.<br></font></td></tr>
<tr>
<td align="center">
	<img src="Special pages/001 Home/04_N402-25-Austria Graz-syn-dome peak.jpg" width="120" height="75" border="0" alt=""onclick="javascript:openwindow('.$p4.')"></a></td>
<td align="center">
	<img src="Special pages/001 Home/05_N9-19-Czechia-Trebic-vu-across-ri.jpg" width="120" height="80" border="0" alt=""onclick="javascript:openwindow('.$p5.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/06_N96-35-Slovakia-B-Stiavnice-tn-vu-.jpg" width="120" height="80" border="0" alt=""onclick="javascript:openwindow('.$p6.')"></a></td>
</tr>
<tr>
<td align="center"><a href="country.php?country=1&places=17&language='.$language.'">AUSTRIA</a></td>
<td align="center"><a href="country.php?country=3&places=3&language='.$language.'">CZECH REPUBLIC</a></td>
<td align="center"><a href="country.php?country=7&places=23&language='.$language.'">SLOVAKIA</a></td>
</tr>
<tr><td colspan="3"><font face="Verdana" size="-1">
With this mission in mind, Rivka and Ben-Zion Dorfman documented synagogue art and architecture,
particularly in the lesser-known hinterland settlements. In Austria, the Czech
Republic, Greece, Hungary Italy, Slovakia and the former Yugoslavia, they sought out
synagogue buildings in villages, towns and cities to record the grandeur and
the desolation that remained of the former Jewish presence.<br>
</font></td></tr>
<tr>
<td align="center">
<img src="Special pages/001 Home/07_N70-20-Hungary-Apostag-syn-fr-cem.jpg" width="120" height="80" border="0" alt="" onclick="javascript:openwindow('.$p7.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/08_Nx-28-002-SAR-Yad-Vshm-exhibn.jpg" width="120" height="83" border="0" alt="" onclick="javascript:openwindow('.$p8.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/09_N347-21-003SWJ-Atlanta-c.jpg" width="120" height="74" border="0" alt="" onclick="javascript:openwindow('.$p9.')"></a></td>
</tr>
<tr>
<td align="center"><a href="country.php?country=5&places=20&language='.$language.'">HUNGARY</a></td>
<td align="center"><a href="general.php?places=10&language=1">SYNAGOGUE ART<BR>RESEARCH</a></TD>
<td align="center"><a href="general.php?places=11&language=1">Book: Synagogeus<br>Without Jews</a></td>
</tr>
<tr><td colspan="3"><font face="Verdana" size="-1">
Local populations had converted many buildings into dwellings, storerooms, churches, commercial
premises or other mundane uses. In the past two decades, however, a trend has
developed for restoration to cultural use and some are being turned into
galleries, museums, libraries and concert halls. As this site develops and
expands, it will present new and current information on this aspect.<br>
</font></td></tr>
<tr>
<td align="center">
<img src="Special pages/001 Home/10_N219-27-004preface-Italy.jpg" width="120" height="78" border="0" alt="" onclick="javascript:openwindow('.$p10.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/11_N185-29-005swj-chap-Pecovska cem.jpg" width="120" height="79" border="0" alt="" onclick="javascript:openwindow('.$p11.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/12_N88-14-006ourteam-Praha-.jpg" width="120" height="80" border="0" alt="" onclick="javascript:openwindow('.$p12.')"></a></td>
</tr>
<tr>
<td align="center"><a href="general.php?places=12&language=1">Book:<br>Preface</a></td>
<td align="center"><a href="general.php?places=13&language=1">Book:<br>Full chapter</a></td>
<td align="center" valign="top"><a href="general.php?places=9&language=1">OUR TEAM</a></td>
</tr>
<tr>
<td colspan="3"><font face="Verdana" size="-1">
The primary source for the present content of this Synagogues Without Jews website
is the Dorfmans&#8217; book <i>Synagogues Without Jews and the communities
that built and used them.</i> A growing cadre of
enthusiastic volunteers is busy researching the histories of more communities
and preparing them for posting on this site, together with photographs from the
Dorfman Archive of Synagogue Art.<br>
</font></td></tr>
<tr>
<td align="center">
<img src="Special pages/001 Home/13_D1376-008-Whats-new-Ruz.jpg" width="120" height="72" border="0" alt="" onclick="javascript:openwindow('.$p13.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/14_D1026-Trebic-HowHelp.jpg" width="120" height="82" border="0" alt="" onclick="javascript:openwindow('.$p14.')"></a></td>
<td align="center">
<img src="Special pages/001 Home/15_N65-6-Baja-Aron-top.jpg" width="120" height="81" border="0" alt="" onclick="javascript:openwindow('.$p15.')"></a></td>
</tr>
<tr>
<td align="center"><a href="general.php?places=15&language=1">What&acute;s New?</a></td>
<td align="center"><a href="general.php?places=16&language=1">How Can I Help?</a></td>
<td align="center">Baja<br>Hungary</td>
</tr>
<tr>
<td colspan="3"><font face="Verdana" size="-1">
<a href="mailto:bdenka@013.net.il; elmargaret@yahoo.com">We invite your comment and participation.<br></A>
</font></td></tr>
<tr>
<td colspan="3"><font face="Verdana" size="-3">
Unless otherwise noted, all photographs on this site are the work of
Rivka and Ben-Zion Dorfman. All the illustrations are copyright © by them and
all rights are reserved. Neither art nor text may be copied by any means or for
any purpose without express, written <a href="mailto:bdenka@013.net.il; elmargaret@yahoo.com">permission</a>.
</font></td></tr>
</tr>
</TABLE>';



print'
</div>
<div id=headlines>
ENGLISH  &nbsp;&nbsp;&nbsp;&nbsp; HEBREW<br><br>


This site<br><br>
<div id="swj">
Synagogues<br>Without Jews<br><br><br>
</div>
<br><br><br><br>
is based partly on the book<br>
of the same name by<br>
Rivka and Ben-Zion Dorfman<br><br>


ART and ARCHITECTURE<br>
of SYNAGOGUES in<br>
COUNTRIES of CENTRAL EUROPE<br><br>';

$link=mysql_connect("localhost","bh","qty79x") or die("could not connect");
@mysql_select_db("bh") or die("could not select database");
 $q='select * from country';
 $results=mysql_query($q);
$x=pe($q);
$j=1;
for($i=0;$i<8;$i++){
	$country= mysql_result($results,$i,"country");
	$serno= mysql_result($results,$i,"serno");
	$places= mysql_result($results,$i,"places");
	if($i>6){print $country;} else{
	print '<a href="country.php?country='.$serno.'&places='.$places.'">'.$country.'&nbsp;|&nbsp;</a>';}
	$j++;
	if($j % 3==0){print"<br>";}

}

print'<br><br>
and the<br><br>

HISTORIES of their RESPECTIVE<br>
COMMUNITIES<br><br>
</div>


<div id="sidebar"></div>
<div id="lowertext">

We are grateful to <a href="http://www.bh.org.il">Beth Hatefutsot</a> for generously hosting our site.<br><br>
Who we are:<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="general.php?places=10&language=1">Synagogue Art Research</a><br><br><br>



Book:<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="general.php?places=11&language=1">Synagogues Without Jews</a>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.jewishpub.org/product.php?isbn=0827606923"></a>Buy or <a href="http://www.amazon.com/exec/obidos/tg/detail/-/0827606923/qid=1086274503/sr=1-1/ref=sr_1_1/103-9194238-9164668?v=glance&s=books">Amazon</a><br><br>



Special pages<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="general.php?places=12&language=1">Preface</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="general.php?places=13&language=1">Chapter of the month</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="general.php?places=9&language=1">Our team</a><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="general.php?places=15&language=1">What&acute;s new?</a><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="general.php?places=16&language=1">How can I help?</a><br><br>


<br>Related sites:<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="-1"><a href="http://www.jewishpub.org">JEWISH PUBLICATION SOCIETY</a></font><br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.yad-vashem.org.il/">YAD VASHEM</a>

</div>

<div id="logo"><img src="Logo-new.jpg" width="108" height="198" border="0" alt=""></div>';
?>
</body>
</html>
