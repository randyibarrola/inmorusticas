<?php
require_once("../zenphp/zen.php");
zen___carga_aplicacion("ruralvia_admin");
$adm = new ruralvia_admin();
$adm->enrutador->establecer_direccion_base("/admin/index.php");
$adm->enrutador->delegar();
?>