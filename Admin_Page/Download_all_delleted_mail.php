<?php
/*******EDIT LINES 3-8*******/
$DB_Server = "localhost"; //MySQL Server    
$DB_Username = "root"; //MySQL Username     
$DB_Password = "";             //MySQL Password     
$DB_DBName = "abu_server";         //MySQL Database Name  
$DB_TBLName = "all_mails"; //MySQL Table Name   
$filename = "All_ABU_Mail_Server_All_Inbox_Records";//File Name
$HostelName = "ALL INBOX MAILS";  
/*******YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE*******/    
//create MySQL connection 
$f="1";
$sql = "Select id,mail_id,sender_name,sender_dept,sender_address,receiver_name,receiver_dept,receiver_address,message_type,subject,date_sent,box_type,attached_status,sent_delete,receive_delete from $DB_TBLName where sent_delete ='".$f."' or receive_delete ='".$f."' ORDER BY ID DESC";

$Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
//select database   
$Db = @mysql_select_db($DB_DBName, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());   
//execute query 
$result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
$file_ending = "xls";
//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
echo ($HostelName) . "\t";
print("\n"); 
//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
echo mysql_field_name($result,$i) . "\t";
}
print("\n");    
//end of printing column names  
//start while loop to get data
    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
			$p = htmlspecialchars_decode($row[$j]);
            if(!isset($p))
                $schema_insert .= "NULL".$sep;
            elseif ($p != "")
                $schema_insert .= "$p".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", htmlspecialchars_decode($schema_insert));
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", htmlspecialchars_decode($schema_insert));
        $schema_insert .= "\t";
        print(trim(htmlspecialchars_decode($schema_insert)));
        print "\n";
    }
?>	