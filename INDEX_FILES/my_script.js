

//alert("sherif");
/* my error handling script */
 onerror=handleErr;
 var txt="";
 function handleErr(msg,url,l)
{
	txt="There was an error on this page.\n\n";
	txt+="Error: " + msg + "\n";
	txt+="URL: " + url + "\n";
	txt+="Line: " + l + "\n\n";
	txt+="Click OK to continue.\n\n";
	alert(txt);
	return true;
}
 /* my error handling script */
 
 
 function byId(e)
  {
        return document.getElementById(e);
  }
function openloader(){
    document.getElementById("result").innerHTML ='<img src="INDEX_FILES/images/loader.gif" height ="30px" class="img-responsive" alt="Uploading...."/>';
}
function closeloader(){
    document.getElementById("result").innerHTML ='';
}
function samedetails1() 
{		
	if(byId('samedusername').checked)
	{		
			/*var value2 = byId('cpassword').value;
			if(value2 !=""){
				var value1 = byId('cusername').value; 
				byId('nusername').value = value1;
				check_existing_username();
			}
			else{
				document.getElementById("result").innerHTML="<p style="+"color:red;"+"> * You must Enter Current Password Before Using This Option</p>";
				document.getElementById("operror").innerHTML="<p style="+"color:red;"+"> * Current Password is Empty</p>";
			}*/
			
			var value1 = byId('cusername').value; 
			byId('nusername').value = value1;
			check_existing_username();
			
	}
	else
	{
		byId('nusername').value = "";
	}
}

function samedetails2() 
{
	if(byId('samepassword').checked)
	{		
			
			var value1 = byId('cpassword').value; 
			byId('npassword').value = value1;
			byId('rpassword').value = value1;
	}
	else
	{
		byId('npassword').value = "";
		byId('rpassword').value = "";
	}
}

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
		{
			 // Firefox, Opera 8.0+, Safari
			xmlHttp=new XMLHttpRequest();
		}
	catch (e)
		{
			// Internet Explorer
			 try
				{
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
			  catch (e)
				{
					xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
		}
			return xmlHttp;
}

function wipeboxeror(vae) 
{
	//alert("sherif");
	if (vae=="1")
	{
		document.getElementById("result").innerHTML="Accepted Characters and symbols for user name ( . _ A - Z a - z 1 - 0 ) and a minnimum of 8 characters.";
	}
	if (vae=="2")
	{
		document.getElementById("nperror").innerHTML="";
		document.getElementById("npretypeerror").innerHTML="";
	}
	if (vae=="3")
	{
		document.getElementById("npretypeerror").innerHTML="";
		document.getElementById("nperror").innerHTML="";
	}
	if (vae=="4")
	{
		document.getElementById("ouerror").innerHTML="";
	}
	if (vae=="5")
	{
		document.getElementById("operror").innerHTML="";
	}
	//alert(vae);
	//for step2 of change details - email box
	if (vae=="20")
	{
		document.getElementById("result").innerHTML="";
	}
	
	//for change_password_email.php of retrieve details - email box
	if (vae=="21")
	{
		document.getElementById("nperror").innerHTML="";
	}
}

//for the email on lost focus too box own
function check_existing_username()
{
	//alert("sherif");
	var juu = document.getElementById('nusername').value;
	openloader();
	if (juu.length==0)
	  { 
		//alert("john");
		document.getElementById("result").innerHTML="<p style="+"color:red;"+">* New User Name Cant Be Empty</p>";
		return;
	  }
	  
		
		/*seperate d string from any where u c @ == the result may contain upto two or more
		but use only the first one bcos in email no @ after @
		drop the rest and create the username with the first one and with the hardcoded @abumail.com*/
		
		
		var x=juu
		var n =juu.split("@");
		if (n.length > 1)
		{
			kk = n[1].toString();
			
			if(kk != "abumail.com"){
				document.getElementById("result").innerHTML= "<p style="+"color:red;"+">* Your Username is incorrect ..Verify and retry</p>";
				return;
				
			}
		}
		
		maillogo = "@abumail.com";
		if(n[0].length < 8){
			document.getElementById("result").innerHTML= "<p style="+"color:red;"+">* Your Username is too Short..IT shoul be 8 characters or more</p>";
			return;
		}
		
	
		juu = n[0].concat(maillogo);
		juu = juu.toLowerCase();
		
		var re=/^(([^<>()[\]\#*%$!?\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (!re.test(juu))
		{
			document.getElementById("result").innerHTML="<p style="+"color:red;"+">* Username is Wrong Contain Unallowed Characters and symbols..Please Verify</p>";
			return;
		}
		
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  alert ("Your browser does not support AJAX!");
	  return;
	 }
	var url="checkmailexist.php";
	//url=url+"?changeemail="+juu+"&oldpasword"+value1;
	url=url+"?changeemail="+juu;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 
function stateChanged() 
{ 
	if (xmlHttp.readyState==4)
	{ 
		closeloader();
		document.getElementById("result").innerHTML="";
		document.getElementById("result").innerHTML=xmlHttp.responseText;
	}
}
function confirm_password()
{
	var  pword = byId('npassword').value;
	var  rpword = byId('rpassword').value;
	if (pword == ""){
		document.getElementById("npretypeerror").innerHTML="<p style="+"color:red;"+">* Password Not Confirmed ...New Password is not entered or confirmed password is empty</p>";
		//document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* Password Not Confirmed ...New Password not Equal with Confirmed Password..Retype</p>";
		return;
	}	
	if (pword != rpword){
		document.getElementById("npretypeerror").innerHTML="<p style="+"color:red;"+">* Password Not Confirmed ...New Password not Equal with Confirmed Password..Retype</p>";
		//document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* Password Not Confirmed ...New Password not Equal with Confirmed Password..Retype</p>";
	}	

}

function confirm_password2()
{
	var  pword = byId('npassword').value;
	var  rpword = byId('rpassword').value;
	
	if ((pword == "") || (rpword == "")){
		document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* Password Box Cant be Empty....Please type Password</p>";
		return;
	}	
	
	if ((pword != rpword)&&(rpword != "")){
		document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* Password Not Confirmed ...New Password not Equal with Confirmed Password..Retype</p>";
		//document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* Password Not Confirmed ...New Password not Equal with Confirmed Password..Retype</p>";
	}	

}

function confirm_password3()
{
	var  pword = byId('npassword').value;
	if (pword == ""){
		document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* New Password Box Cant be Empty....Please type Password</p>";
		return;
	}	
}




//this is for step2 of change details
function check_existing_activation_email()
{
	//alert("sherif");
	var juu = document.getElementById('cusername').value;
	openloader();
	if (juu.length==0)
	  { 
		//alert("john");
		document.getElementById("result").innerHTML="<p style="+"color:red;"+">* Emaill Address Cant Be Empty</p>";
		return;
	  }
	  
		//var re = /^(([^<>()[]\.,;:s@"]+(.[^<>()[]\.,;:s@"]+)*)|(".+"))@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}])|(([a-zA-Z-0-9]+.)+[a-zA-Z]{2,}))$/igm;
		var re=/^(([^<>()[\]\#*%$!?\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (!re.test(juu))
		{
			document.getElementById("result").innerHTML="<p style="+"color:red;"+">* Emaill Address is Wrong ..Please Verify</p>";
			return;
		}
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  alert ("Your browser does not support AJAX!");
	  return;
	 }
	var url="checkmailexist.php";
	url=url+"?checkemail="+juu;
	xmlHttp.onreadystatechange=stateChanged_email;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 
function stateChanged_email() 
{ 
	if (xmlHttp.readyState==4)
	{ 
		closeloader();
		document.getElementById("result").innerHTML="";
		document.getElementById("result").innerHTML=xmlHttp.responseText;
	}
}