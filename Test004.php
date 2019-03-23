<?php
/**
 * Created by PhpStorm.
 * User: davidkazad
 * Date: 23/11/2018
 * Time: 13:48
 */


use PHPMailer\PHPMailer\PHPMailer;

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

class Test
{
    function sendmail($subject, $body, $to, $alias = 'Maishapay Developer', $from = 'maishapay.online@gmail.com', $password = 'Landry@22')
    {
        $mail = new PHPMailer;
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        //$mail->Host = 'mail.maishapay.online';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->addReplyTo("contact@maishapay.online", "David Kazad");
        $mail->setFrom($from, $alias);
        $mail->addAddress($to, $alias);
        $mail->Subject = $subject;

        $mail->msgHTML($body);

        if (!$mail->send()) {

            return array('resultat' => 0, 'message' => $mail->ErrorInfo);

        } else {

            return array('resultat' => 1, 'message' => 'email sent!');

        }
    }
}

$result = new Test();

$rr = $result->sendmail('Test004','je test','14ka135@gmail.com');

echo json_encode($rr);

