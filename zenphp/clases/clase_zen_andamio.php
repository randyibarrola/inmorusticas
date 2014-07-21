<?php
/**
 * clase_zen_andamio.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase zen_andamio, el scaffolding de zenphp
 * @see DOM_XML_PHP
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
if (ZF_CONTROLADOR_DEFECTO=='xml')
 if (!function_exists('domxml_version')) die(_("No existen las librerias DOM necesarias para cargar zen_andamio"));
/**
 * Se define una constante para limitar el numero de acciones mostradas en el lateral
 *
 */
define("ZF_ANDAMIO_LIMITE_ACCIONES_MOSTRADAS",10);
/**
 * Clase Zen Andamio-->Scaffolding de zenphp
 *
 */
class zen_andamio {
	/**
	 * Modelo y vista XML
	 *
	 * @var zen_xml_andamio
	 */
	var $xml;
	/**
	 * Vista HTML
	 *
	 * @var zen_html_andamio
	 */
	var $html;
	/**
	 * Modelo de datos asociado
	 *
	 * @var zen_modelo_datos
	 */
	var $padre;
	/**
	 * Es el nombre del identificador principal, el primer campo de la/s tabla/s
	 *
	 * @var str
	 */
	var $nombre_id;
	/**
	 * Puntero a la base de datos
	 *
	 * @var zen_basedatos
	 */
	var $bd;
	/**
	 * Array extraido de la información de la tabla pasada
	 *
	 * @var array
	 */
	var $campos;
	/**
	 * Array de opciones del Scaffolding o Andamio
	 *
	 * @var array
	 */
	var $opciones = array(
	//Si listar_tablas es true entonces se listan todas las tablas en los visualizadores
	"listar_tablas"         => true,
	//Si recuperar campos está true entonces se recuperan los campos,en otro caso sólo se hará si no hay ninguno especificado
	"recuperar_campos"      => true,
	//La plantilla_defecto es la ruta absoluta al fichero HTML con las etiquetas a reemplazar, i.e.,la plantilla
	//ha de contener los mismos ficheros de plantillas que en /zenphp/contenido/es/andamio/
	"dir_plantilla_defecto" => "" ,//se establece en el constructor (necesita concatenar la cadena de la ruta)
	//Máximo tamaño de recorte para los TEXT,BLOB,etc. para mostrarlos reducidos en los listados
	"num_caracteres_recorte" => 25
	);
	/**
	 * Constructor
	 *
	 * @param zen_modelo_datos $padre
	 * @return zen_andamio
	 */
	function zen_andamio(&$padre){
		$this->padre =& $padre;
		$this->bd    =& $this->padre->bd;
		$this->opciones['dir_plantilla_defecto'] = ZF_DIR_CONTENIDO_ZEN.'plantillas'.
		DIRECTORY_SEPARATOR.$this->padre->padre->idioma.DIRECTORY_SEPARATOR.'andamio'.DIRECTORY_SEPARATOR;
		$this->obtener_campos();
		$this->html=& new zen_html_andamio($this);
		//$this->xml =& new zen_xml_andamio($this,$fichero_xml,$modo,$error);
	}
	/**
	 * Obtiene un listado de campos que guarda en $this->campos
	 *
	 */
	function obtener_campos(){
		//Carga el listado de campos en un array de tablas especificado en el modelo de datos:
		$this->campos = $this->bd->obtener_campos($this->padre->tablas);
		$split = split(",",$this->padre->campos);
		$this->nombre_id = $split[0];
	}
}
/**
 * Clase HTML para el andamio
 *
 */
class zen_html_andamio {
	/**
	 * Andamio asociado
	 *
	 * @var zen_andamio
	 */
	var $padre;
	/**
	 * Clase para mostrar el contenido del Scaffolding o Andamio
	 *
	 * @var zen_plantilla
	 */
	var $plantilla;
	/**
	 * Puntero al contenido de la web para mostrarlo
	 *
	 * @var array
	 */
	var $contenido;
	/**
	 * Directorio con las plantillas para construir la interfaz de scaffolding o Andamio
	 *
	 * @var str
	 */
	var $directorio;
	/**
	 * Nombre del modelo asociado en este momento
	 *
	 * @var str
	 */
	var $modelo;
	/**
	 * Constructor de la vista HTML para el andamio
	 *
	 * @param zen_andamio $_padre
	 * @return zen_html_andamio
	 */
	function zen_html_andamio(&$_padre){
		$this->padre =& $_padre;
		//Estará en la aplicación por defecto
		$this->contenido =& $this->padre->padre->padre->contenido;
		$this->plantilla =& new zen_plantilla();
		$this->directorio=& $this->padre->opciones['dir_plantilla_defecto'];
		$this->modelo    = get_class($this->padre->padre);
		if (!is_array($this->contenido)) $this->contenido = array();
		$this->contenido['ZF_SITIO_WEB'] = ZF_SITIO_WEB;
		if ($this->padre->opciones['listar_tablas']){
			$this->contenido['tablas'] = $this->listar_tablas();
		} else {
			$this->contenido['tablas'] = "";
		}
		$this->contenido['nombre_modelo']= $this->modelo;
		$this->contenido['enlace']       = "index.php/".$this->modelo."/andamio/";
		@$this->contenido['mis_tablas']  = $this->padre->padre->tablas;
		$this->contenido['acciones']     = $this->obtener_acciones();
		@$this->contenido['modelos']     = $this->obtener_modelos($this->padre->padre->padre);
		@$this->contenido['mis_campos']  = str_replace(",","</li><li>",$this->padre->padre->campos);
	}
	/**
	 * Devuelve colocadas las acciones del historial almacenados en la sesión de usuario en la barra lateral de la plantilla HTML
	 *
	 * @return str
	 */
	function obtener_acciones(){
		$p = new zen_plantilla();
		$p->cargar('acciones.html',$this->directorio);
		if (!isset($_SESSION['acciones_andamio'])) $_SESSION['acciones_andamio'] = array();
		$n = count($_SESSION['acciones_andamio']);
		//Vamos quitando de la pila de acciones si hay demasiadas:
		if ($n==ZF_ANDAMIO_LIMITE_ACCIONES_MOSTRADAS){
		 array_shift($_SESSION['acciones_andamio']);
		 $n = count($_SESSION['acciones_andamio']);
		}
		$html = "";
		if ($n==0) {
			$p->pasarAplantilla(array(
			 'titulo_accion' => _("No se ha realizado ninguna acci&oacute;n en esta sesi&oacute;n del andamio"),
			 'nombre_accion' => _("Sin acciones"),
			 'imagen'        => 'e_core_warning.png',
			 'enlace'        => '#'
			));
			$html =& $p->contenido;
		} else {
			$aux = $p->contenido;
			for ($i=0; $i<$n; $i++){
			if ($p->contenido!=$aux) $p->contenido = $aux;
			$s =& $_SESSION['acciones_andamio'];
			$p->pasarAplantilla(array(
			 'titulo_accion' => $s[$i]['titulo_accion'],
			 'nombre_accion' => $s[$i]['nombre_accion'],
			 'imagen'        => $s[$i]['imagen'],
			 'enlace'        => $s[$i]['enlace']
			));
			$html .= $p->contenido;
			}
		}
		return $html;
	}
	/**
	 * Devuelve un listado de modelos y un acceso al andamio de dicho modelo
	 *
	 * @param zen $aplicacion
	 * @return str
	 */
	function obtener_modelos($aplicacion){
		$nombre = get_class($aplicacion);
		$lista  = get_class_vars($nombre);
		if (!$this->plantilla->cargar($this->directorio."modelos.html"))
		 trigger_error(_("No existe la plantilla de zen_andamio (scaffolding) para mostrar los modelos de datos"),E_USER_WARNING);
		$aux    = $this->plantilla->contenido;
		$html   = "";
		foreach ($lista as $clase => $puntero) {
			if ($aux!=$this->plantilla->contenido)
			$this->plantilla->contenido = $aux;
			if (is_a($aplicacion->$clase, 'zen_modelo_datos')){
				//<li><a title="#titulo#" href="#enlace#">#nombre#</a></li>
				$modelo = get_class($aplicacion->$clase);
				$this->plantilla->pasarAplantilla(array(
				"nombre" => $modelo,
				"titulo" => "Modelo $modelo de $nombre",
				"enlace" => "index.php/$modelo"
				));
				$html .= $this->plantilla->contenido;
			}
		}
		return $html;
	}
	/**
	 * Página principal del visualizador del andamio
	 *
	 * @param zen_basedatos $datos
	 */
	function index($datos=null){
		$c =& $this->contenido;
		$c['titulo'] = "zenphp::zen_andamio : ";
		
		switch ($datos[0]){
			default:
				$c['titulo'] .= _("Contenido para el modelo ")."'".$this->modelo."'";
				//Sacamos el listado del modelo de datos-> HTML el visualizador
				$c['contenido'] = $this->listar_campos();
				break;
			case 'crear':
				$c['titulo'] .= _('Nueva tupla para el modelo ')."'".$this->modelo."'";
				$c['contenido'] = $this->crear();
				break;
			case 'insertar':
				$c['titulo'] .= _('Creando nueva tupla para el modelo ')."'".$this->modelo."'";
				$c['contenido'] = $this->insertar();
				break;
		}
		$this->mostrar($c);
	}
	/**
	 * Devuelve por pantalla un formulario con el listado de campos de la tabla asociada
	 *
	 * @return str
	 */
	function crear(){
		$html = $this->construir_listado_campos_html();
		$resul = sprintf(_('<h1>A&ntilde;adir registro a %s</h1>'),$this->modelo).
		'<br><form name="frmCrear" id="frmCrear" action="'.$this->contenido['enlace'].'insertar/" method="post" enctype="multipart/form-data">
		<input name="id" value="##" type="hidden">';
		foreach ($this->padre->campos as $tabla => $campos) {
		  $resul .='<table id="tabla_" border="0" cellpadding="0" cellspacing="0">
		    <tbody>'.
		    $html['frm_edicion_'.$tabla].'</tbody></table>';
		}
		$resul.='<div class="lista_menu">
		 <ul>
		  <li><a href="javascript:document.frmCrear.submit();"><span><img src="zenphp/contenido/img/anadir.png">'._("A&ntilde;adir").
		  '</span></a></li>
		  <li><a href="'.$this->contenido['enlace'].'"><span><img src="zenphp/contenido/img/cancelar.png">'._("Cancelar").'</span></a></li>
		 </ul>
		</div>
		</form>';
		    
		return $resul;
		
	}
	/**
	 * Realiza la inserción de la nueva tupla gracias al formulario devuelto por $this->crear()
	 *
	 * @return str
	 */
	function insertar(){
		//Vamos a usar el padre para insertar el nuevo registro
		$id = $this->padre->padre->insertar($_REQUEST);
		if ($id){
			array_push($_SESSION['acciones_andamio'],array(
			 'titulo_accion' => _("Registro a&ntilde;adido con id:").$id,
			 'nombre_accion' => _("Inserci&oacute;n de registro"),
			 'imagen'        => 'anadir.png',
			 'enlace'        => $this->contenido['enlace'].'editar/'.$id.'/'
			));
			return '<img src="zenphp/contenido/img/ok.png">'.
			 sprintf(_("Se ha insertado correctamente el registro, <a href='%s'>continuar</a>"),$this->contenido['enlace']).
			 ', <a href="'.$this->contenido['enlace'].'editar/'.$id.'/">'.("seguir editando").'</a>';
		} else {
			return '<img src="zenphp/contenido/img/e_error.png">'.
			  _("Ha ocurrido un error al insertar el registro.<a href='javascript:window.history.back();'>Volver</a>");
		}
	}
	/**
	 * Imprime por pantalla un formulario de edición de un registro del modelo de datos.
	 * $datos es un array de entrada de los elementos de la URL
	 *
	 * @param array $datos
	 */
	function editar($datos){
		$c =& $this->contenido;
		$c['titulo'] = "zenphp::zen_andamio : "._("Editar registro");
		if (!$this->padre->padre->existe($datos[0])){
		 $c['contenido']= _("No existe el registro con ID:").intval($datos[0]);
		} else {
		 $html = $this->construir_listado_campos_html();
		 $this->padre->padre->condiciones_where = "where ".$this->padre->nombre_id."='".intval($datos[0])."' limit 1";
		 $registro = $this->padre->padre->obtener();
		 $p = new zen_plantilla();

		 foreach ($this->padre->campos as $tabla => $campos) {
		  $p->contenido .= $html['frm_edicion_'.$tabla];
		 }
		 $p->pasarAplantilla($registro[0]); //solo hay un registro
		 $c['contenido'] = '<form name="frmEditar" id="frmEditar" action="'.$this->contenido['enlace'].'actualizar/'.intval($datos[0]).'">'.
		 $p->contenido
		 .'<div class="lista_menu">
		 <ul>
		  <li><a href="javascript:document.frmEditar.submit();"><span><img src="zenphp/contenido/img/anadir.png">'._("Actualizar").'</span></a></li>
		  <li><a href="'.$this->contenido['enlace'].'"><span><img src="zenphp/contenido/img/cancelar.png">'._("Cancelar").'</span></a></li>
		 </ul>
		</div>
		</form>';
		}
		$this->mostrar($c);
	}
	/**
	 * Actualiza una tupla donde $datos[0] es el id principal y se muestra por pantalla el resultado
	 * $datos es un array de entrada de los elementos de la URL
	 * @param array $datos
	 */
	function actualizar($datos){
		$c =& $this->contenido;
		$c['titulo'] = "zenphp::zen_andamio : "._("Editar registro");
		$id = intval($datos[0]);
		if ($this->padre->padre->actualizar($_REQUEST)){
			array_push($_SESSION['acciones_andamio'],array(
			 'titulo_accion' => _("Registro modificado con id:").$id,
			 'nombre_accion' => _("Actualizaci&oacute;n de registro"),
			 'imagen'        => 'actualizado.png',
			 'enlace'        => $this->contenido['enlace'].'editar/'.$id.'/'
			));
			$c['contenido'] = '<img src="zenphp/contenido/img/ok.png">'.
			 sprintf(_("Se ha actualizado correctamente el registro, <a href='%s'>continuar</a>"),$this->contenido['enlace']).
			 ', <a href="'.$this->contenido['enlace'].'editar/'.$id.'/">'.("seguir editando").'</a>';
		} else {
			$c['contenido'] = '<img src="zenphp/contenido/img/e_error.png">'.
			  _("Ha ocurrido un error al actualizar el registro.<a href='javascript:window.history.back();'>Volver</a>");
		}
		$this->mostrar($c);
	}
	/**
	 * Muestra confirmación y borra una tupla con id=$datos[0]
	 * $datos es un array de entrada de los elementos de la URL
	 *
	 * @param array $datos
	 */
	function borrar($datos){
		$c =& $this->contenido;
		$c['titulo'] = "zenphp::zen_andamio : "._("Borrar registro");
		$id = intval($datos[0]);
		if (isset($_GET['confirmar'])){
			if ($this->padre->padre->borrar(intval($datos[0]))) {
				array_push($_SESSION['acciones_andamio'],array(
				 'titulo_accion' => _("Registro borrado con id:").$id,
				 'nombre_accion' => _("Eliminaci&oacute;n de registro"),
				 'imagen'        => 'borrar.png',
				 'enlace'        => "javascript:window.alert('"._("Registro borrado: $id")."');"
				));
				
				$c['contenido'] = '<img src="zenphp/contenido/img/ok.png">'.
				 sprintf(_("Se ha borrado correctamente el registro, <a href='%s'>continuar</a>"),$this->contenido['enlace']);
			} else {
				$c['contenido'] ='<img src="zenphp/contenido/img/e_error.png">'.
				  _("Ha ocurrido un error al borrar el registro.<a href='javascript:window.history.back();'>Volver</a>");
			}
		} else {
			//enlace de nuevo con confirmar=1
			$c['contenido'] = '<img src="zenphp/contenido/img/borrar.png" border="0" style="border:0px">'.
			_("Confirmaci&oacute;n: &iquest;Desea borrar el registro?:<br>").
			'<a href="'.$_SERVER['REQUEST_URI'].'?confirmar=1'.'">'._("S&iacute;").'</a>'.
			', <a href="'.$this->contenido['enlace'].'">'._("No").'</a>';
		}
		$this->mostrar($c);
	}
	/**
    * Construye un listado de campos de las tablas del andamio para meter el listado de tuplas
    * @return array
    */
	function construir_listado_campos_html(){
		if ( (!isset($this->padre->campos)||!is_array($this->padre->campos)) && $this->padre->opciones['recuperar_campos'])
		 $this->padre->obtener_campos();
		$html = array();
		$aux  = "";
		foreach ($this->padre->campos as $tabla => $campos) {
			$aux = '<table class="tabla_tuplas"><tr>';
			$html['sql_'.$tabla] = "";
			//De todas las tablas vamos obteniendo campos:
			$n = count($campos);
			$html['elementos_'.$tabla] = '<tr>';
			for ($k=0; $k<$n; $k++) {
				$tipo = trim(preg_replace('/(\(.*\))/',"",$campos[$k]['tipo']));
				$nombre =& $campos[$k]['nombre']; //asi es mas rapido el acceso
				switch ($tipo){
					case 'tinyint': case 'smallint': case 'mediumint':
					case 'integer': case 'int': case 'binint':
					case 'float': case 'double': case 'precision':
					case 'real': case 'decimal': case 'numeric':
					case 'year': case 'day': case 'int unsigned':
					 //Los numeros no hace falta recortarlos
					 $html['sql_'.$tabla].= $nombre;
					 $html['frm_edicion_'.$tabla].='<label for="'.$nombre.'">'.$nombre.'</label><br><input name="'.$nombre.'" id="'.$nombre.'" type="text" value="#'.$nombre.'#"><br>';
					 break;
					case 'datetime': case 'timestamp':
					case 'date': //Formateo de fecha
					 $html['sql_'.$tabla].= "DATE_FORMAT(".$nombre.",'%d/%m/%Y %h:%m:%s') as ".$nombre;
					 /** *@todo: insertar JavaScript para el calendario...*/
					 $html['frm_edicion_'.$tabla].='<label for="'.$nombre.'">'.$nombre.'</label><br><input name="'.$nombre.'" id="'.$nombre.'" type="text" value="#'.$nombre.'#"><br>';
					 break;
					case 'enum': case 'set':
					case 'char': //No se pueden recortar ni hacer mucho mas por ellos XD
					 $html['sql_'.$tabla].= $nombre;
					 $html['frm_edicion_'.$tabla].='<label for="'.$nombre.'">'.$nombre.'</label><br><input name="'.$nombre.'" id="'.$nombre.'" type="text" value="#'.$nombre.'#"><br>';
					 break;
					default: //No listado...text,blog,tinytext,varchar,etc. se recortan
					 $html['sql_'.$tabla].= 'SUBSTRING('.$nombre.',1,'.
					 	intval($this->padre->opciones['num_caracteres_recorte']).') as '.$nombre;
					 $html['frm_edicion_'.$tabla].='<label for="'.$nombre.'">'.$nombre.'</label><br><textarea name="'.$nombre.'" id="'.$nombre.'">#'.$nombre.'#</textarea><br>';
					 break;
				}
				$aux .='<th>'.$nombre/*.'('.$campos[$k]['tipo'].')*/.'</th>';
				$html['elementos_'.$tabla] .='<td>#'.$nombre.'#</td>';
				
				if ($n>($k+1)) $html['sql_'.$tabla] .= ","; //Coma para la sintaxis correcta SQL
				else $html['sql_'.$tabla] .= " from $tabla";
			}
			$html['elementos_'.$tabla].=
			"<td>".
			"<a href='".$this->contenido['enlace']."borrar/#id#/'><img src='zenphp/contenido/img/borrar.png' style='border:0'></a>".
			"<a href='".$this->contenido['enlace']."editar/#id#/'><img src='zenphp/contenido/img/editar.png' style='border:0'></a>".
			"</td></tr>\n";
			$aux .= "<td>Op.</td></tr>\n#filas_".$tabla."#\n</table><br>";
			$html['tabla_'.$tabla] .= $aux;
		}
		$html['base'] = $this->plantilla->devolver_contenido($this->directorio."indice.html");
		return $html;
	}
	/**
	 * Devuelve un listado de campos de las tablas del modelo asociado al andamio
	 *
	 * @return str
	 */
	function listar_campos(){
		$html = $this->construir_listado_campos_html();
		$p = new zen_plantilla();
		$b = new zen_plantilla(); //Base
		
		$resultado = "";
		$total = 0;
		foreach ($this->padre->campos as $tabla => $campos) {
			$b->contenido .= $html['tabla_'.$tabla]; //HTML final procesado
			$aux =& $html['elementos_'.$tabla];
			$r = $this->padre->bd->seleccion($html['sql_'.$tabla]);
			
			$total += $this->padre->bd->num_filas_resultantes($r);
			if (!$r) $b->contenido .= '<br>'._("No hay registros en la/s tabla/s ").$tabla.'<br>';
			else {
			 $filas = ""; //contenido de cada conjunto de tuplas o fila de cada tabla
			 while ($fila = $this->padre->bd->obtener_fila($r)) {
				if ($aux!=$p->contenido) $p->contenido = $aux; //Copiar contenido
				//reemplazar contenido
				$fila['id']  =& $fila[$this->padre->nombre_id]; //Id principal fundamental
				$p->pasarAplantilla($fila);
				$filas.=$p->contenido;
			 }
			 $b->reemplazar('filas_'.$tabla,$filas);
			}
			$p->contenido = $html['base'];
			$p->pasarAplantilla(array(
			 'titulo'=> _("Listado de tuplas de las tablas del modelo"),
			 'filas' => $b->contenido //Contenido de la base,las filas de datos
			));
			
		}
		/** @TODO: cambiar todo lo siguiente por una plantilla HTML */
		return  _("Total registros: ").$total.'<br>'.$p->contenido.
				'<pre><a href="'.$this->contenido['enlace'].
				'crear"><img src="zenphp/contenido/img/anadir.png" border=0 align="absmiddle" style="border:0px" title="'.
				_("Insertar una Nueva tupla en el modelo de datos").
				'">'
				._("A&ntilde;adir nuevo").
				'</a></pre>';
	}
	/**
	 * Devuelve una Lista de las tablas de la BD asociada al modelo de la aplicación
	 *
	 * @return str
	 */
	function listar_tablas(){
		$html  ="";
		if (!$this->plantilla->cargar("tablas.html",$this->directorio)){
			return _("No encuentro el fichero tablas.html para construir el listado de tablas del andamio o scaffolding de ").$this->padre->padre->tablas;
		}
		$aux    = $this->plantilla->contenido;
		$tablas = $this->padre->bd->obtener_tablas();
		$n      = count($tablas);
		$enlace = "index.php/".$this->modelo."/andamio/tabla/%s/";
		for ($u=0; $u<$n; $u++){
			if ($aux!=$this->plantilla->contenido) $this->plantilla->contenido = $aux;
			$comentario = $this->padre->bd->obtener_comentario($tablas[$u]);
			$this->plantilla->pasarAplantilla(array(
			"nombre" => $tablas[$u],
			"enlace" => sprintf($enlace,$tablas[$u]),
			"comentarios_tabla" => empty($comentario)?_("Sin comentarios"):$comentario
			));
			$html .= $this->plantilla->contenido;
		}
		return $html;
	}
	/**
	 * Realiza una búsqueda de los datos especificados en las tuplas de las 
	 * tablas asociadas al modelo y devuelve el resultado por pantalla.
	 * $datos es un array de entrada de los elementos de la URL
	 *
	 * @param array $datos
	 * @return str
	 */
	function buscar($datos=null){
		echo _("Buscando...");
		return "";
	}
	/**
	 * Muestra el contenido del procesamiento del andamio con la plantilla zenphp/contenido/plantillas/es/andamio/base_web.html
	 *
	 * @param array $contenido
	 * @return bool
	 */
	function mostrar(&$contenido){
		if (!$this->plantilla->cargar("base_web.html",$this->directorio)){
			trigger_error(sprintf(_("No se puede cargar la plantilla del andamio 'base_web.html' (scaffolding) para el idioma '%s' del directorio '%s'."),
			$this->padre->padre->padre->idioma,
			$this->directorio
			),E_USER_ERROR);
			return false;
		}
		$this->plantilla->pasarAplantilla($contenido);
		$this->plantilla->mostrar();
		return true;
	}
}

// Aún en construcción
///**
// * Clase XML para el Andamio
// *
// */
//class zen_xml_andamio extends domdocument {
//	/**
//      * Andamio asociado
//      *
//      * @var zen_andamio
//      */
//	var $padre;
//	/**
//      * Constructor del XML para el andamio
//      *
//      * @param zen_andamio $_padre
//      * @param str $fichero_xml
//      * @param int $modo
//      * @param array $error
//      * @return zen_xml_andamio
//      */
//	function zen_xml_andamio(&$_padre,$fichero_xml="",$modo=null,$error=null){
//		$this->padre =& $_padre;
//		parent::domdocument($fichero_xml,$modo,$error);
//	}
//}
?>