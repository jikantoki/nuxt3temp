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
  !isset($_GET['id']) ||
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
$token = $_GET['token'];
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
