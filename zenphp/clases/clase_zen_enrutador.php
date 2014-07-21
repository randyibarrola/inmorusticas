<?php
/**
 * clase_zen_enrutador.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que enruta las direcciones de index.php/controlador/accion/parametros a dicho controlador->accion(parametros)
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
class zen_enrutador {
	/**
	 * Direccion base desde donde empezar a comprobar la existencia de ficheros
	 *
	 * @var str
	 */
	var $direccion;
	/**
     * Ruta actual
     *
     * @var str
     */
	var $actual;
	/**
     * Ruta final a procesar
     *
     * @var str
     */
	var $ruta;
	/**
     * Conjunto de reglas a procesar en la ruta
     * Reemplazadas por el mismo indice del array de reemplazos "-" por "_", "//" por "/",etc.
     *
     * @var array
     */
	var $reglas = array("-","//");
	/**
	 * Reemplazos del conjunto de $this->reglas
	 * Reemplazadas por el mismo indice del array de reemplazos "-" por "_", "//" por "/",etc.
	 *
	 * @var array
	 */
	var $reemplazos = array("_","/");
	/**
     * Variables a pasar por $_GET
     *
     * @var str
     */
	var $variables;
	/**
     * Dirección para la página de 404
     *
     * @var str
     */
	var $e404="404.php";
	/**
     * Clase zen asociada,el padre...
     *
     * @var zen
     */
	var $padre;
	/**
     * Constructor
     * @param zen $_padre
     * @param str $_direccion
     * @return zen_enrutador
     */
	function zen_enrutador(&$_padre,$_direccion_base="") {
		$this->padre = $_padre;
		//parse_url -- Procesa una URL y devuelve sus componentes
		$this->establecer_direccion_base($_direccion_base);
		$this->ruta = $this->obtener_ruta();
		$this->e404 = ZF_DIR_CONTENIDO_ZEN."404.php";                
		$this->actual = $this->ruta ==  "/" ?  array("") : explode("/",$this->ruta['path']);                
	}
	/**
     * Redirecciona a la página de error 404
     *
     */
	function error404($mensaje="") {
		header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
		if (file_exists($this->e404)){
			$error = str_replace("#mensaje#", $mensaje, file_get_contents($this->e404));
		} elseif (file_exists($this->direccion.$this->e404)){
			$error = str_replace("#mensaje#", $mensaje,file_get_contents($this->direccion.$this->e404));
		} else {
			$error = $mensaje;
		}
		trigger_error($error,E_USER_ERROR);
	}
	/**
     * Establece la ruta base donde buscar nuestras aplicaciones
     *
     * @param str $ruta
     */
	function establecer_direccion_base($ruta="") {
		if (!empty($ruta)){
			$ruta = trim($ruta, DIRECTORY_SEPARATOR.'\\');
			//Añadir la barra si es necesaria,al final de la cadena:
			$ruta .= !empty($ruta)&&(isset($ruta[strlen($ruta)]) && $ruta[strlen($ruta)])!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'';
			$aux_r = $this->obtener_ruta();
			//Está el directorio en la ruta?
			if (strpos($aux_r,$ruta)==0){
				//Existe el directorio?
				$directorio = str_replace($aux_r,$ruta,ZF_DIR_PPAL);
				if (!is_dir($directorio)) {
					trigger_error(_('Base de Controladores de ruta inv&aacute;lido:').' `' . $ruta. '`',E_USER_NOTICE);
				}
			}
		}
		//Define el directorio Base:
		$this->direccion = $ruta;
	}
	/**
	 * Devuelve la ruta pasada al navegador usando la función parse_url
	 *
	 * @return str
	 */
	function obtener_ruta(){
		$ruta = (isset($_SERVER['REQUEST_URI']))?$ruta = str_replace("file:/","",$_SERVER['REQUEST_URI']):"";
		@$_ruta = parse_url($ruta);
		return $_ruta['path'];
	}
	/**
	 * Delega el control de la aplicación al enrutador para que llame a la clase/función pertinente
	 *
	 * @return bool
	 */
	function delegar( ) {
		$controlador = $accion = $argumentos = null;
		// HTML por defecto ,mirar /zen.php la cte:
		$ZF_CONTROLADOR_DEFECTO = ZF_CONTROLADOR_DEFECTO;
		// Analizar ruta y obtener la acción:
		$this->obtenerControlador($controlador, $accion, $argumentos);
		if (empty($controlador) || $controlador==get_class($this->padre)) {
			//Llamada a la vista/controlador por defecto
			$controlador = $this->padre->{ZF_CONTROLADOR_DEFECTO}; //visor HTML por defecto			
		} else {
			$modelo = $controlador;
			//Está cargado el padre del controlador en memoria?
			if (!class_exists($modelo)){
				if (!zen___carga_clase($modelo)) { //Primero cargar el modelo
					if (is_callable(array($this->padre->html,$modelo))){ //Es un método de la vista de la aplicación?
						$modelo =&$this->padre; //Entrada por defecto al padre
						//Recuperar el primer argumento,puesto que no hay controlador y usamos la accion por defecto, la accion 
						//recuperada de la función obtenerControlador es el primer argumento de la URL,de ahi el uso de:
						array_unshift($argumentos,$accion);
						//El llamado controlador en realidad es la acción o método
						$accion = $controlador; 
						//Por lo tanto el controlador es en realidad html_{nombre_aplicacion}
						$controlador = get_class($this->padre);
					}
				} else {
					//Se ha cargado el padre (suele ser el modelo,la clase que contiene al controlador)? entonces cargar el controlador
					$this->padre->$modelo = new $modelo($this->padre);
				}
			}
			if ($modelo===$controlador) {
				//Si no ha cambiado,corregimos la ruta de acceso en tiempo de ejecución:
				$modelo = $this->padre->$modelo;
			}
			//Intentamos cargar el controlador por defecto: HTML
			$controlador_defecto = constant("ZF_CONTROLADOR_DEFECTO")."_".$controlador; //'html' cte Controlador por defecto (/zen.php)
			if (!class_exists($controlador_defecto)){
				if (!zen___carga_clase($controlador_defecto)){
				 $this->error404(sprintf(_('No se pudo cargar el controlador por defecto(%s): %s.'),ZF_CONTROLADOR_DEFECTO,$controlador_defecto));
				 return false;
				}
			}
			$controlador = $modelo->{ZF_CONTROLADOR_DEFECTO};
		}
		// Está la acción disponible?
		if (!is_callable(array($controlador, $accion))) {
			//Quizás sea el mismo controlador la accion del controlador por defecto HTML de la clase principal o aplicación?
			if (is_callable(array($this->padre->html,$controlador))){
				$accion = $controlador;
				$controlador = $this->padre->html;
			} else {
				//En otro caso,no cabe duda de que ha habido un error con el controlador o la accion o ambos...
				$this->error404(sprintf(_('La acci&oacute;n "%s()" del controlador "%s" no pudo ser llamada'),$accion,get_class($controlador)));
				return false;
			}
		}
		if (in_array("index",$argumentos)) array_shift($argumentos); //quitamos la accion index por defecto
		// Ejecutar la acción ,todo OK
		return $controlador->$accion($argumentos);
	}
	/**
	 * Obtiene el controlador, la acción y los argumentos necesarios para el enrutador de la aplicación
	 * Las variables se pasan por referencia
	 *
	 * @param variant $controlador
	 * @param function $accion
	 * @param array $argumentos
	 */
	function obtenerControlador(&$controlador, &$accion, &$argumentos) {
		$_ruta = (!isset($_REQUEST['ruta']) || empty($_REQUEST['ruta'])) ? $this->obtener_ruta() : zen_sanar($_REQUEST['ruta']);
		if (empty($_ruta)) {
			$_ruta = 'index';
		}
		// Vamos a recortar todas las partes de la ruta para analizarlas:
		$_ruta = trim(substr($_ruta,strlen($this->direccion),strlen($_ruta)-strlen($this->direccion)), '/\\');
		$pos   = strpos($_ruta,__FILE__);
		$_ruta = str_replace($this->reglas,$this->reemplazos,$_ruta);
		$_ruta = str_replace(__FILE__,'',substr($_ruta,$pos,strlen($_ruta)-$pos));
		$partes= explode('/', $_ruta);
		if (empty($partes[0]))
		array_shift($partes); //Elemento inicial vacio (doble//)? : //index.php/controlador/accion/id
		$controlador = array_shift($partes); //Controlador es el primer elemento
		$accion      = array_shift($partes);
		//Ninguna acción?
		if (empty($accion)) {
			$accion = 'index';
		}
		//El resto de elementos del array son argumentos
		$argumentos = $partes;
	}
}
?>