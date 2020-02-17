<?php
session_start(); 
require_once 'settings/connection.php';
require_once 'settings/filter.php';
require_once 'settings/count_mail_record_module.php';
require_once "phpuploader/include_phpuploader.php";
$email = $mail_error=$full_name=$countval="";
$all_receiver_name = $all_receiver_dept = $resend_body=$reply_send=$resend_subject=$resend_attach=$resend_id="";
if((!isset($_SESSION['username'])) && (!isset($_SESSION['my_name'])) && (!isset($_SESSION['statement'])))
{
	header("location: logout.php");
}
$inbox_count = count_inbox();
$read_inbox_count= count_read_inbox();
$unread_inbox_count = $inbox_count - $read_inbox_count;
//$unread_inbox_count = 5 - 10;

$Outbox_count= count_outbox();
$draft_count= count_draft();
$publication_count = count_publication();

//lets check if the mail is from draft or someone wants to forward it
if((isset($_SESSION['resend_attach'])) && (isset($_SESSION['resend_body'])) && (isset($_SESSION['resend_subject'])) && (isset($_SESSION['resend_id'])))
{
	$resend_body=$_SESSION['resend_body'];
	$resend_subject=$_SESSION['resend_subject'];
	$resend_attach=$_SESSION['resend_attach'];
	$resend_id=$_SESSION['resend_id'];
}
if(isset($_SESSION['resend_attach'])){
$reply_send = $_SESSION['sender_address_reply'];
}
if(!isset($_POST['sendA']) && !isset($_POST['saveA'])&& !isset($_POST['draftA']) && !isset($_POST['sendB']) && !isset($_POST['saveB'])&& !isset($_POST['draftB']))
{
			//generate a new ID for the mail
			$query2 = "SELECT mail_id,type FROM mail_identification_no where type=:type";
			$stmt2 = $conn->prepare($query2);
			$stmt2->bindValue(':type','mail', PDO::PARAM_STR);
			$stmt2->execute();
			$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
			$row_count2 = $stmt2->rowCount();
			if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
			{
				//CALL A FUNCTION TO ACTIVATE MAIL
				
					$attachment_folder = $rows3['mail_id'] + 1;
					$_SESSION['attachment_folder']=$attachment_folder = $attachment_folder.$_SESSION['school_id'];
					//check if same folder exist before
					$attachment_folder="Mail_Files/".$attachment_folder;
					if(!is_dir($attachment_folder)) 
					{
						mkdir($attachment_folder,0777);
					}
					else
					{
						//empty what is there before since user refresh its browser
						$a = scandir($attachment_folder);
						$countval=count($a);
						if($countval > 2)
						{
							for($x=0; $x < $countval; $x++)
							{
								//echo $a[$x].'<BR>';
								$b=$a[$x];
								if( $b!="." && $b!="..") 
								{
									unlink($attachment_folder."/".$b);
								}
							}
						}
					}
					
					//check if there is incoming draft use so u can copy the contents files to the newly created folder
					$resend_attach=$_SESSION['resend_attach'];
					$resend_id=$_SESSION['resend_id'];
					if($resend_attach=="1")
					{
						$attached_file = '<table class="table table-condensed" style="background-color:#FFFFFF;">
						<tbody>';
						$old_folder = "Mail_files/".$resend_id;
						if(is_dir($old_folder)) 
						{
							$a = scandir($old_folder);
							$countval=count($a);
							if($countval > 2)
							{
								for($x=0; $x < $countval; $x++)
								{
									//echo $a[$x].'<BR>';
									$b=$a[$x];
									if( $b!="." && $b!="..") 
									{
										$old_folder = "Mail_files/".$resend_id."/".$b;
										$attachment_folder ="Mail_files/".$_SESSION['attachment_folder']."/".$b;
										//dis copy to new folder
										copy($old_folder, $attachment_folder);
									
										$attached_file=$attached_file.'<tr><td><img src='."phpuploader/resources/circle.png".' border="0"/><td>
										'.$b.'</td><td><img src='."phpuploader/resources/uploadok.png".' border="0"/></td>
										<td>[<a href="javascript:void(0)" onclick="submit_nd_Admission(\''.$b.'\')" >Remove <img src="phpuploader/resources/stop.png" border="0"/></a>]</td></tr>';
									}
								}
								$attached_file=$attached_file.'</tbody></table>';
							}
						}
					
					}
					else
					{
						$attached_file="";
					}
					
			}				
}

//lecturer_student_mail

function send_mail_to_receiver($mail_subject,$mail_body,$attached_status,$value,$verify_exist){
	global $conn;
	global $mail_error;
	global $all_receiver_name;
	global $all_receiver_dept;
	$receiver_name=$receiver_username=$receiver_dept=$date="";
	$_SESSION['LETC']=$_SESSION['LETC']." ".$value;
	if($verify_exist=="staff")
	{
		$stmt = $conn->prepare("SELECT * FROM staff_information WHERE username=? AND email_activate=?");		
		$stmt->execute(array($value,'1'));
	}
	if($verify_exist=="student")
	{
		$stmt = $conn->prepare("SELECT * FROM student_information WHERE username=? AND email_activate=?");		
		$stmt->execute(array($value,'1'));
	}
	
	if ($stmt->rowCount () >= 1)
	{
		while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			//save record for all the selected HODS
			$receiver_name = $rows['first_name']." ".$rows['middle_name']." ".$rows['last_name'];
			$receiver_dept= $rows['department'];
			if($all_receiver_name =="")
			{
				$all_receiver_name = $receiver_name;
			}
			else
			{
				$all_receiver_name = $all_receiver_name."\n\r".$receiver_name;
			}
			if($all_receiver_dept =="")
			{
				$all_receiver_dept = $receiver_dept;
			}
			else
			{
				$all_receiver_dept = $all_receiver_dept."\n\r".$receiver_dept;
			}
			
			$receiver_username = $rows['username'];
		//	$receiver_username=$receiver_username."\r\n".$receiver_username;
			$box_type="In";
			//$date = new DateTime("Now");
			$sth = $conn->prepare ("INSERT INTO all_mails (sender_name,sender_dept,sender_address,receiver_name,receiver_dept,
				receiver_address,subject,body,attached_status,mail_id,message_type,box_type,date_sent)
																VALUES (?,?,?,?,?,?,?,?,?,?,?,?,now())");															
				$sth->bindValue (1, $_SESSION['my_name']); $sth->bindValue (2, $_SESSION['department']); $sth->bindValue (3, $_SESSION['username']); 
				$sth->bindValue (4, $receiver_name); $sth->bindValue (5, $receiver_dept); $sth->bindValue (6, $receiver_username);
				$sth->bindValue (7,$mail_subject); $sth->bindValue (8, $mail_body);
				$sth->bindValue (9, $attached_status); $sth->bindValue (10, $_SESSION['attachment_folder']);
				$sth->bindValue (11, $_SESSION['group_type']);
				$sth->bindValue (12, $box_type);
				$sth->execute();
				$affected_rows = $sth->rowCount();														
		}
		
	}
	else
	{
		$mail_error='<p style="color:red">Error :Mail Not Sent No user Match in The Group receiver</p>';
	}
}	
function send_mail_to_sender_outbox($mail_subject,$mail_address,$mail_body,$attached_status,$value,$verify_exist,$group_failled_mail,$all_receiver_name,$all_receiver_dept){
		global $mail_error;
		global $conn;
		//create an out box for the sender
		if($verify_exist=="student")
		{
			$stmt = $conn->prepare("SELECT * FROM student_information WHERE username=? AND email_activate=?");		
			$stmt->execute(array($value,'1'));
		}
		if($verify_exist=="staff")
		{
			$stmt = $conn->prepare("SELECT * FROM staff_information WHERE username=? AND email_activate=?");		
			$stmt->execute(array($value,'1'));
		}
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($stmt->rowCount () >= 1)
		{
			$receiver_name = $rows['first_name']." ".$rows['middle_name']." ".$rows['last_name'];
			$receiver_dept= $rows['department'];
			$receiver_username = $rows['username'];
			
			$box_type="Out";
			$sth = $conn->prepare ("INSERT INTO all_mails (sender_name,sender_dept,sender_address,receiver_name,receiver_dept,
					receiver_address,subject,body,attached_status,mail_id,message_type,box_type,date_sent)
																	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,now())");															
					$sth->bindValue (1, $_SESSION['my_name']); $sth->bindValue (2, $_SESSION['department']); $sth->bindValue (3, $_SESSION['username']); 
					$sth->bindValue (4, $all_receiver_name); $sth->bindValue (5, $all_receiver_dept); $sth->bindValue (6, $mail_address);
					$sth->bindValue (7,$mail_subject); $sth->bindValue (8, $mail_body);
					$sth->bindValue (9, $attached_status); $sth->bindValue (10, $_SESSION['attachment_folder']);
					$sth->bindValue (11,$_SESSION['group_type']);
					$sth->bindValue (12,$box_type);//$sth->bindValue (13, $date);
					$sth->execute();
					$affected_rows = $sth->rowCount();
		
			
			//forward failled report mail to user inbox
			if($group_failled_mail !="")
			{
				//send_mail_to_sender_inbox_failled($mail_subject,$mail_body,$attached_status,$mail_address,$group_failled_mail);
				//send failled maill details to sender
				$box_type="In";
				$mail_subject = $mail_subject." Failled - sending to the following Users Failled ".$group_failled_mail;
				$sth = $conn->prepare ("INSERT INTO all_mails (sender_name,sender_dept,sender_address,receiver_name,receiver_dept,
					receiver_address,subject,body,attached_status,mail_id,message_type,box_type,date_sent)
																	VALUES (?,?,?,?,?,?,?,?,?,?,?,?,now())");															
					$sth->bindValue (1, $_SESSION['my_name']); $sth->bindValue (2, $_SESSION['department']); $sth->bindValue (3, $_SESSION['username']); 
					$sth->bindValue (4, $receiver_name); $sth->bindValue (5, $receiver_dept); $sth->bindValue (6, $receiver_username);
					$sth->bindValue (7,$mail_subject); $sth->bindValue (8, $mail_body);
					$sth->bindValue (9, $attached_status); $sth->bindValue (10, $_SESSION['attachment_folder']);
					$sth->bindValue (11,$_SESSION['group_type']);
					$sth->bindValue (12,$box_type);//$sth->bindValue (13, $date);
					$sth->execute();
					$affected_rows = $sth->rowCount();
			}
			//update the id table
			$query301 = "UPDATE mail_identification_no SET mail_id=mail_id + 1 WHERE type=?";
			$stmt301 = $conn->prepare($query301);
			$stmt301->execute(array('mail'));
			$_SESSION['success']="Your mail was sent successfully";
			header("location: Abu_Mail_Server_Outbox.php");
		}
		else
		{
			$mail_error='<p style="color:red">Error :Mail Not Sent No user Match in The Group hjjgg</p>';
		}
}


function send_mail_to_all_sellected_users($mail_subject,$mail_body,$attached_status,$mail_address)
{
	global $mail_error;
	$group_failled_mail="";
	$all_receivers="";
	$all_address = $mail_address;
	global $all_receiver_name;
	global $all_receiver_dept;
	//check if receiver is not empty
	$k = checkempty($all_address);
	if($k != FALSE)
	{
		//split the addresses using comma
		$kaboom = explode(",", $all_address);
		//loop thru each of the addresses using comma
		
		foreach ($kaboom as $key => $value) 
		{
			//verify if each of the address is good
			
			$good_mail = filterEmail(trim($value));
			if($good_mail != FALSE)
			{
				
				//verify if user name exist in the database
				$verify_exist = verify_exist(trim($value));
				if($verify_exist != FALSE)
				{
					//forward the mail to receivers inbox
					if($all_receivers=="")
					{
						$all_receivers = $value;
					}
					else
					{
						$all_receivers = $all_receivers."\n\r".$value;
					}
					send_mail_to_receiver($mail_subject,$mail_body,$attached_status,$value,$verify_exist);
				}
				else
				{
					if($group_failled_mail=="")
					{
						$group_failled_mail = $value;
					}
					else
					{
						$group_failled_mail = $group_failled_mail."\r\n".$value;
					}
				}
			}
			else
			{
				//group the failled maill and forward it later to sender inbox
				$group_failled_mail = $group_failled_mail."\r\n".$value;
			}
		}
		
				$verify_exist2 = verify_exist(trim($_SESSION['username']));
				//$all_receivers = strip_tags(nl2br($all_receivers));
				//$group_failled_mail = strip_tags(nl2br($group_failled_mail));
				if($verify_exist2 != FALSE)
				{
					//forward mail to user outbox
					send_mail_to_sender_outbox($mail_subject,$all_receivers,$mail_body,$attached_status,$_SESSION['username'],$verify_exist2,$group_failled_mail,$all_receiver_name,$all_receiver_dept);
				}
	}
	else
	{
		//no address entered
		$mail_error='<p style="color:red">Error :None Of the required Field were Selected - No Subject - No attachment - No Body : Mail Failled</p>';
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['sendA']) || isset($_POST['sendB'])))
{
	$mail_error="";
	//make sure one of the maill is not empty both attachment
	//this check if there is attachment
	
	$mail_subject = checkempty($_POST['subject']);
	$mail_body = checkempty($_POST['body']);
	$check_atachment = verify_attachment();
	//1//1 = 1
	if(($mail_subject != FALSE) || ($mail_body != FALSE))
	{
		//($check_atachment != FALSE) ||
		$attached_status= "0";
		$mail_subject = strip_tags(trim($_POST['subject']));
		//$mail_body = strip_tags(trim($_POST['body']));
		$mail_body = trim($_POST['body']);
		$mail_address = strip_tags(trim($_POST['receiver']));
		$mail_body = htmlentities(trim($mail_body));
		$mail_subject = htmlentities(trim($mail_subject));
		$mail_address = htmlentities(trim($mail_address));
		
		$check_atachment = verify_attachment();
		if($check_atachment == TRUE)
		{
			$attached_status= "1";
		}
		
		//create an inbox for all the users selected
		send_mail_to_all_sellected_users($mail_subject,$mail_body,$attached_status,$mail_address);
		
	}
	else
	{
		$mail_error='<p style="color:red">Error :None Of the required Field were Selected - No Subject - No attachment - No Body : Mail Failled</p>';
		//$mail_error="i entered here".$countval.$mail_subject.$mail_body;
	}
}


//save document save_mail_to_users($mail_subject,$mail_body,$attached_status,$verify_exist);
function save_mail_to_users($mail_subject,$mail_body,$attached_status,$verify_exist)
{
	global $conn;
			$sth = $conn->prepare ("INSERT INTO draft_mail (body,subject,attached_status,draft_id,
					username,date_save)
																	VALUES (?,?,?,?,?,now())");															
					$sth->bindValue (1, $mail_body); 
					$sth->bindValue (2, $mail_subject); 
					$sth->bindValue (3, $attached_status); 
					$sth->bindValue (4, $_SESSION['attachment_folder']); 
					$sth->bindValue (5, $_SESSION['username']); 
					$sth->execute();
					$affected_rows = $sth->rowCount();
					
					//update the id table
					$query301 = "UPDATE mail_identification_no SET mail_id=mail_id + 1 WHERE type=?";
					$stmt301 = $conn->prepare($query301);
					$stmt301->execute(array('mail'));
					$_SESSION['success']="Your mail was sent successfully";
					header("location: Abu_Mail_Server_Draft.php");
}
if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['saveA']) || isset($_POST['saveB'])))
{
	$mail_error="";
	//make sure one of the maill is not empty both attachment
	//this check if there is attachment
	
	$mail_subject = checkempty($_POST['subject']);
	$mail_body = checkempty($_POST['body']);
	$check_atachment = verify_attachment();
	//1//1 = 1
	if(($mail_subject != FALSE) || ($mail_body != FALSE))
	{
		//($check_atachment != FALSE) ||
		$attached_status= "0";
		$mail_subject = strip_tags(trim($_POST['subject']));
		$mail_body = strip_tags(trim($_POST['body']));
		//$mail_address = strip_tags(trim($_POST['receiver']));
		$mail_body = htmlentities($mail_body);
		$mail_subject = htmlentities($mail_subject);
	//	$mail_address = htmlentities($mail_address);
		
		$check_atachment = verify_attachment();
		if($check_atachment == TRUE)
		{
			$attached_status= "1";
		}
		$verify_exist = verify_exist($_SESSION['username']);
		if($verify_exist != FALSE){
		//create an inbox for all the users selected
		save_mail_to_users($mail_subject,$mail_body,$attached_status,$verify_exist);
		}
	}
	else
	{
		$mail_error='<p style="color:red">Error :None Of the required Field were Selected - No Subject - No attachment - No Body : Mail Failled</p>';
		//$mail_error="i entered here".$countval.$mail_subject.$mail_body;
	}
}
function verify_attachment()
{
	global $countval;
	global $attachment_folder;
	$check_folder=$attachment_folder="Mail_Files/".$_SESSION['attachment_folder'];
	$a = scandir($check_folder);
	$countval=count($a);
	if($countval == 2)
	{
		//if file no day
		return FALSE ;
	}
	else
	{
		//if file dey
		return TRUE;
	}
}
if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['discardA']) || isset($_POST['discardB'])))
{
					//$_SESSION['attachment_folder']=$attachment_folder = $attachment_folder.$_SESSION['school_id'];
					//check if same folder exist before
					$attachment_folder_delete="Mail_Files/".$_SESSION['attachment_folder'];
					if(is_dir($attachment_folder_delete)) 
					{
						//empty what is there before since user refresh its browser
						$a = scandir($attachment_folder_delete);
						$countval=count($a);
						if($countval > 2)
						{
							for($x=0; $x < $countval; $x++)
							{
								//echo $a[$x].'<BR>';
								$b=$a[$x];
								if( $b!="." && $b!="..") 
								{
									unlink($attachment_folder_delete."/".$b);
								}
							}
						}
						else
						{
							unlink($attachment_folder_delete);
						}
					}
				header("location: Abu_Mail_Server_Inbox.php");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Ahmadu Bello University - Zaria Nigeria</title>
<link rel="shortcut icon" href="INDEX_FILES/images/abulogo.png">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap-theme.css" >
<script type="text/javascript" src="INDEX_FILES/js/bootstrap.js"></script>
<script type="text/javascript" src="INDEX_FILES/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="INDEX_FILES/js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="INDEX_FILES/js/bootstrap.min.js"></script>
<script type="text/javascript" src="INDEX_FILES/js/bootstrap.min.js"></script>
 <script type="text/javascript" src="CK EDITOR/ckeditor.js"></script>
  <script type="text/javascript" src="remove_existing_files.js"></script>
<link rel="stylesheet" type="text/css" href="INDEX_FILES/index.css" >
<script type="text/javascript">

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
			var file_name =item.FileName;
			var row=table.insertRow(-1);
			row.setAttribute("fileguid",item.FileGuid);
			row.setAttribute("filename",item.FileName);
			var td1=row.insertCell(-1);
			td1.innerHTML="<img src='phpuploader/resources/circle.png' border='0'/>";
			var td2=row.insertCell(-1);
			td2.innerHTML=item.FileName;
			var td4=row.insertCell(-1);
			td4.innerHTML="<img src='phpuploader/resources/uploadok.png' border='0'/>";
			var td4=row.insertCell(-1);
			td4.innerHTML="    [<a href='javascript:void(0)' onclick='Attachment_Remove(this)'>Remove<img src='phpuploader/resources/stop.png' border='0'/></a>]  ";
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
	
	function Attachment_Remove(link,file_real_name)
	{
		//alert(link);
		var row=Attachment_FindRow(link);
		
		//YOU CAN COMMENT THIS PART SO IT NEVER REQUEST FOR ALERT ON WETHER YOU REALLY WANT TO DELETE FILE
		//if(!confirm("Are you sure you want to delete '"+row.getAttribute("filename")+"'?"))
		//	return;
		//ENDS HERE
		
		//if( file_exists ($targetfilepath) )
		//unlink($targetfilepath);
		
		
		var guid=row.getAttribute("fileguid");
		//alert(guid);
		//alert (file_real_name);
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
	function send_mail()
	{
		mailform.submit();
	}
	</script>

	
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" style="background-color:green" role="navigation" >
            <div class="navbar-header" style="background-color:blue">
                <div class="container-fluid">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar">Sign Out</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" style="font-size:20px;font-weight:bold;color:black" href="/">Ahmadu Bello University Zaria - Mail Server</a>
                </div>
				
            </div>
						<ul class="nav navbar-nav navbar-right" style="background-color:blue">
								<li><a style="font-size:20px;font-weight:bold;color:yellow" href="#"><?php echo $_SESSION['username']; ?></a></li>
						</ul>
            <div class="collapse navbar-collapse">
                

            </div><!-- /.nav-collapse -->
    </div>

 <div class="nav-overflow"></div>

<div class="container" style="width:98%">
<div class="row" style="margin-top:3%">
	<div class="col-xs-12 col-sm-12">
			<div class="col-xs-3 col-sm-3">
				<div class="col-xs-4 col-sm-4" >
					<?php echo $_SESSION['pic_extension'];?>
				</div>
				<div class="col-xs-8 col-sm-8">
					<p style="margin:0px;font-size:12px"><?php echo $_SESSION['my_name'];?></p>
					<p style="margin:0px;font-size:12px"><?php echo '<a href='.$_SESSION['view_profile'].'>View Profile </a>';?> | <a href="logout.php">Sign Out</a></p>
					<?php echo '<p style="margin:0px;font-size:12px"><a href='.$_SESSION['update_profile'].'>Update Profile</a></p>';?>
				</div>
			</div>
		
			<div class="col-xs-9 col-sm-9">
				<form role="form" name="mailform" class="form-horizontal" action="Search_Mail_Result.php" enctype="multipart/form-data" method="POST">
					<div class="form-group">
						<label for="inputEmail" class="control-label col-xs-3">Enter Description</label>
						<div class="col-xs-7">
							<input type="text" class="form-control" id="inputEmail" name="searchmail" placeholder="Enter Your Description to Search">
						</div>
						<div class=" col-lg-2">
							<button type="submit" name="submitsearch" class="btn btn-success">Search Mail</button>
						</div>
					</div>
				</form>
			</div>
	</div>
</div>	
	<!-- middle content starts here where vertical nav slides and news ticker statr -->
	<div class="row">
        <div class="col-xs-12 col-sm-12">
			<div  class="col-sm-2 col-md-2 col-lg-2 leftnavy">
					<div class="nav-head"><h4>My Mails</h4></div>
					<div class="list-group show" style="margin-bottom:50px">
					<a href="redirect_compose.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Create New Mail</a>
					<a href="Abu_Mail_Server_Inbox.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge"><?php echo $inbox_count; ?></span> View Inbox </a>
					<a href="Abu_Mail_Server_Outbox.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge"><?php echo $Outbox_count; ?></span> View OutBox</a>
					<a href="Abu_Mail_Server_Draft.php" class="list-group-item"> <span class="glyphicon glyphicon-plus mail"></span> <span class="badge"><?php echo $draft_count; ?></span> View Draft Mail</a>
					<a href="Abu_Mail_Server_Search_user.php" target="_blank" class="list-group-item"> <span class="glyphicon glyphicon-plus mail"></span> <span class="badge"></span> Search Mail User</a>
				</div>
					<div class="nav-head"><h4>My Publications</h4></div>
					<div class="list-group show" style="margin-bottom:80px">
						<a href="Create_Publication_Mail.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span><span class="badge"></span> New Publications</a>
						<a href="Abu_Mail_Server_My_Publication.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span><span class="badge"><?php echo $publication_count; ?></span> My Publications</a>
						<a href="Abu_Mail_Server_Search_Publication.php" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> Search Publications</a>
					</div>
			</div>
			
			<div  class="col-sm-10 col-md-10 col-lg-10">
			       <div style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;"> <?php echo $_SESSION['username']; ?> - You are Currently Log In</span></center>
				    <div style="background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:0px;padding-bottom:5%; margin-left:0%; margin-right:0%">
						<div class="col-lg-12" style="margin-bottom:0%">
								<p> <?php echo $_SESSION['username']; ?>, You are about to send a <span style="color:red" > <?php echo $_SESSION['group_type'];?> </span></p>
								<p>Please make sure you write the address correctly i.e <span style="color:red;font-weight:bold">user@abumail.com </span> and if address is more than one make sure they are seperated with <span style="color:blue;font-weight:bold">comma (,)</span><span style="color:red;font-weight:bold"> i.e user@abumail.com , user2@abumail.com  </span>.</p>
								<?php echo $mail_error;?>
							<hr>
						</div>
								
						<form role="form" name="mailform"   class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST">
								<div  class="col-lg-12" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
									<button  type="submit"  name="sendA" class="btn btn-success">Send</button>
									<button  type="submit"  name="saveA" class="btn btn-success">Save Now</button>
									<button  type="submit"  name="discardA" class="btn btn-success">Discard</button>
								</div>
								
								<div class="form-group" style="margin-bottom:0%" >
									<label for="subject" class="control-label col-xs-3">To Email Address(s) :<span style="color:red"class"require">*</span></label>
									<div class="col-xs-8">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
											
											<textarea rows="1" onkeyup="load_the_user()" class="form-control" id="receiver" name="receiver">
												<?php echo trim($reply_send);?>
											</textarea>
										</div>
										<div id="display_all">
											
										</div>
									</div>
									
								</div>
													
								<div class="form-group" style="margin-bottom:0%">
									<label for="subject" class="control-label col-xs-3">Subject :<span style="color:red"class"require">*</span></label>
									<div class="col-xs-8">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
											<input type="text" name="subject" onclick="clear_display()" class="form-control" id="subject" placeholder="Enter the Mail Subject" value="<?php echo $resend_subject;?>"></input>
										</div>
									</div>
								</div>
								
								<div class="form-group" style="margin-bottom:1%">
									<div class=" col-lg-offset-3 col-xs-8">
										
										<?php

														$uploader = new PhpUploader();
														$uploader->MaxSizeKB=100240;
														$uploader->Name="myuploader";
														$uploader->MultipleFilesUpload=true;
														$uploader->InsertText="Add Attachment";
														//$uploader->AllowedFileExtensions="*.jpg,*.png,*.gif,*.bmp,*.txt,*.zip,*.rar,*.docx,*.doc,*.pptx,;		
														$uploader->SaveDirectory="Mail_Files/".$_SESSION['attachment_folder'];
														$uploader->Render();

										?>
										<table id="filelist" width="100%" style='border-collapse: collapse' class='Grid' border='0' cellspacing='25px' cellpadding='15px'>
										
										</table>
										<div id="oldones">
						
											<?php
											echo $attached_file;
											?>
										</div>
									</div>
								</div>
								
								<div class="form-group" onmouseover="clear_display()">
									<label for="level" class="control-label col-xs-3">Body :<span style="color:red"class"require">*</span></label>
									<div class="col-xs-8">
										
											<textarea  rows="5" class="form-control"  id="body" name="body" value="sherif">
													
													<?php echo $resend_body;?>
											</textarea>
											
											 <script>
											 //dis code must be here for ckeditor to work
											 
												// Replace the <textarea id="editor1"> with a CKEditor
												// instance, using default configuration.
												CKEDITOR.replace( 'body' );
												function alertttt(){
													var sherif = document.getElementById('body').value;
													alert(sherif);
												}
												
											</script>
										</div>
									</div>
									<div class="col-xs-offset-3 col-xs-8" id="leerror1"><?php echo $mail_error;?></div>
								</div>
							
							
								<div  class="col-lg-9" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">	
									<button  type="submit" name="sendB" class="btn btn-success">Send</button>
									<button  type="submit"  name="saveB" class="btn btn-success">Save Now</button>
									<button  type="submit"  name="discardB" class="btn btn-success">Discard</button>
								</div>
						</form>			
					</div>
				</div>
			</div>
			
			<div class="clearfix visible-sm-block"></div>
			<div class="clearfix visible-md-block"></div>
			<div class="clearfix visible-lg-block"></div>
        </div>
    </div>
		<!-- middle content ends here where vertical nav slides and news ticker ends -->
	
	
</div>	
<div class="row">
        <div class="col-xs-12 col-sm-12">
            <footer>
                <p>Copyright &copy; 2014 - All Rights Reserved - Software Development Unit,ABU Zaria.</p>
            </footer>
        </div>
    </div>
		<script type='text/javascript'>
											//this is to show the header..
											ShowAttachmentsTable();
										</script>								
</body>
</html>  
