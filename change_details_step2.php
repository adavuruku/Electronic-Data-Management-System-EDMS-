<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';
$email = $result="";

//send mail function
 function send_email_to_user($emailc, $username)
{
	global $conn;
	global $result;
	require 'PHPMailer-master/PHPMailerAutoload.php';
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "aabdulraheemsherif@gmail.com";
	$mail->Password = "sherif419419";
	$mail->setFrom('aabdulraheemsherif@gmail.com', 'Ahmadu Bello University Bauchi - Mail Server');
	
	//$mail->addReplyTo('aabdulraheemsherif@gmail.com', 'Federal Polytechnic Idah Hostels');
	$mail->addAddress($emailc, $username);
	$mail->Subject = $username." ".'ABU Mail Server Activation Mail';
	$message = '<html>
		<body bgcolor="#5F9EA0">
		Hi ' .$username. ',
		<br /><br />
		<P style="color:black;font-weight:bold">Thanks For Acivating Your Account at ABU MAIL SERVER - We promise to offer you the best of services</P>
		<br /><br />
		Your Login Data is as follows: 
		<br /><br />
		
		User Name at ABU Server : ' . $username . ' <br />
		Activation E-mail Address: ' . $emailc . ' <br />
		Password: #########
		<br /><br /> 
		Thanks Once more ...Click on these Button to finish Activation of your Account <br /><br /> 
		<a style="color:yellow;background-color:#5F9EA0;padding:10px;text-decoration:none;font-size:25;font-family: comic sans ms;font-weight:bold" href="Sherif-pc/ABU_MAIL_SERVER/Account_Login.php?emailid=' .$emailc. '"> Activate </a>
		<br /><br />
		Thanks! <br />
		Jimoh Hadi O <br />
		ABU Mail Server Administrator.
		</body>
		</html>';
	$mail->Body=$message;
	$mail->AltBody=$message;
	if ($mail->send())
	{
	   $result ="<p style='color:red;' id='result'>Success : Thanks.. Please Log In to Your Email : ".$emailc." and follow the Link Sent to you to Completely Activate Your Account</p>";
	}
	else
	{
		$result ="<p style='color:red;' id='result'>Error : We were Unable to Send an Activation Email to Your Email : ".$emailc." do Please Retry</p>";
		
		//roll back the email activation process
		$statuu ="";
		$stmt = $conn->prepare("UPDATE student_information SET email=? WHERE username=? Limit 1");
		$stmt->execute(array($statuu,$username));
		if($stmt == false) 
		{
			$stmt = $conn->prepare("UPDATE staff_information SET email=? WHERE username=? Limit 1");
			$stmt->execute(array($statuu,$username));
		}
	}
}



if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{

	//check empty
	$email=checkempty($_POST['cusername']);
	$email2=filterEmail($_POST['cusername']);
	if(($email != FALSE) && ($email2 != FALSE))
	{
		$email=$_POST['cusername'];
		//then update its emaill in database
		$stmt = $conn->prepare("UPDATE staff_information SET email=? WHERE username=? Limit 1");
		$stmt->execute(array($email,$_SESSION['username']));
		$affected_rows = $stmt->rowCount();
		if($stmt == false) 
		{
			$stmt = $conn->prepare("UPDATE student_information SET email=? WHERE username=? Limit 1");
			$stmt->execute(array($email,$_SESSION['username']));
			$affected_rows = $stmt->rowCount();
			if($stmt == true) 
			{
				$result ="<p style='color:red;' id='result'>Error : Unable to Activate Your Email ..retry</p>";
				send_email_to_user($email,$_SESSION['username']);
				
			}
			else
			{
				$result ="<p style='color:red;' id='result'>Error : Unable to Send Activation Email to Your Mail ..retry</p>";
			}
		}
		else
		{
			send_email_to_user($email,$_SESSION['username']);
		}
		
	
	}
	
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
	.form-group{
	
	padding:4px 3px 2px 3px;
	}
</style>
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
							 <a href="index.php" class="navbar-brand">Log In</a>

							 <a href="#" class="navbar-brand"></a>
						</div>
						<!-- Collection of nav links, forms, and other content for toggling -->
						<div id="navbarCollapse" class="collapse navbar-collapse navedit">
							<ul class="nav navbar-nav">	 
							 </ul>
							
							<ul class="nav navbar-nav navbar-right">
								<li><a href="#"><?php echo $_SESSION['username']; ?> </a></li>
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
			
			<div  class="col-sm-9 col-md-9 col-lg-9 hidden-xs">
			       <div style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;"> <?php echo $_SESSION['username']; ?> - Complete Your Account Activation</span></center>
				     <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
						<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
								<div class="col-lg-12">
											<p> This step will help us manage Your account for any future assistance - please enter any other email address you use (example - sam@gmail or rude459@yahoomail)</p>
											<p> We dont require for your Password just the Email Address - we promise to keep it save from  any form of Spam</p>
									<hr>
									<p>Activate Your User Name : <?php echo $_SESSION['username']; ?>  -  at ABU Mail Server </p>
								</div>
							
								<div class="form-group">
									<div class="col-lg-10">
										<label class="labelss" for="cusername">Enter Your Email Address: <span style="color:red"> *</span></label>						
												<input type="email" class="form-control" id="cusername" name="cusername"  value="<?php echo $email; ?>" onblur="check_existing_activation_email()" onkeypress="wipeboxeror('20')" placeholder="Enter Your Email Address example - abuscience@gmail.com">
									</div>
								</div>
								<div class="col-lg-12" id="result">
										<?php echo $result;?>
								</div>
								
								<div class="form-group">
									<div class=" col-lg-10">
											<button type="submit" name="submit" class="btn btn-success">Complete Activation</button>
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
