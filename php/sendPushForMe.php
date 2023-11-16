<?php
require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once '../vendor/autoload.php';
require_once '../vendor/autoload.php';
require_once '../env.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

const VAPID_SUBJECT = 'nuxt.enoki.xyz';
const PUBLIC_KEY = VUE_APP_WebPush_PublicKey;
const PRIVATE_KEY = VUE_APP_WebPush_PrivateKey;

if (isset($_SERVER['HTTP_ICON'])) {
  $icon = $_SERVER['HTTP_ICON'];
} else {
  $icon = null;
}
if (isset($_SERVER['HTTP_TITLE'])) {
  $title = $_SERVER['HTTP_TITLE'];
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
        'body' => $_SERVER['message'],
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
