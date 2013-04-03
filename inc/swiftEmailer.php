<?php 
//error_reporting(E_ALL ^ E_NOTICE);
function sendMail($to, $from, $subject, $text) 
{
						
	include_once "Swift-4.3.0/lib/swift_required.php";
	
	
	$transport = Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587);
	$transport->setUsername('propplus');
	$transport->setPassword('afb90c37-5253-4820-b694-b49b652f4607');
	$swift = Swift_Mailer::newInstance($transport);
	$message = new Swift_Message($subject);
	$message->setFrom($from);
	$message->setTo($to);
	/*$message->setBody($html, 'text/html');
	$message->addPart($text, 'text/plain');*/
	$message->setBody($text, 'text/html');
	
	if ($recipients = $swift->send($message, $failures))
	{
	 //echo 'Message successfully sent!';
	 return '1';
	} else {
	 //echo "There was an error:\n";
	 return '0';
	 //print_r($failures);
	}
	
}
?>