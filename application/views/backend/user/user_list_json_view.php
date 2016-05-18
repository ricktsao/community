<?php
$file = "社區住戶資料_".date("YmdHi").'.json';
$file = iconv('utf-8', 'big5', $file );

header("Content-type:text/plain"); 
header("Content-Disposition: attachment; filename=".$file.";");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
header("Pragma: no-cache");
header("Expires: 0");

echo json_encode($list);
?>