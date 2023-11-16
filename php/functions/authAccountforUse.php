<?php
//ログイン状態が有効かどうかをGET要素から自動判定し、ダメそうなら大元から処理を停止

if (
  !isset($_GET['id']) ||
  !isset($_GET['token'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid GET params',
    'errCode' => 2000
  ]);
  exit;
}

$id = $_GET['id'];
$token = $_GET['token'];
$secretId = idToSecretId($id);
if (!$secretId) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'unknown account',
    'errCode' => 2001
  ]);
  exit;
}
$isAuthed = authAccount($secretId, $token);
if (!$isAuthed) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'unknown account',
    'errCode' => 2002
  ]);
  exit;
}
