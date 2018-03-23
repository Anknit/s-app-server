<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: *");
require_once __DIR__.'/config.php';
session_name(SESSION_NAME);
session_start();
require_once __DIR__.'/definitions.php';
require_once __DIR__.'/common/OperateDB/DbMgrInterface.php';
require_once __DIR__.'/common/mailMgr.php';
?>
