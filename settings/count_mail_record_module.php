<?php
require_once 'connection.php';

//check all inbox
function count_inbox()
{
	global $conn;
	$result = 0;
	$receiver_name=$receiver_username=$receiver_dept=$date="";
	$stmt = $conn->prepare("SELECT * FROM all_mails WHERE receiver_address=?  AND box_type=? AND receive_delete =?");
	$stmt->execute(array($_SESSION['username'],'In',""));
	//$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount () >= 1)
	{
		while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$result = $result + 1;
		}
	}
	return $result;
}
//check all out boxr
function count_outbox()
{
	global $conn;
	$result1 = 0;
	$receiver_name=$receiver_username=$receiver_dept=$date="";
	$stmt = $conn->prepare("SELECT * FROM all_mails WHERE sender_address=?  AND box_type=? AND sent_delete =?");
	$stmt->execute(array($_SESSION['username'],'Out',""));
	//$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount () >= 1)
	{
		while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$result1 = $result1 + 1;
		}
	}
	return $result1;
}

//check all draft
function count_draft()
{
	global $conn;
	$result2 = 0;
	$receiver_name=$receiver_username=$receiver_dept=$date="";
	$stmt = $conn->prepare("SELECT * FROM draft_mail WHERE username=?  AND delete_status=?");
	$stmt->execute(array($_SESSION['username'],''));
	//$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount () >= 1)
	{
		while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$result2 = $result2 + 1;
		}
	}
	return $result2;
}
//check all user publication
function count_publication()
{
	global $conn;
	$result3 = 0;
	$receiver_name=$receiver_username=$receiver_dept=$date="";
	$stmt = $conn->prepare("SELECT * FROM file_server WHERE publisher_mail=?");
	$stmt->execute(array($_SESSION['username']));
	//$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount () >= 1)
	{
		while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$result3 = $result3 + 1;
		}
	}
	return $result3;
}
//check all unread inbox
function count_read_inbox()
{
	global $conn;
	$result4 = 0;
	$receiver_name=$receiver_username=$receiver_dept=$date="";
	$stmt = $conn->prepare("SELECT * FROM all_mails WHERE receiver_address=?  AND box_type=? AND read_status=? AND receive_delete =?");
	$stmt->execute(array($_SESSION['username'],'In','1',""));
	//$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount () >= 1)
	{
		while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$result4 = $result4 + 1;
		}
	}
	return $result4;
}
function verify_exist($value)
{
	global $conn;
	$stmt = $conn->prepare("SELECT * FROM student_information WHERE username=?  AND email_activate=?");	
	$stmt->execute(array($value,'1'));
	if ($stmt->rowCount () >= 1)
	{
		return "student";
	}
	else
	{
		$stmt = $conn->prepare("SELECT * FROM staff_information WHERE username=?  AND email_activate=?");	
		$stmt->execute(array($value,'1'));
			if ($stmt->rowCount () >= 1)
		{
			return "staff";
		}
		else
		{
			return FALSE;
		}
	}
}
?>