<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\wamp64\www\prueba_mail/Exception.php';
require 'C:\wamp64\www\prueba_mail/PHPMailer.php';
require 'C:\wamp64\www\prueba_mail/SMTP.php';


require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

//$mail = new PHPMailer();
$mail->SMTPDebug = 0;                               // Enable verbose debug output
$mail->isSMTP();
$mail->From = 'pruebaproofp@gmail.com';                                     // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'pruebaproofp@gmail.com';                 // SMTP usernamethemeforest@ismail-hossain.me'
$mail->Password = 'mailingproof';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                // TCP port to connect to

$message = "";
$status = "false";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['form_email'] != '') {

        $name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $phone = $_POST['form_phone'];
        $service = $_POST['form_service'];
        $date = $_POST['form_date'];
        $time = $_POST['form_time'];
        $message = $_POST['form_message'];
        $botcheck = $_POST['form_botcheck'];

        $subject = isset($subject) ? $subject : 'New Message | Appointment Form';


        $toemail = 'pruebaproofp@gmail.com'; // Your Email Address 'spam.thememascot@gmail.com';
        $toname = 'Informes'; // Your Name 'ThemeMascot';

        if ($botcheck == '') {

            $mail->SetFrom($toemail, $toname);
            $mail->AddReplyTo($email, $name);
            $mail->AddAddress($toemail, $toname);
            $mail->Subject = $subject;

            $name = isset($name) ? "Name: $name<br><br>" : '';
            $email = isset($email) ? "Email: $email<br><br>" : '';
            $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
            $service = isset($service) ? "Service: $service<br><br>" : '';
            $date = isset($date) ? "Appoinment Date: $date<br><br>" : '';
            $time = isset($time) ? "Appoinment Time: $time<br><br>" : '';
            $message = isset($message) ? "Message: $message<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$name $email $phone $service $date $time $message $referrer";

            $mail->MsgHTML($body);
            $sendEmail = $mail->Send();

            if ($sendEmail == true) :
                $message = 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
                $status = "true";
            else :
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

$status_array = array('message' => $message, 'status' => $status);
echo json_encode($status_array);
