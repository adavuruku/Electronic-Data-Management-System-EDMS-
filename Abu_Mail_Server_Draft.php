<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/count_mail_record_module.php';
require_once 'settings/filter.php';
$email = $passport=$full_name="";
$passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
$full_name = $_SESSION['my_name'];
unset($_SESSION['resend_subject']);
unset($_SESSION['resend_body']);
unset($_SESSION['resend_attach']);
unset($_SESSION['resend_id']);
unset($_SESSION['sender_address_reply']);
if((!isset($_SESSION['username'])) && (!isset($_SESSION['my_name'])))
{
	header("location: logout.php");
}
						
$inbox_count = count_inbox();
$read_inbox_count= count_read_inbox();
$unread_inbox_count = $inbox_count - $read_inbox_count;
//$unread_inbox_count = 5 - 10;

$Outbox_count= count_outbox();
$draft_count= count_draft();
$publication_count = count_publication();

//delete module on check box tick
$value_check="";
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Delete']) && isset($_POST['selectbox']))
{
	$sam = $_POST['selectbox'];
	if (count($sam) >0)
	{
		foreach($sam as $value)
		{
			//$value_check=$value_check." ".$value;
			//sign maill as delleted
			//update the id table
			$query301 = "UPDATE draft_mail SET delete_status = ? WHERE username=? And draft_id=?";
			$stmt301 = $conn->prepare($query301);
			$stmt301->execute(array('1',$_SESSION['username'],$value));
			//$_SESSION['success']="Your mail was sent successfully";
			
			
		}
		$inbox_count = count_inbox();
		$read_inbox_count= count_read_inbox();
		$unread_inbox_count = $inbox_count - $read_inbox_count;
		//$unread_inbox_count = 5 - 10;

		$Outbox_count= count_outbox();
		$draft_count= count_draft();
		$publication_count = count_publication();
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
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" style="background-color:green" role="navigation" >
            <div class="navbar-header" style="background-color:blue">
                <div class="container-fluid">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar">Sign Out</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" style="font-size:20px;font-weight:bold;color:black" href="#">Ahmadu Bello University Zaria - Mail Server</a>
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
					<a href="redirect_compose.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> Create New Mail</a>
					<a href="Abu_Mail_Server_Inbox.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge"><?php echo $inbox_count; ?></span> View Inbox </a>
					<a href="Abu_Mail_Server_Outbox.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span> <span class="badge"><?php echo $Outbox_count; ?></span> View OutBox</a>
					<a href="Abu_Mail_Server_Draft.php" class="list-group-item"> <span class="glyphicon glyphicon-plus mail"></span> <span class="badge"><?php echo $draft_count; ?></span> View Draft Mail</a>
					<a href="Abu_Mail_Server_Search_user.php" target="_blank" class="list-group-item"> <span class="glyphicon glyphicon-plus mail"></span> <span class="badge"></span> Search Mail User</a>
				</div>
				<div class="nav-head"><h4>My Publications</h4></div>
				<div class="list-group show" style="margin-bottom:80px">
					<a href="Create_Publication_Mail.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span><span class="badge"></span> New Publications</a>
					<a href="Abu_Mail_Server_My_Publication.php" class="list-group-item"><span class="glyphicon glyphicon-plus glysize"></span><span class="badge"><?php echo $publication_count; ?></span> My Publications</a>
					<a href="Abu_Mail_Server_Search_Publication.php" class="list-group-item"> <span class="glyphicon glyphicon-plus glysize"></span> Search Publications</a>
				</div>
	
			</div> 
	
			
			<div  class="col-sm-10 col-md-10 col-lg-10">
			       <div style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;"> <?php echo $_SESSION['username']; ?> - You are Currently Log In</span><br></span><span 
				   style="color:yellow; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:20px;"> All DRAFT / SAVED MAIL : <?php echo $draft_count; ?></center>
				     <div style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:20px;padding-bottom:5%; margin-left:0%; margin-right:0%">
<form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST" name="step2">
							<input type="submit" name="Delete" value="Delete All Selected Draft Mails"></input>
							<br/>
							<table class="table table-condensed" style="background-color:#FFFFFF;">
												

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
													//$sql    = "SELECT count(*) FROM draft_mail where sender_address = '".$_SESSION['username']."' AND box_type='".$box_type."' AND sent_delete ='".$status."'";
													$sql    = "SELECT count(*) FROM draft_mail where username = '".$_SESSION['username']."' AND delete_status = '".$status."'";
													$retval = mysql_query($sql, $conn);
													
													if (!$retval)
													{
														die('Could not get data: ' . mysql_error());
													}
													
													
													$current_page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
												
													//record per Page($per_page)	
													$per_page = 20;
													
												
													
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
													$sql = "SELECT * FROM draft_mail where username='".$_SESSION['username']."' AND delete_status ='".$status."' ORDER BY Id DESC LIMIT {$per_page} OFFSET {$offset} "; 
													$retval = mysql_query($sql, $conn);
													if (!$retval) {
														die('Could not get data: ' . mysql_error());
													}
													while ($row = mysql_fetch_array($retval, MYSQL_ASSOC)) 
													{
															//$name = $row['sender_name'];
															//$staffid ="Value=".$row['staff_id'];
															$code = SHA1($row['draft_id']);
															$code2 = MD5($row['draft_id']);
															$j="Abu_server_read_draft_mails.php?d=".$row['draft_id']."&h=".$code."&t=".$code2;
															
															$add="";
															$add = substr($row['subject'],0,30);
															$add = $add."...";
															$user_link = '<a class="delpointer" href='.$j.' title='.$row['username'].'>'.$add.'</a>';	
															
															$checkbox='<input type="checkbox" value='.$row['draft_id'].' name="selectbox[]" ></input>';
															
															$attachment="";
															if($row['attached_status']=="1")
															{
																$attachment = "<a href=".$j."><img src="."INDEX_FILES/images/attachment_icon.jpg"." style='height:20px' title='File Attached'></a>";
															}

														$date_file =$row['date_save'];
														
														$subj="";
														$subj = substr($row['body'],0,50);
														$subj = $subj."...";
														
														//$mail_subject = $subj;
														$mail_subject = "<a href=".$j.">".$subj."</a>";
														echo '<tr>';
														echo '<td>' . $checkbox. '</td>';
														echo '<td >' . $user_link. '</td>';
														echo '<td>' . $mail_subject. '</td>';
														echo '<td>'.$attachment.'<td>';
														echo '<td>'.$date_file.'<td>';
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
														echo ' <li><a href=Register_student.php?page='.$previous_page.'>&laquo; </a> </li>';
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
																echo ' <li><a href=Abu_Mail_Server_Draft.php?page='.$i.'> '. $i .' </a></li>';
															 }
														 }
														//this is for next record		
														if ($has_next_page)
														{
															echo ' <li><a href=Abu_Mail_Server_Draft.php?page='.$next_page.'>&raquo;</a></li> ';
														}
														
													}
													
													echo '</ul>';
													mysql_close($conn);
													?>
												</tbody>
											</table>
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
