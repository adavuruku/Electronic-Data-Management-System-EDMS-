<?php
require_once 'settings/connection.php';
//require_once 'FPI_REGISTRATION_BANKS/site_root_config.php';
//$root = my_site_root();
function zipFilesDownload($file_names,$archive_file_name,$file_path)
{
		$zip = new ZipArchive();
		if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) 
		{
		  exit("cannot open <$archive_file_name>\n");
		}
		foreach($file_names as $files)
		{
		  $zip->addFile($file_path.$files,$files);
		}
		download($archive_file_name);
	$zip->close();
}

$name="";
$dir2='Mail_Files';
	$stmt = $conn->prepare("SELECT * FROM all_mails WHERE attached_status=?");
	$stmt->execute(array('1'));
	//$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount () >= 1)
	{
		while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			//save record for all the selected HODS
			$b = $rows['mail_id'];
			if(isset($fileNames))
			{
				unset($fileNames);
			}
			$new_path="Mail_Files/".$b;
			if(is_dir($new_path) && $b!="." && $b!="..") 
			{
					
					$fileNames=array();
					$iteratepoint = dirname(__FILE__)."/Mail_Files/".$b;
					$dir = new DirectoryIterator($iteratepoint);
					foreach ($dir as $fileinfo) 
					{
						if (!$fileinfo->isDot()) 
						{
							$D=$b."/".$fileinfo->getFilename();
							array_push($fileNames,$D);
						}
					}
					
				$zip_file_name= $b.".zip";
				$file_path= dirname(__FILE__).'/Mail_Files/';
				
				$zip = new ZipArchive();
				if ($zip->open($zip_file_name, ZIPARCHIVE::CREATE )!==TRUE) 
				{
				  exit("cannot open <$zip_file_name>\n");
				}
				foreach($fileNames as $files)
				{
				  $zip->addFile($file_path.$files,$files);
				}
				//download($zip_file_name);
				//header("location: ".$zip_file_name);
				$zip->close();
				
				$name="Mail_Files/All_Student_Uploaded_Files_.zip";
				$zip = new ZipArchive();
				if ($zip->open($name, ZIPARCHIVE::CREATE )!==TRUE) 
				{
				  exit("cannot open <$name>\n");
				}
				$zip->addFile($zip_file_name,$zip_file_name);
				$zip->close();
				unlink($zip_file_name);
			}
		}
		header("location: ".$name);
	}
	
	
	//so that the file will not remain in the server after downloading it just delete the zip file
?>