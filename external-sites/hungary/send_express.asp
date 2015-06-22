<html>

<head>
<meta name=vs_targetSchema content="http://schemas.microsoft.com/intellisense/ie5">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1255">
<%@ LANGUAGE="VBSCRIPT" %>
</head>

<%  
    Dim Mailer
	Dim BhMailer
	Dim image
    Dim sent
	Dim notSent
	Dim bOK
	
	bOK = true
'Step one: Get all user parameters
	key=Request.QueryString("key")   						  'which picture to send over the mail?
	heading=Request.QueryString("heading")   	 			  'some short heading
	full_name=Request.QueryString("full_name")  			  'sender full name
	recipient=Request.QueryString("rec_name")  			      'recipient full name
	signature=Request.QueryString("signature")  			  'signature of the sender (absolutely forgettable) 
	email=Request.QueryString("email")          			  'the sender email
	rec_email=Request.QueryString("rec_email")  			  'the receiver email 
    body_text=Request.QueryString("message")                  'some short message if there is (off course).  
    
 

	if  key=1  then image="postcard1b.jpg" 
    if  key=2  then image="postcard2b.jpg" 
   	if  key=3  then image="postcard3b.jpg" 
   	if  key=4  then image="postcard4b.jpg" 
    

'Step three: HTML 
  message = "<!DOCTYPE HTML PUBLIC""-//IETF//DTD HTML//EN"">" 
  message = message & "<html>" 
  message = message & "<head>" 
  message = message & "<meta http-equiv=""Content-Type"" content=""text/html; charset=windows-1251"">" 
  message = message & "<title>The Land Of Hagar</title>" 
  message = message & "</head>" 
  message = message & "<body bgcolor=""FFFFCC"">" 
  message = message & "<p align=""left"">Dear <b>"& recipient &"</b>,</p>"
  message = message & "An e-mail was sent to you by <B> " & full_name & "</B><p>"           
  message = message & "His message was: <br><font face=""Arial""> " & body_text & "</font><P>"
  message = message & "<IMG SRC=""http://www.bh.org.il/V-Exh/hungary/images/send_a_post_cards/postcard4b.jpg"" width=""350"" height=""233"" border=""0""><p>"
  message = message & "<br>" 
  message = message & "</body>"
  message = message & "</html>" 
						  

BHmessage =               "<html><head>" &_
		                  "<meta http-equiv='Content-Type' CONTENT='text/html; charset=windows-1255'></head>" & _
						  "<body>"& _
						  "<p align='left'>Sender Address: <b>" & email & "</b></P>" &_
						  "has sent an email to: <B>" & rec_email & "</b><P>" &_
						  "with picture No." & key &_
						  "</body>" &_
						  "</html>"
  
         
'Step four: Create the ASP Mail Object and set attributes 
if (email<>"") AND (rec_email<>"")  then

      'For The client side
      Set Mailer = Server.CreateObject("CDONTS.newMail")
      Mailer.From = email
      Mailer.to = rec_email
      Mailer.Subject = heading 
      Mailer.MailFormat=0
      Mailer.BodyFormat=0
      Mailer.Body = message
      Mailer.Send
      Set Mailer = nothing
 

      'Now inform the BH administrator  
      Set BhMailer = Server.CreateObject("CDONTS.newMail")
      BhMailer.From = "Administrator@bh.com"  
      BhMailer.to = "bhmarkt@post.ac.il" ' Administrator Email  
      BhMailer.Subject = "Automatic Email from the " & chr(34) &" Send A Post Cards " & chr(34) &" page ." 
      BhMailer.MailFormat=0
      BhMailer.BodyFormat=0
      BhMailer.Body = BHmessage  
	  BhMailer.Send
      Set BhMailer = nothing

  sent = "<!DOCTYPE HTML PUBLIC""-//IETF//DTD HTML//EN"">" 
  sent = sent & "<html>" 
  sent = sent & "<head>" 
  sent = sent & "<meta http-equiv=""Content-Type"" content=""text/html; charset=iso-8859-1"">" 
  sent = sent & "<title>Message Box Alert</title>" 
  sent = sent & "</head>" 
  sent = sent & "<body bgcolor=""FFFFCC"">" 
  sent = sent & "<p>"
  sent = sent & "<P align=""center"">Your Email was sent successfully !</p> "
  sent = sent & "<p>"
  sent = sent & "<p align=""center""><form><input onClick=""window.close()"" type=""button"" value=""Close""></form></p>"
  sent = sent & "</body>" 
  sent = sent & "</html>" 

  notSent = "<!DOCTYPE HTML PUBLIC""-//IETF//DTD HTML//EN"">" 
  notSent = notSent & "<html>" 
  notSent = notSent & "<head>" 
  notSent = notSent & "<meta http-equiv=""Content-Type"" content=""text/html; charset=iso-8859-1"">" 
  notSent = notSent & "<title>Message Box Alert</title>" 
  notSent = notSent & "</head>" 
  notSent = notSent & "<body bgcolor=""FFFFCC"">" 
  notSent = notSent & "<p>"
  notSent = notSent & "<P align=""center"">Email Failure !"
  notSent = notSent & "<P align=""center""><font face=""Arial"" size=""-2"" color=""#000000"">Some error occurs when trying to send message</font>"
  notSent = notSent & "<p align=""center""><form><input onClick=""window.close()"" type=""button"" value=""Close""></form></p>"
  notSent = notSent & "</body>" 
  notSent = notSent & "</html>" 

  
  'check if Email was sent correctly
   If err.number <> 0 Then bOK = False
   if bOK then
		  response.write (sent)
   else
  		  response.write (notSent)	  
   end if	
	  					   
end if 

%><body></body></html>