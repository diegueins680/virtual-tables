<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require ($configFile);

$inputFileName = 'reqs.xls';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
$requirementsArray = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$ftp_server = '74.208.56.137';
$conn_id = ftp_connect($ftp_server);
$ftp_user_name = 'u47181676-user1';
$ftp_user_pass = 'ACGftpATT!!2';
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

$files = [];

$txtArr = ['.txt', '.TXT'];
$pgpArr = ['.pgp', '.PGP', '.gpg', '.GPG'];
$path = ".";
$contents_on_server = ftp_nlist($conn_id, $path);
foreach($requirementsArray as $index => $requirement)
{
	if($index > 1)
	{
		$file = $requirement['M'];
		if(!stripos(strtoupper($file), 'TXT.PGP'))
		{
			foreach($txtArr as $txt)
			{
				foreach($pgpArr as $pgp)
				{
					$check_file_exist = $file.$txt.$pgp;
					if (in_array($check_file_exist, $contents_on_server))
					{
						echo "<br>";
						echo "I found ".$check_file_exist." in directory : ".$path;
						var_dump($check_file_exist);
						$objPHPExcel->setActiveSheetIndex(1)->setCellValue('M'.$index, $check_file_exist);
						$objWriter->save('reqs.xls');
					}
					else
					{
						echo "<br>";
						echo $check_file_exist." not found in directory : ".$path;
					};

				}
			}
		}
		if(!in_array($file, $files))
		{
			$files[] = $file;
		}
	}
}

$folder = "C:/Documents and Settings/diego.saa/workspace/acg/_work/ll/files";

foreach($files as $file)
{
	$local_file = strip_tags($folder."/".$file);
	if (ftp_get($conn_id, $local_file, $file, FTP_BINARY))
	{
		echo "Successfully written to $local_file\n";
	}
	else
	{
		echo "There was a problem\n";
	}
}
ftp_close($conn_id);
?>