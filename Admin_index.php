<?php
session_start();
$submittab="1";
if (isset($_GET['tabb'])){
$submittab=$_GET['tabb'];
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
							 <a href="logout.php" class="navbar-brand">Home</a>
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
	<div class="well" style="background-color:BLUE;color:RED">
			<h2 style="font-family: comic sans ms;font-weight:bold">ABU MAIL - ADMINISTRATIVE CONTROL PANNEL HOME</h2>
			<h5 style="font-family: comic sans ms;">Welcome - <?php echo $_SESSION['myfile']; ?> - The ABU File Server Portal Administrator - <a style="color:BLUE" href="logout.php">Sign Out </a></h5>
</div>
        <div class="col-xs-12 col-sm-12">
			<div  class="col-sm-4 col-md-4 col-lg-4">
			<div class="nav-head"><h4>User Management</h4></div>
				<div class="list-group show" style="margin-bottom:50px">
					<a href="Register_student.php?tabb=1" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span><span class="badge">New</span>  Register New Staff</a>
					<a href="Register_student.php?tabb=2" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">New</span> Register New Student </a>
					<a href="Admin_edit_details.php?tabb=3" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">Edit</span> Edit Staff / Student Details </a>
					<a href="Admin_Page/Download_all_mail_user_list.php" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">Edit</span>Download All Mail User List</a>
					<a href="Admin_Block_User.php?tabb=1" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">security</span>Block User From Using ABU Mail</a>
					<a href="Admin_Block_User.php?tabb=2" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> <span class="badge">security</span>Block User From Using Publication</a>
				</div>
			</div> 
			<div  class="col-sm-4 col-md-4 col-lg-4">
				<div class="nav-head"><h4>Mail Management</h4></div>
				<div class="list-group show" style="margin-bottom:80px">
					<a href="Admin_Page/Download_all_mail_inbox.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Download All Mail INBOX </a>
					<a href="Admin_Page/Download_all_mail_outbox.php" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> Download All Mail OUTBOX </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Download a User Mail History </a>
					<a href="Download_all_mail_files.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Download All Maill Files </a>
					<a href="Admin_Page/Admin_Page/Download_all_delleted_mail.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Download All Deleted Mail </a>
				</div>
			</div>
			<div  class="col-sm-4 col-md-4 col-lg-4">
				<div class="nav-head"><h4>File Server Management</h4></div>
				<div class="list-group show" style="margin-bottom:80px">
					<a href="Admin_Page/Download_all_publish_record.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Download All Publication </a>
					<a href="Download_all_publish_files.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Download All Publish Files </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Delete Publication </a>
					<a href="#" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Edit Publication Details </a>
				</div>
			</div>
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
