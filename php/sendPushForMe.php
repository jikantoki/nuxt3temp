<?php
require_once '../env.php'; //環境変数読み込み
require_once './settings.php'; //ルートディレクトリ読み込み
require_once DIR_ROOT . '/vendor/autoload.php';
require_once DIR_ROOT . '/php/functions/authAPI.php';
require_once DIR_ROOT . '/php/functions/authAPIforUse.php'; //APIが有効かどうか自動判定

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

const VAPID_SUBJECT = 'nuxt.enoki.xyz';
const PUBLIC_KEY = VUE_APP_WebPush_PublicKey;
const PRIVATE_KEY = VUE_APP_WebPush_PrivateKey;

if (
  !isset($_POST['message'])
  || !isset($_SERVER['HTTP_ENDPOINT'])
  || !isset($_SERVER['HTTP_PUBLICKEY'])
  || !isset($_SERVER['HTTP_AUTHTOKEN'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid authentication information',
    'errCode' => 10
  ]);
  exit;
}

if (isset($_POST['icon'])) {
  $icon = $_POST['icon'];
} else {
  $icon = null;
}
if (isset($_POST['title'])) {
  $title = $_POST['title'];
} else {
  $title = '通知確認テスト';
}

// push通知認証用のデータ
$subscription = Subscription::create([
  'endpoint' => $_SERVER['HTTP_ENDPOINT'],
  'publicKey' => $_SERVER['HTTP_PUBLICKEY'],
  'authToken' => $_SERVER['HTTP_AUTHTOKEN'],
]);

// ブラウザに認証させる
$auth = [
  'VAPID' => [
    'subject' => VAPID_SUBJECT,
    'publicKey' => PUBLIC_KEY,
    'privateKey' => PRIVATE_KEY,
  ]
];

$webPush = new WebPush($auth);

$report = $webPush->sendOneNotification(
  $subscription,
  json_encode(
    array(
      'title' => $title,
      'option' => array(
        'body' => $_POST['message'],
        'icon' => $icon,
        'actions' => [
          array(
            'action' => 'test',
            'title' => 'アクションボタン'
          )
        ]
      )
    )
  )
);

$endpoint = $report->getRequest()->getUri()->__toString();
if ($report->isSuccess()) {
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!'
  ]);
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => $report,
    'errCode' => 10
  ]);
  //この場合は無効なトークンを持っている場合が多い
  //リセットした方がいい
}
