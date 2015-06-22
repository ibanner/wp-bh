/* author: Motti Gondabi */
/* creation date: 29/07/01 */
//*****************************************************************************************
//++++++++THIS FUNCTION CHECKING THE DATA IN THE "CONTACT US" FORM+++++++++++++
function checkData(index)
{
  
  var width, height
  var s1;
  var s2;
  var flag=true;			//if there is any mistake in the FORM the value will turn to false. 
  var full_flag=true;      //if there is a mistake in the FULL NAME the value will turn to false.
  var email_flag=true;      //if there is a mistake in the E-MAIL the value will turn to false.
  var rec_email_flag=true;      //if there is a mistake in the RECIPIENT E-MAIL the value will turn to false.
  var h=self.document.all.form1.heading.value;//			heading   =   h
  var fn=self.document.all.form1.full_name.value;//			full name   =   fn
  var rn=self.document.all.form1.rec_name.value;//			recipient name   =   rn
  var s=self.document.all.form1.signature.value;//			signature  =   s
  var e=self.document.all.form1.email.value;//			    e-mail   =   e
  var re=self.document.all.form1.rec_email.value;//         recipient e-mail =   re
  var m=self.document.all.form1.message.value;//            message  =   m
  var key=self.document.all.form1.key.value;//          which picture = key            
  // alert(fi+la+co+ad+ph+fa+e);
 
 
 //++++++++++++++++++CHECKING THE HEADING++++++++++(H)++++++++++++++++++++++++++++   
 //++++++++++++++++++++++++++++(MORE THAN 2 KEYS)+++++++++++++++++++++++++++++++++++ 
 /*if (h!="")
 {
   if(h.length<4)                          //if there are less then 2 keys in the company name THEN
    {
   	  alert("please fill the HEADING field ,thank you.");
  	  flag=false;
      document.form1.heading.value=""; 
      document.form1.heading.focus(); 
	  return;
    }  
 } else {flag=false; }
 //+++++++++++++++CHECKING THE FULL NAME+++++++++++++++(FN)++++++++++++++
 *///++++++++++++++++(MORE THAN 2 KEYS & NO NUMBERS)+++++++++++++++++++++
/*if (fn!="")
{ 
 if(fn.length<2)           //if there are less then 2 keys in the first THEN
   {
     full_flag=false;                   //the first name is wrong
   }
*/
 for(x=0;x<=fn.length;x++)               //here i check EVERY key in the full name
    {
      if(fn.charAt(x)%fn.charAt(x)==0)   //if there is a number in the full name
       {
         full_flag=false;               //the first name is wrong
       }
    }
 if(full_flag==false)                   //if there is one mistake in the first name THEN
  {
    alert("Please enter your full name, thank you.");
    flag=false;  
    document.form1.full_name.value="";
	document.form1.full_name.focus();
    return;        
  } 
//}   

//++++++++++++++++++CHECKING THE RECIPIENT NAME++++++++++(RN)++++++++++++++++++++++++++++   
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ 
/*if (rn!="")
{ 
 if(rn.length<2)                          //if there are less then 5 keys in the address THEN
   {
	alert("please enter a FULL RECIPIENT NAME, thank you.");
	flag=false;                          //the whole form is wrong  AND
    document.form1.rec_name.value="";     //the ADDRESS field is deleted.
    document.form1.rec_name.focus();
	return;
   }
}else {flag=false; }
 //+++++++CHECKING THE SIGNATURE++++(S)++++++++++++++++++++++++++++++++++++++++++++

 if (s!="")
 {
  if (s.length<2)
     {
	   alert("please enter your private SIGNATURE, thank you.");
       flag=false;                          //the whole form is wrong  AND
       document.form1.signature.value="";     //the SIGNATURE field is deleted.
       document.form1.signature.focus(); 
  	   return;
     }
 }else {flag=false; }
 */
 
 
 
 
 
 //+++++++CHECKING THE E-MAIL++++(E) ++CHECKING THE E-MAIL++++(E) ++CHECKING THE E-MAIL++++(E) 
 
 if(e=="")
 {
  
  //flag=false;//with this mistake the user will not get into the store..
     email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
 }

//HERE I CHECK THE LOCATION OF THE "@" 
if (e.indexOf("@")<1&&e!="")
	    {
		//IF THERE IS NO @@@@@@ AT ALL.
if (e.indexOf("@")==-1&&e!="")
	    {
		 
         //flag=false;//with this mistake the user will not get into the store..
         email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
		//IF THERE IS A @@@@ THEN IT'S IN THE FIRST PLACE(WRONG PLACE TOO).
else
		{
        ///"ALERT" TO THE USER.
		 
        // flag=false;//with this mistake the user will not get into the store..
		 email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		 }
        }
		
//HERE I CHECK IF THE @@@ APPEAR AS THE LAST KEY OF THE ADDRESS.
if (e.lastIndexOf("@")==e.length-1&&e!="")
        {
		//IF THE @@@ IS THE LAST KEY ,"ALERT".
	     
         //flag=false;//with this mistake the user will not get into the store..
		 email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
        }
//HERE I CHECK IF THE POINT APPEAR AS THE FIRST KEY IN THE ADDRESS.

if (e.indexOf(".")==0&&e!="")
        {
		//IF THE POINT IS THE FIRST KEY ,"ALERT".
	     
        // flag=false;//with this mistake the user will not get into the store..
		 email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
        }

//HERE I CHECK IF THE POINT APPEAR AS THE LAST KEY IN THE ADDRESS.
if (e.lastIndexOf(".")==(e.length-1)&&e!="")
        {
		//IF THE POINT IS THE LAST KEY ,"ALERT".
	     
        // flag=false;//with this mistake the user will not get into the store..
		 email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
        }
//HERE I CHECK IF THERE IS ONLY ONE '@' IN THE E-MAIL.
if(e.indexOf("@")!=e.lastIndexOf("@")&&e!="")
        {
		 
		 //flag=false;//with this mistake the user will not get into the store..
		 email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
	    }
		//HERE I CHECK IF THE '@' AND THE '.' ARE CLOSE TO EACH OTHER, 
      //WITH THE CONDITION THAT THERE IS ONLY ONE'@'.
     else

	 {
//IF THE '@' IS BEFORE THE FIRST POINT OR AFTER THE FIRST POINT(WITH NO SPACE BETWEEN).
 
	  if(e.indexOf("@")-e.indexOf(".")==1||e.indexOf("@")-e.indexOf(".")==-1&&e!="")
	    {
		 
		  //flag=false;//with this mistake the user will not get into the store..
		  email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
//IF THE '@' IS BEFORE THE LAST POINT OR AFTER THE LAST POINT(WITH NO SPACE BETWEEN).
	  if(e.indexOf("@")>e.indexOf(".")||e.indexOf("@")>e.lastIndexOf(".")&&e!="")
	   {
	    
        //flag=false;//with this mistake the user will not get into the store..
		email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
	   }
	 }

//HERE I CHECK IF THERE IS A POINT IN THE ADDRESS.
if (e.indexOf(".")==-1&&e!="")
        {
		 
		// flag=false;//with this mistake the user will not get into the store..
		 email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
//HERE I CHECK IF THERE IS ANY SPACE BETWEEN THE KEYS.
if (e.indexOf(" ")!=-1&&e!="")
	    {
		 
		 //flag=false;//with this mistake the user will not get into the store..
		 email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
//after i finished to check all the e mail i'm checking, if the value of "email_flag" isn't "true" then i know that the was a mistake in the e mail....
if(email_flag==false)
	{
	 alert("please enter your e-mail, thank you.");
	 flag=false;
	 document.form1.email.value="";
	 document.form1.email.focus();
	 return;
	}
	
	
	
//+++++++CHECKING THE RECIPIENT E-MAIL++++(RE) ++CHECKING THE E-MAIL++++(RE) ++CHECKING THE E-MAIL++++(RE) 
 
  if(re=="")
 {
  
  //flag=false;//with this mistake the user will not get into the store..
     rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
 }

//HERE I CHECK THE LOCATION OF THE "@" 
if (re.indexOf("@")<1&&re!="")
	    {
		//IF THERE IS NO @@@@@@ AT ALL.
if (re.indexOf("@")==-1&&re!="")
	    {
		 
         //flag=false;//with this mistake the user will not get into the store..
         rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
		//IF THERE IS A @@@@ THEN IT'S IN THE FIRST PLACE(WRONG PLACE TOO).
else
		{
        ///"ALERT" TO THE USER.
		 
        // flag=false;//with this mistake the user will not get into the store..
		 rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		 }
        }
		
//HERE I CHECK IF THE @@@ APPEAR AS THE LAST KEY OF THE ADDRESS.
if (re.lastIndexOf("@")==re.length-1&&re!="")
        {
		//IF THE @@@ IS THE LAST KEY ,"ALERT".
	     
         //flag=false;//with this mistake the user will not get into the store..
		 rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
        }
//HERE I CHECK IF THE POINT APPEAR AS THE FIRST KEY IN THE ADDRESS.

if (re.indexOf(".")==0&&re!="")
        {
		//IF THE POINT IS THE FIRST KEY ,"ALERT".
	     
        // flag=false;//with this mistake the user will not get into the store..
		 rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
        }

//HERE I CHECK IF THE POINT APPEAR AS THE LAST KEY IN THE ADDRESS.
if (re.lastIndexOf(".")==(re.length-1)&&re!="")
        {
		//IF THE POINT IS THE LAST KEY ,"ALERT".
		     
        // flag=false;//with this mistake the user will not get into the store..
		 rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
        }
//HERE I CHECK IF THERE IS ONLY ONE '@' IN THE E-MAIL.
if(re.indexOf("@")!=re.lastIndexOf("@")&&re!="")
        {	 
		 //flag=false;//with this mistake the user will not get into the store..
		 rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
	    }
		//HERE I CHECK IF THE '@' AND THE '.' ARE CLOSE TO EACH OTHER, 
      //WITH THE CONDITION THAT THERE IS ONLY ONE'@'.
     else

	 {
//IF THE '@' IS BEFORE THE FIRST POINT OR AFTER THE FIRST POINT(WITH NO SPACE BETWEEN).
 
	  if(re.indexOf("@")-re.indexOf(".")==1||re.indexOf("@")-re.indexOf(".")==-1&&re!="")
	    {	 
		  //flag=false;//with this mistake the user will not get into the store..
		  rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
//IF THE '@' IS BEFORE THE LAST POINT OR AFTER THE LAST POINT(WITH NO SPACE BETWEEN).
	  if(re.indexOf("@")>re.indexOf(".")||re.indexOf("@")>re.lastIndexOf(".")&&re!="")
	   {    
        //flag=false;//with this mistake the user will not get into the store..
		rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
	   }
	 }

//HERE I CHECK IF THERE IS A POINT IN THE ADDRESS.
if (re.indexOf(".")==-1&&re!="")
        {	 
		// flag=false;//with this mistake the user will not get into the store..
		 rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
//HERE I CHECK IF THERE IS ANY SPACE BETWEEN THE KEYS.
if (re.indexOf(" ")!=-1&&re!="")
	    {	 
		 //flag=false;//with this mistake the user will not get into the store..
		 rec_email_flag=false;//i'll use this flag when the script will finish all the checks for the e mail.
		}
//HERE I CHECK IF THE MESSAGE FIELD IS CARY MORE THAN 50 CHARACTERS
     charNO = self.document.form1.message.value.length;
	 meString = self.document.form1.message.value;
     if (charNO >50)  {  
	                      alert("Message can not be more than 50 characters, thank you.");
						  self.document.form1.message.value = meString.substring(0,50);  
     }

 
//after i finished to check all the e mail i'm checking, if the value of "email_flag" isn't "true" then i know that the was a mistake in the e mail....
if (rec_email_flag==false)
	{
	 alert("please enter the recipient e-mail, thank you.");
	 flag=false;
	 document.form1.rec_email.value="";
	 document.form1.rec_email.focus();
	 return;
	}
	

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++	

 if (flag!=true)
 {
	 alert("The form is either complete or not correctly filled!");
 }
  else	
  {	
  	    if (index == 0) { 
		            		 width = screen.width/2 - 150;
							 height = screen.height/2 - 130;							
	                         s2 = "send_express.asp?key="+key+"&heading="+h+"&full_name="+fn+"&rec_name="+rn+"&signature="+s+"&email="+e+"&rec_email="+re+"&message="+m;
		                     parent.window.open(s2,"express","left="+width+", top="+height+", width=300, height=150");
						}
        else if (index == 1) { 
	        					s1="send_a_post_card_part_four.asp?key="+key+"&heading="+h+"&full_name="+fn+"&rec_name="+rn+"&signature="+s+"&email="+e+"&rec_email="+re+"&message="+m;
								parent.window.open(s1,"send_a_post_card","left=5, top=5, width=500, height=500");		  
		                     }
 
  }
    
}//+++++++++++END OF CHECKING THE DATA IN THE "SEND A POST CARDS" PAGE++++++++++++++++++++++++
 
 
function notDown()
 {
 	x.focus()
 }
  

  
 
  
