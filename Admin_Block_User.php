<?php
session_start(); 
require_once 'settings/connection.php';
require_once 'settings/filter.php';
$submittab="1";	$display=$display2="";
$passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
if (isset($_GET['tabb'])){
$submittab=$_GET['tabb'];;
}
else
{
	$submittab=$_POST['inputhide1'];
}

//activation and deactivation coding				
if (isset($_GET['masaca']))
{
	$username = $_GET['masaca'];
	$type_block = $_GET['tabb'];

	if ($type_block =="1"){

	//maills
		$work = $_GET['work'];
		if ($work =="permit")
		{
			$j="";
			$query301 = "UPDATE student_information SET close_account=? WHERE username=?";
			$stmt301 = $conn->prepare($query301);
			$stmt301->execute(array($j,$username));
			if($stmt301 == True){
				$display= "<p style='color:red' >".$username." Maill account was succesfully Permitted".'</p>';
			}
			else
			{
				$display= "<p style='color:red' >"." Unable to Permit ".$username." Mail account Please Retry ".'</p>';
			}
			//staff
			$query301 = "UPDATE staff_information SET close_account=? WHERE username=?";
			$stmt302 = $conn->prepare($query301);
			$stmt302->execute(array($j,$username));
			if($stmt302 == True){
				$display2= "<p style='color:red' >".$username." Maill account was succesfully Permitted".'</p>';
			}
			else
			{
				$display2= "<p style='color:red' >"." Unable to Permit ".$username." Mail account Please Retry ".'</p>';
			}
		}
		if ($work =="block")
		{
			$j="1";
			$query301 = "UPDATE student_information SET close_account=? WHERE username=?";
			$stmt301 = $conn->prepare($query301);
			$stmt301->execute(array($j,$username));
			if($stmt301 == True){
				$display= "<p style='color:red' >".$username." Maill account was succesfully Blocked".'</p>';
			}
			else
			{
				$display= "<p style='color:red' >"." Unable to Block ".$username." Mail account Please Retry ".'</p>';
			}
			//staff
			$query301 = "UPDATE staff_information SET close_account=? WHERE username=?";
			$stmt302 = $conn->prepare($query301);
			$stmt302->execute(array($j,$username));
			if($stmt302 == True){
				$display= "<p style='color:red' >".$username." Maill account was succesfully Blocked".'</p>';
			}
			else
			{
				$display= "<p style='color:red' >"." Unable to Block ".$username." Mail account Please Retry ".'</p>';
			}
		}

	}

	if ($type_block =="2"){

	//publications
		$work = $_GET['work'];
		if ($work =="permit")
		{
			//student
			$j="";
			$query301 = "UPDATE student_information SET close_publication=? WHERE username=?";
			$stmt301 = $conn->prepare($query301);
			$stmt301->execute(array($j,$username));
			if($stmt301 == True){
				$display2= "<p style='color:red' >".$username." Publication account was succesfully Permitted".'</p>';
			}
			else
			{
				$display2= "<p style='color:red' >"." Unable to Permit ".$username." Publication account Please Retry ".'</p>';
			}
			//staff
			$query301 = "UPDATE staff_information SET close_publication=? WHERE username=?";
			$stmt302 = $conn->prepare($query301);
			$stmt302->execute(array($j,$username));
			if($stmt302 == True){
				$display2= "<p style='color:red' >".$username." Publication account was succesfully Permitted".'</p>';
			}
			else
			{
				$display2= "<p style='color:red' >"." Unable to Permit ".$username." Publication account Please Retry ".'</p>';
			}
		}
		if ($work =="block")
		{
			$j="1";
			$query301 = "UPDATE student_information SET close_publication=? WHERE username=?";
			$stmt301 = $conn->prepare($query301);
			$stmt301->execute(array($j,$username));
			if($stmt301 == True){
				$display2= "<p style='color:red' >".$username." Publication account was succesfully Blocked".'</p>';
			}
			else
			{
				$display2= "<p style='color:red' >"." Unable to Block ".$username." Publication account Please Retry ".'</p>';
			}
			//staff
			$query301 = "UPDATE staff_information SET close_publication=? WHERE username=?";
			$stmt302 = $conn->prepare($query301);
			$stmt302->execute(array($j,$username));
			if($stmt302 == True){
				$display2= "<p style='color:red' >".$username." Publication account was succesfully Blocked".'</p>';
			}
			else
			{
				$display2= "<p style='color:red' >"." Unable to Block ".$username." Publication account Please Retry ".'</p>';
			}
		}

	}
}




//deactivating publication

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit1']) )
{
	$mail = checkempty($_POST['inputpin1']);
	if(($mail != FALSE))
	{$display2="";
		$mail = turn_to_abu_mail($_POST['inputpin1']);
		
		$mail2 = filterEmail($mail);
		if(($mail != FALSE))
		{
			//search records
			$mail = turn_to_abu_mail($_POST['inputpin1']);
			$stmt = $conn->prepare("SELECT * FROM student_information WHERE username=?");	
			$stmt->execute(array($mail));
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmt->rowCount () >= 1)
			{
				if ($rows['pic_extension'] != ""){
					$paspart = "abu_file/".$mail.$rows['pic_extension'];
					$passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
				}
				$path1= "Admin_Block_User.php?tabb=".$submittab."&work=block&masaca=".$rows['username'];
				$path2= "Admin_Block_User.php?tabb=".$submittab."&work=permit&masaca=".$rows['username'];
				$link_block ='<a href='.$path1.'>Block User Account</a>';
				$link_permit ='<a href='.$path2.'>Un-Block User Account</a>';
				$display ='<table class="table table-condensed" style="background-color:#FFFFFF;">
							<tbody>
							<tr><td rowspan="6" height="200px" width="200px">'.$passport.'</td>
							<td>Name :</td>
							<td>'.$rows['first_name']." ".$rows['middle_name']." ".$rows['last_name'].'</td>
							</tr>
							<tr><td>Username :</td>
							<td>'.$rows['username'].'</td>
							</tr>
							<tr><td>Department :</td>
							<td>'.$rows['course'].'</td>
							</tr>
							<tr><td>Student Level :</td>
							<td>'.$rows['Level'].' Level </td>
							</tr>
							<tr><td>'.$link_block.'</td>
							<td>'.$link_permit.'</td>
							</tr>
							</tbody></table>';
			}
			else
			{
				$stmt = $conn->prepare("SELECT * FROM staff_information WHERE username=?");	
				$stmt->execute(array($mail));
				$rows = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($stmt->rowCount () >= 1)
				{
					if ($rows['pic_extension'] != ""){
					$paspart = "abu_file/".$mail.$rows['pic_extension'];
					$passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
				}
				$path1= "Admin_Block_User.php?tabb=".$submittab."&work=block&masaca=".$rows['username'];
				$path2= "Admin_Block_User.php?tabb=".$submittab."&work=permit&masaca=".$rows['username'];
				$link_block ='<a href='.$path1.'>Block User Account</a>';
				$link_permit ='<a href='.$path2.'>Un-Block User Account</a>';
				$display ='<table class="table table-condensed" style="background-color:#FFFFFF;">
							<tbody>
							<tr><td rowspan="6" height="200px" width="200px">'.$passport.'</td>
							<td>Name :</td>
							<td>'.$rows['first_name']." ".$rows['middle_name']." ".$rows['last_name'].'</td>
							</tr>
							<tr><td>Username :</td>
							<td>'.$rows['username'].'</td>
							</tr>
							<tr><td>Department :</td>
							<td>'.$rows['course'].'</td>
							</tr>
							<tr><td>Staff Type :</td>
							<td>'.$rows['staff_type'].'</td>
							</tr>
							<tr><td>'.$link_block.'</td>
							<td>'.$link_permit.'</td>
							</tr>
							</tbody></table>';
				}
				else
				{
					$display="no record found";
				}
			}
		}
		else{$display="Bad user name";}
	}
	else{$display="Enter user name";}
}


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2']))
{	$display="";
	$mail = checkempty($_POST['inputpin2']);
	if(($mail != FALSE))
	{
		$mail = turn_to_abu_mail($_POST['inputpin2']);
		
		$mail2 = filterEmail($mail);
		if(($mail != FALSE))
		{
			//search records
			$mail = turn_to_abu_mail($_POST['inputpin2']);
			$stmt = $conn->prepare("SELECT * FROM student_information WHERE username=?");	
			$stmt->execute(array($mail));
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmt->rowCount () >= 1)
			{
				if ($rows['pic_extension'] != ""){
					$paspart = "abu_file/".$mail.$rows['pic_extension'];
					$passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
				}
				$path1= "Admin_Block_User.php?tabb=".$submittab."&work=block&masaca=".$rows['username'];
				$path2= "Admin_Block_User.php?tabb=".$submittab."&work=permit&masaca=".$rows['username'];
				$link_block ='<a href='.$path1.'>Block User Account</a>';
				$link_permit ='<a href='.$path2.'>Un-Block User Account</a>';
				$display2 ='<table class="table table-condensed" style="background-color:#FFFFFF;">
							<tbody>
							<tr><td rowspan="6" height="200px" width="200px">'.$passport.'</td>
							<td>Name :</td>
							<td>'.$rows['first_name']." ".$rows['middle_name']." ".$rows['last_name'].'</td>
							</tr>
							<tr><td>Username :</td>
							<td>'.$rows['username'].'</td>
							</tr>
							<tr><td>Department :</td>
							<td>'.$rows['course'].'</td>
							</tr>
							<tr><td>Student Level :</td>
							<td>'.$rows['Level'].' Level </td>
							</tr>
							<tr><td>'.$link_block.'</td>
							<td>'.$link_permit.'</td>
							</tr>
							</tbody></table>';
			}
			else
			{
				$stmt = $conn->prepare("SELECT * FROM staff_information WHERE username=?");	
				$stmt->execute(array($mail));
				$rows = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($stmt->rowCount () >= 1)
				{
					if ($rows['pic_extension'] != ""){
					$paspart = "abu_file/".$mail.$rows['pic_extension'];
					$passport ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
				}
				$path1= "Admin_Block_User.php?tabb=".$submittab."&work=block&masaca=".$rows['username'];
				$path2= "Admin_Block_User.php?tabb=".$submittab."&work=permit&masaca=".$rows['username'];
				$link_block ='<a href='.$path1.'>Block User Publication Account</a>';
				$link_permit ='<a href='.$path2.'>Un-Block User Publication Account</a>';
				$display2 ='<table class="table table-condensed" style="background-color:#FFFFFF;">
							<tbody>
							<tr><td rowspan="6" height="200px" width="200px">'.$passport.'</td>
							<td>Name :</td>
							<td>'.$rows['first_name']." ".$rows['middle_name']." ".$rows['last_name'].'</td>
							</tr>
							<tr><td>Username :</td>
							<td>'.$rows['username'].'</td>
							</tr>
							<tr><td>Department :</td>
							<td>'.$rows['course'].'</td>
							</tr>
							<tr><td>Staff Type :</td>
							<td>'.$rows['staff_type'].'</td>
							</tr>
							<tr><td>'.$link_block.'</td>
							<td>'.$link_permit.'</td>
							</tr>
							</tbody></table>';
				}
				else
				{
					$display2="no record found";
				}
			}
		}
		else{$display2="Bad user name";}
	}
	else{$display="Enter user name";}
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
	//var j = "2";
	var j = <?php echo $submittab;?>;
	if(j=="1"){
		 $('#myTab li:eq(0) a').tab('show');
	}
	else{
	 $('#myTab li:eq(1) a').tab('show');
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
							 <a href="#" class="navbar-brand">History</a>
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
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:25px;">Abu - Mail - Administrartive Page - | <a style="color:yellow;font-size:15px;" href="Admin_index.php"> Control Pannel Home </a> | <a style="color:red;font-size:15px;" href="">Log Out</a> </span></center>
					 		
							<div class="tabbable">
								<div class="tabbable" style="background-color:#F08080;border-top-left-radius:1px;margin-top:5%;border-top-right-radius:1px">
									<ul id="myTab" class="nav nav-tabs">
										<li class="active taaab"><a data-toggle="tab" href="#dA"><span class="glyphicon glyphicon-pencil"></span> Block User From Using Mail Server</a></li>
										<li class="tabss taaab"><a  data-toggle="tab" href="#dB"><span class="glyphicon glyphicon-pencil"></span> Block User From Using Publications</a></li>
									</ul>
								</div>
							<!--  tabs contents details begin  -->
								<div class="tab-content tabCONT2  style="padding:0px;margin:0px">
									<!-- staff registraion -->
									<div id="dA" class="tab-pane active ">
									 <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-right:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
										<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
										Search and Activate Or Deactivate User From Using Mail Server
										<hr style="border-color:green">
											<div class="form-group">
													  <div class="col-lg-12">
														<label for="txtfname" class="control-label col-xs-4">Mail_Address :<span style="color:red"class"require">*</span></label>				
															<div class="col-xs-8">
																<div class="input-group">
																	
																	<input type="text" class="form-control" id="inputpin1" name="inputpin1" placeholder="Enter Current User Name">
																	<span class="input-group-addon">@abumail.com</span>
																</div>
															</div>
													</div>
												</div>
										<input type="hidden" class="form-control" id="inputpin1" name="inputhide1" value="1">												
												<div class="form-group">
												 <div class="col-lg-offset-9 col-lg-3">
											  <button type="submit" name="submit1" class="btn btn-success">Search Details</button>
											  </div>
											  </div>
										</form>
												<hr style="border-color:green">
												  <div class="col-lg-12">
												  
												 <?php echo $display;?>
												  
												  </div>
												<hr style="border-color:green">
								</div>
								</div>			
									<!-- student registraion -->
								<div id="dB" class="tab-pane">
								 <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:45px;padding-bottom:5%; margin-left:0%; margin-right:0%">
									<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
										Search and Activate Or Deactivate User From Using Publication
										<hr style="border-color:green">
												
												 <div class="form-group">
													  <div class="col-lg-12">
														<label for="txtfname" class="control-label col-xs-4">Mail_Address :<span style="color:red"class"require">*</span></label>				
															<div class="col-xs-8">
																<div class="input-group">
																	
																	<input type="text" class="form-control" id="inputpin2" name="inputpin2" placeholder="Enter Current User Name">
																	<span class="input-group-addon">@abumail.com</span>
																</div>
															</div>
													</div>
												</div> 
												<input type="hidden" class="form-control" id="inputpin2" name="inputhide1" value="2">	
												<div class="form-group">
												 <div class="col-lg-offset-9 col-lg-3">
											  <button type="submit" name="submit2" class="btn btn-success">Search Details</button>
											  </div>
											  </div>
										</form>
												<hr style="border-color:green">
												  <div class="col-lg-12">
												  
												 <?php echo $display2;?>
												  
												  </div>
												<hr style="border-color:green">
												
												
								
								
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
