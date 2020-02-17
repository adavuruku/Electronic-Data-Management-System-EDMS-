<?php
//works with remove_existing_files.js and create_normal_mail.php
session_start();
require_once 'settings/connection.php';
if(isset($_POST['submit']) && isset($_POST['file']))
{
	$file_path = $_POST['file'];
	$path_to_remove = "Mail_Files/".$_SESSION['attachment_folder']."/".$file_path;
	unlink($path_to_remove);
	//
	
	$attached_file = '<table class="table table-condensed" style="background-color:#FFFFFF;">
						<tbody>';
						$old_folder = "Mail_files/".$_SESSION['attachment_folder'];
						if(is_dir($old_folder)) 
						{
							$a = scandir($old_folder);
							$countval=count($a);
							if($countval > 2)
							{
								for($x=0; $x < $countval; $x++)
								{
									//echo $a[$x].'<BR>';
									$b=$a[$x];
									if( $b!="." && $b!="..") 
									{
										//$link = "Create_Normal_Mail.php?myfile=".$attachment_folder."/".$b."&filename=".$b."&d=".$d."&h=".$h."&t=".$t;
										$attached_file=$attached_file.'<tr><td><img src='."phpuploader/resources/circle.png".' border="0"/><td>
										'.$b.'</td><td><img src='."phpuploader/resources/uploadok.png".' border="0"/></td>
										<td>[<a href="javascript:void(0)" onclick="submit_nd_Admission(\''.$b.'\')" >Remove <img src="phpuploader/resources/stop.png" border="0"/></a>]</td></tr>';
									}
								}
								$attached_file=$attached_file.'</tbody></table>';
							}
							else
							{
								$attached_file="";
							}
						}
						else
							{
								$attached_file="";
							}
	echo $attached_file;
	//
}

//load user to click on
if(isset($_POST['submit4']) && isset($_POST['search']))
{
	$searchdata2 = trim($_POST['search']);
	if ($searchdata2!=""){
		$attached_file1="";
		$address="";
		$my_address = $_SESSION['username'];
		$message_type= "In";
		/*$stmt = $conn->prepare("SELECT * FROM all_mails WHERE ((sender_name LIKE ? OR sender_address LIKE ? OR receiver_address LIKE ? OR receiver_name LIKE ?) 
		AND ((sender_address LIKE ? OR receiver_address LIKE ? ) AND(box_type = ?))) ORDER BY id Desc");*/
		$stmt = $conn->prepare("SELECT * FROM all_mails WHERE ((receiver_address LIKE ? OR receiver_name LIKE ?) 
		AND ((sender_address LIKE ?) AND(box_type = ?))) ORDER BY id Desc");
		//$stmt = $conn->prepare("SELECT * FROM all_mails WHERE (sender_name LIKE ? OR sender_address LIKE ? OR receiver_address LIKE ?) ORDER BY id Desc");
								//$stmt->execute(array("%$searchdata2%","%$searchdata2%","%$searchdata2%","%$searchdata2%","%$my_address%","%$my_address%",$message_type));
								$stmt->execute(array("%$searchdata2%","%$searchdata2%","%$my_address%",$message_type));
								$rows = $stmt->fetch(PDO::FETCH_ASSOC);
								if ($stmt->rowCount () >= 1)
								{
									$attached_file1 = '<div style="overflow:Auto;height:100px;z-index:9999;border:2px solid">
											
										<table class="table table-condensed" style="background-color:#FFFFFF;">
										<tbody>';
										$existing_user_name = array();
										$address="";
									while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
									{
										if($row['sender_address']==$_SESSION['username'])
										{
											$address = $row['receiver_address'];
											$name = $row['receiver_name']."<br/>".$address;
										}
										if($row['receiver_address']==$_SESSION['username'])
										{
											$address = $row['sender_address'];
											$name = $row['sender_name']."<br/>".$address;
										}
										/*if ($existing_user_name == $address)
										{
											continue;
										}*/
										if (in_array($address, $existing_user_name))
										  {
											//stop d display of duplicate user
											continue;
										  }
										$pix_ext = go_get_picture_status($address);
										if($pix_ext =="")
										{
											$attachment = "<img src="."abu_file/default.jpg"." style='height:70px;width:70px'>";
										}
										else
										{
											$t = $address.$pix_ext;
											$attachment = "<img src="."abu_file/".$t." style='height:70px;width:70px'></a>";
										}
										
										
										
										$attached_file1=$attached_file1.'<tr onclick="add_to_list(\''.$address.'\')" >
										<td>' . $attachment. '</td>
										<td>' . $name. '</td></tr>';
										//$existing_user_name = $address;
										array_push($existing_user_name,$address);
									}
									$attached_file1=$attached_file1.'</tbody></table></div>';
									
								}
								else
								{
									$attached_file1="";
								}
			}
			else
{
$attached_file1="";
}			
		echo $attached_file1;
		
}
function go_get_picture_status($search_data){
global $conn;
$p = "";
$query2 = "SELECT pic_extension,username FROM student_information where username =:username";
	$stmt2 = $conn->prepare($query2);
	$stmt2->bindValue(':username',$search_data, PDO::PARAM_STR);
	$stmt2->execute();
	$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
	$row_count2 = $stmt2->rowCount();
	if($row_count2 >= 1) 
	{
	
		if($rows3['pic_extension']!="")
		{
			return $rows3['pic_extension'];
		}
	}
	else
	{
		$query2 = "SELECT pic_extension,username FROM staff_information where username =:username";
		$stmt2 = $conn->prepare($query2);
		$stmt2->bindValue(':username',$search_data, PDO::PARAM_STR);
		$stmt2->execute();
		$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$row_count2 = $stmt2->rowCount();
		if($row_count2 >= 1) 
		{
			if($rows3['pic_extension']!="")
			{
				return $rows3['pic_extension'];
			}
			else
			{
				return $p;
			}
		}
	}

}
?>