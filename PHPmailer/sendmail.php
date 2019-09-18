<?php
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSendmail();
$mail->setLanguage('fa', 'language/');
$mail->CharSet = 'UTF-8';
$mail->setFrom('web@ae.sharif.edu', 'Aero');
$mail->addAddress('nobahari@sharif.edu', 'Dr. Nobahari');
$mail->Subject = 'تست زبان فارسی در سیستم جدید ایمیل';
$mail->Body = 'این ایمیل به عنوان تست برای شما ارسال شده است.';
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
