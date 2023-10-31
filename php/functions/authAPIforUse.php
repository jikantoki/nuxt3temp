<?php
//APIが有効かどうかをGET要素から自動判定し、ダメそうなら大元から処理を停止

if (
  !isset($_GET['apiid']) ||
  !isset($_GET['apitoken']) ||
  !isset($_GET['apipassword'])
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params',
    'errCode' => 1000
  ]);
  exit;
}

$isAPI = authAPI($_GET['apiid'], $_GET['apitoken'], $_GET['apipassword']);
if (!$isAPI) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid API',
    'errCode' => 1001
  ]);
  exit;
}
