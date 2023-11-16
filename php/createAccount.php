<?php
//アカウント作成用API

require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once './functions/functions.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定

if (
  !isset($_SERVER['HTTP_USERNAME']) ||
  !isset($_SERVER['HTTP_PASSWORD']) ||
  !isset($_SERVER['HTTP_MAILADDRESS'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid authentication information',
    'errCode' => 10
  ]);
  exit;
}
if (
  $_SERVER['HTTP_USERNAME'] === '' ||
  $_SERVER['HTTP_PASSWORD'] === '' ||
  $_SERVER['HTTP_MAILADDRESS'] === ''
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid authentication information',
    'errCode' => 11
  ]);
  exit;
}
$username = $_SERVER['HTTP_USERNAME'];
$password = $_SERVER['HTTP_PASSWORD'];
$mailAddress = $_SERVER['HTTP_MAILADDRESS'];

$response = makeAccount($username, $password, $mailAddress);
if (!$response) {
  //アカウント作れた
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you! Please check your mailbox!'
  ]);
} else {
  //既に存在しているとか
  echo json_encode([
    'status' => 'ng',
    'reason' => 'This account already exists',
    'errCode' => 20
  ]);
}
