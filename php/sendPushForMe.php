<?php
require_once '../vendor/autoload.php';
require_once '../env.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

const VAPID_SUBJECT = 'dev.vuetemp.enoki.xyz';
const PUBLIC_KEY = VUE_APP_WebPush_PublicKey;
const PRIVATE_KEY = VUE_APP_WebPush_PrivateKey;

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
      'title' => '通知確認用',
      'option' => array(
        'body' => $_GET['message'],
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
  echo '送信成功！';
} else {
  echo '送信失敗';
  var_dump($report);
  //この場合は無効なトークンを持っている場合が多い
  //リセットした方がいい
}

echo '</pre>';
