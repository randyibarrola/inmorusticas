<?php
class servicios extends zen_modelo_datos {
 /**
  * @var ruralvia
  */
 var $padre;
 /**
  * @var html_servicios
  */
 var $html;
 function servicios(&$padre){
  parent::zen_modelo_datos($padre,"","servicios");
 }
 
 function existe_url($url){
  return $this->bd->seleccion_unica("ids from servicios where url_amiga='".
  	zen_sanar($url,255)."'");
 }
}
?>