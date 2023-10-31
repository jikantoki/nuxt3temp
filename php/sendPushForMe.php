<?php
require_once '../vendor/autoload.php';
require_once '../env.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

const VAPID_SUBJECT = 'dev.vuetemp.enoki.xyz';
const PUBLIC_KEY = VUE_APP_WebPush_PublicKey;
const PRIVATE_KEY = VUE_APP_WebPush_PrivateKey;

if (isset($_GET['icon'])) {
  $icon = $_GET['icon'];
} else {
  $icon = null;
}
if (isset($_GET['title'])) {
  $title = $_GET['title'];
} else {
  $title = '通知確認テスト';
}

// push通知認証用のデータ
$subscription = Subscription::create([
  'endpoint' => $_GET['endpoint'],
  'publicKey' => $_GET['publickey'],
  'authToken' => $_GET['authtoken'],
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
        'body' => $_GET['message'],
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
echo '<pre>';
if ($report->isSuccess()) {
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!'
  ]);
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => $report
  ]);
  //この場合は無効なトークンを持っている場合が多い
  //リセットした方がいい
}

echo '</pre>';
