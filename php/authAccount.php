<?php
require_once '../env.php'; //環境変数読み込み
require_once './settings.php'; //ルートディレクトリ読み込み
require_once DIR_ROOT . '/vendor/autoload.php';
require_once DIR_ROOT . '/php/functions/database.php';
require_once DIR_ROOT . '/php/functions/authAPI.php';
require_once DIR_ROOT . '/php/functions/authAccount.php';
require_once DIR_ROOT . '/php/functions/authAPIforUse.php'; //APIが有効かどうか自動判定
require_once DIR_ROOT . '/php/functions/mailFunctions.php';

if (
  !isset($_SERVER['HTTP_ID']) ||
  !isset($_SERVER['HTTP_TOKEN'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid authentication information',
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
