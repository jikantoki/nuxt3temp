<?php
require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once '../vendor/autoload.php';
require_once '../env.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAccount.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定
require_once './functions/mailFunctions.php';

if (
  !isset($_SERVER['HTTP_ID']) ||
  !isset($_SERVER['HTTP_TOKEN'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid GET params',
    'errCode' => 10
  ]);
  exit;
}

$id = $_SERVER['HTTP_ID'];
$secretId = idToSecretId($id);
if (!$secretId) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'Unknown user',
    'id' => $id,
    'errCode' => 20
  ]);
  exit;
}
$token = $_SERVER['HTTP_TOKEN'];
$res = authAccount($secretId, $token);
if ($res) {
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!',
    'res' => $res,
    'header' => $_SERVER,
    'id' => $id
  ]);
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'Unknown user',
    'id' => $id,
    'errCode' => 20
  ]);
}
