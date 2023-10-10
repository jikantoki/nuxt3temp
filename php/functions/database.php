<?php

/**
 * 環境変数
 */
require_once DIR_ROOT . '/env.php';

/**
 * ランダムな一文字を生成
 *
 * @return string 1文字
 */
function randomChar()
{
  $words = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '_'];
  return $words[array_rand($words)];
}
/**
 * ランダムな文字列を生成
 *
 * @param [string] $length 文字数
 * @return string ランダムな文字列
 */
function randomString($length)
{
  $text = '';
  for ($count = 0; $count < $length; $count++) {
    $text = $text . randomChar();
  }
  return $text;
}

/**
 * SQLに接続して、そのPDOを返す
 *
 * @return object
 */
function SQLConnect()
{
  $sql = 'mysql:host=' . MySQL_Host . ';dbname=' . MySQL_DBName . ';charset=utf8mb4';
  try {
    $pdo = new PDO($sql, MySQL_User, MySQL_Password);
    return $pdo;
  } catch (Exception $e) {
    echo $e->getMessage();
    return false;
  }
}

/**
 * SQL文を実行
 *
 * @param [string] $sql
 * @return object
 */
function SQL($sql)
{
  $pdo = SQLConnect();
  $stmt = $pdo->query($sql);
  return $stmt->fetch();
}

function SQLselectTable($tableName)
{
  return SQL('select * from ' . $tableName);
}

function SQLshowTable()
{
  return SQL('show tables');
}

function SQLsearchTable($tableName)
{
  return SQL('show tables like "' . $tableName . '"');
}

/**
 * ## 新規テーブルの作成
 * 既にテーブルが存在する場合は1を返す
 *
 * 上手くいったらfalseを返す（重要！！！）
 *
 * ### $arrayの使い方
 * ```php
 * $array = ['キーの名前1'=>'付けたい型','キーの名前2'=>'付けたい型'...];
 * ```
 * 型指定に使えるもの→int、float、varchar(自然数)、text、datetime
 *
 * @param [string] $tableName
 * @param [object] $array
 * @return void
 */
function SQLcreateTable($tableName, $array)
{
  if (SQLsearchTable(($tableName))) {
    return 1;
  }
  $array_word = '';
  foreach ($array as $key => $val) {
    $array_word = $array_word . $key . ' ' . $val . ',';
  }
  $array_word = mb_substr($array_word, 0, -1);
  return SQL('create table ' . $tableName . ' (' . $array_word . ')');
}

/**
 * ## 新規コンテンツの挿入
 *
 * ### $arrayの使い方
 * ```php
 * $array = ['列名1'=>'値1','列名2'=>'値2'...];
 * ```
 * @param [string] $table テーブル名を指定
 * @param [object] $array
 * @return void
 */
function SQLinsert($table, $array)
{
  if (!SQLsearchTable($table)) {
    return 1;
  }
  $keys = '';
  $values = '';
  foreach ($array as $key => $val) {
    $keys = $keys . $key . ',';
    $values = $values . '"' . $val . '"' . ',';
  }
  $keys = mb_substr($keys, 0, -1);
  $values = mb_substr($values, 0, -1);
  return SQL('insert into ' . $table . ' (' . $keys . ') values (' . $values . ')');
}

/**
 * ## テーブルの中から複数条件で検索
 *
 * ### $arrayの中身
 * ```
 * $array = [
 *   [
 *     'key' => '検索したいキー',
 *     'value' => '検索したい文字列',
 *     'func' => '=' //演算記号
 *   ],
 *   [
 *     'key' => '検索したいキー',
 *     'value' => '検索したい文字列',
 *     'func' => '=' //演算記号
 *   ]
 * ]
 * ```
 *
 * @param [string] $table 検索したいテーブル
 * @param [array] $array 検索したい条件をまとめた配列
 * @return object 結果
 */
function SQLfindSome($table, $array)
{
  $words = 'select * from ' . $table . ' where ';
  foreach ($array as $obj) {
    $key = $obj['key'];
    $val = $obj['value'];
    $func = $obj['func'];
    if (is_string($val)) {
      $val = '"' . $val . '"';
    }
    $words = $words . $key . $func . ' ' . $val . ' and ';
  }
  $words = substr($words, 0, -4);
  return SQL($words);
}

/**
 * テーブルの中の'key'列から$funcの演算記号で検索する
 *
 * @param [string] $table 検索したいテーブル
 * @param [string] $key 検索したい列
 * @param [*] $value 見つけたい値
 * @param [string] $func 演算記号（=、<、>=、など）
 * @return void
 */
function SQLfindEx($table, $key, $value, $func)
{
  $useValue = $value;
  if (is_string($value)) {
    $useValue = '"' . $value . '"';
  }
  return SQL('select * from ' . $table . ' where ' . $key . $func . $useValue);
}

/**
 * テーブルの中の'key'列から検索値'value'と完全一致するものを出す
 *
 * @param [string] $table 検索したいテーブル
 * @param [string] $key 検索したい列
 * @param [*] $value 見つけたい値
 * @return void
 */
function SQLfind($table, $key, $value)
{
  return SQLfindEx($table, $key, $value, '=');
}

/**
 * テーブルの任意の列で未使用なダンダム英数字を吐き出す
 *
 * @param [type] $table 使いたいテーブル
 * @param [type] $key IDを登録する列
 * @return string 未使用なID
 */
function SQLmakeRandomId($table, $key)
{
  $breakFlag = 0;
  do {
    $random = randomString(16) . time();
    $response = SQLfind($table, $key, $random);
    if (!$response) {
      $breakFlag = 1;
    }
  } while ($breakFlag == 0);
  return $random;
}

/**
 * 二つの表を比較し、baseTableをjoinTableで拡張した表を作成
 *
 * @param [string] $baseTable ベースにするテーブル
 * @param [string] $joinTable 拡張したい情報
 * @param [string] $baseKey baseTableのキー
 * @param [string] $joinKey 拡張テーブルのキー
 * @param [string] $where 命令後のwhere句以降（whereは勝手に入ります）
 * @return void
 */
function SQLjoin($baseTable, $joinTable, $baseKey, $joinKey, $where = null)
{
  $sql = 'select * from ' . $baseTable . ' left join ' . $joinTable . ' on ' . $baseKey . ' = ' . $joinKey;
  if ($where) {
    $sql = $sql . ' where ' . $where;
  }
  return SQL($sql);
}

//ここから先はWebサイト固有の機能

/**
 * ## アカウント作成
 * @param [string] $userId ユーザー名
 * @param [string] $password パスワード
 * @param [string] $mailAddress メアド
 * @return string 既にアカウントがある場合はAlready、問題なく作れたら0
 */
function makeAccount($userId, $password, $mailAddress)
{
  $already = SQLfind('user_list', 'userId', $userId);
  if ($already) {
    //既にアカウントがある
    return 'Already';
  }
  /** 未使用なランダムID */
  $secretId = SQLmakeRandomId('user_list', 'secretId');
  /** 現在のunixtime */
  $createdAt = time();
  /** アカウントステータス:未認証 */
  $status = 'uncertified';
  /** パスワード */
  $password = password_hash($password, PASSWORD_DEFAULT);
  /** メールアドレス */
  $mail = $mailAddress;

  $res = SQLinsert('user_list', [
    'secretId' => $secretId,
    'userId' => $userId,
    'createdAt' => $createdAt,
    'status' => $status
  ]);

  $res = SQLinsert('user_secret_list', [
    'secretId' => $secretId,
    'password' => $password,
    'otp' => null
  ]);

  $res = SQLinsert('user_profile_list', [
    'secretId' => $secretId,
    'icon' => null,
    'coverImg' => null,
    'name' => null,
    'message' => null
  ]);
  $res = SQLinsert('user_mail_list', [
    'secretId' => $secretId,
    'mailAddress' => $mail
  ]);
  return 0;
}
