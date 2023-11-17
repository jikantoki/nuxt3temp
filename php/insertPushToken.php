<?php
require_once '../env.php'; //環境変数読み込み
require_once './settings.php'; //ルートディレクトリ読み込み
require_once DIR_ROOT . '/vendor/autoload.php';
require_once DIR_ROOT . '/php/functions/database.php';
require_once DIR_ROOT . '/php/functions/authAPI.php';
require_once DIR_ROOT . '/php/functions/authAPIforUse.php'; //APIが有効かどうか自動判定
require_once DIR_ROOT . '/php/functions/mailFunctions.php';
require_once DIR_ROOT . '/php/functions/authAccount.php'; //ログイン状態が有効かどうか判定

if (
  !isset($_SERVER['HTTP_ID']) ||
  !isset($_SERVER['HTTP_TOKEN']) ||
  !isset($_SERVER['HTTP_ENDPOINT']) ||
  !isset($_SERVER['HTTP_PUBLICKEY']) ||
  !isset($_SERVER['HTTP_PUSHTOKEN'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid authentication information',
    'errCode' => 1
  ]);
  exit;
}
$id = $_SERVER['HTTP_ID'];
$endpoint = $_SERVER['HTTP_ENDPOINT'];
$publickey = $_SERVER['HTTP_PUBLICKEY'];
$pushtoken = $_SERVER['HTTP_PUSHTOKEN'];
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
  ],
  [
    'key' => 'push_publicKey',
    'value' => $publickey,
    'func' => '='
  ],
  [
    'key' => 'push_authToken',
    'value' => $pushtoken,
    'func' => '='
  ]
]);
if ($res) {
  //既にPushトークンが登録されている
  echo json_encode([
    'status' => 'already',
    'reason' => 'Already inserted',
    'id' => $id,
    'errCode' => 10
  ]);
  exit;
} else {
  $pushId = SQLmakeRandomId('push_token_list', 'pushId');
  SQLinsert('push_token_list', [
    'pushId' => $pushId,
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
