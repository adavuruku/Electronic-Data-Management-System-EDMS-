<?php
$email = $passport=$full_name="";

session_start(); 
require_once 'settings/connection.php';
require_once 'settings/filter.php';
require_once 'settings/count_mail_record_module.php';
require_once "phpuploader/include_phpuploader.php";
$passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
$d = $h=$t=$countval=$date=$subject=$body=$sender_address = $mail_type = $department = $address = $full_name=$attached_file="";
$inbox_count = count_inbox();
$read_inbox_count= count_read_inbox();
$unread_inbox_count = $inbox_count - $read_inbox_count;
//$unread_inbox_count = 5 - 10;

$Outbox_count= count_outbox();
$draft_count= count_draft();
$publication_count = count_publication();

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{
	$_SESSION['student_course'] = $student_course = $_POST['course'];
	$_SESSION['student_level'] = $student_level = $_POST['level'];
	
	$_SESSION['statement'] = "All The ".$_SESSION['student_level']." Level Student in ".$student_course;
	if ($student_course=="All" && $student_level =="All")
	{
		$_SESSION['statement'] = "All The Students in ".$_SESSION['department']. " Department";
	}
	
	if ($student_course=="All" && $student_level !="All")
	{
		$_SESSION['statement'] = "All The ".$student_level." Level Students in ".$_SESSION['department']. " Department";
	}
	////
	if ($student_course!="All" && $student_level =="All")
	{
		$_SESSION['statement'] = "All The Students in ".$_SESSION['student_course'];
	}
	
	if ($student_course!="All" && $student_level !="All")
	{
		$_SESSION['statement'] = "All The ".$student_level." Level Students in ".$_SESSION['student_course'];
	}
	header("location: Compose_Group_Mail.php");
	
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
<script type="text/javascript" src="INDEX_FILES/my_script.js"></script>
<link rel="stylesheet" type="text/css" href="INDEX_FILES/drop_panel.css" >
<link rel="stylesheet" type="text/css" href="INDEX_FILES/index.css" >
<script type="text/javascript" src="INDEX_FILES/reg_deptchange.js"></script>
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
			<div  class="col-sm-3 col-md-3 col-lg-3 leftnavy">
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
			
			<div  class="col-sm-9 col-md-9 col-lg-9">
			       <div style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;"> <?php echo $_SESSION['username']; ?> - You are Currently Log In</span></center>
				     <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:20px;padding-bottom:5%; margin-left:0%; margin-right:0%">
<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
								<div class="col-lg-12">
								<p> <?php echo $_SESSION['username']; ?> , You have sellect a <span style="color:red"><?php echo $_SESSION['group_type'];?> </span> Mail Type.</p>
											<p>Please Select Department, Course and The Level Of Student to Receive The <span style="color:red"><?php echo $_SESSION['group_type'];?> </span> Group mail.</p>
										<hr>
								</div>
													 <div class="form-group">
														<label for="course2" class="control-label col-xs-3">Student Course :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="course" name="course">
																<option value="All">All the Student in <?php echo $_SESSION['department'];?></option>
																	<?php
																			$user_type = $_SESSION['department'];
																		
																			$query = "SELECT * FROM department WHERE department=?  ORDER BY course ASC";
																			$stmt = $conn->prepare($query);
																			$stmt->execute(array($user_type));
																			//$rows = $stmt->fetch(PDO::FETCH_ASSOC);
																			if ($stmt->rowCount () >= 1)
																			{
																				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
																				{
																					//such to be sure there are still avvaillable space for each room in the sellected block availlable=1 not availlable=0
																						$mail_type = $row['course'];
																							echo "<option value='$mail_type'>".$mail_type."</option>";
																				}
																			}
																	?>	
																</select>
															</div>
														</div>
														<div class="col-xs-4" id="cerror1"></div>
													</div>
													<div class="form-group">
														<label for="level" class="control-label col-xs-3">Student Level :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="level" name="level">
																		<option value="All">All The Level</option>
																		<option value="100">100 Level</option>
																		<option value="200">200 Level</option>
																		<option value="300">300 Level</option>
																		<option value="400">400 Level</option>
																</select>
															</div>
														</div>
														
													</div>
													
								<div class="form-group">
									<div class=" col-lg-offset-3 col-lg-10">
											<button type="submit" name="submit" class="btn btn-success">Click to Continue </button>
									</div>
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
</body>
</html>  
