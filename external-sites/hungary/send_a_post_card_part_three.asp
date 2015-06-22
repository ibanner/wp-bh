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
    
'Step one: Get all user parameters
	key=Request.QueryString("key")   						  'which picture to send over the mail?
	heading=Request.QueryString("heading")   	 			  'some short heading
	full_name=Request.QueryString("full_name")  			  'sender full name
	recipient=Request.QueryString("rec_name")  			      'recipient full name
	signature=Request.QueryString("signature")  			  'signature of the sender (absolutely forgettable) 
	email=Request.QueryString("email")          			  'the sender email
	rec_email=Request.QueryString("rec_email")  			  'the reciever email 
    body_text=Request.QueryString("message")                  'some short message if there is (off course).  
    
 

	if  key=1  then image="postcard1b.jpg" 
    if  key=2  then image="postcard2b.jpg" 
   	if  key=3  then image="postcard3b.jpg" 
   	if  key=4  then image="postcard4b.jpg" 
    

'Step three: HTML 
  message = "<!DOCTYPE HTML PUBLIC""-//IETF//DTD HTML//EN"">" 
  message = message & "<html>" 
  message = message & "<head>" 
  message = message & "<meta http-equiv=""Content-Type""" 
  message = message & "content=""text/html; charset=windows-1251"">" 
  message = message & "<title>The Land Of Hagar</title>" 
  message = message & "</head>" 
  message = message & "<body bgcolor=""FFFFCC"">" 
  message = message & "<p align=""left"">Dear <b>"& recipient &"</b>,</p>"
  message = message & "An e-mail was sent to you by <B> " & full_name & "</B><p>"           
  message = message & "His message was: <br><font face=""Arial""> " & body_text & "</font><P>"
  message = message & "<IMG SRC=""http://www.bh.org.il/V-Exh/hungary/images/send_a_post_cards/"&image&""" width=""350"" height=""233"" border=""0""><p>"
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
      BhMailer.to = "bhmarkt@post.tau.ac.il" ' Administrator Email
      BhMailer.Subject = "Automatic Email from the " & chr(34) &" Send A Post Cards " & chr(34) &" page ." 
      BhMailer.MailFormat=0
      BhMailer.BodyFormat=0
      BhMailer.Body = BHmessage  
	  BhMailer.Send
      Set BhMailer = nothing

	  response.write ( "<SCRIPT LANGUAGE=""JavaScript"">" & _
		     		   "window.close();"& _
                       "</script>")
					   
end if 

%><body></body></html>