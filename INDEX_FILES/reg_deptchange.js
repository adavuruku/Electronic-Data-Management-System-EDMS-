
function byId(e)
    {
        return document.getElementById(e);
    }

function schoolComboChange(h)
    {
		
		var combo3="";
		var combo4="";
		
		if(h =="1") {
		var combo3 = byId('dept');
			var combo4 = byId('course');
			document.getElementById("derror").innerHTML="";
			document.getElementById("cerror").innerHTML="";
		}
		
		if (h =="2") {
		var combo3 = byId('dept2');
			var combo4 = byId('course2');
			document.getElementById("derror1").innerHTML="";
			document.getElementById("cerror1").innerHTML="";
		}
			
		
     //   document.getElementById("err9").innerHTML="";
      //  document.firstform.school.style.border="0px solid #ff0000";
        
	  //alert(combo3.value);

        emptydeptCombo(combo4);
        switch(combo3.value)
        {
            case 'Select Department...':  addDeptOption(combo4,  'Select Course...', 'Select Course...');
                        break;
            case 'Physics':  addDeptOption(combo4, 'Physics', 'Physics');
                        addDeptOption(combo4, 'Advanced Physics', 'Advanced Physics');
                        addDeptOption(combo4, 'Industrial Physics', 'Industrial Physics');
                        break;
            case 'Mathematics':  addDeptOption(combo4, 'Computer Science', 'Computer Science');
                        addDeptOption(combo4, 'Statistics', 'Statistics');
                        addDeptOption(combo4, 'Mathematics', 'Mathematics');
                        break;
            case 'Chemistry':  addDeptOption(combo4, 'Chemistry', 'Chemistry');
                        addDeptOption(combo4, 'Industrial Chemistry', 'Industrial Chemistry');
                        break;
			case 'Geography':  addDeptOption(combo4, 'Geography', 'Geography');
                        break;
			case 'Geology':  addDeptOption(combo4, 'Geology', 'Geology');
                        break;
			case 'Bilogical Science':  addDeptOption(combo4, 'Bilogical Science', 'Bilogical Science');
                        break;
			case 'Biochemistry':  addDeptOption(combo4, 'Biochemistry', 'Biochemistry');
                        break;
			case 'Microbiology':  addDeptOption(combo4, 'Microbiology', 'Microbiology');
                        break;
        }
        //cityComboChange();
    }
    function emptydeptCombo(e)
    {
        e.innerHTML = '';
    }
 
    function addDeptOption(combo, val, txt)
    {
        var option = document.createElement('option');
        option.value = val;
        option.title = txt;
        option.appendChild(document.createTextNode(txt));
        combo.appendChild(option);
    }

	
	function check_existing_regno()
	{
		//Ajax function to check if number is already existing
		//alert("sherif");
		var reg = document.getElementById('txtregno').value;
		//alert(reg);
			if (reg.length==0)
			  { 
				//alert("john");
				  document.getElementById("result1").innerHTML="Reg no Cant be Empty";
				  return;
			  }
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null)
			  {
			  alert ("Your browser does not support AJAX!");
			  return;
			  }
				var reg = byId('txtregno').value;
				var url="checkmailexist.php";
				url= url+"?reg="+reg;
				xmlHttp.onreadystatechange=stateChangedreg;
				xmlHttp.open("GET",url,true);
				xmlHttp.send(null);
	} 

//for the email on lost focus too box ownt
function check_existing_email()
{
	//alert("sherif");
	var juu = document.getElementById('emailaddres').value;
	if (juu.length==0)
	  { 
	  //alert("john");
		document.getElementById("result2").innerHTML="Email box Cant be Empty";
		return;
	  }
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  alert ("Your browser does not support AJAX!");
	  return;
	 }
 
	var url="checkmailexist.php";
	url=url+"?email="+juu;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 


function stateChanged() 
{ 
	
	if (xmlHttp.readyState==4)
	{ 
		document.getElementById("result2").innerHTML="";
		document.getElementById("result2").innerHTML=xmlHttp.responseText;
	}
}


function stateChangedreg() 
{ 
	if (xmlHttp.readyState==4)
	{ 
		document.getElementById("result1").innerHTML="";
		document.getElementById("result1").innerHTML=xmlHttp.responseText;
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
	if (vae=="1")
	{
		document.getElementById("result1").innerHTML="";
	}
	if (vae=="2")
	{
		document.getElementById("result2").innerHTML="";
	}
	if (vae=="3")
	{
		document.getElementById("result3").innerHTML="";
	}
	//alert(vae);
}

function check_password_match() 
{
	var pas1 = document.getElementById("loginpass").value;
	var pas2 = document.getElementById("confirmpass").value;
	
	if (pas1 != pas2) 
	{
		document.getElementById("result3").innerHTML="Confirmed Password not equall to real password";
	}
	else
	{
		document.getElementById("result3").innerHTML="";
	}
}