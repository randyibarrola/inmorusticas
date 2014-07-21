<?php
class ruralvia_admin extends zen_aplicacion_admin {
 /**
  * @var html_ruralvia_admin
  */
 var $html;
 /**
  * @var contenidos_admin
  */
 var $contenidos_admin;
 /**
  * @var personas_admin
  */
 var $personas_admin;
 /**
  * @var servicios_admin
  */
 var $servicios_admin;
 /**
  * @var array
  */
 var $idiomas = array("es","en"); //,"fr","de"); 
 /**
  * @return ruralvia_admin
  */
 function ruralvia_admin(){
  parent::zen_aplicacion_admin();
  zen___importar_modelos($this,"contenidos_admin,servicios_admin",true,null);
  $this->html =& new html_ruralvia_admin($this);
 }
}
class html_ruralvia_admin extends zen_html_admin {
 /**
  * @var ruralvia_admin
  */
 var $padre;
 /**
  * Puntero al contenido
  *
  * @var array
  */
 var $c;
 /**
  * @param ruralvia_admin $padre
  * @return html_ruralvia_admin
  */
 function html_ruralvia_admin(&$padre){
  parent::zen_html_admin($padre);
  $this->c =& $this->padre->contenido;
  $this->c['scripts'] = $this->c['ruta'] = $this->c['onloads'] = "";
 }
 /**
  * Indice ADMIN
  * @param array $datos
  */
 function index($datos=null){
   $html['mensaje'] = "";
   $html['destacados'] = $this->destacados($datos);
   $this->padre->contenido['contenido'] = $this->plantilla->contenido_reemplaza("admin/indice.html",$html);
   $this->padre->contenido['titulo'] = "Inicio";
   parent::index();
  
 }
 /**
  * Edita los contenidos de las categorias
  * @param array $datos
  */
 function contenidos($datos){
  $r = array("seccion" => $datos[1],
  	  "nombre_seccion" => $this->padre->contenidos_admin->html->secciones[$datos[1]]);
  $cats = new zen_modelo_datos($this->padre,"","categorias");
  
  switch ($datos[0]){
  	case "listar": //Listar categorias
  	 $cats->html->padre->condiciones_where = "where seccion='".$datos[1]."'";
  	 $this->padre->contenido['contenido'] = $cats->html->listado("admin/categorias/els.html","admin/categorias/indice.html",
  	 	"elementos",$r,$r);
  	 
  	 break;
  	case 'anadir':
  	 $r['secciones'] = $this->padre->contenidos_admin->html->construir_secciones($datos[1]);
  	 $r['tipos']     = $this->padre->contenidos_admin->html->construir_tipos("");
  	 $this->padre->contenido['contenido'] = $this->plantilla->contenido_reemplaza("admin/categorias/nueva.html",$r);
  	 break;
  	case 'crear':
  	 $idc  = $cats->insertar($_POST);
  	 if ($idc)
  	  $this->padre->contenido['contenido'] = "A&ntilde;adido!.".'<a href="index.php/contenidos/listar/'.$datos[1].'/">continuar</a> o '.
  	 	'<a href="index.php/contenidos/editar/'.$datos[1].'/'.$idc.'/">seguir editando</a>';
  	 else 
  	  $this->padre->contenido['contenido'] ='Error actualizando...<a href="index.php/contenidos/listar/'.$datos[1].'/">continuar</a>';
  	 break;
  	case 'editar':
  	 $r['secciones'] = $this->padre->contenidos_admin->html->construir_secciones(
  	  $this->padre->bd->seleccion_unica("seccion from categorias where idc=".$datos[2])
  	 );
  	 $r['tipos'] = $this->padre->contenidos_admin->html->construir_tipos(
  	  $this->padre->bd->seleccion_unica("tipo from categorias where idc=".$datos[2])
  	 );
  	 $this->padre->contenido['contenido'] = $cats->html->editar("admin/categorias/editar.html",$r,$datos[2]);
  	 break;
  	case 'actualizar':
  	 $cats->actualizar($_POST);
  	 $this->padre->contenido['contenido'] = "Actualizado!.".'<a href="index.php/contenidos/listar/'.$datos[1].'/">continuar</a> o '.
  	 	'<a href="index.php/contenidos/editar/'.$datos[1].'/'.$idc.'/">seguir editando</a>';
  	 break;
  	case 'borrar':
  	 $cats->borrar($datos[2]);
  	 $this->padre->contenido['contenido'] = "Borrada!.".'<a href="index.php/contenidos/listar/'.$datos[1].'/">continuar</a>';
  	 break;
  	case 'articulos':
  	 $datos['nombre_categoria'] = $this->padre->contenidos_admin->nombre_categoria($datos[2],"es");
  	 $datos['categoria'] = $datos[2];
  	 array_shift($datos); array_shift($datos); array_shift($datos);
  	 
  	 $this->padre->contenidos_admin->html->index(array_merge($datos,$r));
  	 
  	 break;
  }
  $this->padre->contenido['titulo'] .= " Editar ".$r['nombre_seccion'];
  parent::index();
 }
 function destacados($datos=null){
  $html['mensaje'] = $html['destacados'] = "";
  if (isset($_GET['op']) && $_GET['op']==1 && isset($_POST['destacados'])){
  	//Array ( [destacados] => Array ( [0] => 23 [1] => 22 [2] => 21 ) ) 
    if (
      $this->padre->bd->actualizar(
    	"configuracion set valor='".zen_serializar($_POST['destacados'])."' where nombre='destacados'")
    )
      $html['mensaje'] = "Guardado";
    else 
      $html['mensaje'] = "Error guardando";
  }
  $destacados = zen_deserializar($this->padre->bd->seleccion_unica("valor from configuracion where nombre='destacados'"));
  $r = $this->padre->bd->seleccion("idc,titulo_es from contenidos where publicado=1 order by titulo_es");
  while ($r && $f = $this->padre->bd->obtener_fila($r)){
   $html['destacados'] .= '<option value="'.$f['idc'].'" ';
   $html['destacados'] .= in_array($f['idc'],$destacados)?"selected":"";
   $html['destacados'] .= '>'.$f['titulo_es'].'</option>'."\n";
  
  }
  return $this->plantilla->contenido_reemplaza("admin/destacados/indice.html",$html); 
 }
 
 function contacto($datos=null){
  $this->c['scripts'] = '<script type="text/javascript" src="../media/js/tiny_mce/tiny_mce.js"></script>'.
  						'<script type="text/javascript" src="../media/js/tiny_mce.js" defer="defer"></script>
  						 <script type="text/javascript" src="js/anadir_images.js"></script>';
  $idiomas =& $this->padre->idiomas;
  $n = count($idiomas);
  $campos = array("direccion_contacto","texto_contacto"); $m = count($campos);
  for ($i=0; $i<$m; $i++){
  	for ($j=0; $j<$n; $j++){
  	 $nom = $campos[$i]."_".$idiomas[$j];
  	 if (isset($_GET['op']) && $_GET['op']==1 && isset($_POST[$nom])){
  	  $this->padre->bd->actualizar("configuracion set valor='".$_POST[$nom].
  	  	"' where nombre='".$nom."'");
  	  $html[$nom] = $_POST[$nom];
  	 } else {
  	  $html[$nom] = $this->padre->bd->seleccion_unica("valor from configuracion where nombre='".$nom."'");
  	 }
  	}
  }
  $html['imagenes'] = "";
  $guardar_imgs = array();
  $ims = zen_deserializar($this->padre->bd->seleccion_unica("valor from configuracion where nombre='imagenes_contacto'"));
  if (isset($_GET['op']) && $_GET['op']==1 && isset($_POST['direccion_contacto_es'])){
  	//Guardar imagenes:
  	$errores = "";
  	zen___carga_funciones("zen_ficheros");
  	$guardar_imgs= zen_guardarFicheros("imagenes",ZF_DIR_MEDIA."img/contacto/",$errores,rand(10,100)."_",true,104);
  	
  	$n = count($ims); 
  	for ($i=0; $i<$n; $i++){ 
  	 if (!isset($_POST['borrar_'.str_replace(".","_",$ims[$i])])) {
  	 	array_push($guardar_imgs,$ims[$i]); 
  	 } else echo $_POST['borrar_'.$ims[$i]];
    }
    $this->padre->bd->actualizar("configuracion set valor='".zen_serializar($guardar_imgs)."' where nombre='imagenes_contacto'");
  } else $guardar_imgs =& $ims;
  
  $n = count($guardar_imgs);
  for ($i=0; $i<$n; $i++){
	 $im = ZF_SITIO_WEB.'media/img/contacto/'.$guardar_imgs[$i];
	 $html['imagenes'] .= '<a href="'.$im.'" target="_blank"><img src="'.$im.
	 	'" border="0"></a>[<label for="borrar_'.$guardar_imgs[$i].
	 	'">borrar</label><input type="checkbox" id="borrar_'.$guardar_imgs[$i]
	 	.'" name="borrar_'.$guardar_imgs[$i].'"/>]<br/>';
  }
  
  $this->padre->contenido['contenido'] = $this->plantilla->contenido_reemplaza("admin/contacto/indice.html",$html);
  $this->padre->contenido['titulo'] = "Contacto";
  parent::index();
  
 }
 function zonas_js($datos=null){
  $op = isset($datos[0])?$datos[0]:"";
  $zonas =& new zen_modelo_datos($this->padre,"","zonas_js");
  switch ($op){
  	case "":default:
  	 $zonas->campos = "idz,nombre_es";
  	 $zonas->condiciones_where = " order by nombre_es";
  	 $this->c['contenido']  = $zonas->html->listado("admin/zonas_js/zonas.html","admin/zonas_js/indice.html","zonas");
  	 $this->c['titulo'] = "Listado de Zonas [cl&uacute;steres] del mapa";
  	 break;
  	case "anadir":
  	 $this->c['contenido'] = $this->plantilla->devolver_contenido("admin/zonas_js/crear.html");
  	 $this->c['titulo'] = "Editar Zona [cl&uacute;steres] del mapa";
  	 $this->c['onloads'] = 'onload="IniciarMapa('.ruralvia_lat.', '.ruralvia_lng.',false);'.
  	 	'map.setCenter(new GLatLng('.ruralvia_lat.', '.ruralvia_lng.'), 6);"';
  	 break;
  	case "insertar":
  	 $this->c['titulo'] = "Editar Zona [cl&uacute;steres] del mapa";
  	 $idz = $zonas->insertar($_REQUEST);
  	 if ($idz){
  	  $this->c['contenido'] = "Zona creada correctamente. <a href='index.php/zonas_js/'>continuar</a>";
  	 } else {
  	  $this->c['contenido'] = $this->padre->bd->devolver_ultimo_error().
  	  	"<br/><a href='window.history.back();'>Volver</a>";
  	 }
  	 break;
  	case "editar":
  	 $z = $zonas->obtener_primero("","","where idz=".intval($datos[1]));
  	 $this->c['onloads'] = 'onload="IniciarMapa('.$z['lng'].', '.$z['lat'].',true);" ';
  	 $this->c['contenido'] = $this->plantilla->contenido_reemplaza("admin/zonas_js/editar.html",$z);
  	 $this->c['titulo'] = "Editar (".$z['nombre_es'].") Zona [cl&uacute;steres] del mapa";
  	 break;
  	case "actualizar":
  	 $this->c['titulo'] = "Actualizar Zona [cl&uacute;steres] del mapa";
  	 if ($zonas->actualizar($_REQUEST)){
  	  $this->c['contenido'] = "Zona actualizada correctamente. <a href='index.php/zonas_js/'>continuar</a>";
  	 } else {
  	  $this->c['contenido'] = $this->padre->bd->devolver_ultimo_error().
  	  	"<br/><a href='window.history.back();'>Volver</a>";
  	 }
  	 break;
  	case "borrar":
  	 $this->padre->bd->borrar("zonas_js where idz=".intval($datos[1]));
  	 $this->c['contenido'] = "Zona (".$datos[1].") borrada";
  	 $this->c['titulo'] = "Eliminar Zona [cl&uacute;steres] del mapa";
  	 break;
  }
  if ($op=="editar"||$op=="anadir"){
  	$this->c['scripts'] = '<script type="text/javascript" src="http://www.google.com/jsapi?key='.
  					       ruralvia_gmaps_key.'"></script>
  						 <script type="text/javascript" src="js/anadir_coord.js"></script>
  						 
  						 ';
    
  }
  parent::index();
 }
}
?>