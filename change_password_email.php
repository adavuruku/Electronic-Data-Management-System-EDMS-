<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';
$email = $result=$username=$email_address=$data="";


//get the incoming datas from users email link

if ((isset($_GET['emailid'])) && (isset($_GET['mail'])) && (isset($_GET['data'])))
{	
	$username = filterEmail($_GET['emailid']);
	$email_address = filterEmail($_GET['mail']);
	if(($username != FALSE) && ($email_address != FALSE))
	{
		$username = htmlspecialchars($_GET['emailid']);
		$email_address = htmlspecialchars($_GET['mail']);
		$data = htmlspecialchars($_GET['data']);

		$username = strip_tags($username);
		$email_address = strip_tags($email_address);
		$data = strip_tags($data);
		$data = SHA1($data);
		
		//make sure user realy request for change of password for security reasons
		$f="0";
		$query2 = "SELECT email_used,status,username,Gen_Code FROM change_password WHERE username =:username AND status=:status AND email_used=:email_used AND Gen_Code=:Gen_Code";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':username',$username, PDO::PARAM_STR);
		$stmt2->bindValue(':status',$f, PDO::PARAM_STR);
		$stmt2->bindValue(':email_used',$email_address, PDO::PARAM_STR);
		$stmt2->bindValue(':Gen_Code',$data, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 != 1)
		{
			header("location: index.php");
		}
	}
	else
	{
		header("location: index.php");
	}
	
}
else
{
	header("location: index.php");
}



//save the record to db for user has rquest for change of password
function go_update_password($username,$email,$data)
{
	global $conn;
	global $result;
	$generated_code= SHA1($data);
	//update its existing record to a used link
	$stmt = $conn->prepare("UPDATE change_password SET status=? WHERE username =? AND status=? AND email_used=? AND Gen_Code=?");
	$stmt->execute(array('1',$username,'0',$email,$data));
	$affected_rows = $stmt->rowCount();
	if($stmt == True) 
	{
		$result = "<p style='color:red;'>Succes : Your Password has been Change Succesfully  <a href='Index.php'>Click Here </a> TO Login to your Account </p>";
	}
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{
	$f="1";
	$new_passord = checkempty($_POST['npassword']);
	$confirm_password = checkempty($_POST['rpassword']);
	$username = checkempty($_POST['username']);
	$email = checkempty($_POST['email']);
	$data = checkempty($_POST['data']);
	
	$username_verify = filterEmail($_POST['username']);
	$email_verify = filterEmail($_POST['email']);
	
	if(($new_passord != FALSE) && ($confirm_password != FALSE)&& ($username != FALSE)&& ($email != FALSE)&& ($data != FALSE)&& ($username_verify != FALSE)&& ($email_verify != FALSE))
	{
		$new_passord = strip_tags($_POST['npassword']);
		$confirm_password = strip_tags($_POST['rpassword']);
		$username = strip_tags($_POST['username']);
		$email = strip_tags($_POST['email']);
		$data = strip_tags($_POST['data']);
		//compare the passwords
		if ($new_passord == $confirm_password)
		{
			//update the users password
			$new_passord = SHA1($new_passord);
			$stmt = $conn->prepare("UPDATE student_information SET password=? WHERE username=? Limit 1");
			$stmt->execute(array($new_passord,$username));
			//$affected_rows = $stmt->rowCount();
			if($stmt == False) 
			{
				$stmt = $conn->prepare("UPDATE staff_information SET password=? WHERE username=? Limit 1");
				$stmt->execute(array($new_passord,$username));
				//$affected_rows = $stmt->rowCount();
				if($stmt == True) 
				{
					go_update_password($username,$email,$data);
				}
				else
				{
					$result = "<p style='color:red;'>Error : we were Unable to Update Your Password ..Retry</p>";//.//$affected_rows.$username;
				}
			}
			else
			{
				go_update_password($username,$email,$data);
			}
		}
		else
		{
			$result = "<p style='color:red;'>Error : The new Password can be verified with the Retyped Password</p>";
		}
	}
	else
	{
		$result = "<p style='color:red;'>Error : Some data are not Yet Entered ...retry</p>";
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
<link rel="stylesheet" type="text/css" href="INDEX_FILES/index.css" >
<script type="text/javascript" src="INDEX_FILES/change_password_email.js"></script>

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
								<li><a href="#"> <?php echo $username; ?> </a></li>
							</ul>
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
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;"> <?php echo $email_address; ?> - Complete Your Account Retrieval Request</span></center>
				    <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
						<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
											<p> This Page will help us to complete The Account Retrieval Details You requested for at ABU Mail Server </p>
											<p> To Complete The Account Details Retrieval you must set up a password for the User name : <?php echo $username; ?> : to become Valid at ABU Mail Server </p>
									<hr>
									<p>Reset Your Login Password For : <?php echo $username; ?>  -  at ABU Mail Server </p>
								
							
					
						
									<div class="form-group">
											<label class="control-label col-xs-3" for="npassword">Enter New Password : <span style="color:red"> *</span></label>	
												<div class="col-xs-4">
													<input type="text" class="form-control"  id="npassword" name="npassword"  onkeypress="wipeboxeror('21')" placeholder="">
												</div>
												<div class="col-lg-5" id="nperror">
												
												</div>
									</div>
									<div class="form-group">
											<label class="control-label col-xs-3" for="rpassword">Confirm New Password : <span style="color:red"> *</span></label>	
												<div class="col-xs-4">
													<input type="text" class="form-control" id="rpassword" name="rpassword" onfocus="confirm_password3()" onblur="confirm_password2()" onkeypress="wipeboxeror('21')" placeholder="">
												</div>
									<div class="col-lg-5" id="rperror">
											
									</div>
									</div>
									
									<input type="hidden" name="username" value="<?php echo $username;?>" ></input>
									<input type="hidden" name="email" value="<?php echo $email_address; ?>" ></input>
									<input type="hidden" name="data" value="<?php echo $data; ?>" ></input>
									
									<div class="form-group">
										<div class="col-md-offset-3 col-lg-10">
												<button type="submit" name="submit" class="btn btn-success">Complete Activation</button>
										</div>
									</div>
									<div  class="col-md-offset-3 col-lg-10" >
												<?php echo $result;?>
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
