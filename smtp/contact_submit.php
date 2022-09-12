<?php

$_POST["first_name"]="Srikanth";
$_POST["last_name"]="Aare";
$_POST["email"]="aaresrikanth@gmail.com";
$_POST["phone"]="9848148005";
$_POST["message"]="PHPMailer Testing";

$message = '';
		
$message = '
	<h3 align="center">New Enquiry</h3>
	<table border="1" width="100%" cellpadding="5" cellspacing="5">
		<tr>
			<td width="30%">Name</td>
			<td width="70%">'.$_POST["first_name"]." ".$_POST["last_name"].'</td>
		</tr>
		<tr>
			<td width="30%">Email Address</td>
			<td width="70%">'.$_POST["email"].'</td>
		</tr>
	
		<tr>
			<td width="30%">Mobile Number</td>
			<td width="70%">'.$_POST["phone"].'</td>
		</tr>
		<tr>
			<td width="30%">Message</td>
			<td width="70%">'.$_POST["message"].'</td>
		</tr>
	</table>
';

require 'class/class.phpmailer.php';
			
		
			
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Port=587; 
$mail->SMTPSecure='ssl';



//	===================================================================
//	JUST EDIT BELOW FIVE LINES
//	===================================================================

$mail->Host = "mail.ship2uae.com";					// Enter "mail.my-domain.com"
$mail->Username = "info@ship2uae.com";			// Enter an email address created through cPanel
$mail->Password = "Iccs*1234";	
$mail->AddAddress("srikanth.aare@iblesoft.com" , "IBLESOFT");
        
            

// Enter the email password created through cPanel

/*$mail->AddAddress("christiandwhipple@outlook.com","Auratech");*/ 
// Enter the recipient "to" email address

$mail->Subject = "User Contact Details";	//or subject "Any Preferred Email Subject";
	
//	===================================================================
//  DO NOT EDIT BELOW THIS ~~ MODIFY AT YOUR OWN RISK & DO NOT CONTACT SUPPORT
//  IF YOU NEED HELP, GOOGLE AND LEARN ABOUT PHPMAILER OR CONTACT A PROGRAMMER
//	===================================================================

$mail->From = $_POST['email'];
$mail->FromName = $_POST['first_name']." ".$_POST['last_name'];
$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Body = $message;
$mail->AltBody ="Name    : {$_POST['first_name']}{$_POST['last_name']}\n\nEmail   : {$_POST['email']}\n\nMessage : {$message}";
$mail->SMTPDebug  = 1;	
$mail->Send();

if(!$mail->Send()) {
	echo 'Message was not sent.';
	echo 'Mailer error: ' . $mail->ErrorInfo;
	} else {
	echo 'Message has been sent.';
	}

if(isset($_POST['email'])){
$mail->ClearAddresses();  // each AddAddress add to list
//$mail->ClearCCs();

$mail->From = 'srikanth.aare@zee.esselgroup.com';
$mail->FromName = 'ZEEL';
$mail->AddAddress($_POST['email']);
$mail->Body = "Dear ".$_POST['first_name']." ".$_POST['last_name'].",
We appreciate your inquiry.
Thank You. We will get back to you soon.";
//$mail->Send();

}			
?>