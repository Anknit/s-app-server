<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
echo json_encode(array("status" => "true", "data" => "Hello World!"));
die();
?>
