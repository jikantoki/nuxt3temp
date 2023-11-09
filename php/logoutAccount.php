<?php
require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once '../vendor/autoload.php';
require_once '../env.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定

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

$token = $_GET['token'];
$userId = $_GET['id'];
//DBから削除
$secretId = idToSecretId($userId);
$res = SQLdeleteSome('user_accesstoken_list', [
  [
    'key' => 'secretId',
    'value' => $secretId,
    'func' => '='
  ],
  [
    'key' => 'token',
    'value' => $token,
    'func' => '='
  ]
]);
echo json_encode([
  'status' => 'ok',
  'reason' => 'Deleted!',
  'token' => $token,
  'id' => $userId
]);
