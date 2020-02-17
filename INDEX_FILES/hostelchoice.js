var xmlHttp
function emptyerrors(){
    document.getElementById("error").innerHTML='';
    document.getElementById("error2").value ='';
   /* document.getElementById("errorlink").innerHTML='';*/
}
/**************************************** populating the available block with ajax after selecting hostel*************************/
//for the Hostel change
function block(sherif,kkk)
{
    changehostel();
    emptyerrors();
        /*alert(kkk);*/
    if (sherif=="--Select Hostel--")
        {
            changehostel();
            emptyerrors();
            return;
       }
            xmlHttp=GetXmlHttpObject();
    if (xmlHttp==null)
       {
            alert ("Your browser does not support AJAX!");
            return;
       }
            /*&"?status="+kkk*/
            var url="populate.php";
            url=url+"?name="+sherif;
            xmlHttp.onreadystatechange=hostelChanged;
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
}

/* when hostel is change*/
function hostelChanged() 
{ 
if (xmlHttp.readyState==4)
{ 
document.getElementById("blockSelect").innerHTML="";
document.getElementById("blockSelect").innerHTML=xmlHttp.responseText;
}
}

/**************************************** populating the available rooms with ajax after changing the block***************************/
//for the Block change
function room(rahimat)
{
    changeblock();
   /* alert(jss);*/
   /*alert(rahimat);*/
    if (rahimat=="--Select Block--")
      {
            changeblock();
            return;
      }
        xmlHttp=GetXmlHttpObject();
        if (xmlHttp==null)
          {
            alert ("Your browser does not support AJAX!");
            return;
          }
            /*&"?status="+kkk*/
            var jss = document.getElementById("hostelSelect").value;
           var url="populate.php";
           url=url+"?rahimatname="+rahimat+"&hostelchooze="+jss;
           xmlHttp.onreadystatechange=blockChanged;
           xmlHttp.open("GET",url,true);
           xmlHttp.send(null);
}

/* when block is change*/
function blockChanged() 
{ 
if (xmlHttp.readyState==4)
{ 
document.getElementById("roomSelect").innerHTML="";
document.getElementById("roomSelect").innerHTML=xmlHttp.responseText;
}
}

/**************************************** populating the availlable space with ajax when the room is changed*********************************************************************/

//for the Room change
function space(rahimat2)
{
    changeroom();
   /* alert(jss);*/
   /*alert(rahimat);*/
    if (rahimat2=="--Select Room--")
      {
           changeroom();
            return;
      }
        xmlHttp=GetXmlHttpObject();
        if (xmlHttp==null)
          {
            alert ("Your browser does not support AJAX!");
            return;
          }
            /*&"?status="+kkk*/
            var jss1 = document.getElementById("hostelSelect").value;
            var jss2 = document.getElementById("blockSelect").value;
            var url="populate.php";
           url=url+"?khadijat="+rahimat2+"&nuhu="+jss1+"&muluk="+jss2;
           xmlHttp.onreadystatechange=roomChanged;
           xmlHttp.open("GET",url,true);
           xmlHttp.send(null);
}

/* when Room is change*/
function roomChanged() 
{ 
if (xmlHttp.readyState==4)
{ 
document.getElementById("spaceSelect").innerHTML="";
document.getElementById("spaceSelect").innerHTML=xmlHttp.responseText;
}
}


/**************************************** all the functions use this to check for browser ajax support*********************************************************************/
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

/**************************************** all the functions ends here*********************************************************************/


/**************************************** these empty each succesful combo box whenever change is beign made*********************************************************************/

/*when hostel is changed it empty block space room*/
function changehostel()
{
    /*** empty the Block room space when one change the hostel ****/
    document.getElementById("blockSelect").innerHTML='';
    document.getElementById("roomSelect").innerHTML='';
    document.getElementById("spaceSelect").innerHTML='';
    
    
    /*** Add a default options to them (Block room space) ****/
    var combo = document.getElementById("blockSelect");
    var option = document.createElement('option');
    option.value = "--Select Block--";
    option.title = "--Select Block--";
    option.appendChild(document.createTextNode("--Select Block--"));
    combo.appendChild(option);
    
    var combo = document.getElementById("roomSelect");
    var option = document.createElement('option');
    option.value = "--Select Room--";
    option.title = "--Select Room--";
    option.appendChild(document.createTextNode("--Select Room--"));
    combo.appendChild(option);
    
    var combo = document.getElementById("spaceSelect");
    var option = document.createElement('option');
    option.value = "--Select Space--";
    option.title = "--Select Space--";
    option.appendChild(document.createTextNode("--Select Space--"));
    combo.appendChild(option);          
}

/*when block is changed it empty space and room*/
function changeblock()
{
    /*** empty the room and bedspace when one change the Block ****/
    document.getElementById("roomSelect").innerHTML='';
    document.getElementById("spaceSelect").innerHTML='';
    
    /*** Add a default options to them (room and bedspace) ****/
    var combo = document.getElementById("roomSelect");
    var option = document.createElement('option');
    option.value = "--Select Room--";
    option.title = "--Select Room--";
    option.appendChild(document.createTextNode("--Select Room--"));
    combo.appendChild(option);
    
    var combo = document.getElementById("spaceSelect");
    var option = document.createElement('option');
    option.value = "--Select Space--";
    option.title = "--Select Space--";
    option.appendChild(document.createTextNode("--Select Space--"));
    combo.appendChild(option);          
}

/*when room is changed it empty space*/
function changeroom()
{
    /*** empty the bedspace when one change the Room ****/
    document.getElementById("spaceSelect").innerHTML='';
    
    /*** Add a default options to them ( bedspace) ****/
    var combo = document.getElementById("spaceSelect");
    var option = document.createElement('option');
    option.value = "--Select Space--";
    option.title = "--Select Space--";
    option.appendChild(document.createTextNode("--Select Space--"));
    combo.appendChild(option);          
}
/**************************************** All that ends here*********************************************************************/


/**************************************** these submit the form after all the sellections*********************************************************************/

/*these check for any error existence in all the selected fields*/
function submiting(){
    var b = document.getElementById("blockSelect").value;
   var r = document.getElementById("roomSelect").value;
    var s = document.getElementById("spaceSelect").value;
    var h = document.getElementById("hostelSelect").value;
    if (h == "--Select Hostel--") {
        document.getElementById("error").innerHTML="Error:: Please select a Hostel";
        /*document.getElementById("errorlink").innerHTML='';*/
        document.getElementById("error2").value ='';
    }
    else if (b == "--Select Block--") {
        document.getElementById("error").innerHTML="Error:: Please select a Block";
        /*document.getElementById("errorlink").innerHTML='';*/
        document.getElementById("error2").value ='';
    }
    else if (r == "--Select Room--") {
        document.getElementById("error").innerHTML="Error:: Please select a Room";
        /*document.getElementById("errorlink").innerHTML='';*/
        document.getElementById("error2").value ='';
    }
    else if (s == "--Select Space--") {
        document.getElementById("error").innerHTML="Error:: Please select a BedSpace";
        /*document.getElementById("errorlink").innerHTML='';*/
        document.getElementById("error2").value ='';
    }
    else
	{
	/*call the function that will submit the form if no error exist*/
        givehostel();
    }
}

/************************** Submit the parameters chooze by the Student *****************************/
function givehostel()
{
    var azeez = "sacking";
    var bl = document.getElementById("blockSelect").value;
    var ro = document.getElementById("roomSelect").value;
    var sp = document.getElementById("spaceSelect").value;
    var ho = document.getElementById("hostelSelect").value;
        xmlHttp=GetXmlHttpObject();
        if (xmlHttp==null)
          {
            alert ("Your browser does not support AJAX!");
            return;
          }
            /*&"?status="+kkk*/
        var url="populate.php";
        url=url+"?hostelled="+ho+"&blo="+bl+"&roomed="+ro+"&spaced="+sp+"&booked="+azeez;
        xmlHttp.onreadystatechange = giveoutput;
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
}

/* after trying to submit the student choiced*/
function giveoutput() 
{
    if (xmlHttp.readyState==4)
    {
        var result = xmlHttp.responseText;
        var result22 = result.length;
        
        if (result22 > 10) {
            var q = result.substring(10,result22);
           /* document.getElementById("error").innerHTML= q;*/
            document.getElementById("error2").value = q;
             
            changehostel();
            reportprint();
           /* document.getElementById("errorlink").innerHTML="Print your Accomodation Slip here";*/
            /*alert(q);*/
            }
            else
            {
				document.getElementById("error").innerHTML="Error:: The bedspace has been Booked ..Retry";
				/*document.getElementById("errorlink").innerHTML='';*/
				document.getElementById("error2").value ='';
				changehostel();
			   /* alert(result22); */   
            }
    }
}
function reportprint(){
subeee.submit();
}