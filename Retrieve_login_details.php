<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';
$submittab=$email1=$result1=$email2=$result2=$email3=$result3 = "";


//send mail function
function send_email_to_user($emailc, $username, $submittab2,$generated_code)
{
	global $conn;
	global $result1;
	global $result2;
	global $result3;
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
	$mail->Subject = $username." ".'ABU Mail Server Change Password Mail';
	$message = '<html>
		<body bgcolor="#5F9EA0">
		Hi ' .$username. ',
		<br /><br />
		<P style="color:black;font-weight:bold">Use The Link bellow to Change Login Details At ABU SERVER </P>
		<br /><br />
		Your Login Data is as follows: 
		<br /><br />
		
		User Name at ABU Server : ' . $username . ' <br />
		Activation E-mail Address: ' . $emailc . ' <br />
		Password: #########
		<br /><br /> 
		You can Click on these Button Bellow to Change Your Password <br /><br /> 
		<a style="color:yellow;background-color:#5F9EA0;padding:10px;text-decoration:none;font-size:25;font-family: comic sans ms;font-weight:bold" href="Sherif-pc/ABU_MAIL_SERVER/change_password_email.php?emailid=' .$username.'&mail='.$emailc.'&data='.$generated_code.'"> Proceed </a>
		<br /><br />
		Regards! <br />
		Jimoh Hadi O <br />
		ABU Mail Server Administrator.
		</body>
		</html>';
	$mail->Body=$message;
	$mail->AltBody=$message;
	if ($mail->send())
	{
		if($submittab2=="1")
		{
			$result1 ="<p style='color:red;' id='result'>Success : An Email Containing a Link to Reset Your Account Password with your User Name has been Sent to Your Activation Email : ".$emailc." and follow the Link in the Mail to revive your Account</p>";
			$result2=$result3="";
		}
		if($submittab2=="2")
		{
			$result2 ="<p style='color:red;' id='result'>Success : An Email Containing a Link to Reset Your Account Password with your User Name has been Sent to Your Activation Email : ".$emailc." and follow the Link in the Mail to revive your Account</p>";
			$result1=$result3="";
		}
		if($submittab2=="3")
		{
			$result3 ="<p style='color:red;' id='result'>Success : An Email Containing a Link to Reset Your Account Password with your User Name has been Sent to Your Activation Email : ".$emailc." and follow the Link in the Mail to revive your Account</p>";
			$result1=$result2="";
		}
		
		//save the link record to database so other users can not manipulate the link to
		//change another persons password
		save_to_db($username,$emailc,$generated_code);
	}
	else
	{
		$result ="<p style='color:red;' id='result'>Error : We were Unable to Send an Activation Email to Your Email : ".$emailc." do Please Retry</p>";
		
		if($submittab2=="1")
		{
			$result1 ="<p style='color:red;' id='result'>Error : We were Unable to Send an Activation Email to Your Email : ".$emailc." do Please Retry</p>";
			$result2=$result3="";
		}
		if($submittab2=="2")
		{
			$result2 ="<p style='color:red;' id='result'>Error : We were Unable to Send an Activation Email to Your Email : ".$emailc." do Please Retry</p>";
			$result1=$result3="";
		}
		if($submittab2=="3")
		{
			$result3 ="<p style='color:red;' id='result'>Error : We were Unable to Send an Activation Email to Your Email : ".$emailc." do Please Retry</p>";
			$result1=$result2="";
		}
	}
}

//generate a sub code
function rendPwd($pwd_length = 6)
{
		$pwd_length = $pwd_length;
		$possible_letters = implode(range(0, 9)).implode(range('a', 'z')).implode(range('A', 'Z')).'@_^';
		$alphaLength = strlen($possible_letters) - 1; //put the length -1 in cache
		$code = '';
		$i = 0;
		while($i < $pwd_length)
		{
			$code .=  substr($possible_letters, mt_rand(0, $alphaLength), 1);
			$i++;
		}
	return $code;
}
//save the record to db for user has rquest for change of password
function save_to_db($username,$emailc,$generated_code)
{
	global $conn;
	$generated_code= SHA1($generated_code);
	//update its existing record to a used link
	$stmt = $conn->prepare("UPDATE change_password SET status=? WHERE username=?");
	$stmt->execute(array('1',$username));
	if($stmt == true) 
	{
		//create a new record for user so another person can not manipulate the change password link to edit other users password
		$sth = $conn->prepare ("INSERT INTO change_password (email_used,status,username,Gen_Code)
																VALUES (?,?,?,?)");															
				$sth->bindValue (1, $emailc); 
				$sth->bindValue (2, '0'); 
				$sth->bindValue (3, $username); 
				$sth->bindValue (4, $generated_code); 
				$sth->execute();
	}
}

/* Note: before a user can request for change of password the user must have activate its Account before or the user is already using 
ABU Mail Server - any new user that have not activate its Account can not request for a new Password*/

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit1']))
{
$submittab="1";
$f="1";
	//check empty
	$email1=checkempty($_POST['email1']);
	if(($email1 != FALSE))
	{
		$email1= strip_tags($email1);
		$your_new_mail= turn_to_abu_mail($email1);
		$good_mail=filterEmail($your_new_mail);
		if(($good_mail != FALSE))
		{
			//check user username validity to retrieve its details
					
					//generate random code
					$generated_code= rendPwd();
					//$generated_code= SHA1($generated_code);
					
					$f="1";
					$query2 = "SELECT username,change_password,email_activate,email FROM student_information WHERE username =:username AND change_password=:change_password AND email_activate=:email_activate";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':username',$your_new_mail, PDO::PARAM_STR);
					$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
					$stmt2->bindValue(':email_activate',$f, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 != 1)
					{
						$query2 = "SELECT username,change_password,email_activate,email FROM staff_information WHERE username =:username AND change_password=:change_password AND email_activate=:email_activate";
						$stmt2 = $conn->prepare($query2);
						$stmt2->bindValue(':username',$your_new_mail, PDO::PARAM_STR);
						$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
						$stmt2->bindValue(':email_activate',$f, PDO::PARAM_STR);
						$stmt2->execute();
						$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
						$row_count2 = $stmt2->rowCount();
						if($row_count2 == 1)
						{
							//call function to send email
							$username = $rows3['username'];
							$emailc = $rows3['email'];
							
							send_email_to_user($emailc, $username,$submittab,$generated_code);
							
						}
						else
						{
							$result1="<p style='color:red;'>Error : User Name Dont Correspond with Any User Name in ABU SERVER User List or You have Not Activate This User Name Before To Activate the Account <a href='change_details.php'>Click Here </a></p>";
						}
					}
					else
					{
						//call function to send email
							$username = $rows3['username'];
							$emailc = $rows3['email'];
							send_email_to_user($emailc, $username,$submittab,$generated_code);
					}
		}
		else
		{
			$result1="<p style='color:red;'>Error : User Name Is Invalid try a Valid type i.e = abuscience@abumail.com </p>";
		}
	}
	else
	{
		$result1="<p style='color:red;'>Error : User Name Box Cant Be Empty</p>";
	}
}



if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2']))
{
$submittab="2";
$f="1";
	//check empty
	//generate random code
					$generated_code= rendPwd();
					//$generated_code= SHA1($generated_code);
	$email2=checkempty($_POST['email2']);
	if(($email2 != FALSE))
	{
		$email2= strip_tags($_POST['email2']);
		$your_new_mail= $email2;
		$good_mail=filterEmail($email2);
		if(($good_mail != FALSE))
		{
			//check user username validity to retrieve its details
					$f="1";
					$query2 = "SELECT username,change_password,email_activate,email FROM student_information WHERE email =:email AND change_password=:change_password AND email_activate=:email_activate";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':email',$your_new_mail, PDO::PARAM_STR);
					$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
					$stmt2->bindValue(':email_activate',$f, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 != 1)
					{
						$query2 = "SELECT username,change_password,email_activate,email FROM staff_information WHERE email =:email AND change_password=:change_password AND email_activate=:email_activate";
						$stmt2 = $conn->prepare($query2);
						$stmt2->bindValue(':email',$your_new_mail, PDO::PARAM_STR);
						$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
						$stmt2->bindValue(':email_activate',$f, PDO::PARAM_STR);
						$stmt2->execute();
						$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
						$row_count2 = $stmt2->rowCount();
						if($row_count2 == 1)
						{
							//call function to send email
							$username = $rows3['username'];
							$emailc = $rows3['email'];
							send_email_to_user($emailc, $username,$submittab,$generated_code);
							
						}
						else
						{
							$result2="<p style='color:red;'>Error : User Name Dont Correspond with Any User Name in ABU SERVER User List or You have Not Activate This User Name Before To Activate the Account <a href='change_details.php'>Click Here </a></p>";
						}
					}
					else
					{
						//call function to send email
							$username = $rows3['username'];
							$emailc = $rows3['email'];
							send_email_to_user($emailc, $username,$submittab,$generated_code);
					}
		}
		else
		{
			$result2="<p style='color:red;'>Error : Email Address Is Invalid try a Valid Email </p>";
		}
	}
	else
	{
		$result2="<p style='color:red;'>Error : Email Box Cant Be Empty</p>";
	}
}


//search with staff no or registration no

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit3']))
{
	$submittab="3";
$f="1";
	//check empty
	$generated_code= rendPwd();
	//$generated_code= SHA1($generated_code);
	$email3=checkempty($_POST['email3']);
	if(($email3 != FALSE))
	{
		$email3= strip_tags($_POST['email3']);
		$your_new_mail= $email3;
		//$good_mail=filterEmail($email2);
			//check user username validity to retrieve its details
					$f="1";
					$query2 = "SELECT username,student_id,change_password,email_activate,email FROM student_information WHERE student_id =:student_id AND change_password=:change_password AND email_activate=:email_activate";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':student_id',$your_new_mail, PDO::PARAM_STR);
					$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
					$stmt2->bindValue(':email_activate',$f, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 != 1)
					{
						$query2 = "SELECT username,staff_id,change_password,email_activate,email FROM staff_information WHERE staff_id =:staff_id AND change_password=:change_password AND email_activate=:email_activate";
						$stmt2 = $conn->prepare($query2);
						$stmt2->bindValue(':staff_id',$your_new_mail, PDO::PARAM_STR);
						$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
						$stmt2->bindValue(':email_activate',$f, PDO::PARAM_STR);
						$stmt2->execute();
						$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
						$row_count2 = $stmt2->rowCount();
						if($row_count2 == 1)
						{
							//call function to send email
							$username = $rows3['username'];
							$emailc = $rows3['email'];
							send_email_to_user($emailc, $username,$submittab,$generated_code);
							
						}
						else
						{
							$result3="<p style='color:red;'>Error : User Name Dont Correspond with Any User Name in ABU SERVER User List or You have Not Activate This User Name Before To Activate the Account <a href='change_details.php'>Click Here </a></p>";
						}
					}
					else
					{
						//call function to send email
							$username = $rows3['username'];
							$emailc = $rows3['email'];
							send_email_to_user($emailc, $username,$submittab,$generated_code);
					}
	}
	else
	{
		$result3="<p style='color:red;'>Error : Email Box Cant Be Empty</p>";
	}
}





if (isset($_GET['tabb'])){
$submittab=$_GET['tabb'];
}
$back_link = "Register_student.php?tabb=".$submittab;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Ahmadu Bello University - Zaria Nigeria</title>
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
<script type="text/javascript" src="INDEX_FILES/reg_deptchange.js"></script>
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

<script type="text/javascript">	
$(document).ready(function()
{
	var j = <?php echo $submittab;?>;
	if(j=="1"){
		 $('#myTab li:eq(0) a').tab('show');
	}
	else if(j=="2"){
		 $('#myTab li:eq(1) a').tab('show');
	}
	else{
	 $('#myTab li:eq(2) a').tab('show');
	}
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
							 <a href="index.php" class="navbar-brand">Home</a>
							 <a href="change_password_email.php" class="navbar-brand">History</a>
							 <a href="#" class="navbar-brand">About Us</a>
						</div>
						<!-- Collection of nav links, forms, and other content for toggling -->
						<div id="navbarCollapse" class="collapse navbar-collapse navedit">
							
						</div>
					</nav>
			</div>
		</div>
	</div>
	
	<!-- middle content starts here where vertical nav slides and news ticker statr -->
	<div class="row">
        <div class="col-xs-12 col-sm-12">
			
			
			<div  class="col-sm-12 col-md-12 col-lg-12 hidden-xs">
			       <div style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:25px;">Use Any Of The Options Bellow To Retrieve Your Login Details <a style="color:red;font-size:15px;" href="">Log In</a> </span></center>
					 		
							<div class="tabbable">
								<div class="tabbable" style="background-color:#F08080;border-top-left-radius:1px;margin-top:5%;border-top-right-radius:1px">
									<ul id="myTab" class="nav nav-tabs">
										<li class="active taaab"><a data-toggle="tab" href="#dA"><span class="glyphicon glyphicon-pencil"></span> Use Your ABU Server User Name</a></li>
										<li class="tabss taaab"><a  data-toggle="tab" href="#dB"><span class="glyphicon glyphicon-pencil"></span> Use Your ABU Server Activation Email </a></li>
										<li class="tabss taaab"><a  data-toggle="tab" href="#dC"><span class="glyphicon glyphicon-pencil"></span> Use Your ABU School Registration / Staff ID </a></li>
									</ul>
								</div>
							<!--  tabs contents details begin  -->
					<div class="tab-content tabCONT2  style="padding:0px;margin:0px">
									<!-- staff registraion -->
									<div id="dA" class="tab-pane active ">
									 <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-right:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
										<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step1">
											An Email containing A Link to Reactivate Your Account will be Sent to the Emaill Address You Used For Activating Your Account at ABU Server
											<hr style="border-color:green">
												
												<div class="form-group">
										<div class="col-lg-10">
											<label class="labelss" for="cusername">Enter Your ABU Mail Server User Name : <span style="color:red"> *</span></label>						
													<input type="email" class="form-control" id="email1" name="email1"  value="<?php echo $email1; ?>" onblur="check_existing_activation_email()" onkeypress="wipeboxeror('20')" placeholder="Enter Your Email Address example - abuscience@gmail.com">
										</div>
									</div>
									<div class="col-lg-12" id="result">
											<?php echo $result1;?>
									</div>
									
									<div class="form-group">
										<div class=" col-lg-10">
												<button type="submit" name="submit1" class="btn btn-success"> Validate Details </button>
										</div>
									</div>
									</form>
								</div>
								</div>							
									<!-- student registraion -->
						<div id="dB" class="tab-pane">
								 <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
										<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
											An Email containing A Link to Reactivate Your Account will be Sent to the Emaill Address You Used For Activating Your Account at ABU Server
											<hr style="border-color:green">
												
												<div class="form-group">
										<div class="col-lg-10">
											<label class="labelss" for="cusername">Enter Your ABU Mail Server Activation Email Address : <span style="color:red"> *</span></label>						
													<input type="email" class="form-control" id="email2" name="email2"  value="<?php echo $email2; ?>" onblur="check_existing_activation_email()" onkeypress="wipeboxeror('20')" placeholder="Enter Your Email Address example - abuscience@gmail.com">
										</div>
									</div>
									<div class="col-lg-12" id="result">
											<?php echo $result2;?>
									</div>
									
									<div class="form-group">
										<div class=" col-lg-10">
												<button type="submit" name="submit2" class="btn btn-success"> Validate Details </button>
										</div>
									</div>
									</form>
												
												
								
								
								</div>
						</div>	
						<div id="dC" class="tab-pane">
								<div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-right:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
											<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step3">
											An Email containing A Link to Reactivate Your Account will be Sent to the Emaill Address You Used For Activating Your Account at ABU Server
											<hr style="border-color:green">
												
												<div class="form-group">
										<div class="col-lg-10">
											<label class="labelss" for="cusername">Enter Your Email Address: <span style="color:red"> *</span></label>						
													<input type="text" class="form-control" id="email3" name="email3"  value="<?php echo $email3; ?>" onblur="check_existing_activation_email()" onkeypress="wipeboxeror('20')" placeholder="Enter Your Email Address example - abuscience@gmail.com">
										</div>
									</div>
									<div class="col-lg-12" id="result">
											<?php echo $result3;?>
									</div>
									
									<div class="form-group">
										<div class=" col-lg-10">
												<button type="submit" name="submit3" class="btn btn-success"> Validate Details </button>
										</div>
									</div>
									</form>
								</div>
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
</body>

<!-- Mirrored from www.tutorialrepublic.com/codelab.php?topic=bootstrap&file=responsive-layout by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Nov 2014 15:48:18 GMT -->
</html>  
