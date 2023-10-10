<?php

require_once './settings.php';
require_once './functions/database.php';
require_once './functions/functions.php';
$test = SQLcreateTable('tableAA', ['id' => 'text', 'name' => 'text', 'unixtime' => 'int']);
dump($test);
echo '<br><br><br><br><br>';
$test = SQLshowTable();
dump($test);
$test = SQLsearchTable('usersList');
dump($test);
if (!$test) {
  $test = SQL('create table usersList (id int, name varchar(10), col varchar(10))');
  dump($test);
}
$test = SQLshowTable();
dump($test);
