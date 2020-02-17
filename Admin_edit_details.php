<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';

$txtfname = $txtmname = $txtlname=$txtstaffid=$course =$dept= $rank=$comingreg=$reg_id=$reg_id2="";

$txtfname1 = $txtmname1 = $txtlname1 =$txtstaffid1 = $course1 = $dept1 = $rank1 = $comingreg1= $level="";

$generr2 = $generr= $submittab = $submittab2 =$generr3 = $generr4= $delstaff = $delstaff2=$staff_name="";
$username=$change_password=$username1=$change_password1= "";

if (isset($_GET['tabb'])){
$submittab=$_GET['tabb'];
}



if ((isset($_GET['tabb'])) && (isset($_GET['sherif']))){
$submittab =$_GET['tabb'];
$reg_id =$_GET['sherif'];
//if is staff
if ($submittab=="3")
	{
		//verify if user exist to prevent error
		$query2 = "SELECT username,change_password,first_name, middle_name, last_name, staff_type, staff_id, department, course FROM staff_information WHERE staff_id =:staff_id";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':staff_id',$reg_id, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 ==1) 
		{
			$txtfname = $rows3['first_name'];
			$txtmname = $rows3['middle_name'];
			$txtlname=$rows3['last_name'];
			
			$username=$rows3['username'];
			$change_password=$rows3['change_password'];
			
			$txtstaffid = $rows3['staff_id'];
			$course ='<option value='.$rows3['course'].' selected="selected">'.$rows3['course'].'</option>';
			$dept ='<option value='.$rows3['department'].' selected="selected">'.$rows3['department'].'</option>';
			$rank ='<option value='.$rows3['staff_type'].' selected="selected">'.$rows3['staff_type'].'</option>';

		}
	}


	
//if is student
if ($submittab=="4")
	{
		$reg_id2 = $reg_id;
		//verify if user exist to prevent error
		$query2 = "SELECT username,change_password,first_name, middle_name,Level, last_name, student_type, student_id, department, course FROM student_information WHERE student_id =:student_id";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':student_id',$reg_id, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 ==1) 
		{
			$txtfname1 = $rows3['first_name'];
			$txtmname1 = $rows3['middle_name'];
			$txtlname1 =$rows3['last_name'];
			
			$username1=$rows3['username'];
			$change_password1=$rows3['change_password'];
			
			$level ='<option value='.$rows3['Level'].' selected="selected">'.$rows3['Level']." Level".'</option>';
			$txtstaffid1 = $rows3['student_id'];
			$course1 ='<option value='.$rows3['course'].' selected="selected">'.$rows3['course'].'</option>';
			$dept1 ='<option value='.$rows3['department'].' selected="selected">'.$rows3['department'].'</option>';
			$rank1 ='<option value='.$rows3['student_type'].' selected="selected">'.$rows3['student_type'].'</option>';

		}
	}

}

function update_staff($staff_identity,$txtfname,$txtmname,$txtlname,$txtstaffid,$course,$dept,$rank,$uname,$pass)
{
	global $conn;
	global $generr2; global $generr;
	//create default user name for the staff = staff firstname + lastname + staff_id +@abumail.com
	$username = strtolower($txtfname.$txtlname.$txtstaffid."@abumail.com");
	$pword = "0";
	//if the staff activate its record before then use its former username dont update it
	if($pass !="0")
	{
		$username = $uname;
		$pword = $pass;
	}
	
	
	$stat = "";
	$stmt = $conn->prepare("UPDATE staff_information SET username=?,change_password=?,first_name=?,middle_name=?,last_name=?,
	staff_type=?,staff_id=?,department=?,course=? WHERE staff_id=? Limit 1");
	$stmt->execute(array($username,$pword,$txtfname,$txtmname,$txtlname,$rank,$txtstaffid,$dept,$course,$staff_identity));
	$affected_rows = $stmt->rowCount();
	if($stmt == true) 
	{
		$generr='<div class="alert alert-info">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Success!</strong>'.' Account Details : username = '.$username.'
			</div>';
													
		$generr2='<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong> Account was Succesfully Updated.
					</div>';
	}
}


function update_student($staff_identity,$txtfname1,$txtmname1,$txtlname1,$txtstaffid1,$course1,$dept1,$rank1,$level,$uname1,$pass1)
{
	global $conn;
	global $generr4; global $generr3;
	
	//create default user name for the staff = staff firstname + lastname + staff_id +@abumail.com
	$username = strtolower($txtfname1.$txtlname1.$txtstaffid1."@abumail.com");
	$pword = "0";
	//if the staff activate its record before then use its former username dont update it
	if($pass1 !="0")
	{
		$username = $uname1;
		$pword = $pass1;
	}
	$stat = "";
	$stmt = $conn->prepare("UPDATE student_information SET username=?,change_password=?,first_name=?,middle_name=?,last_name=?,
	student_type=?,student_id=?,department=?,course=?,Level=? WHERE student_id=? Limit 1");
	$stmt->execute(array($username,$pword,$txtfname1,$txtmname1,$txtlname1,$rank1,$txtstaffid1,$dept1,$course1,$level,$staff_identity));
	$affected_rows = $stmt->rowCount();
	if($stmt == true) 
	{

		$generr4='<div class="alert alert-info">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Success!</strong>'.' Account Details : username = '.$username. '
			</div>';
													
		$generr3='<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong> Account was Succesfully Updated.
					</div>';
	}
}

//STAFF SUBMIT
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))  
{
	$submittab = "3";
	$txtfname = checkempty($_POST['txtfname']);
	$txtlname= checkempty($_POST['txtlname']);
	$txtstaffid = checkempty($_POST['txtstaffid']);
	$dept = checkempty($_POST['dept']);
	$course=checkempty($_POST['course']);
	$rank=checkempty($_POST['rank']);
	
	$comingreg = checkempty($_POST['staffhide']);
	
	if(($txtfname != FALSE)&&($txtlname != FALSE)&&($txtstaffid != FALSE)&&($dept != FALSE)&&($course != FALSE)&&($rank != FALSE)&&($comingreg != FALSE))
	{
			$dept = $_POST['dept'];
			$course=$_POST['course'];
			$rank=$_POST['rank'];
			if(($dept != "Select Department...")&&($course != "Select Course...")&&($rank != "Select Rank...."))
			{
				try
					{
						//flush out hackers variable
						$txtfname = strip_tags($_POST['txtfname']);
						$txtlname= strip_tags($_POST['txtlname']);
						$txtstaffid = strip_tags($_POST['txtstaffid']);
						$dept = strip_tags($_POST['dept']);
						$course=strip_tags($_POST['course']);
						$rank=strip_tags($_POST['rank']);
						$txtmname=strip_tags($_POST['txtmname']);
						
						$comingreg = strip_tags($_POST['staffhide']);
						$pass = strip_tags($_POST['change_password']);
						$uname = strip_tags($_POST['username']);
						
						if ($comingreg != $txtstaffid )
						{
								//check if staffid exist before //verify
								$query2 = "SELECT staff_id FROM staff_information WHERE staff_id =:staff_id";
								$stmt2 = $conn->prepare($query2);
								$stmt2->bindValue(':staff_id',$txtstaffid, PDO::PARAM_STR);
								$stmt2->execute();
								$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
								$row_count2 = $stmt2->rowCount();
								if($row_count2!=1) 
								{
									//call update function			
									update_staff($comingreg,$txtfname,$txtmname,$txtlname,$txtstaffid,$course,$dept, $rank,$uname,$pass);
								}
								else
								{
									//$generr="<p style='color:red;'>Staff Id Already Exist...Please Choose Another Email</p>";
									$generr='<div class="alert alert-danger alert-error">
															<a href="#" class="close" data-dismiss="alert">&times;</a>
															<strong>Error!</strong> Staff Id Already Exist...Please Choose Another Staff Id.
														</div>';
								}
						}
						else
						{
							//dont verify
							update_staff($comingreg,$txtfname,$txtmname,$txtlname,$txtstaffid,$course,$dept, $rank,$uname,$pass);	
						}
					}
					catch(PDOException $ex)
					{
						echo $ex->getMessage();
					}
			}
			else
			{
				$generr3='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Some Fields are not Yet Selected ...Please Verify.
												</div>';
			}
	}
	else
	{
		$generr3='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Some Fields are Empty ...Please Verify.
												</div>';
	}
}


//STUDENT SUBMIT
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2']))  
{
	$submittab = "4";
	$txtfname1 = checkempty($_POST['txtfname1']);
	$txtlname1= checkempty($_POST['txtlname1']);
	$txtstaffid1 = checkempty($_POST['txtstaffid1']);
	$dept1 = checkempty($_POST['dept2']);
	$course1=checkempty($_POST['course2']);
	$rank1=checkempty($_POST['rank1']);
	$level1=checkempty($_POST['level']);
	$comingreg1 = checkempty($_POST['staffhide1']);

	if(($txtfname1 != FALSE)&&($txtlname1 != FALSE)&&($txtstaffid1 != FALSE)&&($dept1 != FALSE)&&($course1 != FALSE)&&($rank1 != FALSE)&&($level1 != FALSE)&&($comingreg1 != FALSE))
	{
			$dept1 = $_POST['dept2'];
			$course1=$_POST['course2'];
			$rank1=$_POST['rank1'];
			$level1=$_POST['level'];
			if(($dept1 != "Select Department...")&&($course1 != "Select Course...")&&($rank1 != "Select Rank.....")&&($level1 != "Select Level..."))
			{
				try
					{

						//flush out hackers variable
						$txtfname1 = strip_tags($_POST['txtfname1']);
						$txtlname1= strip_tags($_POST['txtlname1']);
						$txtstaffid1 = strip_tags($_POST['txtstaffid1']);
						$dept1 = strip_tags($_POST['dept2']);
						$course1=strip_tags($_POST['course2']);
						$rank1=strip_tags($_POST['rank1']);
						$txtmname1=strip_tags($_POST['txtmname1']);
						$level1=strip_tags($_POST['level']);
						
						$comingreg1 = strip_tags($_POST['staffhide1']);
						$pass1 = strip_tags($_POST['change_password1']);
						$uname1 = strip_tags($_POST['username1']);
						
						
						
						if ($comingreg1 != $txtstaffid1)
						{
								//check if staffid exist before //verify
								$query2 = "SELECT student_id FROM student_information WHERE student_id =:student_id";
								$stmt2 = $conn->prepare($query2);
								$stmt2->bindValue(':student_id',$txtstaffid1, PDO::PARAM_STR);
								$stmt2->execute();
								$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
								$row_count2 = $stmt2->rowCount();
								if($row_count2 !=1) 
								{
									//call update function			
									update_student($comingreg1,$txtfname1,$txtmname1,$txtlname1,$txtstaffid1,$course1,$dept1,$rank1,$level1,$uname1,$pass1);	
								}
								else
								{
									//$generr="<p style='color:red;'>Staff Id Already Exist...Please Choose Another Email</p>";
									$generr='<div class="alert alert-danger alert-error">
															<a href="#" class="close" data-dismiss="alert">&times;</a>
															<strong>Error!</strong> Student Id Already Exist...Please Choose Another Staff Id.
														</div>';
								}
						}
						else
						{
							//dont verify
							update_student($comingreg1,$txtfname1,$txtmname1,$txtlname1,$txtstaffid1,$course1,$dept1,$rank1,$level1,$uname1,$pass1);	
						}
					}
					catch(PDOException $ex)
					{
						echo $ex->getMessage();
					}
			}
			else
			{
				//$generr="<p style='color:red;'>Some Fields are not Yet Selected ...Please Verify</p>";
				$generr4='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Some Fields are not Yet Selected ...Please Verify.
												</div>';
			}
	}
	else
	{
		//$generr="<p style='color:red;'>Some Fields are Empty ...Please Verify</p>";
		$generr4='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Some Fields are Empty ...Please Verify.
												</div>';
	}
}

//search button of staff clicked	
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchstaff']))  
{
	$submittab ="3";
	//verify if user exist to prevent error
	$reg_id = checkempty($_POST['searchstaffid']);
	if($reg_id != FALSE)
	{
		$reg_id = strip_tags($_POST['searchstaffid']);
		$query2 = "SELECT username,change_password,first_name, middle_name, last_name, staff_type, staff_id, department, course FROM staff_information WHERE staff_id =:staff_id";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':staff_id',$reg_id, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 ==1) 
		{
			$txtfname = $rows3['first_name'];
			$txtmname = $rows3['middle_name'];
			$txtlname=$rows3['last_name'];
			
			$username=$rows3['username'];
			$change_password=$rows3['change_password'];
			
			$txtstaffid = $rows3['staff_id'];
			$course ='<option value='.$rows3['course'].' selected="selected">'.$rows3['course'].'</option>';
			$dept ='<option value='.$rows3['department'].' selected="selected">'.$rows3['department'].'</option>';
			$rank ='<option value='.$rows3['staff_type'].' selected="selected">'.$rows3['staff_type'].'</option>';

		}
	}
}

//search button of student clicked	
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchstaff2']))  
{
	$submittab ="4";
	//verify if user exist to prevent error
	$reg_id2 = checkempty($_POST['searchstaffid2']);
	if($reg_id2 != FALSE)
	{
		$reg_id2 = strip_tags($_POST['searchstaffid2']);
		//verify if user exist to prevent error
		$query2 = "SELECT username,change_password,first_name, middle_name,Level, last_name, student_type, student_id, department, course FROM student_information WHERE student_id =:student_id";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':student_id',$reg_id2, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 ==1) 
		{
			$txtfname1 = $rows3['first_name'];
			$txtmname1 = $rows3['middle_name'];
			$txtlname1 =$rows3['last_name'];
			
			$username1=$rows3['username'];
			$change_password1=$rows3['change_password'];
			
			$level ='<option value='.$rows3['Level'].' selected="selected">'.$rows3['Level']." Level".'</option>';
			$txtstaffid1 = $rows3['student_id'];
			$course1 ='<option value='.$rows3['course'].' selected="selected">'.$rows3['course'].'</option>';
			$dept1 ='<option value='.$rows3['department'].' selected="selected">'.$rows3['department'].'</option>';
			$rank1 ='<option value='.$rows3['student_type'].' selected="selected">'.$rows3['student_type'].'</option>';

		}
	}
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
<link rel="stylesheet" type="text/css" href="INDEX_FILES/index.css" >
<script type="text/javascript" src="INDEX_FILES/reg_deptchange.js"></script>
<script type="text/javascript" src="INDEX_FILES/validate_email2.js"></script>
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
	if(j=="3"){
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
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:25px;">Abu - Mail - Administrartive Page -  <?php echo '<a style="color:yellow;font-size:15px;" href='.$back_link.'>Registration Page</a>';?> | <a style="color:yellow;font-size:15px;" href="Admin_index.php"> Control Pannel Home </a> | <a style="color:red;font-size:15px;" href="">Log Out</a> </span></center>
					 		
							<div class="tabbable">
								<div class="tabbable" style="background-color:#F08080;border-top-left-radius:1px;margin-top:5%;border-top-right-radius:1px">
									<ul id="myTab" class="nav nav-tabs">
										<li class="active taaab"><a data-toggle="tab" href="#dA"><span class="glyphicon glyphicon-pencil"></span> Edit Registered Staff Details</a></li>
										<li class="tabss taaab"><a  data-toggle="tab" href="#dB"><span class="glyphicon glyphicon-pencil"></span>  Edit Registered Student Details</a></li>
									</ul>
								</div>
							<!--  tabs contents details begin  -->
								<div class="tab-content tabCONT2  style="padding:0px;margin:0px">
									<!-- staff registraion -->
									<div class="tab-content tabCONT2  style="padding:0px;margin:0px">
									<!-- staff registraion -->
									<div id="dA" class="tab-pane active ">
									<form role="search" class="navbar-form navbar-right"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="adminlogin">
										<div class="form-group">
										<label>Staff ID</label>
										   <input type="text" name="searchstaffid" value="<?php echo $reg_id;?>" class="form-control"></input>
										</div>
										 <button type="submit" name="searchstaff" class="btn btn-default">Search</button>
									</form>
										
									 <div style="width:auto; background-color:#FFFFFF; margin-top:1%; padding-top:20px; padding-right:20px; padding-left:45px;padding-bottom:5%; margin-left:1%; margin-right:1%">
										
										
										<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="registeredit" onsubmit="return submiting();" style="margin-top:5px;margin-right:5px;margin-left:5px">
										EDIT STAFF REGISTRATION AND DETAILS <?php echo $generr2; ?>
										<hr style="border-color:green">
												<div class="form-group">
													<label for="txtfname" class="control-label col-xs-3">First Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtfname" name="txtfname" onkeypress="wipeboxeror('3')" value="<?php echo $txtfname;  ?>" placeholder="Enter First Name ">
														</div>
													</div>
			
													<div class="col-xs-4" id="ferror"></div> 
												</div>
												
												<div class="form-group">
													<label for="txtmname" class="control-label col-xs-3">Middle Name :</label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtmname" name="txtmname" value="<?php echo $txtmname;  ?>" placeholder="Enter Middle Name">
														</div>
													</div>
													<div class="col-xs-4" ></div>
												</div>
												
												<div class="form-group">
													<label for="txtlname" class="control-label col-xs-3">Last Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtlname" name="txtlname" value="<?php echo $txtlname;  ?>" onkeypress="wipeboxeror('2')" placeholder="Enter Last Name">
														</div>
													</div>
													<div class="col-xs-4" id="lerror"></div>
												</div>
												
												<div class="form-group">
													<label for="txtstaffid" class="control-label col-xs-3">Staff ID_No :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtstaffid" onblur="check_existing_regno()" onkeypress="wipeboxeror('1')" name="txtstaffid" value="<?php echo $txtstaffid; ?>" placeholder="Enter Staff ID_No">
														</div>
													</div>
													<div class="col-xs-4" id="result"></div>
												</div>
												
												<input type="hidden"  value="<?php echo $txtstaffid; ?>" id="staffhide" name="staffhide"></input>
												<input type="hidden"  value="<?php echo $username; ?>" id="username" name="username"></input>
												<input type="hidden"  value="<?php echo $change_password; ?>" id="change_password" name="change_password"></input>
												
												
												 <div class="form-group">
														<label for="dept" class="control-label col-xs-3">Staff Department :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="dept" name="dept"  onchange="schoolComboChange('1');">
																<?php echo $dept;  ?>
																<option value="Select Department...">Select Department...</option>
																		<option value="Mathematics">Mathematics</option>
																		<option value="Physics">Physics</option>
																		<option value="Chemistry">Chemistry</option>
																		<option value="Bilogical Science">Bilogical Science</option>
																		<option value="Biochemistry">Biochemistry</option>
																		<option value="Geography">Geography</option>
																		<option value="Geology">Geology</option>
																		<option value="Microbiology">Microbiology</option>
																</select>
															</div>
														</div>
														<div class="col-xs-4" id="derror"></div>
													</div>
													
													 <div class="form-group">
														<label for="course" class="control-label col-xs-3">Staff Course :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="course" name="course">
																		<?php echo $course;?>
																		<option value="Select Course...">Select Course...</option>
															
																</select>
															</div>
														</div>
														<div class="col-xs-4" id="cerror"></div>
													</div>
												 <div class="form-group">
														<label for="rank" class="control-label col-xs-3">Staff Rank :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="rank" name="rank" onchange="wipeboxeror('4');">
																		<?php echo $rank;?>
																		<option value="Select Rank....">Select Rank....</option>
																		<option value="DEAN">Dean (Head of the Faculty)</option>
																		<option value="HOD">HOD (Head of the Department)</option>
																		<option value="Lecturer">Lecturer</option>
																		<option value="Non_Accademic_Staff">Non_Accademic_Staff</option>
															
																</select>
															</div>
														</div>
														<div class="col-xs-4" id="rerror"></div>
													</div>
									
												<hr style="border-color:green">
													<div class="col-lg-9" id="finalresult"> 
														<?php echo $generr; ?>
													</div>
													<div class="form-group">
														<div class="col-lg-3">
															<button type="submit" name="submit" class="btn btn-success">Update Detail</button>
														</div>
													</div>
											  <hr style="border-color:green">
										</form>
									
									</div>
								</div>	
															
									<!-- student registraion -->
								<div id="dB" class="tab-pane">
								  
								  <form role="search" class="navbar-form navbar-right"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="adminlogin">
										<div class="form-group">
										<label>Student_ID</label>
										   <input type="text" name="searchstaffid2" value="<?php echo $reg_id2;?>" class="form-control"></input>
										</div>
										 <button type="submit" name="searchstaff2" class="btn btn-default">Search</button>
									</form>
								  
								  
								  
								  <div style="width:auto; background-color:#FFFFFF; margin-top:1%; padding-top:20px; padding-right:20px; padding-left:45px;padding-bottom:5%; margin-left:1%; margin-right:1%">
									<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="registeredit2" onsubmit="return submiting2();" style="margin-top:5px;margin-right:5px;margin-left:5px">
										EDIT STUDENT REGISTRATION AND DETAILS - <?php echo $generr4; ?>
										<hr style="border-color:green">
												<div class="form-group">
													<label for="txtfname1" class="control-label col-xs-3">First Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtfname1" name="txtfname1" value="<?php echo $txtfname1; ?>" onkeypress="wipeboxeror2('3')" placeholder="Enter First Name ">
														</div>
													</div>
													<div class="col-xs-4" id="ferror1" id="ferror1"></div>
												</div>
												
												<div class="form-group">
													<label for="txtmname1" class="control-label col-xs-3">Middle Name :</label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtmname1" name="txtmname1" value="<?php echo $txtmname1; ?>" placeholder="Enter Middle Name">
														</div>
													</div>
													<div class="col-xs-4" id="merror1"></div>
												</div>
												
												<div class="form-group">
													<label for="txtlname1" class="control-label col-xs-3">Last Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtlname1" name="txtlname1" value="<?php echo $txtlname1; ?>" onkeypress="wipeboxeror2('2')" placeholder="Enter Last Name">
														</div>
													</div>
													<div class="col-xs-4" id="lerror1"></div>
												</div>
												
												<div class="form-group">
													<label for="txtstaffid1" class="control-label col-xs-3">Student Reg_No :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control"  id="txtstaffid1" name="txtstaffid1" value="<?php echo $txtstaffid1; ?>" onblur="check_existing_email()" onkeypress="wipeboxeror2('1')" placeholder="Enter Student Reg_No">
														</div>
													</div>
													<div class="col-xs-4" id="result2"></div>
												</div>
												
												
												<input type="hidden"  value="<?php echo $txtstaffid1; ?>" id="staffhide1" name="staffhide1"></input>
												<input type="hidden"  value="<?php echo $username1; ?>" id="username1" name="username1"></input>
												<input type="hidden"  value="<?php echo $change_password1; ?>" id="change_password1" name="change_password1"></input>
												
												
												 <div class="form-group">
														<label for="dept2" class="control-label col-xs-3">Student Department :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="dept2" name="dept2"  onchange="schoolComboChange('2');">
																<?php echo $dept1; ?>
																<option value="Select Department...">Select Department...</option>
																		<option value="Mathematics">Mathematics</option>
																		<option value="Physics">Physics</option>
																		<option value="Chemistry">Chemistry</option>
																		<option value="Bilogical Science">Bilogical Science</option>
																		<option value="Biochemistry">Biochemistry</option>
																		<option value="Geography">Geography</option>
																		<option value="Geology">Geology</option>
																		<option value="Microbiology">Microbiology</option>
																</select>
															</div>
														</div>
														<div class="col-xs-4" id="derror1"></div>
													</div>
													
													 <div class="form-group">
														<label for="course2" class="control-label col-xs-3">Student Course :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="course2" name="course2">
																		<?php echo $course1; ?>
																		<option value="Select Course...">Select Course...</option>
															
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
																<select class="form-control" id="level" name="level" onchange="wipeboxeror2('4')">
																		<?php echo $level; ?>
																		<option value="Select Level...">Select Level...</option>
																		<option value="100">100 Level</option>
																		<option value="200">200 Level</option>
																		<option value="300">300 Level</option>
																		<option value="400">400 Level</option>
																</select>
															</div>
														</div>
														<div class="col-xs-4" id="leerror1"></div>
													</div>
													
												 <div class="form-group">
														<label for="rank1" class="control-label col-xs-3">Student Rank :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="rank1" name="rank1" onchange="wipeboxeror2('5')">
																		<?php echo $rank1; ?>
																		<option value="Select Rank.....">Select Rank.....</option>
																		<option value="Student">Student</option>
																		<option value="Class Rep">Class Representative</option>
															
																</select>
															</div>
														</div>
														<div class="col-xs-4" id="rerror1"></div>
													</div>
									
												<hr style="border-color:green">
													<div class="col-lg-9" id="finalresult2"> 
														<?php echo $generr3; ?>
													</div>
													<div class="form-group">
														<div class="col-lg-3">
															<button type="submit" name="submit2" class="btn btn-success">Update Detail</button>
														</div>
													</div>
											  <hr style="border-color:green">
										</form>
								
								
								</div>
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
