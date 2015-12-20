<?php

function initiate_email(){
	global $mail;
	
	require_once(ABSPATH.'lib/phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->CharSet = "UTF-8";
	$mail->isSMTP();
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	if(DEBUG)
		$mail->SMTPDebug = 2;
	else
		$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = SMTP_HOST;
	$mail->Port = 25;
	$mail->SMTPSecure = '';
	$mail->SMTPAutoTLS = false;
	$mail->SMTPAuth = true;
	$mail->Username = SMTP_USER;
	$mail->Password = SMTP_PASS;
	$mail->setFrom(SMTP_EMAIL, 'Περιφέρεια Δυτικής Μακεδονίας');
	
}

function email_send($address, $receiver, $subject, $body, $attachment = '', $altbody = ''){
	global $mail;
	
	$mail->addAddress($address, $receiver);
	$mail->Subject = $subject;
	$mail->msgHTML($body);
	
	if($altbody !='')
		$mail->AltBody = $altbody;
	
	if($attachment !='')
		$mail->addAttachment($attachment);
	
	if (!$mail->send()) {
		$message_list[] = array( 'type' => 'danger', 'message'	=> 'Σφάλμα! To email δεν απεστάλη..' );
		//echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		//echo "Message sent!";
	}
}
