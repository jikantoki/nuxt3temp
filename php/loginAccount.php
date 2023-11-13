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
  !isset($_GET['password']) ||
  !isset($_GET['token'])
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
$otp = $_GET['token'];
$token = createUserToken($id, $password, $otp);
if ($token) {
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!',
    'token' => $token,
    'id' => $id
  ]);
  $secretId = idToSecretId($id);
  $mailAddress = secretIdToMailAddress($secretId);
  sendMail($mailAddress, 'ログインがあったよ！', '<h1>ハッハッハあああ</h1><p>本文</p>');
  SQLupdate('user_secret_list', 'otp', null, 'secretId', $secretId);
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'Unknown user',
    'errCode' => 20
  ]);
}
