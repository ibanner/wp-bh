<%@ Language=VBScript %>
<%
   	 key=Request.QueryString("key")   						  'which picture to send over the mail?
%>
<html>
	<head>
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">
		<title>S E N D A P O S T C A R D</title>
		<meta name="Author" content="Motti Gondabi">
		<script language="JavaScript" src="validate.js">

		</script>
		<script language="JavaScript">

function clearForm()
{
self.document.form1.heading.value="";
self.document.form1.full_name.value="";
self.document.form1.rec_name.value="";
self.document.form1.signature.value="";
self.document.form1.email.value="";
self.document.form1.rec_email.value="";
self.document.form1.message.value="";
}

var ButtonPath = "images/send_a_post_cards/";
var ButtonOut = new Array("previewBut.gif","sendBut.gif","clearBut.gif");
var ButtonOver  = new Array("previewButa.gif","sendButa.gif","clearButa.gif");

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

function imgOver(index)
        {
        document['Button' + index].src = ButtonPath + ButtonOver[index];
        }
function imgOut(index)
        {
        document['Button' + index].src = ButtonPath + ButtonOut[index];
        }
  
		</script>
		<style>
<!--
.link {
	color:#333333;
	font-family:helvetica;
	font-weight:bold;
	font-size:10px;
	text-decoration:none;
	text-align:justify;
	}
-->
		</style>
	</head>
	<body bgcolor="#FFFFCC" onload="LoadImages()" leftmargin="5" topmargin="0">
		<form action method="post" name="form1">
			<table cellpadding="0" cellspacing="0" border="0" align="right" width="700">
				<!-- main -->
				<tr>
					<td>
						<table cellpadding="0" cellspacing="5" border="0">
							<!-- Form Table -->
							<tr>
								<td colspan="2">
									<img src="images/send_a_post_cards/SendAPostcardPic.gif" border="0" width="386" height="42"></td>
							</tr>
							<tr>
								<td colspan="2" height="10">&nbsp;</td>
							</tr>
							<tr>
								<td class="link">HEADING<br>
									<input style="border-bottom-color: green; border-top-color: green; border-right-color: green;  border-left-color: green; BACKGROUND-COLOR: #FFFFCC; FONT-WEIGHT:bold; COLOR: #336633; FONT-WEIGHT:bold; FONT-FAMILY: sans-serif;"
										type="text" name="heading" size="20" maxlength="40"></td>
								<td class="link">SIGNATURE<br>
									<input style="border-bottom-color: green; border-top-color: green; border-right-color: green;  border-left-color: green; BACKGROUND-COLOR: #FFFFCC; FONT-WEIGHT:bold; COLOR: #336633; FONT-FAMILY: sans-serif;"
										type="text" name="signature" size="20" maxlength="40"></td>
							</tr>
							<tr>
								<td class="link">YOUR NAME<br>
									<input style="border-bottom-color: green; border-top-color: green; border-right-color: green;  border-left-color: green; BACKGROUND-COLOR: #FFFFCC; FONT-WEIGHT:bold; COLOR: #336633; FONT-FAMILY: sans-serif;"
										type="text" name="full_name" size="20" maxlength="50"></td>
								<td class="link">YOUR E-MAIL ADDRESS<br>
									<input style="border-bottom-color: green; border-top-color: green; border-right-color: green;  border-left-color: green; BACKGROUND-COLOR: #FFFFCC; FONT-WEIGHT:bold; COLOR: #336633; FONT-FAMILY: sans-serif;"
										type="text" name="email" size="20" maxlength="40"></td>
							</tr>
							<tr>
								<td class="link">RECIPIENT NAME<br>
									<input style="border-bottom-color: green; border-top-color: green; border-right-color: green;  border-left-color: green; BACKGROUND-COLOR: #FFFFCC; FONT-WEIGHT:bold; COLOR: #336633; FONT-FAMILY: sans-serif;"
										type="text" name="rec_name" size="20" maxlength="50"></td>
								<td class="link">RECIPIENT E-MAIL ADDRESS<br>
									<input style="border-bottom-color: green; border-top-color: green; border-right-color: green;  border-left-color: green; BACKGROUND-COLOR: #FFFFCC; FONT-WEIGHT:bold; COLOR: #336633; FONT-FAMILY: sans-serif;"
										type="text" name="rec_email" size="20" maxlength="40"></td>
							</tr>
							<tr>
								<td colspan="2" class="link">MESSAGE<br>
									<textarea style="border-bottom-color: green; border-top-color: green; border-right-color: green;  border-left-color: green; BACKGROUND-COLOR: #FFFFCC;FONT-WEIGHT:bold; COLOR: #336633;  OVERFLOW: hidden; FONT-FAMILY: sans-serif;"
										cols="49" name="message" rows="4"></textarea> <input type="hidden" name="key" value="<%=key%>"><input type="hidden" name="send" value="0">
								</td>
							</tr>
						</table>
					</td>
					<td align="left">
						<table cellpadding="0" align="left" cellspacing="0" border="0">
							<!-- The Selected PostCard Table -->
							<tr>
								<td height="50"></td>
							</tr>
							<tr>
								<td><%	
			if  key=1  then Response.write("<img src=""images/send_a_post_cards/Postcard1b.jpg"" border=""0"" width=""350"" height=""234"">" )
			if  key=2  then Response.write("<img src=""images/send_a_post_cards/postcard2b.jpg"" border=""0"" width=""350"" height=""234"">" )
	     	if  key=3  then Response.write("<img src=""images/send_a_post_cards/postcard3b.jpg"" border=""0"" width=""350"" height=""234"">" )
	     	if  key=4  then Response.write("<img src=""images/send_a_post_cards/postcard4b.jpg"" border=""0"" width=""350"" height=""234"">" )
          %>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="2" border="0" align="left" height="40">
							<!-- Buttons Table -->
							<tr>
								<td><img src="images/t.gif" border="0" width="2" height="1"></td>
								<td><a href="#" onmouseover="imgOver(0)" onmouseout="imgOut(0)" onclick="checkData(1)"><img name="Button0" src="images/send_a_post_cards/previewBut.gif" border="0" width="64"
											height="22"></a></td>
								<td><img src="images/t.gif" border="0" width="71" height="1"></td>
								<td><a href="#" onmouseover="imgOver(2)" onmouseout="imgOut(2)" onclick="clearForm()"><img name="Button2" src="images/send_a_post_cards/clearBut.gif" border="0" width="64"
											height="22"></a></td>
								<td><img src="images/t.gif" border="0" width="71" height="1"></td>
								<td><a href="#" onmouseover="imgOver(1)" onmouseout="imgOut(1)" onclick="checkData(0)"><img name="Button1" src="images/send_a_post_cards/SendBut.gif" border="0" width="64"
											height="22"></a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- main -->
		</form>
	</body>
</html>
