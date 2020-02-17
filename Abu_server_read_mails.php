<?php
session_start(); 
require_once 'settings/connection.php';
require_once 'settings/filter.php';
require_once 'settings/count_mail_record_module.php';
$passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
$d = $h=$t=$countval=$date=$subject=$body=$sender_address = $mail_type = $department = $address = $full_name=$attached_file=$verify_user_link="";
$inbox_count = count_inbox();
$read_inbox_count= count_read_inbox();
$unread_inbox_count = $inbox_count - $read_inbox_count;
//$unread_inbox_count = 5 - 10;

$Outbox_count= count_outbox();
$draft_count= count_draft();
$publication_count = count_publication();



if((!isset($_SESSION['username'])) && (!isset($_GET['d'])) && (!isset($_GET['h']))&& (!isset($_GET['t'])))
{
	header("location: logout.php");
}
else
{
	$d=	$_GET['d'];
	$h=	SHA1($_GET['d']);
	$t=	MD5($_GET['d']);
	if (($d != $_GET['d'])|| ($h != $_GET['h'])|| ($t != $_GET['t'])){
		header("location: Abu_Mail_Server_Inbox.php");
	}
}

//download single file
if(isset($_GET['myfile']))
{
	//header("location: ".$_GET['myfile']);	
	$d=	$_GET['d'];
	$h=	SHA1($_GET['d']);
	$t=	MD5($_GET['d']);

	$FILE = $_GET['myfile'];
	$path_parts = pathinfo($FILE);
	$ext= $path_parts['extension'];
	
	//replace space with underscore(_) so browser can understand it
	$file_name = str_ireplace(" ", "_", $_GET['filename']);
	
	//replace space with underscore(_) so browser can understand it
	$file_name = str_ireplace("#", "_", $file_name);
	
	//$file_name = $_GET['filename'];
	header("Content-Disposition: attachment; filename=".$file_name);
	header("Content-type: application/".$ext);
	header("Pragma: no-cache"); 
	header("Expires: 0");
	readfile($_GET['myfile']);
}


$_SESSION['file_folder'] = $d;

//get the mail details
$query2 = "SELECT * FROM all_mails where receiver_address =:receiver_address AND mail_id=:mail_id";
$stmt2 = $conn->prepare($query2);
$stmt2->bindValue(':receiver_address',$_SESSION['username'], PDO::PARAM_STR);
$stmt2->bindValue(':mail_id',$d, PDO::PARAM_STR);
$stmt2->execute();
$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
$row_count2 = $stmt2->rowCount();
if($row_count2 >= 1) 
{
	$date=$rows3['date_sent'];
						$date500 = new DateTime($date);
						$J = date_format($date500,"D");
						$Q = date_format($date500,"d-F-Y");
						$date = $J.", ".$Q;
						
	$_SESSION['resend_subject'] = $subject = $rows3['subject'];
	$_SESSION['resend_body'] = $body = htmlspecialchars_decode($rows3['body']);
	$_SESSION['resend_attach'] = $attached_file =$rows3['attached_status'];
	$_SESSION['resend_id'] = $rows3['mail_id'];
	
	$_SESSION['sender_address_reply'] = $sender_address = $rows3['sender_address'];
	$mail_type = $rows3['message_type'];

	$department  = $rows3['sender_dept'];$address = $rows3['sender_address']; $full_name= $rows3['sender_name'];

	$verify_user_link = verify_exist($address);
	//retrieve pasport
	$emp="";
	$query2 = "SELECT pic_extension,username FROM student_information where username =:username";
	$stmt2 = $conn->prepare($query2);
	$stmt2->bindValue(':username',$sender_address, PDO::PARAM_STR);
	$stmt2->execute();
	$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
	$row_count2 = $stmt2->rowCount();
	if($row_count2 >= 1) 
	{
		if($rows3['pic_extension']!="")
		{
			$passport ="";
			$paspart = "abu_file/".$sender_address.$rows3['pic_extension'];
			$passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
		}
	}
	else
	{
		$query2 = "SELECT pic_extension,username FROM staff_information where username =:username";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':username',$sender_address, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 >= 1) 
		{
			if($rows3['pic_extension']!="")
			{
				$passport ="";
				$paspart = "abu_file/".$sender_address.$rows3['pic_extension'];
				$passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
			}
		}
	}
	//update box to read mail
	//update the id table
	$query301 = "UPDATE all_mails SET read_status=? WHERE receiver_address=? AND mail_id=?";
	$stmt301 = $conn->prepare($query301);
	$stmt301->execute(array('1',$_SESSION['username'],$d));		
}

if ($attached_file == "1")
{
	//loop through its box to set all files
	$attached_file = '<table class="table table-condensed" style="background-color:#FFFFFF;">
	<tbody>';
	$attachment_folder="Mail_Files/".$d;
	if(is_dir($attachment_folder)) 
	{
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
					$link = $attachment_folder."/".$b;
					$link2 = "Mail_Files/Download_All_Attached_Files.php";
					$link = "Abu_server_read_mails.php?myfile=".$attachment_folder."/".$b."&filename=".$b."&d=".$d."&h=".$h."&t=".$t;
					$attached_file=$attached_file.'<tr><td>'.$b.'</td><td><a href="'.$link.'">Download</a></td></tr>';
				}
			}
		}
		$attached_file=$attached_file.'<tr><td></td><td><a href="'.$link2.'">Download All</a></td><tr></tbody></table>';
	}
	else
	{
		$attached_file ="No attached files";
	}
	
}
else
{
	$attached_file ="No attached files";
}

if($verify_user_link == "student"){
$verify_user_link = "view_student_profile.php?username=".$sender_address;
}
if($verify_user_link == "staff"){
$verify_user_link = "view_staff_profile.php?username=".$sender_address;
}
//forward
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['forward']))
{
	unset($_SESSION['sender_address_reply']);
	header("location: Abu_Mail_Compose_Step1.php");
}
//reply
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply']))
{
	$_SESSION['group_type']="Normal_Mail";
	header("location: Create_Normal_Mail.php");
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
<link rel="stylesheet" type="text/css" href="INDEX_FILES/index.css" >
	
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
			<div  class="col-sm-2 col-md-2 col-lg-2 leftnavy" style="overflow:auto;height:400px">
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
			       <div class="col-lg-12" style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;"> <?php echo $_SESSION['username']; ?> - You are Currently Log In</span></center>
				    <div class="col-lg-12" style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:20px;padding-bottom:5%; margin-left:0%; margin-right:0%">
						
						<div class="col-lg-9" style="margin-bottom:0%">
								<div  class="col-lg-12" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
									<div  class="col-lg-7">
										<div  class="col-lg-3">
											<p>FROM : </p>
										</div>
										<div  class="col-lg-9">
											<?php echo $full_name."<br>".$sender_address;?>
										</div>
									</div>
									
									<div  class="col-lg-5">
										<div  class="col-lg-4">
												<p>Mail Type :</p>
											</div>
											<div  class="col-lg-8">
												<?php echo $mail_type;?>
											</div>
									</div>
									<hr>
								</div>
						
								<div  class="col-lg-12" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
									<div  class="col-lg-7">
										<div  class="col-lg-3">
											<p>Subject : </p>
										</div>
										<div  class="col-lg-9">
											<?php echo $subject;?>
										</div>
									</div>
									
									<div  class="col-lg-5">
										<div  class="col-lg-3">
												<p>Date : </p>
											</div>
											<div  class="col-lg-9">
												<?php echo $date;?>
											</div>
									</div>
								</div>
								
								<div  class="col-lg-12" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
									<div  class="col-lg-12">
										<div  class="col-lg-2">
											<p>Mail Body :</p>
										</div>
										<div  class="col-lg-10">
											<?php echo $body;?>
										</div>
									</div>
								</div>
								
								<div  class="col-lg-12" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
									<div  class="col-lg-12">
										<div  class="col-lg-2">
											<p>Attachments : </p>
										</div>
										<div  class="col-lg-10">
											<?php echo $attached_file;?></p>
										</div>
									</div>
								</div>
							
						<form role="form" name="mailform" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST">
								<div  class="col-lg-9" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
									<button  type="submit"  name="reply" class="btn btn-success">Reply</button>
									<button  type="submit"  name="forward" class="btn btn-success">Forward</button>
								</div>
						</form>	
					</div>	
						<div  class="col-lg-3" style="overflow:auto;height:400px">
						<!-- holds image and other details of user -->
						<?php echo $passport;?>
						<p><?php echo $full_name;?></p>
						<p><?php echo $address;?></p>
						<p><?php echo $department;?></p>
						<p><?php echo '<a href='.$verify_user_link.'>View Full Profile </a>';?></p>
		
						</div>
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
