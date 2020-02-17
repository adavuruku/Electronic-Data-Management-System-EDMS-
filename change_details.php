<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';
//my error messages variables
$ouerror=$operror=$nperror=$npretypeerror=$generror="";
$result='<p id="result">Accepted Characters and symbols for user name  <b>( . _ A - Z a - z 1 - 0 ) </b> and a minnimum of 8 characters.</p>';	 

//name of textboxes
$rpassword=$npassword=$nusername=$cpassword=$cusername="";

/*$p="aabdulraheemsherif@sherif.com";
//$w=strlen($p);
$k = strpos($p,"@");
$E = substr($p,$k);
echo "this result is  = ".str_replace($E,"@abumail.com",$p)."<br>";*/
/*function turn_to_abu_mail($abumail)
{
	$final_result="";
	$k = strpos($abumail,"@");
	//check if user add @ sign
	if($k >= 0)
	{
		$E = substr($abumail,$k);
		$final_result = strtolower(str_replace($E,"@abumail.com",$abumail));		
	}
	else
	{
		$final_result = strtolower($abumail."@abumail.com");
	}
	return $final_result;
}*/

function check_username_exist($your_new_mail)
{
	global $conn;
	$d = checkempty($your_new_mail);
	$p = filterEmail($your_new_mail);
			if(($d != FALSE)|| ($p != FALSE))
			{
				$f = strip_tags($your_new_mail);
				$pword="1";
				$query2 = "SELECT username,change_password FROM student_information WHERE username =:username AND change_password=:change_password";
				$stmt2 = $conn->prepare($query2);
				$stmt2->bindValue(':username',$f, PDO::PARAM_STR);
				$stmt2->bindValue(':change_password',$pword, PDO::PARAM_STR);
				$stmt2->execute();
				$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
				$row_count2 = $stmt2->rowCount();
				if($row_count2 != 1)  	//if ($rows2['log_block'] == "1")
				{
					//loook for it in staff records tooo echo "";
					$query2 = "SELECT username,change_password  FROM staff_information WHERE username =:username AND change_password=:change_password";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':username',$f, PDO::PARAM_STR);
					$stmt2->bindValue(':change_password',$pword, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
					{
						 return FALSE;
					}
					else
					{
						 return $your_new_mail;
					}
				}
				else
				{
					 return FALSE;
				}
			}
			else
			{
				 return FALSE;
			}
}

function update_user_details($your_new_mail,$your_new_password,$type,$your_former_mail,$your_former_password){
	global $conn;
	$hardcode_password=SHA1($your_new_password);
	global $generror;
	if ($type =="staff"){
	
		$stmt = $conn->prepare("UPDATE staff_information SET username=?,password=?,change_password=? WHERE username=?  AND password=? Limit 1");
		$stmt->execute(array($your_new_mail,$hardcode_password,"1",$your_former_mail,$your_former_password));
		//$affected_rows = $stmt->rowCount();
		if($stmt == true) 
		{
	
			$generror='<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong>'.' Your Account Details was successfully Activated : Your Username : '.$your_new_mail.'
				</div>';
				$_SESSION['username'] = $your_new_mail;
				header("location: change_details_step2.php");	
		}
	}
	
	if ($type =="student"){
		
		$stmt = $conn->prepare("UPDATE student_information SET username=?,password=?,change_password=? WHERE username=?  AND password=? Limit 1");
		$stmt->execute(array($your_new_mail,$hardcode_password,"1",$your_former_mail,$your_former_password));
		//$affected_rows = $stmt->rowCount();
		if($stmt == true) 
		{

			$generror='<div class="alert alert-info">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong>'.' Your Account Details was successfully Activated : Your Username : '.$your_new_mail.'
				</div>';
				$_SESSION['username'] = $your_new_mail;
				//header("location: change_details_step2.php".$global);	
				header("location: change_details_step2.php");	
				
		}
	}
}
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{

	//check empty
	$rpassword=checkempty($_POST['rpassword']);
	$npassword=checkempty($_POST['npassword']);
	$nusername=checkempty($_POST['nusername']);
	$cpassword=checkempty($_POST['cpassword']);
	$cusername=checkempty($_POST['cusername']);
	if (($rpassword != FALSE) && ($npassword != FALSE) && ($nusername != FALSE) && ($cpassword != FALSE) && ($cusername != FALSE))
	{
		$rpassword=$_POST['rpassword'];
		$npassword=$_POST['npassword'];
		$nusername=$_POST['nusername'];
		$cpassword=$_POST['cpassword'];
		$cusername=$_POST['cusername'];
		//confirm if new user name is 8 character or more and is a valid email type
		
		$your_new_mail =strip_tags($_POST['nusername']);
		
		//Convert to Abu mail
		$your_new_mail= turn_to_abu_mail($your_new_mail);
		
		//CHECK IF USERNAME EXIST
		$your_new_mail_exist = check_username_exist($your_new_mail);
		//Get USERNAME Size
		$the_username_size = strlen($your_new_mail);
		//Make sure is a good USERNAME
		$mail_good = filterEmail($your_new_mail);
		//check new username size and its validity
		if(($the_username_size >= 20) && ($mail_good != FALSE)&& ($your_new_mail_exist != FALSE))
		{
			//confirm if new password and old password are same
			$your_new_password =strip_tags($_POST['npassword']);
			$your_new_password_retype =strip_tags($_POST['rpassword']);
			if($your_new_password == $your_new_password_retype)
			{
				
				//validate former email
				$your_former_mail = strip_tags($_POST['cusername']);
				$your_former_password = strip_tags($_POST['cpassword']);
				//turn it to abu mail
				$your_former_mail= turn_to_abu_mail($your_former_mail);
				
				$the_username_size = strlen($your_former_mail);
				$mail_good = filterEmail($your_former_mail);
				//check new username size and its validity
				if(($the_username_size >= 20) && ($mail_good != FALSE))
				{
					//confirm that the existing user name and password match a record in db and the record has not been activated before
					$f="0";
					$query2 = "SELECT username,password,change_password FROM student_information WHERE username =:username AND password=:password AND change_password=:change_password";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':username',$your_former_mail, PDO::PARAM_STR);
					$stmt2->bindValue(':password',$your_former_password, PDO::PARAM_STR);
					$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 != 1)  	//if ($rows2['log_block'] == "1")
					{
						//loook for it in staff records tooo
						$query2 = "SELECT username,password,change_password FROM staff_information WHERE username =:username AND password=:password AND change_password=:change_password";
						$stmt2 = $conn->prepare($query2);
						$stmt2->bindValue(':username',$your_former_mail, PDO::PARAM_STR);
						$stmt2->bindValue(':password',$your_former_password, PDO::PARAM_STR);
						$stmt2->bindValue(':change_password',$f, PDO::PARAM_STR);
						$stmt2->execute();
						$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
						$row_count2 = $stmt2->rowCount();
						if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
						{
							//CALL A FUNCTION TO ACTIVATE MAIL
							$type="staff";
							update_user_details($your_new_mail,$your_new_password,$type,$your_former_mail,$your_former_password);
						}
						else
						{
							$ouerror ="<p style='color:red;'>Error : is Either The Old User name and Password dont match or has been Activated Before you can <a href='#'>click here</a> to Recover Login Details</p>";
						}
					}
					else
					{
						//CALL A FUNCTION TO ACTIVATE MAIL
						$type="student"; 
						update_user_details($your_new_mail,$your_new_password,$type,$your_former_mail,$your_former_password);
					}
				}
				else
				{
					$ouerror ="<p style='color:red;'>Error : Either The Old User name is not up to 8 characters or more or contain invalid characters</p>";
				}	
			}
			else
			{
				$nperror ="<p style='color:red;'> Error :Your New Password is not thesame with your Confirmed Password</p>";
				$npretypeerror ="<p style='color:red;'>Error :Your  Confirmed Password is not Equal with your New Password</p>";
			}
		}
		else
		{
			$result ="<p style='color:red;' id='result'>Error : Either The new User name is not up to 8 characters or contain invalid characters...or is used by another ABU MAIL server User</p>";
		}	
	}
	else
	{
		$generror='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Some Fields are not Yet Selected ...Please Verify.
												</div>';
		$rpassword=$_POST['rpassword'];
		$npassword=$_POST['npassword'];
		$nusername=$_POST['nusername'];
		$cpassword=$_POST['cpassword'];
		$cusername=$_POST['cusername'];
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



  <script type="text/javascript">
	$(document).ready(function(){
		$("#myModal").modal('show');		
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
							 <a href="#" class="navbar-brand">History</a>
							 <a href="#" class="navbar-brand">About Us</a>
						</div>
						<!-- Collection of nav links, forms, and other content for toggling -->
						<div id="navbarCollapse" class="collapse navbar-collapse navedit">
							<ul class="nav navbar-nav">
								<li class="active"><a href="login_to_profile.php">Login</a></li>
								<li><a href="#">Register</a></li>
								 
							 </ul>
							<form role="search" class="navbar-form navbar-right">
								<div class="form-group">
								   <input type="text" placeholder="Search" class="form-control">
								</div>
								 <button type="submit" class="btn btn-default">Submit</button>
							</form>
							<ul class="nav navbar-nav navbar-right">
								<li><a href="FPI_HOSTEL_BANKS/">Search :</a></li>
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
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;">EDIT LOGIN DETILS</span></center>
				     <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
		              	   <?php echo $generror?>
						  <form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="adminlogin">
							 <div class="form-group">
								  <div class="col-lg-10">
									<label class="labelss" for="cusername">Enter Current User Name:</label>						
										<div class="input-group">
											
											<input type="text" class="form-control" id="cusername" name="cusername" placeholder="Enter Current User Name">
											<span class="input-group-addon">@abumail.com</span>
										</div>
								</div>
							</div> 
							<div class="col-lg-12">
										<p id="ouerror"><?php echo $ouerror ?></p>
							</div>
							  <div class="form-group">
							  
								 <div class="col-lg-10">
								 <label class="labelss" for="cpassword">
												Enter Current Password
								</label>
								 <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Enter Current Password">
								  </div>
							 </div>
							 <div class="col-lg-12">
										<p id="operror"><?php echo $operror ?></p>
							</div>
							 <div class="col-lg-12">
										<p >Cant Remember My Existing Login Details <a href="change_details_step2.php">Click here</a> to Retrieve</p>
										 <hr style="border-color:green">
							</div>
									
							 
							
							  
							 <div class="form-group">
									<div class="col-lg-10">
										<div class="checkbox">
											<label>
												<input type="checkbox" id="samedusername" onclick="samedetails1();">Use My Existing User_Name
											</label>
										</div>
									</div>
							</div>
							 
							 <div class="form-group">
								  <div class="col-lg-10">
									<label class="labelss" for="nusername">Enter New User Name:</label>						
										<div class="input-group">
											
											<input type="text" class="form-control" id="nusername" name="nusername" onblur="check_existing_username()" onkeypress="wipeboxeror('1')" placeholder="Enter New User Name">
											<span class="input-group-addon">@abumail.com</span>
										</div>
								</div>
							</div> 	
							 <div class="col-lg-12">
							 
										<?php echo $result ?>
							</div>
							<div class="form-group">
									<div class="col-lg-10">
										<div class="checkbox">
											<label>
												<input type="checkbox" id="samepassword" onclick="samedetails2();">Use My Existing Password
											</label>
										</div>
									</div>
							</div>
							
							
							  <div class="form-group">
							  
								 <div class="col-lg-10">
								 <label class="labelss" for="npassword">
												Enter New Password
								</label>
								 <input type="password" class="form-control" id="npassword" name="npassword" onblur="confirm_password2()" onkeypress="wipeboxeror('2')" placeholder="Enter New Password">
								  </div>
							 </div>
							 <div class="col-lg-12">
										<p id="nperror"><?php echo $nperror ?></p>
							</div>
							 <div class="form-group">
							 
								 <div class="col-lg-10">
								  <label class="labelss" for="rpassword">
												Confirm New Password
								</label>
								 <input type="password" class="form-control" id="rpassword" name="rpassword" onpaste="return false" onblur="confirm_password()" onkeypress="wipeboxeror('3')" placeholder="Re Type New Password">
								  </div>
							 </div>
							<div class="col-lg-12">
							 
										<p id="npretypeerror"> <?php echo $npretypeerror ?></p>
							</div>
							<div class="form-group">
							 <div class=" col-lg-10">
						  <button type="submit" name="submit" class="btn btn-success">Update Detail</button>
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

<!-- Mirrored from www.tutorialrepublic.com/codelab.php?topic=bootstrap&file=responsive-layout by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Nov 2014 15:48:18 GMT -->
</html>  
