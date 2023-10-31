<?php
//アカウント作成用API

require_once './settings.php';
require_once './functions/functions.php';
require_once './functions/database.php';
require_once './functions/authAPI.php';

if (
  !isset($_GET['username']) ||
  !isset($_GET['password']) ||
  !isset($_GET['mailaddress']) ||
  !isset($_GET['apiid']) ||
  !isset($_GET['apitoken']) ||
  !isset($_GET['apipassword'])
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params'
  ]);
  exit;
}
if (
  $_GET['username'] === '' ||
  $_GET['password'] === '' ||
  $_GET['mailaddress'] === '' ||
  $_GET['apiid'] === '' ||
  $_GET['apitoken'] === '' ||
  $_GET['apipassword'] === ''
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params'
  ]);
  exit;
}
$isAPI = authAPI($_GET['apiid'], $_GET['apitoken'], $_GET['apipassword']);

if ($isAPI) {
  $response = makeAccount($_GET['username'], $_GET['password'], $_GET['mailaddress']);
  if (!$response) {
    //アカウント作れた
    echo json_encode([
      'status' => 'ok',
      'reason' => 'Thank you!'
    ]);
  } else {
    //既に存在しているとか
    echo json_encode([
      'status' => 'ng',
      'reason' => 'This account already exists'
    ]);
  }
}
