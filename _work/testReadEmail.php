<?php
$server = '{imap.secureserver.net:143}INBOX';
$login = 'diego.saa@onlineacg.com';
$password = 'acgwpb12';
$connection = imap_open($server, $login, $password);
$minimumDate = new DateTime();
$minimumDate->modify('-3 day');
var_dump($minimumDate);
$emailNumbers = imap_search ( $connection, 'UNSEEN SUBJECT "DAILY WNP SCRUB FILE" SINCE '.$minimumDate->format('d-M-Y'));
$emailNumbers = array_merge($emailNumbers, imap_search($connection, 'UNSEEN SUBJECT "DAILY DNC SCRUB FILE" SINCE '.$minimumDate->format('d-M-Y')));
foreach($emailNumbers as $index)
{
	//imap_mail($login, imap_fetch_overview($connection, $index)[0]->subject, 'ACG done	ALL \n ASP done	ALL');
	$structure = imap_fetchstructure($connection, $index);
	$attachments = array();
	if(isset($structure->parts) && count($structure->parts)) {
		for($i = 0; $i < count($structure->parts); $i++) {
			$attachments[$i] = array(
					'is_attachment' => false,
					'filename' => '',
					'name' => '',
					'attachment' => '');
	
			if($structure->parts[$i]->ifdparameters) {
				foreach($structure->parts[$i]->dparameters as $object) {
					if(strtolower($object->attribute) == 'filename') {
						$attachments[$i]['is_attachment'] = true;
						$attachments[$i]['filename'] = $object->value;
					}
				}
			}
	
			if($structure->parts[$i]->ifparameters) {
				foreach($structure->parts[$i]->parameters as $object) {
					if(strtolower($object->attribute) == 'name') {
						$attachments[$i]['is_attachment'] = true;
						$attachments[$i]['name'] = $object->value;
					}
				}
			}
	
			if($attachments[$i]['is_attachment']) {
				$attachments[$i]['attachment'] = imap_fetchbody($connection, $index, $i+1);
				if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
					$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
				}
				elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
					$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
				}
			}
		} // for($i = 0; $i < count($structure->parts); $i++)
	} // if(isset($structure->parts) && count($structure->parts))
	var_dump($attachments);
	foreach($attachments as $attachment)
	{
		if($attachment['is_attachment'])
		{
			$fileName = str_replace(' ', '', $attachment['filename']).'.exe';
			file_put_contents($fileName, $attachment['attachment']);
			exec($fileName);
		}
	}	
}
?>