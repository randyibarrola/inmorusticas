<?php
class html_servicios_admin extends zen_html_modelo_datos {
 /**
  * @var array
  */
 var $c;
 /**
  * @var zen_plantilla
  */
 var $p;
 /**
  * @param servicios_admin $padre
  * @return html_servicios_admin
  */
 function html_servicios_admin(&$padre){
  parent::zen_html_modelo_datos($padre);
  $this->c =& $this->padre->padre->contenido;
  $this->p =& new zen_plantilla();
 }
 
 function index(){
  $html['elementos'] = "";
  $this->padre->campos ="ids,titulo_es";
  $this->c['titulo'] = "Listado de art&iacute;culos de servicios dados de alta";
  $this->c['contenido'] = $this->listado("admin/servicios/servicios.html",
  	 "admin/servicios/indice.html","elementos");
  $this->padre->padre->html->mostrar($this->c);
 }
 
 function anadir($datos){
  $this->c['scripts'] = '<script type="text/javascript" src="../media/js/tiny_mce/tiny_mce.js"></script>
  						 <script type="text/javascript" src="../media/js/tiny_mce.js"></script>';
  $this->c['titulo']  = "Crear nuevo servicio";
  $this->c['contenido'] = $this->p->devolver_contenido("admin/servicios/crear.html");
  $this->padre->padre->html->mostrar($this->c);
 }
 
 function insertar($datos=null){
  $this->c['titulo']  = "Creando nuevo servicio";
  if (!isset($_POST['titulo_es'])) {
  	$co = "Rellene todos los campos. <a href='javascript:window.history.back();'>Volver</a>";
  } else {
  	$this->c['titulo'].= "- ({$_POST['titulo_es']}) - ";
  	$_REQUEST['fecha'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha']);
  	$ids = $this->padre->insertar($_REQUEST);
  	if ($ids)
  	 $co = '<img src="../media/img/icon_aceptar.jpg" height="23" border=0> Creaci&oacute;n correcta, 
  	  <a href="index.php/servicios_admin/">continuar</a>,o '.
  	 '<a href="index.php/servicios_admin/editar/'.$ids.'/">continuar editando</a>';
  	else 
  	 $co = 'Error creando la nueva entrada...<a href="javascript:window.history.back();';
  	
  }
  $this->c['contenido'] = $this->padre->padre->html->plantilla->rellena_HTML(
  	"admin/servicios/mensaje.html",array("mensaje"=>$co));
  $this->padre->padre->html->mostrar($this->c);
 }
 
 function editar($datos){
  $ids = $this->padre->existe($datos[0]);
  if (!$ids) die("No existe el articulo");
  $articulo           = $this->padre->obtener_primero("","","where ids=$ids");
  $this->c['scripts'] = '<script type="text/javascript" src="../media/js/tiny_mce/tiny_mce.js"></script>
  						 <script type="text/javascript" src="../media/js/tiny_mce.js"></script>';
  $this->c['titulo']  = "Editar servicio";
  $articulo['fecha']    = zen_parsear_fecha_a_normal($articulo['fecha']);
  $this->c['contenido'] = parent::editar("admin/servicios/editar.html",$articulo);
  $this->padre->padre->html->mostrar($this->c);
 }
  

 
 function actualizar($datos=null){
  $ids = $this->padre->existe($datos[0]);
  if (!$ids) die("No existe el articulo");
  $this->c['titulo']  = "Actualizar art&iacute;culo de servicios";
  
  if (!isset($_POST['titulo_es'])) {
  	$co = "Rellene todos los campos. <a href='javascript:window.history.back();'>Volver</a>";
  } else {
  	$this->c['titulo'].= "- {$_POST['titulo_es']}";
  	$_REQUEST['url_amiga']  = zen_codifica_nombre_para_url($_REQUEST['titulo_es']);
  	$_REQUEST['fecha'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha']);
  	if ($this->padre->actualizar($_REQUEST))
  	 $co = '<img src="../media/img/icon_aceptar.jpg" height="23" border=0> Actualizaci&oacute;n correcta, 
  	 <a href="index.php/servicios_admin/">continuar</a>,o ';
  	else 
  	 $co = 'Error actualizando...';
  	
  	$co .= '<a href="index.php/servicios_admin/editar/'.$_POST['ids'].'/">continuar editando</a>';
  }
  $this->c['contenido'] = $this->padre->padre->html->plantilla->rellena_HTML("admin/servicios/mensaje.html",
  	array("mensaje"=>$co));
  $this->padre->padre->html->mostrar($this->c);
 }
 function borrar($datos=null){
  $ids = $this->padre->existe($datos[0]);
  if (!$ids) die("No existe el articulo");
  $this->c['titulo']  = "Borrar art&iacute;culo de servicios";
  
  if (!isset($_GET['confirmacion'])){
  	$co = '&iquest;Seguro que desea borrar este contenido? [<strong>'. 
  		$this->padre->obtener_campo($ids,"titulo_es").'</strong>]'.
  		'<br><a href="index.php/servicios_admin/borrar/'.$ids.
  		'/?confirmacion=1"><img src="/media/images/icon_ok.gif" border="0">Si</a>,<br>'.
  		'<a href="index.php/servicios_admin/"><img src="/media/images/icon_error.gif" border="0">No</a>';
  } else {
  	$this->padre->borrar($ids);
  	$co = "Contenido borrado con &eacute;xito de la base de datos.".
  	'<a href="index.php/servicios_admin/">continuar</a>';
  }
  $this->c['contenido'] = $this->padre->padre->html->plantilla->rellena_HTML(
  	"admin/servicios/mensaje.html",array("mensaje"=>$co));
  $this->padre->padre->html->mostrar($this->c);
 }
 
}
?>