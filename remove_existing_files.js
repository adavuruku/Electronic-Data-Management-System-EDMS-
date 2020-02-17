//works with reload_the_files.php and create_normal_Mail.php
function byId(e)
{
    return document.getElementById(e);
}

//loads contact auto
function load_the_user()
{
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  alert ("Your browser does not support AJAX!");
	  return;
	 }
	
	var submit3 = "sumit3";
	var search_val = byId('receiver').value;
	var str =search_val;
	var n=str.split(",");
	var q = n.length;
	var send = q - 1;
	var search_val = n[send];
	//alert(search_val);
	var url="reload_the_files.php";
	parameters="submit4="+submit3+"&search="+search_val;
    xmlHttp.onreadystatechange=stateChanged2;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlHttp.send(parameters);
}
function stateChanged2() 
{ 
	if (xmlHttp.readyState==1)
	{ 
		document.getElementById('display_all').innerHTML="<img src='INDEX_FILES/images/loader.gif' border='0'/>";
	}
	if (xmlHttp.readyState==2)
	{ 
		document.getElementById('display_all').innerHTML="<img src='INDEX_FILES/images/loader.gif' border='0'/>";
	}
	if (xmlHttp.readyState==3)
	{ 
		document.getElementById('display_all').innerHTML="<img src='INDEX_FILES/images/loader.gif' border='0'/>";
	}
	if (xmlHttp.readyState==4)
	{ 
		
		//alert(xmlHttp.responseText);
		document.getElementById('display_all').innerHTML="";
		document.getElementById('display_all').innerHTML=xmlHttp.responseText;
	}
}
function submit_nd_Admission(p)
{
	var path_to_remove = p;
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
	  alert ("Your browser does not support AJAX!");
	  return;
	 }
	
	var submit3 = "sumit3";
	var url="reload_the_files.php";
	parameters="submit="+submit3+"&file="+path_to_remove;
    xmlHttp.onreadystatechange=stateChanged;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlHttp.send(parameters);
	
} 
function stateChanged() 
{ 

	if (xmlHttp.readyState==4)
	{ 
		document.getElementById('oldones').innerHTML="";
		document.getElementById('oldones').innerHTML=xmlHttp.responseText;
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
function add_to_list(address){
var current_val = byId('receiver').value;
var str =current_val;
var n=str.split(",");
var q = n.length;
var send = q - 1;
var search_val ="";
for (x=0; x<send; x++){
	if(search_val==""){
		search_val = n[x];
	}
	else
	{
		search_val = search_val + "," + n[x];
	}
	
}
var new_value ="";
if(search_val==""){
	 new_value = address;
}
else
	{
		 new_value = search_val + "," + address;
	}
document.getElementById('receiver').value="";
document.getElementById('receiver').value = new_value;
document.getElementById('display_all').innerHTML="";
}
function clear_display(){
document.getElementById('display_all').innerHTML="";
}