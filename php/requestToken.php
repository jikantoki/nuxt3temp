<?php
require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once '../vendor/autoload.php';
require_once '../env.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定
require_once './functions/mailFunctions.php';

if (
  !isset($_GET['id']) ||
  !isset($_GET['password'])
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params',
    'errCode' => 10
  ]);
  exit;
}

$id = $_GET['id'];
$password = $_GET['password'];
$otp = requestOnetimeToken($id, $password);
if ($otp) {
  $otpString = (string) $otp;
  $otpFirst = substr($otpString, 0, 3);
  $otpSecond = substr($otpString, 4, 6);
  $otpForMail = $otpFirst . '-' . $otpSecond;
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Send mail to your mailaddress!',
    'id' => $id
  ]);
  $secretId = idToSecretId($id);
  $mailAddress = secretIdToMailAddress($secretId);
  sendMail($mailAddress, 'アクセストークンのお知らせ', '<h1>アクセストークンはこちら！</h1><p>' . $otpForMail . '</p>');
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'Unknown user',
    'errCode' => 20
  ]);
}
