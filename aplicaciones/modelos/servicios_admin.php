<?php
class servicios_admin extends zen_modelo_datos {
 /**
  * @var html_servicios_admin
  */
 var $html;
 /**
  * @param ruralvia_admin $padre
  * @return servicios_admin
  */
 function servicios_admin(&$padre){
  parent::zen_modelo_datos($padre,"","servicios");
 }
 
}
?>