<?php
//アカウント作成用API

require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once './functions/functions.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定

if (
  !isset($_GET['username']) ||
  !isset($_GET['password']) ||
  !isset($_GET['mailaddress'])
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params',
    'errCode' => 10
  ]);
  exit;
}
if (
  $_GET['username'] === '' ||
  $_GET['password'] === '' ||
  $_GET['mailaddress'] === ''
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params',
    'errCode' => 11
  ]);
  exit;
}

$response = makeAccount($_GET['username'], $_GET['password'], $_GET['mailaddress']);
if (!$response) {
  //アカウント作れた
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!'
  ]);
} else {
  //既に存在しているとか
  echo json_encode([
    'status' => 'ng',
    'reason' => 'This account already exists',
    'errCode' => 20
  ]);
}
