<?php
require 'phpmail/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = '*';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = '*';                 // SMTP username
$mail->Password = '*';                           // SMTP password
$mail->SMTPSecure = '*';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->setFrom($_POST['email'], $_POST['name']);
$mail->addAddress('*');
$mail->addReplyTo($_POST['email'], $_POST['name']);
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $_POST['subject'];
$mail->Body    = $_POST['message'];
$mail->AltBody = $_POST['message'];
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
