<?php
$file = "d.js";
$file = iconv('utf-8', 'big5', $file );

header("Content-type:text/plain"); 
header("Content-Disposition: attachment; filename=".$file.";");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
header("Pragma: no-cache");
header("Expires: 0");

echo 'var member ='.json_encode($list);
?>