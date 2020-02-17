

function submiting(){
   // document.getElementById("finalresult").innerHTML ='<img src="INDEX_FILES/images/loader2.gif" class="img-responsive" alt="Uploading...."/>';
	var fname = document.getElementById('txtfname').value;
	var lname = document.getElementById('txtlname').value;
	var staffid = document.getElementById('txtstaffid').value;
	var dept = document.getElementById('dept').value;
	var course = document.getElementById('course').value;
	var rank = document.getElementById('rank').value;

	if (fname.length==0){
		document.getElementById("ferror").innerHTML="<p style="+"color:red;"+">* Please Enter First Name</p>";
		return false;
	}
	else if (lname.length==0){
		document.getElementById("lerror").innerHTML="<p style="+"color:red;"+">* Please Enter First Last Name</p>";
		return false;
	}
	else if (staffid.length==0){
		document.getElementById("result").innerHTML="<p style="+"color:red;"+">* Please Enter Staff_ID</p>";
		return false;
	}
	else if ((dept.length==0)|| (dept == "Select Department...")){
		document.getElementById("derror").innerHTML="<p style="+"color:red;"+">* Please Select Department</p>";
		return false;
	}
	else if ((course.length==0) || (course=="Select Course...")){
		document.getElementById("cerror").innerHTML="<p style="+"color:red;"+">* Please Select Course</p>";
		return false;
	}
	else if ((rank.length==0) || (rank=="Select Rank....")){
		document.getElementById("rerror").innerHTML="<p style="+"color:red;"+">* Please Select Rank</p>";
		return false;
	}
	else{
		return true;
		 document.getElementById("finalresult").innerHTML ='<img src="INDEX_FILES/images/loader2.gif" class="img-responsive" alt="Uploading...."/>';
	}
}

function openloader(){
    document.getElementById("result2").innerHTML ='<img src="INDEX_FILES/images/loader.gif" class="img-responsive" alt="Uploading...."/>';
}
function openloader2(){
    document.getElementById("result").innerHTML ='<img src="INDEX_FILES/images/loader.gif" class="img-responsive" alt="Uploading...."/>';
}

function closeloader(){
    document.getElementById("result2").innerHTML ='';
	document.getElementById("result").innerHTML ='';
}

function closeloader2(){
    document.getElementById("result").innerHTML ='';
}

function check_existing_regno()
	{
		//Ajax function to check if number is already existing
		var reg = document.getElementById('txtstaffid').value;
		var freg = document.getElementById('staffhide').value;
			if ((reg.length==0) || (freg.length==0))
			  { 
				  document.getElementById("result").innerHTML="<p style="+"color:red;"+">* Staff_ID Cant be Empty</p>";
				  return;
			  }
			  
			  if (reg == freg)
			  { 
				  return;
			  }
			 openloader2(); 
			xmlHttp=GetXmlHttpObject();
			if (xmlHttp==null)
			  {
			  alert ("Your browser does not support AJAX!");
			  return;
			  }
				var reg = document.getElementById('txtstaffid').value;
				var url="checkmailexist.php";
				url= url+"?reg="+reg;
				xmlHttp.onreadystatechange=stateChangedreg;
				xmlHttp.open("GET",url,true);
				xmlHttp.send(null);
	} 

function stateChangedreg() 
{ 
	
	if (xmlHttp.readyState==4)
	{ 
		closeloader2();
		document.getElementById("result").innerHTML="";
		document.getElementById("result").innerHTML=xmlHttp.responseText;
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
		document.getElementById("result").innerHTML="";
	}
	if (vae=="2")
	{
		document.getElementById("lerror").innerHTML="";
	}
	if (vae=="3")
	{
		document.getElementById("ferror").innerHTML="";
	}
	if (vae=="4")
	{
		document.getElementById("rerror").innerHTML="";
	}
	//alert(vae);
}



/*the second form editing

this downward is for the student details 
submittion as upward is for staff detaills submitions

*/

function submiting2(){
   // document.getElementById("finalresult").innerHTML ='<img src="INDEX_FILES/images/loader2.gif" class="img-responsive" alt="Uploading...."/>';
	
	var fname1 = document.getElementById('txtfname1').value;
	var lname1 = document.getElementById('txtlname1').value;
	var staffid1 = document.getElementById('txtstaffid1').value;
	var dept1= document.getElementById('dept2').value;
	var course1 = document.getElementById('course2').value;
	var rank1 = document.getElementById('rank1').value;
	var level = document.getElementById('level').value;
	//alert(rank1);
	if (fname1.length==0){
		document.getElementById("ferror1").innerHTML="<p style="+"color:red;"+">* Please Enter First Name</p>";
		return false;
	}
	else if (lname1.length==0){
		document.getElementById("lerror1").innerHTML="<p style="+"color:red;"+">* Please Enter First Last Name</p>";
		return false;
	}
	else if (staffid1.length==0){
		document.getElementById("result2").innerHTML="<p style="+"color:red;"+">* Please Enter Staff_ID</p>";
		return false;
	}
	else if ((dept1.length==0)|| (dept1 == "Select Department...")){
		document.getElementById("derror1").innerHTML="<p style="+"color:red;"+">* Please Select Department</p>";
		return false;
	}
	else if ((course1.length==0) || (course1=="Select Course...")){
		document.getElementById("cerror1").innerHTML="<p style="+"color:red;"+">* Please Select Course</p>";
		return false;
	}
	else if ((level.length==0) || (level=="Select Level...")){
		document.getElementById("leerror1").innerHTML="<p style="+"color:red;"+">* Please Select Level</p>";
		return false;
	}
	else if ((rank1.length==0) || (rank1=="Select Rank.....")){
		document.getElementById("rerror1").innerHTML="<p style="+"color:red;"+">* Please Select Rank</p>";
		return false;
	}
	else{
		return true;
		 document.getElementById("finalresult2").innerHTML ='<img src="INDEX_FILES/images/loader2.gif" class="img-responsive" alt="Uploading...."/>';
	}
}

function openloader(){
    document.getElementById("result2").innerHTML ='<img src="INDEX_FILES/images/loader.gif" height ="30px" class="img-responsive" alt="Uploading...."/>';
}
function openloader2(){
    document.getElementById("result").innerHTML ='<img src="INDEX_FILES/images/loader.gif" height ="30px" class="img-responsive" alt="Uploading...."/>';
}

function closeloader(){
    document.getElementById("result2").innerHTML ='';
}

function closeloader2(){
    document.getElementById("result").innerHTML ='';
}

//for the email on lost focus too box own
function check_existing_email()
{
	//alert("sherif");
	//alert("sherif");
	var juu = document.getElementById('txtstaffid1').value;
	var freg2 = document.getElementById('staffhide1').value;
	//alert(freg2);
	
	if ((juu.length==0) || (freg2.length==0))
	  { 
		document.getElementById("result2").innerHTML="Student Reg_No gg";
		return;
	  }
	  if (juu == freg2)
		{ 
			  return;
		}
		openloader();
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
		closeloader();
		document.getElementById("result2").innerHTML="";
		document.getElementById("result2").innerHTML=xmlHttp.responseText;
	}
}

function wipeboxeror2(vae) 
{
	
	if (vae=="1")
	{
		document.getElementById("result2").innerHTML="";
	}
	if (vae=="2")
	{
		document.getElementById("lerror1").innerHTML="";
	}
	if (vae=="3")
	{
		document.getElementById("ferror1").innerHTML="";
	}
	if (vae=="4")
	{
		document.getElementById("leerror1").innerHTML="";
	}
	if (vae=="5")
	{
		document.getElementById("rerror1").innerHTML="";
	}
}

