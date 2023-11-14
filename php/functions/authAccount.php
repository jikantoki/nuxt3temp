<?php
require_once DIR_ROOT . '/php/settings.php';
require_once DIR_ROOT . '/php/functions/functions.php';
require_once DIR_ROOT . '/php/functions/database.php';

/**
 * アカウントにログインできているか確認する
 *
 * @param [string] $secretId
 * @param [string] $token
 * @return void トークンが有効ならtrue
 */
function authAccount($secretId, $token)
{
  $isOk = SQLfindSome('user_accesstoken_list', [
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
  if ($isOk) {
    //アカウント不明
    return $isOk;
  } else {
    return false;
  }
}
