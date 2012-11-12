<?php
// set up basic connection
$ftp_server = '74.208.56.137';
$conn_id = ftp_connect($ftp_server);

$folder = 'ACQ';

// login with username and password
$ftp_user_name = 'u47181676-user1';
$ftp_user_pass = 'ACGftpATT!!2';
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// check connection
if ((!$conn_id) || (!$login_result)) {
	echo "FTP connection has failed!";
	echo "Attempted to connect to $ftp_server for user $ftp_user_name";
	exit;
} else {
	echo "Connected to $ftp_server, for user $ftp_user_name";
}

$files = array
(
		"157407_SepACG_SE_AT&T_Proprietary_(Restricted)_authorized_individuals_Only.txt.pgp"
);

foreach($files as $file)
{
	
	$server_file = $file;
	$local_file = strip_tags("J:/SAA/Lead lists/Encrypted/MW/".$folder."/".$server_file);

	// try to download $server_file and save to $local_file
	if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		echo "Successfully written to $local_file\n";
	} else {
		echo "There was a problem\n";
	}
}
// close the connection
ftp_close($conn_id);

//die;
// upload the file
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY);

// check upload status
if (!$upload) {
	echo "FTP upload has failed!";
} else {
	echo "Uploaded $source_file to $ftp_server as $destination_file";
}

// close the FTP stream
ftp_close($conn_id);
?>