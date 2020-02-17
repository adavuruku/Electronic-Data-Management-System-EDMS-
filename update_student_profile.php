<?php
$email = $mail_error=$full_name=$countval="";
session_start(); 
require_once 'settings/connection.php';
require_once 'settings/filter.php';
require_once 'settings/count_mail_record_module.php';
$passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
$d = $h=$t=$countval=$date=$subject=$body=$sender_address = $mail_type = $department = $address = $full_name=$attached_file=$generr="";
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

if((isset($_SESSION['username'])))
{
	$user_name = strip_tags($_SESSION['username']);
}
if($user_name =="")
{
	header("location: logout.php");
}

//remove course begins
if((isset($_GET['record'])) && (isset($_GET['otatum']))&& (isset($_GET['mataatum'])))
{
	$ota = SHA1(MD5($_GET['mataatum']));
	$otatum= strip_tags($_GET['otatum']);
	if($ota != $otatum )
	{
		$generr='<div class="alert alert-danger alert-error">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong>Error!</strong>Unable To Remove Record Retry - server Error..Please Retry
		</div>';
	}
	else
	{
		if($_GET['record'] = "40xy")
		{
			//delete from course table
			$id_search = strip_tags($_GET['mataatum']);
			$stmt = $conn->prepare("DELETE FROM staff_course WHERE staff_mail=? AND id=? Limit 1");
			$stmt->execute(array($_SESSION['username'],$id_search));
			$affected_rows = $stmt->rowCount();
			if($affected_rows >= 1) 
			{
				$generr='<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong>The selected record or informations was successfully Removed
				</div>';
			}
			else
			{
				$generr='<div class="alert alert-danger alert-error">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong>Unable to Remove The selected record or informations- Server Error
				</div>';
			}
		}
		if($_GET['record'] = "53yx")
		{
			//delete from qualifications table
			//delete from course table
			$id_search = strip_tags($_GET['mataatum']);
			$stmt = $conn->prepare("DELETE FROM staff_qualification WHERE staff_mail=? AND id=? Limit 1");
			$stmt->execute(array($_SESSION['username'],$id_search));
			$affected_rows = $stmt->rowCount();
			if($affected_rows >= 1) 
			{
				$generr='<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong>The selected record or informations was successfully Removed
				</div>';
			}
			else
			{
				$generr='<div class="alert alert-danger alert-error">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong>Unable to Remove The selected record or informations- Server Error
				</div>';
			}
			
		}
	}
}

//update Codings Begins - Name
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_name']))
	{
		$new_name_first = checkempty($_POST['new_name_first']);
		$new_name_last = checkempty($_POST['new_name_last']);
		//$new_name_middle = checkempty($_POST['new_name_middle']);
		if(($new_name_first != FALSE) && ($new_name_last != FALSE))
		{
			$new_name_first = trim($_POST['new_name_first']);
			$new_name_last = trim($_POST['new_name_last']);
			$new_name_middle = trim($_POST['new_name_middle']);
			
			$stmt = $conn->prepare("UPDATE student_information SET first_name=?,middle_name=?,last_name=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($new_name_first,$new_name_middle,$new_name_last,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your Name Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Name - server Error..Please Retry
												</div>';
											}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Name - Firstname or Last Name are Empty..Please Retry
												</div>';
		}
	}
	
	//update State and Local Government
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_state']))
	{
		$cmbstate = checkempty($_POST['cmbstate']);
		$cmblgov = checkempty($_POST['cmblgov']);
		$cmbstate1 = trim($_POST['cmbstate']);
		$cmblgov1 = trim($_POST['cmblgov']);
		//$new_name_middle = checkempty($_POST['new_name_middle']);
		if(($cmbstate != FALSE) && ($cmblgov != FALSE) && ($cmbstate1 != "-select state-") && ($cmblgov1 !="-select local govt-"))
		{
			$cmbstate = trim($_POST['cmbstate']);
			$cmblgov = trim($_POST['cmblgov']);
			$stmt = $conn->prepare("UPDATE student_information SET state_origin=?,local_gov=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($cmbstate,$cmblgov,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your State and Local Gov\'t Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your State and Local Gov\'t - server Error..Please Retry
												</div>';
											}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your State and Local Gov\'t - Either No state was selected or No Local Govt was swlwcted ..are Empty..Please Retry
												</div>';
		}
	}
	
//update Date Employed
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_emp_date']))
	{
		$new_name_first = checkempty($_POST['new_emp_date_year']);
		$new_name_last = checkempty($_POST['new_emp_date_month']);
		$new_name_middle = checkempty($_POST['new_emp_date_day']);
		if(($new_name_first != FALSE) && ($new_name_last != FALSE)&& ($new_name_last != FALSE))
		{

			$new_emp_date_day = trim($_POST['new_emp_date_day']);
			$new_emp_date_month = trim($_POST['new_emp_date_month']);
			$new_emp_date_year = trim($_POST['new_emp_date_year']);
			$all = $new_emp_date_year."-".$new_emp_date_month."-".$new_emp_date_day;
			//$date5 = new DateTime($all);
			$stmt = $conn->prepare("UPDATE student_information SET date_admited=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($all,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your Employment Date Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Employment Date - server Error..Please Retry
												</div>';
											}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Employment Date - Day or Month or Year might be Empty or not in numbers.. use format 01/02/2014 Please Retry
												</div>';
		}
	}
	
//update Date Birth
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_date_birth']))
	{
		$new_name_first = checkempty($_POST['new_birth_date_year']);
		$new_name_last = checkempty($_POST['new_birth_date_month']);
		$new_name_middle = checkempty($_POST['new_birth_date_day']);
		if(($new_name_first != FALSE) && ($new_name_last != FALSE)&& ($new_name_last != FALSE))
		{

			$new_emp_date_day = trim($_POST['new_birth_date_day']);
			$new_emp_date_month = trim($_POST['new_birth_date_month']);
			$new_emp_date_year = trim($_POST['new_birth_date_year']);
			$all = $new_emp_date_year."-".$new_emp_date_month."-".$new_emp_date_day;
			//$date5 = new DateTime($all);
			$stmt = $conn->prepare("UPDATE student_information SET dob=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($all,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your Birth Date Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Birth Date - server Error..Please Retry
												</div>';
											}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Birth Date - Day or Month or Year might be Empty or not in numbers.. use format 01/02/2014 Please Retry
												</div>';
		}
	}
	
	//update phone number
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_phone']))
	{
		$new_phone = checkempty($_POST['new_phone']);
		$new_name_last = checksize($_POST['new_phone']);
		//$new_name_middle = checkempty($_POST['new_birth_date_day']);
		if(($new_phone != FALSE) && ($new_name_last != FALSE))
		{

			$new_phone = trim($_POST['new_phone']);
			//$date5 = new DateTime($all);
			$stmt = $conn->prepare("UPDATE student_information SET phone_no=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($new_phone,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your Phone Number Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Phone Number - server Error..Please Retry
												</div>';
											}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Phone Number - Phone Number box might be Empty or not in numbers.. use format 01/02/2014 Please Retry
												</div>';
		}
	}
	
//update Email Address
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_email']))
	{
		$new_email = checkempty($_POST['new_email']);
		//$new_name_last = checkempty($_POST['new_emp_date_month']);
		//$new_name_middle = checkempty($_POST['new_emp_date_day']);
		if($new_email != FALSE)
		{
			$new_email = filterEmail($_POST['new_email']);
			if($new_email != FALSE)
			{
				$new_email = trim($_POST['new_email']);
				//make sure the email is not yet in use by another person
				$query6 = "SELECT email FROM staff_information WHERE email =:email";
				$stmt6 = $conn->prepare($query6);
				$stmt6->bindValue(':email',$new_email, PDO::PARAM_STR);
				$stmt6->execute();
				$rows6 = $stmt6->fetch(PDO::FETCH_ASSOC);
				$row_count6 = $stmt6->rowCount();
				if($row_count6 != 1) 
				{
					$query6 = "SELECT email FROM student_information WHERE email =:email";
					$stmt6 = $conn->prepare($query6);
					$stmt6->bindValue(':email',$new_email, PDO::PARAM_STR);
					$stmt6->execute();
					$rows6 = $stmt6->fetch(PDO::FETCH_ASSOC);
					$row_count6 = $stmt6->rowCount();
					if($row_count6 != 1) 
					{
								$stmt = $conn->prepare("UPDATE student_information SET email=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($new_email,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your Account Email Address Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Account Email Address - server Error..Please Retry
												</div>';
											}
					}
					else
					{
						$generr='<div class="alert alert-danger alert-error">
																<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>
																Unable to Update Your Account Email Address.. Email in use by anothe user.. Please Retry</div>';
					}
				}
				else
				{
					$generr='<div class="alert alert-danger alert-error">
																<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>
																Unable to Update Your Account Email Address.. Email in use by anothe user.. Please Retry</div>';
				}
			}
			else
			{
				$generr='<div class="alert alert-danger alert-error">
														<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>Unable To Update Your Your Account Email Address
														Invalid Email Entered ...Please Retry</div>';
			}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
														<a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>Unable To Update Your Your Account Email Address
														Email Box is Empty ...Please Retry</div>';
		}
	}	
	
	//update Gender
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_gender']))
	{
		$new_gender = checkempty($_POST['new_gender']);
		
		if($new_gender != FALSE)
		{

			$new_gender = trim($_POST['new_gender']);
			//$date5 = new DateTime($all);
			$stmt = $conn->prepare("UPDATE student_information SET Gender=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($new_gender,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your Gender Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Gender - server Error..Please Retry
												</div>';
											}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Gender - Gender box may be Empty or not in numbers.. use format 01/02/2014 Please Retry
												</div>';
		}
	}

//update Marital Status
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_marital']))
	{
		$new_marital = checkempty($_POST['new_marital']);
		
		if($new_marital != FALSE)
		{

			$new_marital = trim($_POST['new_marital']);
			//$date5 = new DateTime($all);
			$stmt = $conn->prepare("UPDATE student_information SET marital_status=?
											 WHERE username=? Limit 1");
											$stmt->execute(array($new_marital,$_SESSION['username']));
											$affected_rows = $stmt->rowCount();
											if($stmt == true) 
											{
												$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your Marital Status Was successfully Updated
												</div>';
											}
											else
											{
												$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Marital Status - server Error..Please Retry
												</div>';
											}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Update Your Marital Status - Marital Status box may be Empty or not in numbers.. use format 01/02/2014 Please Retry
												</div>';
		}
	}

	//update Staff Qualification
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_qualification']))
	{
		$new_qualification_school = checkempty($_POST['new_qualification_school']);
		$new_qualification = checkempty($_POST['new_qualification']);
		//$new_name_middle = checkempty($_POST['new_name_middle']);
		if(($new_qualification_school != FALSE) && ($new_qualification != FALSE))
		{
			$new_qualification_school = trim($_POST['new_qualification_school']);
			$new_qualification = trim($_POST['new_qualification']);
			$sth = $conn->prepare ("INSERT INTO staff_qualification (qualification,school_attended, staff_mail)
																VALUES (?,?,?)");
						$sth->bindValue (1, $new_qualification); $sth->bindValue (2, $new_qualification_school); $sth->bindValue (3, $_SESSION['username']);
						$sth->execute();
						$affected_rows = $sth->rowCount();
						if($affected_rows == 1) 
						{
							$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your New Qualification Was successfully Added to your Qualifications
												</div>';
						}
						else
						{
							$generr='<div class="alert alert-danger alert-error">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							<strong>Error!</strong>Unable To Add Your New Qualification - server Error..Please Retry
							</div>';
						}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Add Your New Qualification - Either The Qualification Box or Qualification School are Empty..Please Retry
												</div>';
		}
	}
	
	//update Course Staff is Currently taken in the faculty
	if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_new_Course']))
	{
		$new_department = checkempty($_POST['new_department']);
		$new_level = checkempty($_POST['new_level']);
		$new_course = checkempty($_POST['new_course']);
		if(($new_department != FALSE) && ($new_level != FALSE)&& ($new_course != FALSE))
		{
			$new_department = trim($_POST['new_department']);
			$new_level = trim($_POST['new_level']);
			$new_course = trim($_POST['new_course']);
			$sth = $conn->prepare ("INSERT INTO staff_course (course,department,level,staff_mail)
																VALUES (?,?,?,?)");
						$sth->bindValue (1, $new_course); $sth->bindValue (2, $new_department); 
						$sth->bindValue (3, $new_level); $sth->bindValue (4, $_SESSION['username']);
						$sth->execute();
						$affected_rows = $sth->rowCount();
						if($affected_rows == 1) 
						{
							$generr='<div class="alert alert-success">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Success!</strong>Your New Course Was successfully Added to your Qualifications
												</div>';
						}
						else
						{
							$generr='<div class="alert alert-danger alert-error">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
							<strong>Error!</strong>Unable To Add Your New Course - server Error..Please Retry
							</div>';
						}
			
		}
		else
		{
			$generr='<div class="alert alert-danger alert-error">
													<a href="#" class="close" data-dismiss="alert">&times;</a>
													<strong>Error!</strong>Unable To Add Your New Course - Either The Department Box or Level box or Course box are Empty..Please Retry
												</div>';
		}
	}

	//************************************************///
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
			$_SESSION['passport'] = $staff_picture = '<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
			if($rows3['pic_extension']!=""){
				$paspart = "abu_file/".$user_name.$rows3['pic_extension'];
				$_SESSION['passport'] = $staff_picture ='<img src='.$paspart.' class="img-responsive" style="margin:0px"></img>';
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
<script type="text/javascript" src="INDEX_FILES/combo_box_change.js"></script>

<link rel="stylesheet" type="text/css" href="INDEX_FILES/index.css" >
<script type="text/javascript" src="INDEX_FILES/jquery.min.js"></script>
<script type="text/javascript" src="INDEX_FILES/jquery.form.js"></script>
<script type="text/javascript" >
 $(document).ready(function()
	{ 
		$('#photoimg').live('change', function()
		{ 
			$("#preview").html('');
			$("#preview").html('<img src="INDEX_FILES/images/loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({target: '#preview'}).submit();
		});
    }); 
</script>	
</head>

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
									<?php echo $generr; ?>
							</div>
						<div class="col-lg-12" style="margin-bottom:0%">
							<div class="col-lg-3" style="margin-bottom:0%">
								
								<form id="imageform" method="post" enctype="multipart/form-data" action='upload_passport.php'>
									<div class="col-lg-12" style="margin-bottom:10%" id="preview">
									  <?php echo $staff_picture; ?>
									</div>
									<div class="col-lg-12" style="margin-bottom:0%">
										<input type="file" name="photoimg" value="browse" id="photoimg"/>
									</div>
								</form>
							</div>
							<div class="col-lg-9" style="margin-bottom:0%">
									<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
									<table class="table table-condensed" style="background-color:#FFFFFF;">
										<tbody>
											<tr>
												<td>Name : </td>
												<td colspan="2"><?php echo $staff_name; ?></td>
											</tr>
											<tr>
												<td  style="width:45%"><input style="width:100%" type="text" value="" name="new_name_first" placeholder="Enter New First_Name"></input></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td  style="width:45%"><input style="width:100%" type="text" value="" name="new_name_middle" placeholder="Enter New Middle_Name"></input></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td  style="width:45%"><input style="width:100%" type="text" value="" name="new_name_last" placeholder="Enter New Lat_Name"></input></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_name" ></input></td>
											</tr>
											<tr>
												<td>User name : </td>
												<td colspan="2"><?php echo $staff_username; ?></td>
											</tr>
											
											<tr>
												<td>Student Rank : </td>
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
												<td  style="width:45%"><input style="width:20%" type="text" value="" onkeydown="return noNumbers(event,this)" name="new_emp_date_day" placeholder="DD"></input>/<input style="width:20%" onkeydown="return noNumbers(event,this)" type="text" value="" name="new_emp_date_month" placeholder="MM"></input>/<input style="width:40%" type="text" value="" onkeydown="return noNumbers(event,this)" name="new_emp_date_year" placeholder="YYYY"></input></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_emp_date" ></input></td>
											</tr>
											<tr>
												<td colspan="3" style="color:red">Personal Information</td>
											</tr>
											<tr>
												<td>Email : </td>
												<td colspan="2"><?php echo $staff_email; ?></td>
											</tr>
											<tr>
												<td  style="width:45%"><input style="width:100%" type="email" value="" name="new_email" placeholder="Enter New Personal Email Address"></input></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_email" ></input></td>
											</tr>
											<tr>
												<td>Phone NO : </td>
												<td colspan="2"><?php echo $student_phone; ?></td>
											</tr>
											<tr>
												<td  style="width:45%"><input onkeydown="return noNumbers(event,this)" style="width:100%" type="phone" value="" name="new_phone" placeholder="Enter New Phone"></input></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_phone" ></input></td>
											</tr>
											<tr>
												<td>Gender : </td>
												<td colspan="2"><?php echo $staff_gender; ?></td>
											</tr>
											<tr>
												<td  style="width:60%"><select style="width:60%" type="text" name="new_gender">
													<option value="Male">Male</option>
													<option value="Female">Female</option>
												</select></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_gender" ></input></td>
											</tr>
											<tr>
												<td>Date Of Birth : </td>
												<td colspan="2"><?php echo $staff_dob; ?></td>
											</tr>
											<tr>
												<td  style="width:45%"><input style="width:20%" type="text" onkeydown="return noNumbers(event,this)" name="new_birth_date_day" placeholder="DD"></input>/<input style="width:20%" type="text" onkeydown="return noNumbers(event,this)" name="new_birth_date_month" placeholder="MM"></input>/<input style="width:40%" type="text" onkeydown="return noNumbers(event,this)" name="new_birth_date_year" placeholder="YYYY"></input></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_date_birth" ></input></td>
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
												<td  style="width:60%"><select style="width:60%" type="text" id="cmbstate" name="cmbstate" onchange="stateComboChange();">
												<option value="-select state-" title="-select state-">-select state-</option>
													<option value="Abuja" title="Abuja">Abuja</option>
													<option value="Abia" title="Abia">Abia</option>
													<option value="Adamawa" title="Adamawa">Adamawa</option>
													<option value="Akwa Ibom" title="Akwa Ibom">Akwa Ibom</option>
													<option value="Anambra" title="Anambra">Anambra</option>
													<option value="Bauchi" title="Bauchi">Bauchi</option>
													<option value="Bayelsa" title="Bayelsa">Bayelsa</option>
													<option value="Benue" title="Benue">Benue</option>
													<option value="Bornu" title="Bornu">Bornu</option>
													<option value="Cross River" title="Cross River">Cross River</option>
													<option value="Delta" title="Delta">Delta</option>
													<option value="Ebonyi" title="Ebonyi">Ebonyi</option>
													<option value="Edo" title="Edo">Edo</option>
													<option value="Ekiti" title="Ekiti">Ekiti</option>
													<option value="Enugu" title="Enugu">Enugu</option>
													<option value="Gombe" title="Gombe">Gombe</option>
													<option value="Imo" title="Imo">Imo</option>
													<option value="Jigawa" title="Jigawa">Jigawa</option>
													<option value="Kaduna" title="Kaduna">Kaduna</option>
													<option value="Kano" title="Kano">Kano</option>
													<option value="Katsina" title="Katsina">Katsina</option>
													<option value="Kebbi" title="Kebbi">Kebbi</option>
													<option  value="Kogi" title="Kogi">Kogi</option>
													<option value="Kwara" title="Kwara">Kwara</option>
													<option value="Lagos" title="Lagos">Lagos</option>
													<option value="Nassarawa" title="Nassarawa">Nassarawa</option>
													<option value="Niger" title="Niger">Niger</option>
													<option value="Ogun" title="Ogun">Ogun</option>
													<option value="Ondo" title="Ondo">Ondo</option>
													<option value="Osun" title="Osun">Osun</option>
													<option value="Oyo" title="Oyo">Oyo</option>
													<option value="Plateau" title="Plateau">Plateau</option>
													<option value="Rivers" title="Rivers">Rivers</option>
													<option value="Sokoto" title="Sokoto">Sokoto</option>
													<option value="Taraba" title="Taraba">Taraba</option>
													<option value="Yobe" title="Yobe">Yobe</option>
													<option value="Zamfara" title="Zamfara">Zamfara</option>
												</select></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td  style="width:60%"><select style="width:60%" type="text" id="cmblgov" name="cmblgov">
												<option value="select....." title="-select local govt-">-select local govt-</option>
												
												</select></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_state" ></input></td>
											</tr>
											<tr>
												<td>Marital Status : </td>
												<td colspan="2"><?php echo $staff_marital_status; ?></td>
											</tr>
											<tr>
												<td  style="width:60%"><select style="width:60%" type="text" value="" name="new_marital">
												<option value="Married">Married</option>
												<option value="Single">Single</option>
												<option value="Divorce">Divorce</option>
												</select></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_marital" ></input></td>
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
													$ota2 = SHA1(MD5($rows['id']));
													$link2 = "update_staff_profile.php?mataatum=".$rows['id']."&otatum=".$ota2."&record=53yx";
													echo '<tr><td>'.$rows['qualification'].'</td><td >'.$rows['school_attended'].'</td><td ><a href='.$link2.'>Remove</a></td></tr>';
												}
											}
											else
											{
												echo '<tr><td colspan="3">No Record Uploaded for Student Educational Qualification</td></tr>';
											}
										
											?>
											<tr>
												<td  style="width:60%"><input style="width:100%" type="text" value="" name="new_qualification" placeholder="Enter Name of Qualification"></input></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td  style="width:60%"><input style="width:100%" type="text" value="" name="new_qualification_school" placeholder="Enter Name of School Attended"></input></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_qualification" ></input></td>
											</tr>
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
													$ota = SHA1(MD5($rows['id']));
													$link = "update_student_profile.php?mataatum=".$rows['id']."&otatum=".$ota."&record=40xy";
													echo '<tr><td>'.$rows['department'].'</td><td>'.$rows['level'].'</td><td>'.$rows['course'].'</td><td ><a href='.$link.'>Remove</a></td></tr>';
												}
											}
											else
											{
												echo '<tr><td colspan="3">No Record Uploaded for Student Current Best Course of Study</td></tr>';
											}
											?>
											<tr>
												<td  style="width:60%"><select style="width:60%" type="text" value="" name="new_department">
												<?php
													$stmt = $conn->prepare("SELECT * FROM department ORDER BY course ASC");
					
													$stmt->execute();
													if ($stmt->rowCount () >= 1)
													{
														while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
														{
															echo '<option value='.$rows['course'].'>'.$rows['course'].'</option>';
														}
													}
													
													?>
												
												</select></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td  style="width:60%"><select style="width:60%" type="text" value="" name="new_level">
												<option value="200 Level">100 Level</option>
												<option value="200 Level">200 Level</option>
												<option value="300 Level">300 Level</option>
												<option value="400 Level">400 Level</option>
												</select></td>
												<td colspan="2"></td>
											</tr>
											<tr>
												<td  style="width:60%"><input style="width:100%" type="text" value="" name="new_course" placeholder="Enter Name and Code of Course Taken"></input></td>
												<td colspan="2"><input  type="submit" value="Update" name="submit_new_Course" ></input></td>
											</tr>
										<tbody>
									</table>
									</form>
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
</html>  
