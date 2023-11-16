<?php
require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once '../vendor/autoload.php';
require_once '../env.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定
require_once './functions/mailFunctions.php';
require_once './functions/authAccount.php'; //ログイン状態が有効かどうか判定

if (
  !isset($_GET['id']) ||
  !isset($_GET['token']) ||
  !isset($_GET['endpoint']) ||
  !isset($_GET['publickey']) ||
  !isset($_GET['pushtoken'])
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params',
    'errCode' => 1
  ]);
  exit;
}
$id = $_GET['id'];
$endpoint = $_GET['endpoint'];
$publickey = $_GET['publickey'];
$pushtoken = $_GET['pushtoken'];
$secretId = idToSecretId($id);
$res = SQLfindSome('push_token_list', [
  [
    'key' => 'secretId',
    'value' => $secretId,
    'func' => '='
  ],
  [
    'key' => 'push_endPoint',
    'value' => $endpoint,
    'func' => '='
  ]
]);
if ($res) {
  //既にPushトークンが登録されている
  echo json_encode([
    'status' => 'ng',
    'reason' => 'Already inserted',
    'id' => $id,
    'errCode' => 10
  ]);
  exit;
} else {
  SQLinsert('push_token_list', [
    'secretId' => $secretId,
    'push_endPoint' => $endpoint,
    'push_publicKey' => $publickey,
    'push_authToken' => $pushtoken,
    'createdAt' => time()
  ]);
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!',
    'id' => $id
  ]);
}
