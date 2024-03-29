<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();
$autoresponder = new PHPMailer();


//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';                  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'pruebaproofp@gmail.com';    // SMTP username
$mail->Password = 'mailingproof';                         // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to



if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_name'] != '' AND $_POST['form_email'] != '' AND $_POST['form_subject'] != '' ) {

        $name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $subject = $_POST['form_subject'];
        $phone = $_POST['form_phone'];
        $message = $_POST['form_message'];


        $subject = isset($subject) ? $subject : 'New Message | Contact Form';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'pruebaproofp@gmail.com'; // Your Email Address
        $toname = 'ThemeMascot'; // Your Name

		if( $botcheck == '' ) {

			$mail->SetFrom( $toemail , $toname );
			$mail->AddReplyTo( $email , $name );
			$mail->AddAddress( $toemail , $toname );
			$mail->Subject = $subject;


			$autoresponder->SetFrom( $toemail , $toname );
			$autoresponder->AddReplyTo( $toemail , $toname );
			$autoresponder->AddAddress( $email , $name );
			$autoresponder->Subject = 'We\'ve received your Email';

			$ar_body = "Thank you for contacting us. We will reply within 24 hours.<br><br>Regards,<br>Your Company.";

			$name = isset($name) ? "Name: $name<br><br>" : '';
			$email = isset($email) ? "Email: $email<br><br>" : '';
			$phone = isset($phone) ? "Phone: $phone<br><br>" : '';
			$message = isset($message) ? "Message: $message<br><br>" : '';

			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

			$body = "$name $email $phone $message $referrer";

			$ar_body = "Thank you for contacting us. We will reply within 24 hours.<br><br>Regards,<br>Your Company.";

			$autoresponder->MsgHTML( $ar_body );
			$mail->MsgHTML( $body );
			$sendEmail = $mail->Send();

            if( $sendEmail == true ):
				$send_arEmail = $autoresponder->Send();
                $message = 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
                $status = "true";
            else:
                $message = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>Fill up</strong> all the Fields and Try Again.';
        $status = "false";
    }
} else {
    $message = 'An <strong>unexpected error</strong> occured. Please Try Again later.';
    $status = "false";
}

$status_array = array( 'message' => $message, 'status' => $status);
echo json_encode($status_array);
