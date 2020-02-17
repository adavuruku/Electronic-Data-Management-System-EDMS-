<?php
$email = $mail_error=$full_name=$countval="";
session_start(); 
require_once 'settings/connection.php';
require_once 'settings/filter.php';
require_once 'settings/count_mail_record_module.php';
require_once "phpuploader/include_phpuploader.php";
$passport ='<img src="abu_file/DefaultHead.jpg" class="img-responsive" style="margin:0px"></img>';
$d = $h=$t=$countval=$date=$subject=$body=$sender_address = $mail_type = $department = $address = $full_name=$attached_file="";
$inbox_count = count_inbox();
$read_inbox_count= count_read_inbox();
$unread_inbox_count = $inbox_count - $read_inbox_count;
//$unread_inbox_count = 5 - 10;

$Outbox_count= count_outbox();
$draft_count= count_draft();
$publication_count = count_publication();

if((!isset($_SESSION['username'])) && (!isset($_SESSION['my_name'])) && (!isset($_SESSION['statement'])))
{
	header("location: logout.php");
}

if((!isset($_SESSION['searchpublication'])))
{
	$_SESSION['searchpublication'] = $_SESSION['username'];
}


if($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['searchpubish'])))
{
	$mail_error="";
	$mail_subject = checkempty($_POST['searchfield']);

	//1//1 = 1
	if($mail_subject != FALSE)
	{
		$attached_status= "0";
		$mail_subject = strip_tags($_POST['searchfield']);
		$mail_subject = htmlentities(trim($mail_subject));
		$_SESSION['searchpublication']=$mail_subject;
	}
	else
	{
		$_SESSION['searchpublication'] = $_SESSION['username'];
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
<script type="text/javascript" src="INDEX_FILES/js/bootstrap.min.js"></script>

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
			       <div class="col-lg-12" style="width:100%; background-color:#006633; margin-bottom:10%; border:4px groove #006633;">
				   <center><span style="color:#FFFFFF; font-weight:bold; font-family:'Times New Roman', Times, serif; font-size:16px;"> <?php echo $_SESSION['username']; ?> - You are Currently Log In</span></center>
				    <div class="col-lg-12" style="width:auto; background-color:#FFFFFF; margin-top:2%; padding-top:20px; padding-left:20px;padding-bottom:5%; margin-left:0%; margin-right:0%">
						
						
						<div class="col-lg-12" style="margin-bottom:0%">
								<p> <?php echo $_SESSION['username']; ?>,
								<p>Enter any sentence or a key word (like Department, Name, Course Code e.t.c) to search for Publications.</p>
								<?php echo $mail_error;?>
							<hr>
						</div>
								
						<div  class="col-lg-12" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
								<form role="form" name="mailform" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="POST">
								
									<div class="form-group">
										<label for="inputEmail" class="control-label col-xs-3">Enter Description</label>
										<div class="col-xs-7">
											<input type="text" class="form-control" name="searchfield" Value="<?php echo $_SESSION['searchpublication'];?>" id="searchfield" placeholder="Enter Your Description to Search">
										</div>
										<div class=" col-lg-2">
											<button type="submit" name="searchpubish" class="btn btn-success">Search Mail</button>
										</div>
									</div>
								</form>										
						</div>
								
								
							
								<div  class="col-lg-9" style="width:100%; padding-top:10px; padding-left:5px; padding-bottom:10px; background-color:grey;margin-bottom:1%">
						
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
													
													$box_type="Draft";
													$sql    = "SELECT count(*) FROM file_server where publisher_meta LIKE '%".$_SESSION['searchpublication']."%'";
													$retval = mysql_query($sql, $conn);
													
													if (!$retval)
													{
														die('Could not get data: ' . mysql_error());
													}
													
													
													$current_page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
												
													//record per Page($per_page)	
													$per_page = 10;
												
													$searchlink="&searchlink=".$_SESSION['searchpublication'];
													
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
													$sql = "SELECT * FROM file_server where publisher_meta LIKE '%".$_SESSION['searchpublication']."%' ORDER BY id DESC LIMIT {$per_page} OFFSET {$offset} "; 
													$retval = mysql_query($sql, $conn);
													if (!$retval) {
														die('Could not get data: ' . mysql_error());
													}
													while ($row = mysql_fetch_array($retval, MYSQL_ASSOC)) 
													{
															$name = $row['publisher_name'];
															$code = SHA1($row['publish_id']);
															$code2 = MD5($row['publish_id']);
															$j="Abu_Mail_Server_Read_Publication.php?d=".$row['publish_id']."&h=".$code."&t=".$code2;
															
															$subj="";
															$subj = substr(htmlspecialchars_decode($row['publisher_description']),0,300);
															$subj = $subj."...";
															
															$date500 = new DateTime($row['publisher_date']);
															$J = date_format($date500,"D");
															$Q = date_format($date500,"d-F-Y");
															$date = $J.", ".$Q;
						
															$search = '<div class="col-xs-12">
																<div class="col-xs-9"><a href='.$j.'>'.
																	$row['publisher_title'].
																'</a></div>
																<div class="col-xs-3">'.
																	$date.'
																</div>
															</div>
															<div class="col-xs-12"><div class="col-xs-12">'.
																$subj.'
															</div></div>
															<div class="col-xs-12">
																<div class="col-xs-5">'.
																	$row['publisher_name'].'
																</div>
																<div class="col-xs-4">'.
																	$row['publisher_department'].'
																</div>
																<div class="col-xs-3">'.
																	$row['publisher_mail'].'
																</div>
															</div>';
															
										
														$mail_subject = "<a href=".$j.">".$subj."</a>";
														echo '<tr>';
														echo '<td >' . $search. '</td>';
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
														echo ' <li><a href=Abu_Mail_Server_Search_Publication.php?page='.$previous_page.$searchlink.'>&laquo; </a> </li>';
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
																echo ' <li><a href=Abu_Mail_Server_Search_Publication.php?page='.$i.$searchlink.'> '. $i .' </a></li>';
															 }
														 }
														//this is for next record		
														if ($has_next_page)
														{
															echo ' <li><a href=Abu_Mail_Server_Search_Publication.php?page='.$next_page.$searchlink.'>&raquo;</a></li> ';
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
		<script type='text/javascript'>
											//this is to show the header..
											ShowAttachmentsTable();
										</script>								
</body>
</html>  
