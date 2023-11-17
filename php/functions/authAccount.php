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
  $account = SQLfind('user_accesstoken_list', 'secretId', $secretId);
  if ($account) {
    if (password_verify($token, $account['token'])) {
      //アカウント有効
      return true;
    } else {
      echo $token;
      var_dump($account);
      //トークンがちゃう
      return false;
    }
  } else {
    //アカウント不明
    return false;
  }
}
