<?php
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
  $account = SQLfindSome('user_accesstoken_list', [
    [
      'key' => 'secretId',
      'value' => $secretId,
      'func' => '='
    ]
  ]);
  if ($account) {
    if (password_verify($token, $account['token'])) {
      //APIアカウント有効
      return true;
    } else {
      //トークンがちゃう
      return false;
    }
  } else {
    //アカウント不明
    return false;
  }
}
