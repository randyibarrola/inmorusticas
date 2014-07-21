<?php
require_once("zenphp/zen.php");
error_reporting(E_ALL);
ini_set("display_errors","on");
zen___carga_aplicacion("ruralvia");
$app = new ruralvia();
$app->enrutador->delegar();
?>