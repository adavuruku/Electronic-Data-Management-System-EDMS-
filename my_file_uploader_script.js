
		var handlerurl='this_works_with_remove.php'
		
		function CreateAjaxRequest()
		{
			var xh;
			if (window.XMLHttpRequest)
				xh = new window.XMLHttpRequest();
			else
				xh = new ActiveXObject("Microsoft.XMLHTTP");
			
			xh.open("POST", handlerurl, false, null, null);
			xh.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
			return xh;
		}

	var fileArray=[];
	
	function ShowAttachmentsTable()
	{
		var table = document.getElementById("filelist");
		while(table.firstChild)table.removeChild(table.firstChild);
		
		AppendToFileList(fileArray);
	}
	function AppendToFileList(list)
	{
		var table = document.getElementById("filelist");
		
		for (var i = 0; i < list.length; i++)
		{
			var item = list[i];
			var row=table.insertRow(-1);
			row.setAttribute("fileguid",item.FileGuid);
			row.setAttribute("filename",item.FileName);
			var td1=row.insertCell(-1);
			td1.innerHTML="<img src='phpuploader/resources/circle.png' border='0'/>";
			var td2=row.insertCell(-1);
			td2.innerHTML=item.FileName;
			var td4=row.insertCell(-1);
			td4.innerHTML="[<a href='"+handlerurl+"?download="+item.FileGuid+"'>download</a>]";
			var td4=row.insertCell(-1);
			td4.innerHTML="[<a href='javascript:void(0)' onclick='Attachment_Remove(this)'>X</a>]";
		}
	}
	
	function Attachment_FindRow(element)
	{
		while(true)
		{
			if(element.nodeName=="TR")
				return element;
			element=element.parentNode;
		}
	}
	
	function Attachment_Remove(link)
	{
		var row=Attachment_FindRow(link);
		
		//YOU CAN COMMENT THIS PART SO IT NEVER REQUEST FOR ALERT ON WETHER YOU REALLY WANT TO DELETE FILE
		if(!confirm("Are you sure you want to delete '"+row.getAttribute("filename")+"'?"))
			return;
		//ENDS HERE
		
		
		var guid=row.getAttribute("fileguid");
		
		var xh=CreateAjaxRequest();
		xh.send("delete=" + guid);

		var table = document.getElementById("filelist");
		table.deleteRow(row.rowIndex);
		
		for(var i=0;i<fileArray.length;i++)
		{
			if(fileArray[i].FileGuid==guid)
			{
				fileArray.splice(i,1);
				break;
			}
		}
	}
	
	function CuteWebUI_AjaxUploader_OnPostback()
	{
		var uploader = document.getElementById("myuploader");
		var guidlist = uploader.value;

		var xh=CreateAjaxRequest();
		xh.send("guidlist=" + guidlist);

		//call uploader to clear the client state
		uploader.reset();

		if (xh.status != 200)
		{
			alert("http error " + xh.status);
			setTimeout(function() { document.write(xh.responseText); }, 10);
			return;
		}

		var list = eval(xh.responseText); //get JSON objects
		
		fileArray=fileArray.concat(list);

		AppendToFileList(list);
	}
	
	function ShowFiles()
	{
		var msgs=[];
		for(var i=0;i<fileArray.length;i++)
		{
			msgs.push(fileArray[i].FileName);
		}
		alert(msgs.join("\r\n"));
	}