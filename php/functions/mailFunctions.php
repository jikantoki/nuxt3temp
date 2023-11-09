<?php
//メール関係

/**
 * 環境変数
 */
require_once DIR_ROOT . '/env.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($to, $title, $message)
{
  $mail = new PHPMailer(true);
  $mail->CharSet = 'iso-2022-jp';
  $mail->Encoding = '7bit';
  try {
    //全メール共通設定
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = SMTP_Server;
    $mail->Username = SMTP_Username;
    $mail->Password = SMTP_Password;
    $mail->SMTPSecure = 'tls';
    $mail->Port = SMTP_Port;
    $mail->setFrom(SMTP_Mailaddress, mb_encode_mimeheader(SMTP_Name));
    $mail->isHTML(true);

    //メールによる設定
    $mail->addAddress($to);
    $mail->Subject = mb_encode_mimeheader($title);
    $mail->Body = mb_encode_mimeheader($message);

    //送信
    $mail->send();
  } catch (Exception $e) {
    echo $e;
  }
}
