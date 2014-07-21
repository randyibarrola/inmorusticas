<?php
class html_servicios extends zen_html_modelo_datos {
 /**
  * @var servicios
  */
 var $padre;
 /**
  * @var array
  */
 var $c;
 function html_servicios(&$padre){
  parent::zen_html_modelo_datos($padre);
  $this->c =& $this->padre->padre->contenido;
 }
 
 function index(){
  $len = $this->padre->padre->idioma;
  $this->padre->campos  = "ids,titulo_$len,intro_$len,url_amiga";
  $this->c['contenido'] = $this->listado("servicios/els.html","servicios/indice.html","elementos");
  $this->padre->padre->html->mostrar_web(TITULO_SERVICIOS);
 }
 function ver($datos){
  $len = $this->padre->padre->idioma;
  $ids = $this->padre->existe_url($datos[0]);
  
  if (!$ids) {
  	$this->index();
  } else {
  	$this->padre->campos = "ids,titulo_".$len.",descripcion_".$len.",DATE_FORMAT(fecha, '%d/%m/%Y') as fecha";
    $this->c['contenido'] = $this->mostrar($ids,"servicios/ver.html");
    $this->padre->padre->html->mostrar_web(TITULO_SERVICIOS. " - ". $this->padre->tuplas[0]['titulo_'.$len]);
  }
 }
}
?>