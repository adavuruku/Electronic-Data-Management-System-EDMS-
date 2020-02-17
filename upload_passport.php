<?php
session_start(); 
require_once 'settings/connection.php';
$global="";
$passport_part = '<img src="abu-files/DefaultHead.jpg"  class="img-responsive"  ></img>';
$c = $dd = $school = $dept = $level = $matno=$email = $gender = $datereg = $studname =$newpassword = $oldpassword = $retype= $err = $paserr ="";

	$passport_part = $_SESSION['passport'];
	
	
	function update_database_record($moveto4,$imgData)
	{
		global $conn;
		
			$stmt = $conn->prepare("UPDATE student_information SET pic_extension=?,picture_file=? WHERE username=?");
			$stmt->execute(array($moveto4,$imgData,$_SESSION['username']));
			$affected_rows = $stmt->rowCount();
			
			$stmt = $conn->prepare("UPDATE staff_information SET pic_extension=?,picture_file=? WHERE username=?");
			$stmt->execute(array($moveto4,$imgData,$_SESSION['username']));
			$affected_rows = $stmt->rowCount();
	}
		
	
	function water_mark_image($moveto2,$moveto3,$imgData)
	{
		$watermark = imagecreatefrompng('abu_file/fpiputme.png');
		$watermark_widht = imagesx($watermark);
		$watermark_height =imagesy($watermark);
		$image =imagecreatetruecolor ($watermark_widht, $watermark_height);
		$image = imagecreatefromjpeg($moveto2);
		$image_size = getimagesize($moveto2);
		$x = $image_size[0] - $watermark_widht - 20;
		$y = $image_size[1] - $watermark_height - 20;
		imagecopymerge($image, $watermark, $x, $y, 0, 0, $watermark_widht, $watermark_height, 50);
		
		//this saves it to its destination folder
		imagejpeg ($image,$moveto2);
		
		update_database_record($moveto3,$imgData);
		//$passportpath = '<img src='.$moveto3.' class="img-responsive"  ></img>';
	}
	
	//process your image
	if(($_SERVER['REQUEST_METHOD'] == "POST") && !(empty($_FILES['photoimg']['name'])))
	{
				$userfolder = $_SESSION['username'];
				
                $FILE = addslashes($_FILES['photoimg']['name']);
                $path_parts = pathinfo($FILE);
                $ext= $path_parts['extension'];
                $image_size = getimagesize($_FILES['photoimg']['tmp_name']);
				
               //check if the user select something else apart from image
                   if ($image_size == false)
                    {
                        $paserr= "<p style='color:red'>Error: Please the selected file is not a Valid image</p>";
						$passport_part = $_SESSION['passport'];
						echo $paserr;
						echo $passport_part;
                    }
                    else
                    {           
                        //get the height and width of image
						 $image_size = getimagesize($_FILES['photoimg']['tmp_name']);
						$x = $image_size[0];
						$y = $image_size[1];
                        $size = $_FILES['photoimg']['size'];
                        $size2 = $size /1240;
						if (($x > 100050) || ($y > 100050))
						{
							$paserr= "<p style='color:red'>Error: Please check the File size height and breadth</p>";
								$passport_part = $_SESSION['passport'];
								echo $paserr;
								echo $passport_part;
						}
						else
						{
						
							 //this hold the files and its path
							 $tmpName  = $_FILES['photoimg']['tmp_name'];
							 //get the file in binary format
							$imgData =addslashes (file_get_contents($_FILES['photoimg']['tmp_name']));
							 
							 if ($ext =="jpg" && $size2 <=10000 )
							 {  
								$extension =".jpg";                            
								//this copy the file to a new folder which can be specify by you
								$newpath= $_SESSION['username'].$extension;
								$moveto= "abu_file/".$newpath;
								move_uploaded_file($tmpName,$moveto);
								$passport_part = '<img src='.$moveto.' class="img-responsive"  ></img>';
								
								
								
								if (file_exists("abu_file/".$_SESSION['username'].".jpeg"))
								 {
									unlink("abu_file/".$_SESSION['username'].".jpeg");
								 }
								water_mark_image($moveto,$extension,$imgData);
								$passport_part = '<img src='.$moveto.' class="img-responsive"  ></img>';
								echo $passport_part;
								//refresh the updated picture page....
								echo "<meta http-equiv='refresh' content='1' />";
							 }
							elseif ($ext =="jpeg" && $size2 <= 200)
							 {
								 $extension =".jpeg";                            
								//this copy the file to a new folder which can be specify by you
								$newpath= $_SESSION['username'].$extension;
								$moveto= "abu_file/".$newpath;
								move_uploaded_file($tmpName,$moveto);
								$passport_part = '<img src='.$moveto.' class="img-responsive"  ></img>';
								
								if (file_exists("abu_file/".$_SESSION['username'].".jpg"))
								 {
									unlink("abu_file/".$_SESSION['username'].".jpg");
								 }
								water_mark_image($moveto,$extension);
								$passport_part = '<img src='.$moveto.' class="img-responsive"  ></img>';
								$_SESSION['passport']= $passport_part;
								echo $passport_part;
								echo "<meta http-equiv='refresh' content='1' />";
							 }
							 else
							 {
								$paserr= "<p style='color:red'>Error: Please check the File size and file extension</p>";
								$passport_part = $_SESSION['passport'];
								echo $paserr;
								echo $passport_part;
								
							 }
						}
					}
			//$_SESSION['passport'] = $passport_part;	
		
	}
	else
	{
		$_SESSION['passport'] = $passport_part;
		echo $passport_part;
	}
	
	//$_SESSION['passport'] = $passport_part;
	
?>