<?php
session_start();
require_once 'settings/connection.php';
require_once 'settings/filter.php';

if (isset($_GET['email']))
{
			$d = checkempty($_GET['email']);
			//$p = filterEmail($_GET['email']);
			if($d != FALSE) 
			{
				$d = $_GET['email'];
				$f = strip_tags($d);
				$query2 = "SELECT student_id FROM student_information WHERE student_id =:student_id";
				$stmt2 = $conn->prepare($query2);
				$stmt2->bindValue(':student_id',$f, PDO::PARAM_STR);
				$stmt2->execute();
				$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
				$row_count2 = $stmt2->rowCount();
				if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
				{
					echo "<p style='color:red;'>The student_registration_No is already used by another Student or Not Valid<p>";
				}
				else
				{
					echo "";
				}
			}
			else
			{
				echo "<p style='color:red;'>The student_registration no box is empty<p>";
			}
}


if (isset($_GET['reg']))
{
			$d = checkempty($_GET['reg']);
			if($d != FALSE)
			{
				$d = $_GET['reg'];
				$f = strip_tags($d);
				$query2 = "SELECT staff_id FROM staff_information WHERE staff_id =:staff_id";
				$stmt2 = $conn->prepare($query2);
				$stmt2->bindValue(':staff_id',$f, PDO::PARAM_STR);
				$stmt2->execute();
				$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
				$row_count2 = $stmt2->rowCount();
				if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
				{
					echo "<p style='color:red;'>The Staff_ID is already used by another Staff or Not Valid<p>";
				}
				else
				{
					echo "";
				}
			}
			else
			{
				echo "<p style='color:red;'>The Staff_ID is empty invalid<p>";
			}
}

//if username is existing at both staffs and student .....for validate login page
if (isset($_GET['changeemail']))
{
			$d = checkempty($_GET['changeemail']);
			$p = filterEmail($_GET['changeemail']);
			//$pword=checkempty($_GET['oldpasword'])
			//if(($d != FALSE)&& ($p != FALSE) && ($pword != FALSE))
			if(($d != FALSE)&& ($p != FALSE))
			{
				//$pword=$_GET['oldpasword'];
				$d = $_GET['changeemail'];
				$f = strip_tags($d);
				$pword="1";
				//$pword=strip_tags($_GET['oldpasword']);
				$query2 = "SELECT username,change_password FROM student_information WHERE username =:username AND change_password=:change_password";
				$stmt2 = $conn->prepare($query2);
				$stmt2->bindValue(':username',$f, PDO::PARAM_STR);
				$stmt2->bindValue(':change_password',$pword, PDO::PARAM_STR);
				$stmt2->execute();
				$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
				$row_count2 = $stmt2->rowCount();
				if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
				{
					echo "<p style='color:red;'>The Username is already used by another Abu Mail Server User or is  Not Valid<p>";
				}
				else
				{
					//loook for it in staff records tooo echo "";
					$query2 = "SELECT username,change_password FROM staff_information WHERE username =:username  AND change_password=:change_password";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':username',$f, PDO::PARAM_STR);
					$stmt2->bindValue(':change_password',$pword, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
					{
						echo "<p style='color:red;'>The Username is already used by another Abu Mail Server User or is  Not Valid<p>";
					}
					else
					{
						echo "";
					}
				}
			}
			else
			{
				echo "<p style='color:red;'>The Username box is empty<p>";
			}
}


/*this will make sure no one is using thesam external email address - because this emaill adress will be use later to 
communicate with ABU MAIL SERVER USER
 tins like = password retrieval
 */
 //if username is existing at both staffs and student .....for validate login page
if (isset($_GET['checkemail']))
{
			$d = checkempty($_GET['checkemail']);
			$p = filterEmail($_GET['checkemail']);
			if(($d != FALSE) && ($p != FALSE))
			{
				//$pword=$_GET['oldpasword'];
				$d = $_GET['checkemail'];
				$f = strip_tags($d);
				$query2 = "SELECT email FROM student_information WHERE email =:email";
				$stmt2 = $conn->prepare($query2);
				$stmt2->bindValue(':email',$f, PDO::PARAM_STR);
				$stmt2->execute();
				$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
				$row_count2 = $stmt2->rowCount();
				if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
				{
					echo "<p style='color:red;'>The Email Address is already used by another Abu Mail Server User to Activate Their Account<p>";
				}
				else
				{
					//loook for it in staff records tooo echo "";
					$query2 = "SELECT email FROM staff_information WHERE email =:email";
					$stmt2 = $conn->prepare($query2);
					$stmt2->bindValue(':email',$f, PDO::PARAM_STR);
					$stmt2->execute();
					$rows3 = $stmt2->fetch(PDO::FETCH_ASSOC);
					$row_count2 = $stmt2->rowCount();
					if($row_count2 == 1)  	//if ($rows2['log_block'] == "1")
					{
						echo "<p style='color:red;'>The Email Address is already used by another Abu Mail Server User to Activate Their Account<p>";
					}
					else
					{
						echo "";
					}
				}
			}
			else
			{
				echo "<p style='color:red;'>The Email Address box is empty<p>";
			}
}
?>