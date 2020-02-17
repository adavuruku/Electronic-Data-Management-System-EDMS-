<?php
session_start(); 
function zipFilesDownload($file_names,$archive_file_name,$file_path){
$zip = new ZipArchive();
if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
  exit("cannot open <$archive_file_name>\n");

}
foreach($file_names as $files){
  $zip->addFile($files,$files);
}
$zip->close();


header("Content-type: application/zip"); 
header("Content-Disposition: attachment; filename=$archive_file_name"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
readfile("$archive_file_name");
//so that the file will not remain in the server after downloading it just delete the zip file
unlink($archive_file_name);
exit;
}
			$fileNames=array();
			$iteratepoint = $_SESSION['file_folder'];
			//$iteratepoint ="1000008u1234567M8";
			$dir = new DirectoryIterator($iteratepoint);
			foreach ($dir as $fileinfo) 
			{
				if (!$fileinfo->isDot()) 
				{
					$D=$iteratepoint."/".$fileinfo->getFilename();
					array_push($fileNames,$D);
				}
			}
			$date500 = new DateTime("Now");
			$J = date_format($date500,"D");
			$Q = date_format($date500,"d-F-Y");
			$dateprint = "ABU_Mail_".$Q;
	$zip_file_name=$dateprint.'.zip';
	$file_path = $_SESSION['file_folder'].'/';

	zipFilesDownload($fileNames,$zip_file_name,$file_path);
?>