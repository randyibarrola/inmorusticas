<?php
class html_contenidos_admin extends zen_html_modelo_datos {
 /**
  * @var contenidos_admin
  */
 var $padre;
 /**
  * @var array
  */
 var $c;
 /**
  * @var array
  */
 var $secciones = array('alquiler'=>"En Alquiler", "venta"=> "En Venta",'compra'=>"Se compra",'trueque'=>"Trueque",'traspaso'=>"Se traspasa",'gratis'=>"Anuncio Gratis",'autopromo'=>"Autopromoci&oacute;n");
 /**
  * @var array
  */
 var $tipos;
 /**
  * Constructor
  * @param contenidos_admin $padre
  * @return html_contenidos_admin
  */
 function html_contenidos_admin(&$padre){
  parent::zen_html_modelo_datos($padre);
  $this->c =& $this->padre->padre->contenido;
  $this->tipos = $this->padre->bd->obtener_set("tipo","categorias");
 }
 
 function index($datos){
  $accion = $datos[0];
  
  array_shift($datos);
  
  $datos[1] = $datos[0];
  $datos[0] =& $datos["seccion"];
 
  switch ($accion){
  	case 'listar':
  	 $this->listar($datos);
  	 break;
  	case 'crear':
  	 $this->crear($datos);
  	 break;
  	case 'actualizar':
  	 $this->actualizar($datos);
  	 break;
  	case 'insertar':
  	 $this->insertar($datos);
  	 break;
  	case 'borrar':
  	 $this->borrar($datos);
  	 break;
  	case 'editar':
  	 $this->editar($datos);
  	 break;
  }
 }
 
 function listar($datos){
  $p = new zen_plantilla();
  $html['elementos'] = "";
  
  $this->c['titulo'] = $this->secciones[$sec];
  $this->c['scripts'].= '<script language="JavaScript" type="text/javascript" src="js/ajax.js"></script>';
  $this->padre->filtros_postprocesamiento = array(
   "publicado" => array($this,"esta_publicado")
  );
  $this->padre->condiciones_where = "where categoria=".intval($datos["categoria"]);
  
  $sec = $datos['seccion'];
  $this->padre->campos  = str_replace("modificado","DATE_FORMAT(modificado,'%d/%m/%Y') as fecha".
  	",DATE_FORMAT(modificado,'%h:%i:%s%p') as hora",$this->padre->campos);
  $this->c['contenido'] = $this->listado("admin/secciones/elementos.html",
  	 "admin/secciones/indice_articulos.html","elementos",$datos,$datos);
  
 }
 
 function publicar($datos){
  $id= intval($datos[0]);
  $p = $this->padre->bd->seleccion_unica("publicado from contenidos where idc=".$id);
  $this->padre->bd->actualizar("contenidos set publicado=".intval(!$p)." where idc=".$id);
  die(sprintf('[{"id":'.$id.',"html":\'%s\'}]',$this->esta_publicado(!$p)));
 }
 
 function esta_publicado($b){
  if ($b){
   return '<img src="/media/images/icon_ok.gif" border="0">Si';
  } else {
   return '<img src="/media/images/icon_error.gif" border="0">No';
  }
 }
 
 function comprobar_seccion_idc($datos){
  $sec = addslashes($datos[0]);
  list($idc,$cat) = $this->padre->existe_categoria($datos[1]," and seccion='$sec'");
  $tipo= $this->padre->bd->seleccion_unica("tipo from categorias where idc=".$datos["categoria"]);
  return array($sec,$idc,$tipo,$cat);
 }
 
 function construir_secciones($seleccionada){
  $html = '';
  foreach ($this->secciones as $id=> $nombre) {
  	$html .= '<option value="'.$id.'" ';
  	$html .= $seleccionada==$id?' selected ':'';
  	$html .= '>'.$nombre."</option>\n";
  }
  return $html;
 }
 function construir_tipos($tipo_seleccionado){
  $html = '';
  foreach ($this->tipos as $nombre) {
  	$html .= '<option value="'.$nombre.'" ';
  	$html .= $tipo_seleccionado==$nombre?' selected ':'';
  	$html .= '>'.$nombre."</option>\n";
  }
  return $html;	
 }
 
 function construir_select($nombre,$tabla,$seleccionado){
  $html = "";
  foreach ($this->padre->bd->obtener_set($nombre,$tabla) as $k){   
    $html .= '<option value="'.$k.'" ';
  	$html .= $seleccionado==$k?' selected ':'';
  	$html .= '>'.$k."</option>\n";
  }
  return $html;	
 }
 
 function crear($datos){
  list($sec,$idc,$tipo,$cat) = $this->comprobar_seccion_idc($datos);
  $this->c['scripts'] = '<script type="text/javascript" src="../media/js/tiny_mce/tiny_mce.js"></script>
  						 <script type="text/javascript" src="../media/js/tiny_mce.js"></script>
  						 <script type="text/javascript" src="js/anadir_images.js"></script>'
  						/*<!-- <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.
  						 ruralvia_gmaps_key.'" type="text/javascript"></script>-->*/
  						 .'<script type="text/javascript" src="http://www.google.com/jsapi?key='.ruralvia_gmaps_key.'"></script>
  						 <script type="text/javascript" src="js/anadir_coord.js"></script>
  						 
  						 ';
  $this->c['onloads'] = 'onload="IniciarMapa(37.18811710404501, -3.6072492599487305,false);" ';
  $this->c['titulo']  = "Crear nuevo contenido [{$this->secciones[$sec]}]";
  $articulo = array(
   'seccion' 	 		=> $sec,
   'fecha'       		=>  date("d/m/Y"),
   'fecha_alta'         => date("d/m/Y"),
   'secciones'  		=> $this->construir_secciones($sec),
   'tipos_propietario'  => $this->construir_select("tipo_propietario",$this->padre->tablas,null),
   'tipos_inmueble' 	=> $this->construir_select("tipo_inmueble",$this->padre->tablas,null),
   'tipos_terreno'      => $this->construir_select("tipo_terreno",$this->padre->tablas,null),
   '_caracteristicas_fisicas' => $this->construir_select("caracteristicas_fisicas",$this->padre->tablas,null),
   'orientaciones'      => $this->construir_select("orientacion",$this->padre->tablas,null),
   'categoria' 			=> $datos['categoria'],
   'ccaas'              => $this->construir_select("ccaa",$this->padre->tablas,"null"),
   'imagenes' 			=> "", 
   'documentos'         => "",
   'nombre_categoria'   => $datos['nombre_categoria'],
   'nombre_seccion'     => $datos['nombre_seccion'],
   'decoraciones'      => $this->construir_select("decoracion",$this->padre->tablas,null)
  );
  $this->c['contenido'] = parent::editar("admin/secciones/$sec/crear_$tipo.html",$articulo);
  //$this->c['contenido'] = parent::editar("admin/secciones/crear_$sec.html",$articulo);
  
 }
 
 function formatear_campos($tipo,&$articulo){
  $checks = $ints = $dates = array();
  switch ($tipo){
  	case "hotel":
  	 $checks = array("licencia_actividad","titulo_confidencial","tlf_confidencial","url_confidencial","licencia_apertura",
  	 "restaurante","spa","piscina","cafeteria","bar","capilla","jardines","sala_banquetes","ascensores",
  	 "aceptaria_alquiler","aceptaria_alquiler_compra","aceptaria_permuta","aceptaria_otras",
  	 "sala_reuniones","licencia_obra");
  	 break;
  	case "casa": case "alojamiento":
  	 $checks = array("tlf_confidencial");
  	 break;
  	case "parcela": case "finca": case "propiedad":
  	 $checks = array("licencia_actividad","licencia_apertura","servidumbres","restaurante","spa","piscina","cafeteria","bar",
  	 "capilla","jardines","sala_banquetes","ascensores",
  	 "aceptaria_alquiler","aceptaria_alquiler_compra","aceptaria_permuta","aceptaria_otras",
  	 "sala_reuniones","licencia_obra");
  	 break;
  }
  foreach ($checks as $check) {
  	$articulo[$check] = $articulo[$check]==1?'checked':'';
  }
 }
 
 function editar($datos){
  list($sec,$idc,$tipo,$cat)= $this->comprobar_seccion_idc($datos);
  $this->c['scripts'] = '<script type="text/javascript" src="../media/js/tiny_mce/tiny_mce.js"></script>
  						 <script type="text/javascript" src="../media/js/tiny_mce.js"></script>
  						 <script type="text/javascript" src="js/anadir_images.js"></script>'
  						/*<!-- <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.
  						 ruralvia_gmaps_key.'" type="text/javascript"></script>-->*/
  						 .'<script type="text/javascript" src="http://www.google.com/jsapi?key='.ruralvia_gmaps_key.'"></script>
  						 <script type="text/javascript" src="js/anadir_coord.js"></script>
  						 
  						 ';
  
  $this->c['titulo']  = "Editar contenido [{$this->secciones[$sec]}]";
  $articulo           = $this->padre->obtener_primero("","","where idc=$idc");
  $this->c['onloads'] = 'onload="IniciarMapa('.$articulo['lng'].','.$articulo['lat'].',true);" ';
  $articulo['secciones']= $this->construir_secciones($sec);
  $articulo['tipos_propietario']  = $this->construir_select("tipo_propietario",$this->padre->tablas,$articulo['tipo_propietario']);
  $articulo['tipos_inmueble'] 	= $this->construir_select("tipo_inmueble",$this->padre->tablas,$articulo['tipo_inmueble']);
  $this->formatear_campos($tipo,$articulo);
  $articulo['fecha']    = zen_parsear_fecha_a_normal($articulo['fecha']);
  $articulo['fecha_alta'] = zen_parsear_fecha_a_normal($articulo['fecha_alta']);
  $articulo['categoria']= $datos['categoria'];
  $articulo['imagenes'] = $this->cargar_imagenes($idc,$sec);
  $articulo['documentos'] = $this->cargar_documentos($idc);
  $articulo['nombre_categoria'] = $datos['nombre_categoria'];
  $articulo['nombre_seccion'] = $datos['nombre_seccion'];
  $articulo['seccion'] = $sec;
  $articulo['ccaas'] = $this->construir_select("ccaa",$this->padre->tablas,$articulo['ccaa']);
  $articulo['decoraciones'] = $this->construir_select("decoracion",$this->padre->tablas,$articulo['decoracion']);
  
  //$this->c['contenido'] = parent::editar("admin/secciones/editar_$sec.html",$articulo);
  $this->c['contenido'] = parent::editar("admin/secciones/$sec/editar_$tipo.html",$articulo);
  
 }
  
 /**
  * Guarda un array de imagenes y devuelve la lista de ellos que se ha logrado almacenar
  * tambien comprueba si se ha borrado alguno previamente
  * @param int $idc
  * @param str $seccion
  * @return str
  */
 function guardar_imagenes($idc=null,$seccion){  
  if (!is_null($idc))
   $ims = zen_deserializar($this->padre->bd->seleccion_unica("imagenes from contenidos where idc=".$idc)); 
  else 
   $ims = array();
  //Guardar imagenes:
  $errores = "";
  zen___carga_funciones("zen_ficheros");
  $guardar_imgs= zen_guardarFicheros("imagenes",ZF_DIR_MEDIA."img/$seccion/",$errores,rand(10,100)."_",true,104);
  $n = count($ims); 
  for ($i=0; $i<$n; $i++){ 
  	if (!isset($_POST['borrar_'.str_replace(".","_",$ims[$i])])) {
  	 array_push($guardar_imgs,$ims[$i]); 
  	} else echo $_POST['borrar_'.$ims[$i]];
  }
  return zen_serializar($guardar_imgs);
 }
 /**
  * Devuelve una lista en HTML de imagenes asociadas a un articulo
  * @param int $idc
  * @param str $seccion
  * @return str
  */
 function cargar_imagenes($idc=null,$seccion){
  $html = "";
  if (!is_null($idc))
   $ims = zen_deserializar($this->padre->bd->seleccion_unica("imagenes from contenidos where idc=".$idc)); 
  else 
   $ims = array();
  $n = count($ims);
  for ($i=0; $i<$n; $i++){
	 $im = ZF_SITIO_WEB.'media/img/'.$seccion.'/'.$ims[$i];
	 $html .= '<a href="'.$im.'" target="_blank"><img src="'.$im.
	 	'" border="0"></a>[<label for="borrar_'.$ims[$i].
	 	'">borrar</label><input type="checkbox" id="borrar_'.$ims[$i]
	 	.'" name="borrar_'.$ims[$i].'"/>]<br/>';
  }
  return $html;
 }
 function guardar_documentos($idc=null){  
  if (!is_null($idc))
   $ims = zen_deserializar($this->padre->bd->seleccion_unica("documentos from contenidos where idc=".$idc)); 
  else 
   $ims = array();
  //Guardar
  $errores = "";
  zen___carga_funciones("zen_ficheros");
  $guardar_docs= zen_guardarFicheros("documentos",ZF_DIR_MEDIA."documentos/",$errores,rand(10,100)."_",false);
  $n = count($ims); 
  for ($i=0; $i<$n; $i++){ 
  	if (!isset($_POST['borrar_'.str_replace(".","_",$ims[$i])])) {
  	 array_push($guardar_docs,$ims[$i]); 
  	} else echo $_POST['borrar_'.$ims[$i]];
  }
  return zen_serializar($guardar_docs);
 }
 function cargar_documentos($idc=null){
  $html = "";
  if (!is_null($idc))
   $ims = zen_deserializar($this->padre->bd->seleccion_unica("documentos from contenidos where idc=".$idc)); 
  else 
   $ims = array();
  $n = count($ims);
  for ($i=0; $i<$n; $i++){
	 $im = ZF_SITIO_WEB.'media/documentos/'.$ims[$i];
	 $html .= '<a href="'.$im.'" target="_blank">'.$im.
	 	'</a>[<label for="borrar_'.$ims[$i].
	 	'">borrar</label><input type="checkbox" id="borrar_'.$ims[$i]
	 	.'" name="borrar_'.$ims[$i].'"/>]<br/>';
  }
  return $html;
 }
 function insertar($datos=null){
  list($sec,$idc,$tipo,$cat)= $this->comprobar_seccion_idc($datos);
  
  $this->c['titulo']  = "Creando nuevo [{$this->secciones[$sec]} ] - $tipo ";
  if (!isset($_POST['titulo_es'])) {
  	$co = "Rellene todos los campos. <a href='javascript:window.history.back();'>Volver</a>";
  } else {
  	$this->c['titulo'].= "- ({$_POST['titulo_es']}) - ";
  	$_REQUEST['fecha'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha']);
  	$_REQUEST['fecha_alta'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha_alta']);
  	$_REQUEST['modificado'] = date("Y-m-d H:i:s");//2009-06-16 10:49:19
  	$_REQUEST['url_amiga']  = zen_codifica_nombre_para_url($_REQUEST['titulo_es']);
  	$_REQUEST['categoria']  = $datos['categoria'];
  	$_REQUEST['imagenes']   = $this->guardar_imagenes(null,$sec);
  	$_REQUEST['documentos'] = $this->guardar_documentos(null);
  	$_REQUEST['publicado']  = 1;
  	zen___carga_funciones("zen_ficheros"); $errores = "";
  	$miniatura = zen_guardarFichero("miniatura",ZF_DIR_MEDIA.'img'.DIRECTORY_SEPARATOR.$sec.DIRECTORY_SEPARATOR,$errores);
  	if (!empty($miniatura) && empty($errores)) $_REQUEST['miniatura'] =& $miniatura;
  	$idc = $this->padre->insertar($_REQUEST);
  	if ($idc)
  	 $co = '<img src="../media/img/icon_aceptar.jpg" height="23" border=0> Creaci&oacute;n correcta, 
  	  <a href="index.php/contenidos/articulos/'.$sec.'/'.$datos['categoria'].'/listar/'.$_REQUEST['seccion'].'/">continuar</a>,o '.
  	 '<a href="index.php/contenidos/articulos/'.$sec.'/'.$datos['categoria'].'/editar/'.$idc.'/">continuar editando</a>';
  	else 
  	 $co = 'Error creando la nueva entrada...<a href="javascript:window.history.back();';
  	
  }
  $this->c['contenido'] = $this->padre->padre->html->plantilla->rellena_HTML(
  	"admin/secciones/mensaje.html",array("mensaje"=>$co));
  
 }
 function actualizar($datos=null){
  list($sec,$idc,$tipo,$cat) = $this->comprobar_seccion_idc($datos);
  $this->c['titulo']  = "Actualizar [{$this->secciones[$sec]}] - $tipo -";
  
  if (!isset($_POST['titulo_es'])) {
  	$co = "Rellene todos los campos. <a href='javascript:window.history.back();'>Volver</a>";
  } else {
  	$this->c['titulo'].= "- {$_POST['titulo_es']}";
  	$_REQUEST['url_amiga']  = zen_codifica_nombre_para_url($_REQUEST['titulo_es']);
  	$_REQUEST['fecha'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha']);
  	$_REQUEST['fecha_alta'] = zen_parsear_fecha_a_mysql($_REQUEST['fecha_alta']);
  	$_REQUEST['modificado'] = date("Y-m-d H:i:s");//2009-06-16 10:49:19
    $_REQUEST['imagenes'] = $this->guardar_imagenes($_POST['idc'],$sec);
    $_REQUEST['documentos'] = $this->guardar_documentos($_POST['idc']);
    zen___carga_funciones("zen_ficheros"); $errores = "";
  	$miniatura = zen_guardarFichero("miniatura",ZF_DIR_MEDIA.'img'.DIRECTORY_SEPARATOR.$sec.DIRECTORY_SEPARATOR,$errores);
  	if (!empty($miniatura) && empty($errores)) $_REQUEST['miniatura'] =& $miniatura;
  	//$sec = $_REQUEST['seccion'];
  	if ($this->padre->actualizar($_REQUEST))
  	 $co = '<img src="../media/img/icon_aceptar.jpg" height="23" border=0> Actualizaci&oacute;n correcta, 
  	 <a href="index.php/contenidos/articulos/'.$sec.'/'.$datos['categoria'].'/listar/'.$_REQUEST['seccion'].'/">continuar</a>,o ';
  	else 
  	 $co = 'Error actualizando...';
  	
  	$co .= '<a href="index.php/contenidos/articulos/'.$sec.'/'.
  		$datos['categoria'].'/editar/'.$_POST['idc'].'/">continuar editando</a>';
  }
  $this->c['contenido'] = $this->padre->padre->html->plantilla->rellena_HTML("admin/secciones/mensaje.html",array("mensaje"=>$co));
  
 }
 function borrar($datos=null){
  list($sec,$idc,$tipo,$cat) = $this->comprobar_seccion_idc($datos);
  $this->c['titulo']  = "Borrar contenido [$sec] - $tipo";
  
  if (!isset($_GET['confirmacion'])){
  	$co = '&iquest;Seguro que desea borrar este contenido? [<strong>'. $this->padre->obtener_campo($idc,"titulo_es").'</strong>]'.
  		'<br><a href="index.php/contenidos/articulos/'.$sec.'/'.$datos['categoria'].'/borrar/'.$idc.
  		'/?confirmacion=1"><img src="/media/images/icon_ok.gif" border="0">Si</a>,<br>'.
  		'<a href="index.php/contenidos/articulos/'.$sec.'/'.$datos['categoria'].'/listar/'.$sec.'/"><img src="/media/images/icon_error.gif" border="0">No</a>';
  } else {
  	$this->padre->borrar($idc);
  	$co = "Contenido borrado con &eacute;xito de la base de datos.".
  	'<a href="index.php/contenidos/articulos/'.$sec.'/'.$datos['categoria'].'/listar/'.$sec.'/">continuar</a>';
  }
  //$this->padre->bd->mostrar_ultima_consulta();
  $this->c['contenido'] = $this->padre->padre->html->plantilla->rellena_HTML("admin/secciones/mensaje.html",array("mensaje"=>$co));
 }
}
?>