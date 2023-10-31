<?php
require_once('./functions/database.php');
require_once('./functions/authAPIforUse.php'); //APIが有効かどうか自動判定

if (
  !isset($_GET['id'])
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params',
    'errCode' => 10
  ]);
  exit;
}

$id = $_GET['id'];
$SQLres = idToSecretId($id);
var_dump($SQLres);
