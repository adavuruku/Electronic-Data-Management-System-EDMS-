
 function byId(e)
  {
        return document.getElementById(e);
  }
function confirm_password2()
{
	var  pword = byId('npassword').value;
	var  rpword = byId('rpassword').value;
	if ((pword == "") || (rpword == "")){
		document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* Password Box Cant be Empty.... type Password</p>";
		document.getElementById("rperror").innerHTML="<p style="+"color:red;"+">* Password Box Cant be Empty.... type Password</p>";
		return;
	}	
	
	if ((pword != rpword)&&(rpword != "")){
		document.getElementById("nperror").innerHTML="<p style="+"color:red;"+">* New Password not Equal with Confirmed Password..</p>";
		document.getElementById("rperror").innerHTML="<p style="+"color:red;"+">* New Password not Equal with Confirmed Password.</p>";
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

function wipeboxeror(vae) 
{
	//for change_password_email.php of retrieve details - email box
	if (vae=="21")
	{
		document.getElementById("nperror").innerHTML="";
		document.getElementById("rperror").innerHTML="";
	}
}
