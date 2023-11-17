<?php
require_once DIR_ROOT . '/vendor/autoload.php';
require_once DIR_ROOT . '/env.php';
require_once DIR_ROOT . '/php/functions/authAPI.php';
require_once DIR_ROOT . '/php/functions/authAccount.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

const VAPID_SUBJECT = WebPush_URL;
const PUBLIC_KEY = VUE_APP_WebPush_PublicKey;
const PRIVATE_KEY = VUE_APP_WebPush_PrivateKey;

/**
 * プッシュ通知を送信する
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
function sendPush($endPoint, $publickey, $authToken, $title, $message = '', $image = '', $options = [])
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

  $report->getRequest()->getUri()->__toString();
  if ($report->isSuccess()) {
    return true;
  } else {
    return false;
  }
}
