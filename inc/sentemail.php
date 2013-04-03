<?php 
error_reporting(E_ALL ^ E_NOTICE);
function sendMail($to_address, $email_subject, $email_text, $email_type,$from_address, $attachment_file, $attachment_type) 
				{
					$header="";
					$header = "From: $from_address \n ";
				/*    $header = "From: $from_address \n Reply-To: $from_address";
				//$header = " Reply-To: $from_address\n";*/
					$header .= " \nMIME-Version: 1.0\n";
					$headers .= "X-Priority: 1\n"; // Urgent message!
				if($attachment_file!="")
				{
					$attachment_type=TypeOfAttachment($attachment_type);
					
					$content = fread(fopen($attachment_file,"r"),filesize($attachment_file));
					$content = chunk_split(base64_encode($content));
					$uid = strtoupper(md5(uniqid(time())));
					$name = basename($attachment_file);
					$header .= "Content-Type: multipart/mixed; boundary=$uid\n";
					$header .= "--$uid\n";
				}
				if($email_type==0)
			   {
					$header .= "Content-Type: text/plain\n";
					$header .= "Content-Transfer-Encoding: 8bit\n\n";
					$header .= strip_tags($email_text)."\n";
				}
				else 
				{
					$header .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type
					$header .= "Content-Transfer-Encoding: 8bit\n\n";
					$header .= "$email_text\n";
			//	 	echo "<br>TYPE : $email_type ";
				}
				  if($uid!="") $header .= "--$uid\n";
				   
				if($attachment_file!="")
				{
					$header .= "Content-Type: $attachment_type; name=\"$name\"\n";
					$header .= "Content-Transfer-Encoding: base64\n";
					$header .= "Content-Disposition: attachment; filename=\"$name\"\n\n";
					$header .= "$content\n";
			//		echo "Att: $attachment_type";
				}
			   if(mail($to_address, $email_subject, "", $header)) return true;
			   else return false;
			}
?>