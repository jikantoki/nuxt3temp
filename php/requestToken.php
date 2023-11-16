<?php
require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once '../vendor/autoload.php';
require_once '../env.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定
require_once './functions/mailFunctions.php';

if (
  !isset($_SERVER['HTTP_ID']) ||
  !isset($_SERVER['HTTP_PASSWORD'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid authentication information',
    'errCode' => 10
  ]);
  exit;
}

$id = $_SERVER['HTTP_ID'];
$password = $_SERVER['HTTP_PASSWORD'];
$otp = requestOnetimeToken($id, $password);
if ($otp) {
  $otpString = (string) $otp;
  $otpFirst = substr($otpString, 0, 3);
  $otpSecond = substr($otpString, 3, 6);
  $otpForMail = $otpFirst . '-' . $otpSecond;
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Send mail to your mailaddress!',
    'id' => $id
  ]);
  $secretId = idToSecretId($id);
  $mailAddress = secretIdToMailAddress($secretId);
  sendMail($mailAddress, 'アクセストークンのお知らせ', '<p>アクセストークンはこちら！</p><h1>' . $otpForMail . '</h1><p>このコードは一回のみ有効やで<br>身に覚えがなかったらヤバいかも気を付けてね</p>');
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'Unknown user',
    'errCode' => 20
  ]);
}
