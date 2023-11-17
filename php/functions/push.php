<?php
require_once DIR_ROOT . '/vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

const VAPID_SUBJECT = WebPush_URL;
const PUBLIC_KEY = VUE_APP_WebPush_PublicKey;
const PRIVATE_KEY = VUE_APP_WebPush_PrivateKey;

/**
 * ## プッシュ通知を送信する
 *
 * $actions使用例（最大二つのボタンを追加可能）
 * ```php
  $actions = [
    array(
      'action' => '/search?q=google',
      'title' => 'googleの検索結果を見る'
    )
  ]
 * ```
 *
 * @param [String] $endPoint
 * @param [String] $publickey
 * @param [String] $authToken
 * @param [String] $title 通知タイトル
 * @param [String] $message 通知の本文
 * @param [String] $image 通知に添付する画像URL
 * @param [String] $options その他オプション
 * @return bool うまくいけばTrue、ダメならfalse
 */
function sendPush($endPoint, $publickey, $authToken, $title, $message = '', $image = '', $actions = [])
{
  if ($image !== '') {
    $icon = $image;
  } else {
    $icon = null;
  }

  // push通知認証用のデータ
  $subscription = Subscription::create([
    'endpoint' => $endPoint,
    'publicKey' => $publickey,
    'authToken' => $authToken,
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
          'body' => $message,
          'icon' => $icon,
          'actions' => $actions
        )
      )
    )
  );

  $report->getRequest()->getUri()->__toString();
  if ($report->isSuccess()) {
    return true;
  } else {
    return false;
  }
}

function sendPushForAccount($secretId, $title, $message = '', $image = '', $options = [])
{
  $pushList = SQLfind('push_token_list', 'secretId', $secretId);
  var_dump($pushList);
}
