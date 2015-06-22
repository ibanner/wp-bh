<%@ Language=VBScript %>

<%Response.Buffer=true%>

<%
	
	key=Request.QueryString("key")   						  'which picture to send over the mail?
	heading=Request.QueryString("heading")   	 			  'some short heading
	full_name=Request.QueryString("full_name")  			  'sender full name
	recipient=Request.QueryString("rec_name")  			      'recipient full name
	signature=Request.QueryString("signature")  			  'signature of the sender (absolutely forgettable) 
	email=Request.QueryString("email")          			  'the sender email
	rec_email=Request.QueryString("rec_email")  			  'the reciever email 
    body_text=Request.QueryString("message")      			  'some short message if there is (off course).  
	
%>

<html>
<head>
<meta name=vs_targetSchema content="http://schemas.microsoft.com/intellisense/ie5">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">
<title>S E N D    A    P O S T  C A R D</title>
<meta name="Author" content="Motti Gondabi">
	<style>
<!--
.link {
	color:#333333;
	font-family:helvetica;
	font-weight:bold;
	font-size:21px;
	}
-->
</style>

</head>
<body bgcolor="#ffffcc" leftmargin="0" topmargin="10">
<table cellpadding="0" cellspacing="0" border="0" align="center" width="460">
  <tr>
    <td class="link" align="center" valign="top" height="42"><B><% Response.Write(heading) %></B></td>
  </tr>
  <tr>
 
    <td align="center">
	  <%	
			if  key=1  then Response.write( "<img src=""images/send_a_post_cards/Postcard1b.jpg"" border=""0"" width=""350"" height=""234"">" )
			if  key=2  then Response.write( "<img src=""images/send_a_post_cards/postcard2b.jpg"" border=""0"" width=""350"" height=""234"">" )
	     	if  key=3  then Response.write( "<img src=""images/send_a_post_cards/postcard3b.jpg"" border=""0"" width=""350"" height=""234"">" )
	     	if  key=4  then Response.write( "<img src=""images/send_a_post_cards/postcard4b.jpg"" border=""0"" width=""350"" height=""234"">" )
      %>
	</td>
  </tr>
  <tr> 
    <td align="center"><% if body_text = "" then Response.write("No message was specified") else Response.write(body_text) %></td>
  </tr>
  <tr>
     <td height="10" colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="right"><% Response.write(signature) %></td>
  </tr>
  <tr>
    <td height="20">&nbsp;</td>
  </tr>
  <tr>
   <td align="center">  
        <A HREF="#" onClick="window.close(self); return false;"  onMouseOver="Button0.src='images/backButa.gif'"  onMouseOut="Button0.src='images/backBut.gif'"><img src="images/backBut.gif" name="Button0" border="0" width="64" height="22" onLoad="tempImg=new Image(0,0)"></A>       
        <img src="images/t.gif" border="0" width="10" height="1">
        <A HREF="send_a_post_card_part_three.asp?key=<%=key%>&heading=<%=heading%>&full_name=<%=full_name%>&rec_name=<%=recipient%>&signature=<%=signature%>&email=<%=email%>&rec_email=<%=rec_email%>&message=<%=body_text%>" onMouseOver="Button1.src='images/send_a_post_cards/SendButa.gif'" onMouseOut="Button1.src='images/send_a_post_cards/SendBut.gif'"><img name="Button1" src="images/send_a_post_cards/SendBut.gif" border="0" width="64" height="22" onLoad="tempImg=new Image(0,0)"></A>        
   </td>
  </tr>
    
 	 
</table>



</body>
</html>


   

