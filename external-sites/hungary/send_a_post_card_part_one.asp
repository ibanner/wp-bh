<%@ Language=VBScript %>

<%Response.Buffer=true%>

<html>
<head>
<meta name=vs_targetSchema content="http://schemas.microsoft.com/intellisense/ie5">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">
<title> S E N D  A  P O S T  C A R D </title>
<meta name="Autor" content="Motti Gondabi">

<style>
<!--
.link {
	color:#333333;
	font-family:helvetica;
	font-weight:bold;
	font-size:12px;
	text-decoration:none;
	text-align:justify;
	}
-->
</style>
<script language="JavaScript">
var ButtonPath = "images/send_a_post_cards/";
var ButtonOut = new Array("postcard1.jpg","postcard2.jpg","postcard3.jpg","postcard4.jpg");
var ButtonOver  = new Array("postcard1b.jpg","postcard2b.jpg","postcard3b.jpg","postcard4b.jpg");

function LoadImages()
        {
        imageOff= new Array();
        imageOn= new Array();
        for (i=0; i<ButtonOut.length; i++)
                {
                imageOff[i] = new Image();
                imageOff[i].src = ButtonPath + ButtonOut[i];
                }       
        for (i=0; i<ButtonOver.length; i++)
                {
                imageOn[i] = new Image();
                imageOn[i].src = ButtonPath + ButtonOver[i];
                }       
        }

</script>
</head>
<body bgcolor="#ffffcc" onLoad="LoadImages()" leftmargin="0" topmargin="0">

<table cellpadding="0" cellspacing="0" border="0" align="left"><!-- main -->

<tr>
<td><table cellpadding="0" cellspacing="0" border="0" align="left" width="381" height="400">
  
  <tr>
      <td align="left" colspan="3" valign="top"><img src="images/send_a_post_cards/SendAPostcardCot.gif" border="0" width="381" height="46"></td>
      <td></td>
	  <td></td>
  </tr>
  <tr>
      <td colspan="3" height="4"></td>
      <td></td>
	  <td></td>
  </tr>
  <tr>
    <td><img src="images/t.gif" border="0" width="24" height="1"></td>
    <td align="right"><A href="send_a_post_card_part_two.asp?key=1" onMouseOver="document.Image.src='images/send_a_post_cards/postcard1b.jpg';document.sentence.src='images/send_a_post_cards/postcard1p.gif'"  target="MIDDLE"><img src="images/send_a_post_cards/postcard1.jpg" border="0" width="167" height="167"></A></td>
    <td align="right"><A href="send_a_post_card_part_two.asp?key=2" onMouseOver="document.Image.src='images/send_a_post_cards/postcard2b.jpg';document.sentence.src='images/send_a_post_cards/postcard2p.gif'"  target="MIDDLE"><img src="images/send_a_post_cards/postcard2.jpg" border="0" width="167" height="167"></A></td>
  </tr>
  <tr>
    <td><img src="images/t.gif" border="0" width="24" height="1"></td>
    <td align="right"><A href="send_a_post_card_part_two.asp?key=3"  onMouseOver="document.Image.src='images/send_a_post_cards/postcard3b.jpg';document.sentence.src='images/send_a_post_cards/postcard3p.gif'"  target="MIDDLE"><img src="images/send_a_post_cards/postcard3.jpg" border="0" width="167" height="167"></A></td>
    <td align="right"><A href="send_a_post_card_part_two.asp?key=4"  onMouseOver="document.Image.src='images/send_a_post_cards/postcard4b.jpg';document.sentence.src='images/send_a_post_cards/postcard4p.gif'"  target="MIDDLE"><img src="images/send_a_post_cards/postcard4.jpg" border="0" width="167" height="167"></A></td>
  </tr>
</table></td>


<td><table cellpadding="0" cellspacing="0" border="0" width="370">
  
  <tr>
     <td align="right"><img name="Image" src="images/send_a_post_cards/Postcard1b.jpg" border="0" width="350" height="233"></td>  
  </tr> 
  <tr>
     <td align="center" valign="bottom" height="41"><img  name="sentence" src="images/send_a_post_cards/Postcard1p.gif" border="0" width="262" height="30"></td>
  </tr>
</table></td>
</tr>  
  
  
</table>  <!-- main -->  

</body>
</html>
