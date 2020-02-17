<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';

$generr2 = $generr= $submittab =$generr3 = $generr4= $delstaff = $delstaff2=$staff_name="";
if (isset($_GET['tabb'])){
$submittab=$_GET['tabb'];
}


/* Note: before an Administrator can DEllete a user record the user must not be the type that is already using the maill server or has activated his ABU 
mAILL SERVER USER NAME
the Only thing Admin can do to such user is to Deactivate their Account - option to deactivate is in the Admin Control Pannel*/


//the delete module
if ((isset($_GET['tabb'])) && (isset($_GET['delete']))){
$h = $_GET['tabb'];
$reg_id = $_GET['delete'];
//if staff
	if ($h=="3")
	{
		$ST = "0";
		//verify if user exist to prevent error
		$query2 = "SELECT staff_id,change_password FROM staff_information WHERE staff_id =:staff_id AND change_password=:change_password";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':staff_id',$reg_id, PDO::PARAM_STR);
		$stmt2->bindValue(':change_password',$ST, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 ==1) 
		{
			$staff_name = $rows3['first_name']." ".$rows3['middle_name']." ".$rows3['last_name'];
			$f="";
			$stmt = $conn->prepare("UPDATE staff_information SET username=?,change_password=?,first_name=?,middle_name=?,last_name=?,
			staff_type=?,staff_id=?,department=?,course=?,date_register=now() WHERE staff_id=? Limit 1");
			$stmt->execute(array($f,$f,$f,$f,$f,$f,$f,$f,$f,$reg_id));
			$affected_rows = $stmt->rowCount();
			if($stmt == true) 
			{
				$delstaff ='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>'.$staff_name.' - Account was Succesfully Deleted.
												</div>';
			}
			else{
			$delstaff='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Sorry! Server Unable to Remove - '.$staff_name.' Account - Retry.
												</div>';
			}
		}
		else{
			$delstaff='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Is Either the Staff Id Does not Exist..OR the user Account cant be deleted but can be blocked because is already activated Please Verify and Retry ..you can <a href="Admin_Block_User.php">Click here</a> to block Account. 
													</div>';
		}
	}
	

//if student
	if ($h=="4")
	{
		$ST = "0";
		//verify if user exist to prevent error
		$query2 = "SELECT student_id,change_password FROM student_information WHERE student_id =:student_id AND change_password =:change_password";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':student_id',$reg_id, PDO::PARAM_STR);
		$stmt2->bindValue(':change_password',$ST, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 ==1) 
		{
			$_SESSION['student_name'] = $student_name = $rows3['first_name']." ".$rows3['middle_name']." ".$rows3['last_name'];
			$f="";
			$stmt = $conn->prepare("UPDATE student_information SET username=?,change_password=?,first_name=?,middle_name=?,last_name=?,
			student_type=?,student_id=?,department=?,course=?,Level=?,date_register=now() WHERE student_id=? Limit 1");
			$stmt->execute(array($f,$f,$f,$f,$f,$f,$f,$f,$f,$f,$reg_id));
			$affected_rows = $stmt->rowCount();
			if($stmt == true) 
			{
				$delstaff2 ='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>'.$_SESSION['student_name'].' - Account was Succesfully Deleted.
												</div>';
			}
			else{
			$delstaff2='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Sorry! Server Unable to Remove - '.$_SESSION['student_name'].' Account - Retry.
												</div>';
			}
		}
		else{
			$delstaff2='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Is Either the student Id Does not Exist..OR the user Account cant be deleted but can be blocked because is already activated Please Verify and Retry ..you can <a href="Admin_Block_User.php">Click here</a> to block Account. 
													</div>';
		}
	}

}



//ends here
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))  
{
	$submittab = "1";
	
	$txtfname = checkempty($_POST['txtfname']);
	$txtlname= checkempty($_POST['txtlname']);
	$txtstaffid = checkempty($_POST['txtstaffid']);
	$dept = checkempty($_POST['dept']);
	$course=checkempty($_POST['course']);
	$rank=checkempty($_POST['rank']);
	
	$transaction_id="";
	if(($txtfname != FALSE)&&($txtlname != FALSE)&&($txtstaffid != FALSE)&&($dept != FALSE)&&($course != FALSE)&&($rank != FALSE))
	{
			$dept = $_POST['dept'];
			$course=$_POST['course'];
			$rank=$_POST['rank'];
			if(($dept != "Select Department...")&&($course != "Select Course...")&&($rank != "Select Rank...."))
			{
					try{
						$conn->beginTransaction();
						//flush out hackers variable
						$txtfname = strip_tags($_POST['txtfname']);
						$txtlname= strip_tags($_POST['txtlname']);
						$txtstaffid = strip_tags($_POST['txtstaffid']);
						$dept = strip_tags($_POST['dept']);
						$course=strip_tags($_POST['course']);
						$rank=strip_tags($_POST['rank']);
						$txtmname=strip_tags($_POST['txtmname']);
						
						//check if staffid exist before
						$query2 = "SELECT staff_id FROM staff_information WHERE staff_id =:staff_id";
						$stmt2 = $conn->prepare($query2);
						$stmt2->bindValue(':staff_id',$txtstaffid, PDO::PARAM_STR);
						$stmt2->execute();
						$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
						$row_count2 = $stmt2->rowCount();
						if($row_count2!=1) 
						{
							
									
										//retrieve a default Password for the user
										$F="";
										$query6 = "SELECT password FROM staff_information WHERE change_password =:change_password AND username =:username ORDER BY id ASC LIMIT 0,1";
										$stmt6 = $conn->prepare($query6);
										$stmt6->bindValue(':change_password',$F, PDO::PARAM_STR);
										$stmt6->bindValue(':username',$F, PDO::PARAM_STR);
										$stmt6->execute();
										$rows6 = $stmt6->fetch(PDO::FETCH_ASSOC);
										$row_count6 = $stmt6->rowCount();
										if($row_count6 == 1) 
										{
											$password = $rows6['password'];
											//create default user name for the staff = staff firstname + lastname + staff_id +@abumail.com
											
											$username = strtolower($txtfname.$txtlname.$txtstaffid."@abumail.com");
											//save the trans id with the immediate one you just picked
											//update serial pin table
											$stat = "";
											$stmt = $conn->prepare("UPDATE staff_information SET username=?,change_password=?,first_name=?,middle_name=?,last_name=?,
											staff_type=?,staff_id=?,department=?,course=?,date_register=now() WHERE password=? AND change_password =? Limit 1");
											$stmt->execute(array($username,'0',$txtfname,$txtmname,$txtlname,$rank,$txtstaffid,$dept,$course,$password,$stat));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												
												//$generr2="<p style='color:brown;'>Account was Succesfully Created";
												//$generr="<p style='color:brown;'>Account Details : username = ".$username." - "." Password = ".$password."</p>";
												$generr='<div class="alert alert-info">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>'.' Account Details : username = '.$username.' - '.' Password = '.$password.'
												</div>';
												
												
												$generr2='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong> Account was Succesfully Created.
												</div>';
											}
											
										}
						}
						else
						{
							//$generr="<p style='color:red;'>Staff Id Already Exist...Please Choose Another Email</p>";
							$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Staff Id Already Exist...Please Choose Another Staff Id.
												</div>';
						}
						$conn->commit();
					}
					catch(PDOException $ex)
					{
						$conn->rollBack();
						echo $ex->getMessage();
					}
			}
			else
			{
				//$generr="<p style='color:red;'>Some Fields are not Yet Selected ...Please Verify</p>";
				$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Some Fields are not Yet Selected ...Please Verify.
												</div>';
			}
	}
	else
	{
		//$generr="<p style='color:red;'>Some Fields are Empty ...Please Verify</p>";
		$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Some Fields are Empty ...Please Verify.
												</div>';
	}
}


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2']))  
{
	$submittab = "2";
	$txtfname1 = checkempty($_POST['txtfname1']);
	$txtlname1= checkempty($_POST['txtlname1']);
	$txtstaffid1 = checkempty($_POST['txtstaffid1']);
	$dept1 = checkempty($_POST['dept2']);
	$course1=checkempty($_POST['course2']);
	$rank1=checkempty($_POST['rank1']);
	$level1=checkempty($_POST['level']);
	$transaction_id="";
	if(($txtfname1 != FALSE)&&($txtlname1 != FALSE)&&($txtstaffid1 != FALSE)&&($dept1 != FALSE)&&($course1 != FALSE)&&($rank1 != FALSE)&&($level1 != FALSE))
	{
			$dept1 = $_POST['dept2'];
			$course1=$_POST['course2'];
			$rank1=$_POST['rank1'];
			$level1=$_POST['level'];
			if(($dept1 != "Select Department...")&&($course1 != "Select Course...")&&($rank1 != "Select Rank.....")&&($level1 != "Select Level..."))
			{
					try{
						$conn->beginTransaction();
						//flush out hackers variable
						$txtfname1 = strip_tags($_POST['txtfname1']);
						$txtlname1= strip_tags($_POST['txtlname1']);
						$txtstaffid1 = strip_tags($_POST['txtstaffid1']);
						$dept1 = strip_tags($_POST['dept2']);
						$course1=strip_tags($_POST['course2']);
						$rank1=strip_tags($_POST['rank1']);
						$txtmname1=strip_tags($_POST['txtmname1']);
						$level1=strip_tags($_POST['level']);
						
						//check if staffid exist before
						$query2 = "SELECT student_id FROM student_information WHERE student_id =:student_id";
						$stmt2 = $conn->prepare($query2);
						$stmt2->bindValue(':student_id',$txtstaffid1, PDO::PARAM_STR);
						$stmt2->execute();
						$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
						$row_count2 = $stmt2->rowCount();
						if($row_count2!=1) 
						{

										//retrieve a default Password for the user
										$F="";
										$query6 = "SELECT password FROM student_information WHERE change_password =:change_password AND username =:username ORDER BY id ASC LIMIT 0,1";
										$stmt6 = $conn->prepare($query6);
										$stmt6->bindValue(':change_password',$F, PDO::PARAM_STR);
										$stmt6->bindValue(':username',$F, PDO::PARAM_STR);
										$stmt6->execute();
										$rows6 = $stmt6->fetch(PDO::FETCH_ASSOC);
										$row_count6 = $stmt6->rowCount();
										if($row_count6 == 1) 
										{
											$password1 = $rows6['password'];
											//create default user name for the staff = staff firstname + lastname + staff_id +@abumail.com
											
											$username1= strtolower($txtfname1.$txtlname1.$txtstaffid1."@abumail.com");
											//save the trans id with the immediate one you just picked
											//update serial pin table
											$stat = "";
											$stmt = $conn->prepare("UPDATE student_information SET username=?,change_password=?,first_name=?,middle_name=?,last_name=?,
											student_type=?,student_id=?,department=?,course=?,Level=?,date_register=now() WHERE password=? AND change_password =? Limit 1");
											$stmt->execute(array($username1,'0',$txtfname1,$txtmname1,$txtlname1,$rank1,$txtstaffid1,$dept1,$course1,$level1,$password1,$stat));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{											
												//$generr2="<p style='color:brown;'> Account was Succesfully Created";
												//$generr="<p style='color:brown;'> Account Details : username = ".$username." - "." Password = ".$password."</p>";
												
												$generr3='<div class="alert alert-info">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>'.' Account Details : username = '.$username1.' - '.' Password = '.$password1.'
												</div>';
												
												
												$generr4='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong> Account was Succesfully Created.
												</div>';
											}
											
										}
						}
						else
						{
							//$generr="<p style='color:red;'> Student_Id Already Exist...Please Choose Another Email</p>";
							$generr4='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong> Student_Id Already Exist...Please Choose Another Student_Id.
												</div>';
						}
						$conn->commit();
					}
					catch(PDOException $ex)
					{
						$conn->rollBack();
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
<script type="text/javascript" src="INDEX_FILES/validate_email.js"></script>
<script>
function deleteuser(id,name,type)
{
	var x;
	var r = confirm("Are You sure you want to Delete or Remove Permanently \n" + name + "'s Record \n From Mail Server ?");
	if (r==true)
	  {
			if (type=="3")
			{
			 document.getElementById("load").innerHTML ='<img src="INDEX_FILES/images/loader.gif" class="img-responsive" alt="Deleting user...."/>';
			}
			if (type=="4")
			{
			 document.getElementById("load2").innerHTML ='<img src="INDEX_FILES/images/loader.gif" class="img-responsive" alt="Deleting user...."/>';
			}
		//if ok button is press then reload this page
		<?php
			//header("location: ".$root."My_Profile_Account.php".$global);	
		?>
		window.location.replace(id);
		 //window.open(id,name="_blank");
	  }
}
</script>
<script type="text/javascript">	
$(document).ready(function()
{
	//var j = "2";
	var j = <?php echo $submittab;?>;
	if(j=="1"){
		 $('#myTab li:eq(0) a').tab('show');
	}
	else if(j=="2"){
		 $('#myTab li:eq(1) a').tab('show');
	}
	else if(j=="3"){
		 $('#myTab li:eq(2) a').tab('show');
	}
	else{
	 $('#myTab li:eq(3) a').tab('show');
	}
});
	//var j = <?php echo $submittab;?>;
//alert(j);
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
							 <a href="Admin_index.php" class="navbar-brand">Control Pannel Home</a>
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
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:25px;">Abu - Mail - Administrartive Page - | - <a style="color:yellow;font-size:15px;" href="logout.php">Log Out</a> | <a style="color:yellow;font-size:15px;" href="Admin_index.php"> Control Pannel Home </a></span></center>
							
							<div class="tabbable">
								<div class="tabbable" style="background-color:#F08080;border-top-left-radius:1px;margin-top:5%;border-top-right-radius:1px">
									<ul id="myTab" class="nav nav-tabs">
										<li class="active taaab"><a data-toggle="tab" href="#dA"><span class="glyphicon glyphicon-pencil"></span> Register Staff</a></li>
										<li class="tabss taaab"><a  data-toggle="tab" href="#dB"><span class="glyphicon glyphicon-pencil"></span>  Register Student</a></li>
										<li class="tabss taaab"><a  data-toggle="tab" href="#dC"><span class="glyphicon glyphicon-pencil"></span> Edit Registered Staff</a></li>
										<li class="tabss taaab"><a  data-toggle="tab" href="#dD"><span class="glyphicon glyphicon-pencil"></span>  Edit Registered Student</a></li>
									</ul>
								</div>
							<!--  tabs contents details begin  -->
								<div class="tab-content tabCONT2  style="padding:0px;margin:0px">
									<!-- staff registraion -->
									<div id="dA" class="tab-pane active ">
									 <div style="width:auto; background-color:#FFFFFF; margin-top:1%; padding-top:20px; padding-right:20px; padding-left:45px;padding-bottom:5%; margin-left:1%; margin-right:1%">
										<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="registeredit" onsubmit="return submiting();" style="margin-top:5px;margin-right:5px;margin-left:5px">
										STAFF REGISTRATION AND DETAILS <?php echo $generr2; ?>
										<hr style="border-color:green">
												<div class="form-group">
													<label for="txtfname" class="control-label col-xs-3">First Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtfname" name="txtfname" onkeypress="wipeboxeror('3')" value="" placeholder="Enter First Name ">
														</div>
													</div>
													<div class="col-xs-4" id="ferror"></div> 
												</div>
												
												<div class="form-group">
													<label for="txtmname" class="control-label col-xs-3">Middle Name :</label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtmname" name="txtmname" value="" placeholder="Enter Middle Name">
														</div>
													</div>
													<div class="col-xs-4" ></div>
												</div>
												
												<div class="form-group">
													<label for="txtlname" class="control-label col-xs-3">Last Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtlname" name="txtlname" value="" onkeypress="wipeboxeror('2')" placeholder="Enter Last Name">
														</div>
													</div>
													<div class="col-xs-4" id="lerror"></div>
												</div>
												
												<div class="form-group">
													<label for="txtstaffid" class="control-label col-xs-3">Staff ID_No :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtstaffid" onblur="check_existing_regno()" onkeypress="wipeboxeror('1')" name="txtstaffid" value="" placeholder="Enter Staff ID_No">
														</div>
													</div>
													<div class="col-xs-4" id="result"></div>
												</div>
												
												 <div class="form-group">
														<label for="dept" class="control-label col-xs-3">Staff Department :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="dept" name="dept"  onchange="schoolComboChange('1');">
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
																<select class="form-control" id="rank" name="rank"  onchange="wipeboxeror('4');">
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
								  <div style="width:auto; background-color:#FFFFFF; margin-top:1%; padding-top:20px; padding-right:20px; padding-left:45px;padding-bottom:5%; margin-left:1%; margin-right:1%">
									<form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="registeredit2" onsubmit="return submiting2();" style="margin-top:5px;margin-right:5px;margin-left:5px">
										STUDENT REGISTRATION AND DETAILS - <?php echo $generr4; ?>
										<hr style="border-color:green">
												<div class="form-group">
													<label for="txtfname1" class="control-label col-xs-3">First Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtfname1" name="txtfname1" value="" onkeypress="wipeboxeror2('3')" placeholder="Enter First Name ">
														</div>
													</div>
													<div class="col-xs-4" id="ferror1" id="ferror1"></div>
												</div>
												
												<div class="form-group">
													<label for="txtmname1" class="control-label col-xs-3">Middle Name :</label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtmname1" name="txtmname1" value="" placeholder="Enter Middle Name">
														</div>
													</div>
													<div class="col-xs-4" id="merror1"></div>
												</div>
												
												<div class="form-group">
													<label for="txtlname1" class="control-label col-xs-3">Last Name :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control" id="txtlname1" name="txtlname1" value="" onkeypress="wipeboxeror2('2')" placeholder="Enter Last Name">
														</div>
													</div>
													<div class="col-xs-4" id="lerror1"></div>
												</div>
												
												<div class="form-group">
													<label for="txtstaffid1" class="control-label col-xs-3">Student Reg_No :<span style="color:red"class"require">*</span></label>
													<div class="col-xs-5">
														<div class="input-group">
															<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
															<input type="text" class="form-control"  id="txtstaffid1" name="txtstaffid1" value="" onblur="check_existing_email()" onkeypress="wipeboxeror2('1')" placeholder="Enter Student Reg_No">
														</div>
													</div>
													<div class="col-xs-4" id="result2"></div>
												</div>
												
												 <div class="form-group">
														<label for="dept2" class="control-label col-xs-3">Student Department :<span style="color:red"class"require">*</span></label>
														<div class="col-xs-5">
															<div class="input-group">
																<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
																<select class="form-control" id="dept2" name="dept2"  onchange="schoolComboChange('2');">
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
								<!-- STaff Edit -->
								<div id="dC" class="tab-pane">
								 <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:0px; padding-left:1px;padding-bottom:5%; margin-left:0%; margin-right:0%">
								
									<div class="well" >
										<div class="col-lg-6">LIST OF ALL REGISTERED STAFF</div><div class="col-lg-6" id="load"><?php echo $delstaff; ?></div>
											<table class="table table-condensed" style="background-color:#FFFFFF;">
												<thead>
													<tr>
														<th>Id</th>
														<th>Staff Name</th>
														<th>Staff_ID_No</th>
														<th>Rank</th>
														<th>Email</th>
														<th>Department</th>
														<th>Course</th>
														<th></th>
														<th></th>
													</tr>
												</thead>

												<tbody>
													<?php
													//create a mySQL connection
													$dbhost    = 'localhost';
													$dbuser    = 'root';
													$dbpass    = '';
													$conn = mysql_connect($dbhost, $dbuser, $dbpass);
													if (!$conn) {
														die('Could not connect: ' . mysql_error());
													}
													mysql_select_db('abu_server');
													/* Get total number of records */
													$status="";
													$sql    = "SELECT count(*) FROM staff_information where username != '".$status."' and change_password !='".$status."'";
													$retval = mysql_query($sql, $conn);
													
													if (!$retval)
													{
														die('Could not get data: ' . mysql_error());
													}
													
													
													$current_page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
												
													//record per Page($per_page)	
													$per_page = 20;
													$tabvalue = "&tabb=3";
													$t = "3";
													
													$otherpage2=!empty($_GET['page1']) ? (int)$_GET['page1'] : 1;
													$otherpage3="&page1=".$otherpage2;
													
													$row = mysql_fetch_array($retval, MYSQL_NUM);
													//the total number of page is in this variable bellow $total_count
													$total_count = $row[0];
													
													$total_pages = $total_count/$per_page;

													$offset = ($current_page - 1) * $per_page;
													
													$previous_page = $current_page - 1;
		
													$next_page = $current_page + 1;
													$has_previous_page =  $previous_page >= 1 ? true : false;
													$has_next_page = $next_page <= $total_pages ? true : false;
													
													//find records of employee and we specify the offset and the limit record per page
													$status="";
													$sql = "SELECT Id,first_name,middle_name,last_name,staff_type,course,department,staff_id,username FROM staff_information where username != '".$status."' and change_password !='".$status."' LIMIT {$per_page} OFFSET {$offset}"; 
													$retval = mysql_query($sql, $conn);
													if (!$retval) {
														die('Could not get data: ' . mysql_error());
													}
													while ($row = mysql_fetch_array($retval, MYSQL_ASSOC)) 
													{
															$name = $row['first_name']." ".$row['middle_name']." ".$row['last_name'];
															//$staffid ="Value=".$row['staff_id'];
															
															$j="Admin_edit_details.php?sherif=".$row['staff_id'].$tabvalue;
															//$edit_link = "<a href=".$j.">Edit</a>";
															$edit_link = "<a href=".$j."><img src="."INDEX_FILES/images/Text-Edit-icon.png"." style='height:20px' title='Edit Record'></a>";
															$q="Register_student.php?delete=".$row['staff_id'].$tabvalue;
															//$delete_link = "<a href=".$q.">Delete</a>";
							
															$delete_link = '<a class="delpointer" onclick="deleteuser(\''.$q.'\',\''.$name.'\',\''.$t.'\')" ><img src="INDEX_FILES/images/delete-icon.jpg" style="height:20px" title="Delete Record"></a>';			

														echo '<tr>';
														echo '<td>' . $row['Id'] . '</td>';
														echo '<td>' . $name. '</td>';
														echo '<td>' . $row['staff_id']. '</td>';
														echo '<td>' . $row['staff_type']. '</td>';
														echo '<td>' . $row['username']. '</td>';
														echo '<td>' . $row['department']. '</td>';
														echo '<td>' . $row['course']. '</td>';	
																										
														//echo '<td><input type="button" value="Submit or Update"   onclick="submit_hnd_Admission(\''.$id.'\')"></td>';
														echo '<td>'.$edit_link.'<td>';
														echo '<td>'.$delete_link.'<td>';
													}

													echo '</tr>';
													echo '</tbody>';
													echo '</table>';
													
													echo '<ul class="pagination" align="center">';
																	
													if ($total_pages > 1)
													{
														//this is for previous record
														if ($has_previous_page)
														{
														echo ' <li><a href=Register_student.php?page='.$previous_page.$tabvalue.$otherpage3.'>&laquo; </a> </li>';
														}
														 //it loops to all pages
														 for($i = 1; $i <= $total_pages; $i++)
														 {
															//check if the value of i is set to current page	
															if ($i == $current_page)
															{
															//then it sset the i to be active or focused
																echo '<li class="active"><span>'. $i.' <span class="sr-only">(current)</span></span></li>';
															 }
															 else
															 {
															 //display the page number
																echo ' <li><a href=Register_student.php?page='.$i.$tabvalue.$otherpage3.'> '. $i .' </a></li>';
															 }
														 }
														//this is for next record		
														if ($has_next_page)
														{
															echo ' <li><a href=Register_student.php?page='.$next_page.$tabvalue.$otherpage3.'>&raquo;</a></li> ';
														}
														
													}
													
													echo '</ul>';
													mysql_close($conn);
													?>
												</tbody>
											</table>
										</div>
									</div>
								
								</div>
								
								<!-- STUDENT Edit -->
								<div id="dD" class="tab-pane" style="background-color:#FFFFFF;">
									 <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:0px; padding-left:1px;padding-bottom:5%; margin-left:0%; margin-right:0%">
											<div class="well" style="background-color:#FFFFFF;" >
												<div class="col-lg-6">LIST OF ALL REGISTERED STUDENT </div><div class="col-lg-6" id="load2"><?php echo $delstaff2; ?></div>
											<table class="table table-condensed" style="background-color:#FFFFFF;">
												<thead>
													<tr>
														<th>Id</th>
														<th>Student Name</th>
														<th>Registration_No</th>
														<th>Rank</th>
														<th>Email</th>
														<th>Level</th>
														<th>Course</th>
														<th></th>
														<th></th>
													</tr>
												</thead>

												<tbody>
													<?php
													//create a mySQL connection
													$dbhost    = 'localhost';
													$dbuser    = 'root';
													$dbpass    = '';
													$conn = mysql_connect($dbhost, $dbuser, $dbpass);
													if (!$conn) {
														die('Could not connect: ' . mysql_error());
													}
													mysql_select_db('abu_server');
													/* Get total number of records */
													$status = "";
													$sql    = "SELECT count(*) FROM staff_information where username != '".$status."' and change_password !='".$status."'";
													$retval = mysql_query($sql, $conn);
													
													if (!$retval)
													{
														die('Could not get data: ' . mysql_error());
													}
													
													
													$current_page = !empty($_GET['page1']) ? (int)$_GET['page1'] : 1;
												
													//record per Page($per_page)	
													$per_page = 20;
													$tabvalue = "&tabb=4";
													
													$otherpage1=!empty($_GET['page']) ? (int)$_GET['page'] : 1;
													$otherpage="&page=".$otherpage1;
													
													$row = mysql_fetch_array($retval, MYSQL_NUM);
													//the total number of page is in this variable bellow $total_count
													$total_count = $row[0];
													$t="4";
													$total_pages = $total_count/$per_page;

													$offset = ($current_page - 1) * $per_page;
													
													$previous_page = $current_page - 1;
		
													$next_page = $current_page + 1;
													$has_previous_page =  $previous_page >= 1 ? true : false;
													$has_next_page = $next_page <= $total_pages ? true : false;
													$status="";
													//find records of employee and we specify the offset and the limit record per page   
													$sql = "SELECT Id,first_name,middle_name,last_name,student_type,course,Level,student_id,username FROM student_information where username != '".$status."' and change_password !='".$status."' LIMIT {$per_page} OFFSET {$offset}"; 
													$retval = mysql_query($sql, $conn);
													if (!$retval) {
														die('Could not get data: ' . mysql_error());
													}
													while ($row = mysql_fetch_array($retval, MYSQL_ASSOC)) 
													{
															$name = $row['first_name']." ".$row['middle_name']." ".$row['last_name'];
															
															$j="Admin_edit_details.php?sherif=".$row['student_id'].$tabvalue;
															$edit_link = "<a href=".$j."><img src="."INDEX_FILES/images/Text-Edit-icon.png"." style='height:20px' title='Edit Record'></a>";
															
															/*$q="Register_student.php?delete=".$row['student_id'].$tabvalue;
															$delete_link = "<a href=".$q."><img src="."INDEX_FILES/images/delete-icon.jpg"." style='height:20px'></a>";
															//$delete_link = "<a href=".$q.">Delete</a>";*/
															
															$q="Register_student.php?delete=".$row['student_id'].$tabvalue;
															$delete_link = '<a class="delpointer" onclick="deleteuser(\''.$q.'\',\''.$name.'\',\''.$t.'\')" ><img src="INDEX_FILES/images/delete-icon.jpg" style="height:20px" title="Delete Record"></a>';			

											
														echo '<tr>';
														echo '<td>' . $row['Id'] . '</td>';
														echo '<td>' . $name. '</td>';
														echo '<td>' . $row['student_id']. '</td>';
														echo '<td>' . $row['student_type']. '</td>';
														echo '<td>' . $row['username']. '</td>';
														echo '<td>' . $row['Level']. '</td>';
														echo '<td>' . $row['course']. '</td>';	
																					
														echo '<td>'.$edit_link.'<td>';
														echo '<td>'.$delete_link.'<td>';	
													}

													echo '</tr>';
													echo '</tbody>';
													echo '</table>';
													
													echo '<ul class="pagination" align="center">';
																	
													if ($total_pages > 1)
													{
														//this is for previous record
														if ($has_previous_page)
														{
														echo ' <li><a href=Register_student.php?page1='.$previous_page.$tabvalue.$otherpage.'>&laquo; </a> </li>';
														}
														 //it loops to all pages
														 for($i = 1; $i <= $total_pages; $i++)
														 {
															//check if the value of i is set to current page	
															if ($i == $current_page)
															{
															//then it sset the i to be active or focused
																echo '<li class="active"><span>'. $i.' <span class="sr-only">(current)</span></span></li>';
															 }
															 else
															 {
															 //display the page number
																echo ' <li><a href=Register_student.php?page1='.$i.$tabvalue.$otherpage.'> '. $i .' </a></li>';
															 }
														 }
														//this is for next record		
														if ($has_next_page)
														{
															echo ' <li><a href=Register_student.php?page1='.$next_page.$tabvalue.$otherpage.'>&raquo;</a></li> ';
														}
														
													}
													
													echo '</ul>';
													mysql_close($conn);
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
								
								
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
