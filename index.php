<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';
$loginerror=$post_username="";

if((isset($_COOKIE['user'])) && (isset($_COOKIE['pass'])))
{
	$post_username=$_COOKIE['user'];
	$post_password=$_COOKIE['pass'];
	login_user($post_username,$post_password);
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password']))
{
	$J = checkempty($_POST['password']);
	if ($J != FALSE){
		$pword = strip_tags($_POST['password']);
		$pword = SHA1($pword);
		
		$query2 = "SELECT * FROM admin_login WHERE Admin_access =:Admin_access Limit 1";
		$stat="1";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':Admin_access',$pword, PDO::PARAM_STR); 
		$stmt2->execute();
		$rows3a = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count3 = $stmt2->rowCount();
		if ($row_count3 ==1)
		{
			//update last login
			
			$stmt = $conn->prepare("UPDATE admin_login SET last_login=now() WHERE Admin_access=? Limit 1");
			$stmt->execute(array($pword));
			if( $stmt == true)
			{
				
				$_SESSION['myfile'] =$rows3a['Admin_Name'];
				$_SESSION['user'] ="Admin";
				header("location: Admin_index.php");	
			}
			
			
		}
	}
}

function head_user_to_their_page($change_password,$email_activate,$other_mail)
{				
	//check if username is activated before
	global $loginerror;
	if ($change_password !="1")
	{
		//redirect to change details 1 page
		header("location: change_details.php");
	}
							
	//check if change details2 is done before - user has put activation mail before
	if ($other_mail =="")
	{
		//redirect to change details 2 page
		header("location: change_details_step2.php");
	}
							
	//check if username has been activated from the user mail before
	if ($email_activate =="")
	{
		//give error message that user should try activate the address
		$loginerror="<p style='color:red;'>Error : Please Login in to - <span style='color:blue;'>".$other_mail."</span> - to ACTIVATE your USER NAME before Using it.!!</p>";
	}
	else
	{
		//if all these are done - just direct the user to its mail inbox
		header("location: Abu_Mail_Server_Inbox.php");
	}
	
	
}


//login module
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitlogin']))
	{
		$_SESSION['success']="";
		$post_username =$_POST['inputEmail1'];
		$post_password=SHA1($_POST['inputPassword']);
		login_user($post_username,$post_password);
	}
function login_user($post_username,$post_password){
	global $conn;
	global $loginerror;
		$user_name_value = checkempty($post_username);
		$password_value = checkempty($post_password);
		if (($user_name_value != FALSE) && ($password_value != FALSE))
		{ 
			//go and validate the email address
			$user_name_value = strip_tags($post_username);
			
			$abu_real_mail = turn_to_abu_mail($user_name_value);
			
			//make sure the email is correct after conversion
			$mail_good = filterEmail($abu_real_mail);
			if ($mail_good != FALSE)
			{ 
				//convert password to SHA1
				$password_value = $post_password;
				$accnt_permit = "";
				//comparing with the server
					$f="0";
					$query2 = "SELECT * FROM student_information WHERE username =:username AND password=:password AND close_account=:close_account";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':username',$abu_real_mail, PDO::PARAM_STR);
					$stmt2->bindValue(':password',$password_value, PDO::PARAM_STR);
					$stmt2->bindValue(':close_account',$accnt_permit, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 != 1)
					{
						$f="0";
						$query2 = "SELECT * FROM staff_information WHERE username =:username AND password=:password AND close_account=:close_account";
						$stmt2 = $conn->prepare($query2);
						$stmt2->bindValue(':username',$abu_real_mail, PDO::PARAM_STR);
						$stmt2->bindValue(':password',$password_value, PDO::PARAM_STR);
						$stmt2->bindValue(':close_account',$accnt_permit, PDO::PARAM_STR);
						$stmt2->execute();
						$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
						$row_count2 = $stmt2->rowCount();
						if($row_count2 == 1) 
						{
							//proceed to the next required page
							$change_password = $rows3['change_password'];
							$email_activate = $rows3['email_activate'];
							$other_mail = $rows3['email'];
							
							$_SESSION['username'] = $abu_real_mail;
							$_SESSION['my_name'] =$rows3['first_name']." ".$rows3['middle_name']." ".$rows3['last_name'];
							$_SESSION['user_type'] = $rows3['staff_type'];
							
							if ($rows3['pic_extension'] != ""){
								$paspart = "abu_file/".$_SESSION['username'].$rows3['pic_extension'];
								$_SESSION['pic_extension'] = $passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
							}
							else
							{
								$_SESSION['pic_extension'] = $passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
							}
							$_SESSION['department']=$rows3['department'];
							$_SESSION['login_course']=$rows3['course'];
							$_SESSION['school_id']=$rows3['staff_id'];
							$_SESSION['user_rate'] = "staff";
							$_SESSION['view_profile'] = "view_staff_profile.php?username=".$_SESSION['username'];
							$_SESSION['update_profile'] = "update_staff_profile.php";
							
							setcookie("user", $abu_real_mail, strtotime( '+30 days' ), "/", "", "", TRUE);
							setcookie("pass", $post_password, strtotime( '+30 days' ), "/", "", "", TRUE);
							
							head_user_to_their_page($change_password,$email_activate,$other_mail);
						}
						else
						{
							$loginerror="<p style='color:red;'>Error : Invalid User Name Or Password or account is blocked ..Verify</p>";
						}
					}
					else
					{
						//proceed to the next required page
						
							$change_password = $rows3['change_password'];
							$email_activate = $rows3['email_activate'];
							$other_mail = $rows3['email'];
							
							$_SESSION['username'] = $abu_real_mail;
							$_SESSION['my_name'] =$rows3['first_name']." ".$rows3['middle_name']." ".$rows3['last_name'];
							$_SESSION['user_type'] =$rows3['student_type'];
							
							if ($rows3['pic_extension'] != ""){
							
								$paspart = "abu_file/".$_SESSION['username'].$rows3['pic_extension'];
								$_SESSION['pic_extension'] = $passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
							}
							else
							{
								$_SESSION['pic_extension'] = $passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
							}
							$_SESSION['department']=$rows3['department'];
							
							$_SESSION['school_id']=$rows3['student_id'];
							$_SESSION['user_rate'] = "student";
							$_SESSION['view_profile'] = "view_student_profile.php?username=".$_SESSION['username'];
							$_SESSION['update_profile'] = "update_student_profile.php";
							
							setcookie("user", $abu_real_mail, strtotime( '+30 days' ), "/", "", "", TRUE);
							setcookie("pass", $post_password, strtotime( '+30 days' ), "/", "", "", TRUE);
							
							head_user_to_their_page($change_password,$email_activate,$other_mail);
					}
			}
			else
			{
				$loginerror="<p style='color:red;'>Error : Invalid User Name ..Verify</p>";
			}
			
		}
		else
		{
			$loginerror="<p style='color:red;'>Error : Some Details Are Empty ..Verify</p>";
		}
	}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Home | Ahmadu Bello University - Zaria Nigeria</title>
<link rel="shortcut icon" href="INDEX_FILES/images/abulogo.png">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/engine1/style.css" />
	<script type="text/javascript" src="INDEX_FILES/engine1/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="INDEX_FILES/css/bootstrap-theme.css" >
<script type="text/javascript" src="INDEX_FILES/js/bootstrap.js"></script>
<script type="text/javascript" src="INDEX_FILES/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="INDEX_FILES/js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="INDEX_FILES/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="INDEX_FILES/plugins/slide.css" >
<script type="text/javascript" src="INDEX_FILES/plugins/wb.newsviewer.min.js"></script>
<link rel="stylesheet" type="text/css" href="INDEX_FILES/plugins/ticker.css" >
<link rel="stylesheet" type="text/css" href="INDEX_FILES/index.css" >
<style>
.imgcont1{
	padding-top:15px;
	padding-bottom:15px;
	margin-bottom:10px;
	box-shadow: 5px 5px 5px gray;
	background: linear-gradient(#5F9EA0 25%, #077000   10%);
	background: -moz-linear-gradient(#5F9EA0 25%, #077000   100%); /* FF3.6+ */
	background: -webkit-linear-gradient(#5F9EA0 25%, #008B8B  100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(#5F9EA0 25%, #008B8B   100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(#5F9EA0 25%, #008B8B  100%); /* IE10+ */
}

#fom{
		background-color:#37639A;
	padding:4px 3px 2px 3px;
	margin-top:10px;
	height:250px;
	 border:#37639A 3px solid;
	  border-radius:10px;
	
	}
</style>
<script type="text/javascript">
$(document).ready(function()
{
   var NewsFeedTicker2_Data = new Array();
   NewsFeedTicker2_Data[0] = ["#", "SIWES Eligibility", "Fri, 24 Oct 2014 10:08:41 GMT", "<br>The students that are eligible to apply for SIWES program information is now available click to view ... "];
   NewsFeedTicker2_Data[1] = ["#", "SIWES Rules and Regulations", "Wed, 22 Oct 2014 19:43:12 GMT", "The rules and regulations for all siwes students are available ... click to view "];
   NewsFeedTicker2_Data[2] = ["#", "2014 / 2015 Siwes Student lists Out", "Fri, 24 Oct 2014 10:08:44 GMT", "The 2014 / 2015 Siwes Students lists is out Click here to Check"];
   NewsFeedTicker2_Data[3] = ["#", "The 2013 / 2014 Siwes Program Schedule", "Fri, 24 Oct 2014 10:08:50 GMT", "2013 / 2014 Siwes program Begins on the 21 July, 2014. All siwes students are expected to Resume soon as activities commence immediately"];
   NewsFeedTicker2_Data[4] = ["#", "How To Apply at SDU", "Fri, 24 Oct 2014 10:08:54 GMT", "to know or get informations on how to apply for siwes program at SDU click here..."];
   NewsFeedTicker2_Data[5] = ["#", "Requirements for Siwes applicants", "Fri, 24 Oct 2014 10:09:15 GMT", "the list for all the requirements and materials needed at SDU from all SIWES students is now available,click to view..."];
   $("#NewsFeedTicker2").newsviewer({ mode: 'rotate', pause: 5000, pause: 5000, animation: 4, animationDuration: 500, sortOrder: 0, dataSource: 'local', param: NewsFeedTicker2_Data, target: '_self', dateFormat: 'DD, d MM, yy', maxItems: 1});

   });
</script>


</head>
<body>

<div class="container">
	<div class="row" >
		<div  class=" col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color:#5F9EA0;margin:0px">
		
				 <div  class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color:#5F9EA0;margin-top:5px;margin-bottom:5px">
					<img src="INDEX_FILES/images/indexbanner.png"  class="img-responsive" style="margin:0px"></img>
				</div>
		
		</div>
		
		<!-- navigation menu -->
		<div class="col-xs-12 col-sm-12 navigay">
			<div class="row">
						<nav role="navigation" class="navbar navbar-inverse navedit">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header navedit">
							<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle navedit">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							 <a href="#" class="navbar-brand">Home</a>
							 <a href="#" class="navbar-brand">History</a>
							 <a href="#" class="navbar-brand">About Us</a>
						</div>
						<!-- Collection of nav links, forms, and other content for toggling -->
						<div id="navbarCollapse" class="collapse navbar-collapse navedit">
							<ul class="nav navbar-nav">
								<li class="active"><a href="login_to_profile.php">Login</a></li>
								<li><a href="#">Register</a></li>
								 
							 </ul>
							<form role="search" class="navbar-form navbar-right"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="adminlogin">
								<div class="form-group">
								   <input type="password" name="password" class="form-control"></input>
								</div>
								 <button type="submit" class="btn btn-default">Log In</button>
							</form>
							<ul class="nav navbar-nav navbar-right">
								<li><a href="#">Admin Login:</a></li>
							</ul>
						</div>
					</nav>
			</div>
		</div>
	</div>
	
	<!-- middle content starts here where vertical nav slides and news ticker statr -->
	<div class="row">
        <div class="col-xs-12 col-sm-12">
			<div  class="col-sm-3 col-md-3 col-lg-3 leftnavy">
			<div class="nav-head"><h4>What 's New</h4></div>
				<div class="list-group show" style="margin-bottom:50px">
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span><span class="badge">New</span>  View New Siwes Students</a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">New</span> 2014 /2015 Siwes Updates </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">New</span> Siwes Application Guides </a>
					<a href="#" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">New</span> SDU head speech</a>
				</div>
				<div class="nav-head"><h4>Quick Links</h4></div>
				<div class="list-group show" style="margin-bottom:80px">
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> SDU Information  </a>
					<a href="#" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> SDU Staff </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> SDU Siwes Application </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> SDU Programs </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> SDU Library </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> SDU Resources </a>
				</div>
	
			</div> 
			<div  class="col-sm-6 col-md-6 col-lg-6 hidden-xs">
				<div  class="col-sm-12 col-md-12 col-lg-12 hidden-xs">
				<!--  image wao slider -->	
						<div id="wowslider-container1">
							<div class="ws_images"><ul>
						<li><img src="INDEX_FILES/data1/images/photo1116.jpg" alt="ENTRANCE VIEW OF SOFTWARE DEVELOPMENT UNIT" title="ENTRANCE VIEW OF SOFTWARE DEVELOPMENT UNIT" id="wows1_0"/>The entrance view of software development unit was built in 1966 but renovoted in 2014 under the supervision of J O Hadi</li>
						<li><img src="INDEX_FILES/data1/images/hd_house_wallpapers_widescreen_hd_wallpaper.jpg" alt="HORIZONTAL VIEW OF AYEGBA HOSTEL" title="HORIZONTAL VIEW OF SDU ADMIN BLOCK" id="wows1_1"/>in 1968 ,the software development unit, ABU zaria decided to build the most beautiful building ABU .it was completed by J O Hadi in 2104 after long series of developments.</li>
						<li><img src="INDEX_FILES/data1/images/img_09811024x768.jpg" alt="A VERTICAL VIEW OF AMINA HOSTEL" title="A VERTICAL VIEW OF SDU ADMIN BLOCK" id="wows1_2"/>the Admin block which was commissioned in 1966 was restructured</li>
						<li><img src="INDEX_FILES/data1/images/img00059.jpg" alt="A DIAGONALL VIEW OF AZIKIWE HOSTEL" title="A DIAGONALL VIEW OF SDU ADMIN BLOCK" id="wows1_3"/>Azikiwe Hostel is For Male Students and it contain just Eight Persons per Room.it has Two (2) block and it contains 20 rooms in each block it was buld in May 1975.</li>
						<li><img src="INDEX_FILES/data1/images/lai_kok_estate.jpg" alt="A FRONT VIEW OF INIKPI HOSTEL" title="A FRONT VIEW OF SDU ADMIN BLOCK" id="wows1_4"/>Inikpi Hostel is For Female Students and it contain just Eight Persons per Room.it has Two (2) block and it contains 20 rooms in each block it was buld in February 1975.</li>
						<li><img src="INDEX_FILES/data1/images/resizd2.jpg" alt="resizd2" title="resizd2" id="wows1_5"/></li>
						<li><img src="INDEX_FILES/data1/images/resized1.jpg" alt="DIAGONAL VIEW OF OMODOKO EXTENSION" title="DIAGONAL VIEW OF SIWES STUDENTS BLOCK" id="wows1_6"/>Omodoko Extensioni Hostel is For Female Students and it contain just Eight Persons per Room.it has Two (2) block and it contains 20 rooms in each block it was buld in January 2013.</li>
						</ul></div>
						<div class="ws_bullets"><div>
						<a href="#" title="ENTRANCE VIEW OF AWOLOWO HOSTEL"><img src="INDEX_FILES/data1/tooltips/photo1116.jpg" alt="ENTRANCE VIEW OF SOFTWARE DEVELOPMENT UNIT"/>1</a>
						<a href="#" title="HORIZONTAL VIEW OF AYEGBA HOSTEL"><img src="INDEX_FILES/data1/tooltips/hd_house_wallpapers_widescreen_hd_wallpaper.jpg" alt="HORIZONTAL VIEW OF SDU ADMIN BLOCK"/>2</a>
						<a href="#" title="A VERTICAL VIEW OF AMINA HOSTEL"><img src="INDEX_FILES/data1/tooltips/img_09811024x768.jpg" alt="A VERTICAL VIEW OF SDU ADMIN BLOCK"/>3</a>
						<a href="#" title="A DIAGONALL VIEW OF AZIKIWE HOSTEL"><img src="INDEX_FILES/data1/tooltips/img00059.jpg" alt="A DIAGONALL VIEW OF SDU ADMIN BLOCK"/>4</a>
						<a href="#" title="A FRONT VIEW OF INIKPI HOSTEL"><img src="INDEX_FILES/data1/tooltips/lai_kok_estate.jpg" alt="A FRONT VIEW OF SDU ADMIN BLOCK"/>5</a>
						<a href="#" title="resizd2"><img src="INDEX_FILES/data1/tooltips/resizd2.jpg" alt="resizd2"/>6</a>
						<a href="#" title="DIAGONAL VIEW OF OMODOKO EXTENSION"><img src="INDEX_FILES/data1/tooltips/resized1.jpg" alt="DIAGONAL VIEW OF SIWES STUDENTS BLOCK"/>7</a>
						</div></div>
						<span class="wsl"><a href="#">bootstrap carousel</a> by WOWSlider.com v5.5</span>
							<div class="ws_shadow"></div>
							</div>
							<script type="text/javascript" src="INDEX_FILES/engine1/wowslider.js"></script>
							<script type="text/javascript" src="INDEX_FILES/engine1/script.js"></script>
				</div>
				<div class="col-xs-12 col-sm-12">
				<br><br><br>
				</div>
				<div class="col-xs-12 col-sm-12">
			
				</div>
			</div>
			<div  class="col-sm-3 col-md-3 col-lg-3 hidden-xs">
			       <div style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;">MEMBERS' LOGIN</span></center>
				     <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:15px;padding-bottom:5%">
		              	  <form class="form-horizontal" role="form"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="userlogin">
							 <div class="form-group">
							
								 <div class="col-lg-11">
								 <input type="text" class="form-control"  id="inputEmail1" name="inputEmail1" value="<?php echo $post_username; ?>" placeholder="Username@abumail.com">
								  </div>
							 </div>
							 <div class="form-group">
								 <div class="col-lg-11">
								 <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
								  </div>
							 </div>
							 
							 <div class="col-lg-12">
										<p id="loginerror"><?php echo $loginerror ?></p>
							</div>
							
							 <div class="form-group">
									<div class="col-lg-offset-1 col-lg-10">
										<div class="checkbox">
											<label>
												<input type="checkbox"> Remember me
											</label>
										</div>
									</div>
							</div>
							<div class="form-group">
							 <div class="col-lg-offset-1 col-lg-10">
						  <button type="submit" name="submitlogin" class="btn btn-success">Sign in</button>
						  </div>
						  </div>
						</form>
						<p ><a href="change_details.php">Click here</a> to Activate your Login Detail</p>
						<p ><a href="Retrieve_login_details.php">Click here</a> to Retrieve Login Details</p>
                  </div>
				   
				</div>
				<div id="NewsFeedTicker2" style="height:260px;background-color:#FFFFFF;">
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

<!-- Mirrored from www.tutorialrepublic.com/codelab.php?topic=bootstrap&file=responsive-layout by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Nov 2014 15:48:18 GMT -->
</html>  
