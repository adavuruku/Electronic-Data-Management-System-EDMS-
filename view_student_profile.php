<?php
$email = $mail_error=$full_name=$countval="";
session_start(); 
require_once 'settings/connection.php';
require_once 'settings/filter.php';
require_once 'settings/count_mail_record_module.php';
$passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
$d = $h=$t=$countval=$date=$subject=$body=$sender_address = $mail_type = $department = $address = $full_name=$attached_file="";
$inbox_count = count_inbox();
$read_inbox_count= count_read_inbox();
$unread_inbox_count = $inbox_count - $read_inbox_count;
//$unread_inbox_count = 5 - 10;

$Outbox_count= count_outbox();
$draft_count= count_draft();
$publication_count = count_publication();
$user_name="";
if((!isset($_SESSION['username'])) && (!isset($_SESSION['my_name'])) && (!isset($_SESSION['statement'])))
{
	header("location: logout.php");
}

if((isset($_GET['username'])))
{
	$user_name = strip_tags($_GET['username']);
}
if($user_name =="")
{
	header("location: logout.php");
}

//search for the staff if the staff did not exist log out the user it might site attack
		$query2 = "SELECT * FROM student_information WHERE username =:username";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':username',$user_name, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 >= 1)
		{
			$staff_name = $rows3['first_name']." ".$rows3['middle_name']." ".$rows3['last_name'];
			
			$staff_type = $rows3['student_type']." In ".$rows3['course']." Programme";
			if($rows3['student_type']!="Student" && $rows3['student_type']!="" ){
				$staff_type = $rows3['Level']." Level ".$rows3['course']." Programme ".$rows3['student_type'];
			}
			
			$staff_department = $rows3['department']." Department";
			$staff_course = $rows3['course']." Programme";
			$staff_marital_status = $rows3['marital_status'];
			$staff_dob = $rows3['dob'];
			$student_phone = $rows3['phone_no'];
			
			$date500 = new DateTime($staff_dob);
				$J = date_format($date500,"D");
				$Q = date_format($date500,"d-F-Y");
				$staff_dob = $J.", ".$Q;
				
				$date_admitted = $rows3['date_admited'];
				$date500 = new DateTime($date_admitted);
				$J = date_format($date500,"D");
				$Q = date_format($date500,"d-F-Y");
				$date_admitted = $J.", ".$Q;
			
			$staff_gender = $rows3['Gender'];
			$staff_state = $rows3['state_origin'];
			$staff_localgov = $rows3['local_gov'];
			$staff_username = $rows3['username'];
			$staff_email = $rows3['email'];
			$staff_picture = '<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
			if($rows3['pic_extension']!=""){
				$paspart = "abu_file/".$user_name.$rows3['pic_extension'];
				$staff_picture ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
			}
		}
		else
		{
			header("location: logout.php");
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
			<div  class="col-sm-2 col-md-2 col-lg-2 leftnavy">
					<div class="nav-head"><h4>My Mails</h4></div>
					<div class="list-group show" style="margin-bottom:50px">
						<a href="Abu_Mail_Compose_Step1.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Create New Mail</a>
						<a href="Abu_Mail_Server_Inbox.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge"><?php echo $inbox_count; ?></span> View Inbox </a>
						<a href="Abu_Mail_Server_Outbox.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge"><?php echo $Outbox_count; ?></span> View OutBox</a>
						<a href="Abu_Mail_Server_Draft.php" class="list-group-item"> <span class="glyphicon glyphicon-plus mail"></span> <span class="badge"><?php echo $draft_count; ?></span> View Draft Mail</a>
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
						
						
						<div class="col-lg-12" style="margin-bottom:0%">
							<div class="col-lg-3" style="margin-bottom:0%">
							
									<p> <?php echo $staff_picture; ?>
							</div>
							<div class="col-lg-9" style="margin-bottom:0%">
									
									<table class="table table-condensed" style="background-color:#FFFFFF;">
										<tbody>
											<tr>
												<td>Name : </td>
												<td colspan="2"><?php echo $staff_name; ?></td>
											</tr>
											<tr>
												<td>User name : </td>
												<td colspan="2"><?php echo $staff_username; ?></td>
											</tr>
											
											<tr>
												<td>Staff Rank : </td>
												<td colspan="2"><?php echo $staff_type; ?></td>
											</tr>
											<tr>
												<td>Department : </td>
												<td colspan="2"><?php echo $staff_department; ?></td>
											</tr>
											<tr>
												<td>Programme : </td>
												<td colspan="2"><?php echo $staff_course; ?></td>
											</tr>
											<tr>
												<td>Date Admission : </td>
												<td colspan="2"><?php echo $date_admitted; ?></td>
											</tr>
											<tr>
												<td colspan="3" style="color:red">Personal Information</td>
											</tr>
											<tr>
												<td>Email : </td>
												<td colspan="2"><?php echo $staff_email; ?></td>
											</tr>
											<tr>
												<td>Phone NO : </td>
												<td colspan="2"><?php echo $student_phone; ?></td>
											</tr>
											<tr>
												<td>Gender : </td>
												<td colspan="2"><?php echo $staff_gender; ?></td>
											</tr>
											<tr>
												<td>Date Of Birth : </td>
												<td colspan="2"><?php echo $staff_dob; ?></td>
											</tr>
											<tr>
												<td>State Of Origin : </td>
												<td colspan="2"><?php echo $staff_state; ?></td>
											</tr>
											<tr>
												<td>Local Government : </td>
												<td colspan="2"><?php echo $staff_localgov; ?></td>
											</tr>
											<tr>
												<td>Marital Status : </td>
												<td colspan="2"><?php echo $staff_marital_status; ?></td>
											</tr>
											<tr>
												<td colspan="3" style="color:red">Educational Qualification</td>
											</tr>
											<?php
											$stmt = $conn->prepare("SELECT * FROM staff_qualification WHERE staff_mail=?");
			
											$stmt->execute(array($user_name));
											if ($stmt->rowCount () >= 1)
											{
												while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
												{
													echo '<tr><td>'.$rows['qualification'].'</td><td colspan="2" >'.$rows['school_attended'].'</td></tr>';
												}
											}
											else
											{
												echo '<tr><td colspan="3">No Record Uploaded for Student Educational Qualification</td></tr>';
											}
										
											?>
											<tr>
												<td colspan="3" style="color:red">My Best Course</td>
											</tr>
											<?php
											$stmt = $conn->prepare("SELECT * FROM staff_course WHERE staff_mail=?");
			
											$stmt->execute(array($user_name));
											if ($stmt->rowCount () >= 1)
											{
												while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
												{
													echo '<tr><td>'.$rows['department'].'</td><td>'.$rows['level'].'</td><td>'.$rows['course'].'</td></tr>';
												}
											}
											else
											{
												echo '<tr><td colspan="3">No Record Uploaded for Student Current Best Course of Study</td></tr>';
											}
										
											?>
										<tbody>
									</table>
							</div>							
						
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
