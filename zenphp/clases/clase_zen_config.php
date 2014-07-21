<?php
/**
 * clase_config.php
 * @author Juan Belon, autor original: <jbarwick@sentienthealth.com>
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para configurar el entorno de forma dinámica con 3 clases super completas, usan ficheros .INI
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/**
 * Este fichero representa una jerarquia de objetos clase de
 * gestor de ficheros INI. Este conjunto de objetos clase representa
 * un fichero INI mediante:
 * zen_config                - Clase que carga la configuración y usa el resto
 * zen_config_ini_Comentario - Un comentario dentro de un fichero .INI
 * zen_config_ini_Valor      - Un par llave/valor representado por llave=valor
 * zen_config_ini_Seccion    - Una sección de un fichero .INI representado por [sección]
 * zen_config_ini_Archivo    - Una colección de objetos zen_config_ini_Seccion.
 * <br>
 * Como leer y parsear ficheros .INI puede llevar un tiempo de proceso considerable
 * no se debe leer y parsear un fichero cada vez que se haga una petición de una 
 * página web... PHP ofrece una solución totalmente factible: Las Sesiones.
 * Véase un ejemplo de uso de sesiones en la wikipedia de zenphp.
 * http://www.wikilearning.com/curso_de_zenphp-wkc-24717.htm
 *
 * @author Juan Belon 
 * @package zenphp
 */

//Constantes necesarias para las clases:
define('ZF_CONFIG_TERMINADOR_INI', "");
define('ZF_CONFIG_INI_LINEA',      "\n");
define('ZF_CONFIG_INI_COMENTARIO', "#");
define('ZF_CONFIG_INI_MSG_NOFICH', _("El fichero no existe"));
define('ZF_CONFIG_INI_MSG_NOESCRI',_("Error escribiendo el fichero"));
define('ZF_CONFIG_INI_MSG_NOPERMI',_("No se puede escribir el fichero"));
define('ZF_CONFIG_INI_SECCION_RAIZ',"****zen_config_raiz****");

class zen_config {	
	/**
	 * Archivo de configuración .INI
	 *
	 * @var zen_config_ini_Archivo
	 */
	var $config_ini;	
	/**
	 * Constructor de la clase de configuracion
	 *
	 * @param str $ruta_fichero_ini
	 * @return zen_config
	 */
	function zen_config($ruta_fichero_ini="",$nombre_config=""){
		if (!empty($fichero_ini)) {
			if (empty($nombre_config)) $nombre_config = substr(
			//No tiene nombre la config? se le pone la del nombre del fichero
			 basename($ruta_fichero_ini),0,(strlen($ruta_fichero_ini)-4)
			);
			$this->cargar_configuracion_fichero_ini($ruta_fichero_ini,$nombre_config);
		}
	}
	/**
	 * Carga la configuracion del fichero .INI y la guarda en una sesion para 
	 * ahorrar recursos la próxima vez que se haga una petición web desde el
	 * mismo cliente.
	 *
	 * @param str $ruta_al_fichero_ini
	 * @param str $nombre
	 */
	function cargar_configuracion_fichero_ini($ruta_al_fichero_ini,$nombre=""){
		if (empty($nombre)) $nombre = substr(
			//No tiene nombre la config? se le pone la del nombre del fichero
			 basename($ruta_al_fichero_ini),0,(strlen($ruta_al_fichero_ini)-4)
			);
		$this->config_ini =& new zen_config_ini_Archivo($ruta_al_fichero_ini);
		$this->config_ini->leer_fichero();
    	$_SESSION[$nombre]=serialize($this->config_ini);
	}
	
	/**
	 * Carga un fichero de configuracion .php
	 *
	 * @param str $nombre
	 * @return bool
	 */
	function cargar_configuracion_php($nombre){
		if (strtolower(substr($nombre,-4,4))!='.php') $nombre.= ".php";
		if (!file_exists(ZF_DIR_CONFIG.$nombre)) {
			zen__manejador_de_errores(E_USER_ERROR,
                            sprintf(_("No existe el fichero de configuraci&oacute;n: %s en el/los directorio/s de configuraci&oacute;n"),$nombre),_('Cargando configuraci&oacute;n con zen___carga_configuracion','Llamada a &eacute;sta funci&oacute;n'));
			return false;
		} else {
			require_once(ZF_DIR_CONFIG.$nombre);
			return true;
		}
	}

	function cargar_constantes_idioma_ini($ruta_al_fichero_ini){
		//Comprobar que existe antes de enviar a cargar el fichero:
		if (!file_exists($ruta_al_fichero_ini)){
			//Directorio de idioma en las plantillas?
			if (!file_exists(ZF_DIR_IDIOMA. $ruta_al_fichero_ini)){
				die(sprintf(_("No se pudo cargar las constantes de idiomas %s"),$ruta_al_fichero_ini));
			} else {
				//Cargar
				$this->cargar_configuracion_fichero_ini(ZF_DIR_IDIOMA. $ruta_al_fichero_ini);
			}
		} else {
			//Cargar
			$this->cargar_configuracion_fichero_ini($ruta_al_fichero_ini);
		}
	}
	
	/**
	 * Carga las constantes de idioma desde un fichero y devuelve el resultado de la operacion
	 *
	 * @param str $fichero
	 * @return bool
	 */
	function carga_constantes_idioma_php($nombre_fichero){
		//Contiene el ".php" al final?
		if (strtolower(substr($nombre_fichero,-4,4))!='.php') $nombre_fichero .= ".php";
		//En la carpeta de idiomas del usuario?
		if (!file_exists(ZF_DIR_IDIOMA.$nombre_fichero)) {
			//En la carpeta de zenphp de plantillas por defecto?
			if (file_exists(ZF_DIR_CONTENIDO_ZEN.DIRECTORY_SEPARATOR.$nombre_fichero)){
				//Devolver
			   require_once(ZF_DIR_CONTENIDO_ZEN.DIRECTORY_SEPARATOR.$nombre_fichero);
			   return true;
			} else {
				//Error: no existe:
			   return false;
			}
			
		} else {
			//Devolver
			require_once(ZF_DIR_IDIOMA.$nombre_fichero);
			return true;
		}
	}
}


/**
* zen_config_ini_Comentario
*
* Representa los comentarios del zen_config_ini_Archivo como un objeto.
*
* @author   Juan Belon & James Barwick <jbarwick@sentienthealth.com>
* @package  zenphp
*/
class zen_config_ini_Comentario {
    /**
     * Comentario es un vector de cadenas
     * @var array()
     */
    var $comentarios;

    /**
     * Contructor
     *
     * Contruye los comentarios pasados en la cadena $comentarios
     *
     * @param str $comentarios
     * @return zen_config_ini_Comentario
     */
    function zen_config_ini_Comentario($comentarios) {
        $this->establecer_Comentarios($comentarios);
    }

    /**
     * establecer_Comentarios
     *
     * Establece los $comentarios pasados
     *
     * @param str $comentarios
     * @access public
     */
    function establecer_Comentarios($comentarios) {
        $this->comentarios = array();
        $this->anadir_Comentarios($comentarios);
    }

    /**
     * anadir_Comentarios
     *
     * Añade comentarios la linea de array() en $comentarioss.
     *
     * @param str $comentarios
     * @access public
     */
    function anadir_Comentarios($comentarios) {
        $comentarios = ltrim($comentarios,"#,;");
        array_push($this->comentarios,$comentarios);
    }

    /**
     * crear_Comentarios
     *
     * Añade comentarios al final,los $comentarios especificados o
     * crea un nuevo objeto
     * Para la versión PHP5 es un método estático y se puede hacer:
     * zen_config_ini_Comentario::crear_Comentarios($c_obj,$comentarios);<br>
     *
     * @param  zen_config_ini_Comentario  $objetoComentarios
     * @param  str       $comentarios
     * @return zen_config_ini_Comentario Un nuevo zen_config_ini_Comentario o el mismo pasado
     * @access public
     */
    function crear_Comentarios($objetoComentarios,$comentarios) {
        if (empty($objetoComentarios)) {
            $objetoComentarios =& new zen_config_ini_Comentario($comentarios);
            return $objetoComentarios;
        }
        /*if ($objetoComentarios instanceof zen_config_ini_Comentario) {*/
        if (is_a($objetoComentarios,'zen_config_ini_Comentario')){
        	$objetoComentarios->anadir_Comentarios($comentarios);
            return $objetoComentarios;
        }
        return null;
    }

    /**
     * pasarAcadena
     *
     * Devuelve una cadena formateada representando todas las lineas de comentarios
     *
     * Los comentarios son precedidos por ZF_CONFIG_INI_COMENTARIO
     * Cada linea de comentario es finalizada con ZF_CONFIG_INI_LINEA
     *
     * @param int $marca_linea1
     * @param int $marca_linea2
     * @return str que representa los comentarios
     * @access public
     */
    function pasarAcadena($marca_linea1=0,$marca_linea2=0) {
        $spaces = str_repeat(' ',$marca_linea2);
        $s=str_repeat(' ',$marca_linea1);
        $i=0;
        foreach ($this->comentarios as $comentarios_linea) {
            if ($i==0) $i++; else $s.=$spaces;
            $s.=ZF_CONFIG_INI_COMENTARIO.$comentarios_linea.ZF_CONFIG_INI_LINEA;
        }
        return $s;
    }

}

/**
* zen_config_ini_Valor
*
* @author   Juan Belon & James Barwick
* @package  zenphp
*/
class zen_config_ini_Valor {
    /**
     * comentarios para el valor.  El valor debe empezar en la 
     * misma linea que la llave pero puede contener + lineas
     * 
     * @example :
     * millave=mivalor #comentarios
     *                 #comentarios linea 2<br>
     *                 #comentarios linea 3<br>
     *
     * @var zen_config_ini_Comentario
     * @access private
     */
    var $comentarios_seguidos;

    /**
     * Los usuarios a veces ponen comentarios junto con la llave para 
     * reconocerlas. zen_config_ini_Comentario los asocian con la llave
     * 
     * @example 
     * 
     * #comentarios linea 1<br>
     * #comentarios linea 2<br>
     * llave=valor<br>
     *
     * @var zen_config_ini_Comentario
     * @access private
     */
    var $comentarios_en_llave;

    /**
    * La LLAVE para el VALOR
    *
    * @var  str
    * @access private
    */
    var $llave;

    /**
    * El VALOR para la LLAVE
    *
    * @var  str
    * @access private
    */
    var $valor;

    /**
    * Constructor de zen_config_ini_Valor
    *
    * @param  str  $llave    Nombre del valor. Llamado LLAVE
    * @param  str  $valor    Valor para la llave
    * @param  str  $comentarios    Comentarios iniciales tras el VALOR
    * @param  mixed $padre     Opcional. zen_config_ini_Seccion [ ver secciones ]
    * @access public
    */
    function zen_config_ini_Valor($llave, $valor, $comentarios='',$padre=null)
    {
        $this->llave = $llave;
        $this->valor = $valor;
        $this->comentarios_en_llave = null;
        $this->comentarios_seguidos = null;
        if ($comentarios!='')
            $this->anadirDespuesDeComentarios($comentarios);
    } // fin constructor

    /**
     * obtenerLlave
     *
     * Obtiene la llave para este valor
     *
     * @return str
     * @access public
     */
    function obtenerLlave() {
        return $this->llave;
    }

    /**
     * establecerAntesDeComentarios
     *
     * Establece los comentarios del objeto zen_config_ini_Comentario
     * 
     * Si el parametro pasado no es un objeto zen_config_ini_Comentario
     * no hace nada
     *
     * @param zen_config_ini_Comentario $comentarioss
     * @return zen_config_ini_Comentario Los comentarios en la llave
     * @access public
     */
    function establecerAntesDeComentarios($comentarioss) {
        //if ($comentarioss instanceof zen_config_ini_Comentario)
        if (is_a($comentarioss,'zen_config_ini_Comentario'))
            $this->comentarios_en_llave = $comentarioss;

        return $this->comentarios_en_llave;
    }

    /**
     * establecerComentariosEnLlave
     *
     * Establece los comentarios en la llave
     *
     * @param str $comentarios
     * @return zen_config_ini_Comentario Comentarios en la llave
     * @access public
     */
    function establecerComentariosEnLlave($comentarios) {
        $this->comentarios_en_llave =& new zen_config_ini_Comentario($comentarios);

        return $this->comentarios_en_llave;
    }

    /**
     * obtener_Comentarios
     *
     * Devuelve los comentarios DESPUES de la entrada de la llave asociada a este objeto
     *
     * @return zen_config_ini_Comentario object
     * @access public
     */
    function obtener_Comentarios() {
        return $this->comentarios_seguidos;
    }

    /**
     * anadirAntesDeComentarios
     *
     * Añade comentarios de este valor
     *
     * @param str $comentarios
     * @return zen_config_ini_Comentario Instancia del objeto zen_config_ini_Comentario a usar o se crea uno nuevo
     * @access public
     */
    function anadirAntesDeComentarios($comentarios)
    {
        if (empty($this->comentarios_en_llave))
            $this->comentarios_en_llave =& new zen_config_ini_Comentario($comentarios);
        else
            $this->comentarios_en_llave->establecer_Comentarios($comentarios);

        return $this->comentarios_en_llave;
    }

    /**
    * anadirDespuesDeComentarios
    *
    * Añade comentarios a este item.
    *
    * @param  str $comentarios Comentarios a añadir a este valor
    * @return zen_config_ini_Comentario referencia al objeto comentarios
    * @access public
    */
    function anadirDespuesDeComentarios($comentarios)
    {
        if (empty($this->comentarios_seguidos))
            $this->comentarios_seguidos =& new zen_config_ini_Comentario($comentarios);
        else
            $this->comentarios_seguidos->anadir_Comentarios($comentarios);

        return $this->comentarios_seguidos;
    }

    /**
     * establecerDespuesDeComentarios
     *
     * Establece los $comentarios después de la llave a los comentarios
     *
     * @param str $comentarios
     * @return zen_config_ini_Comentario
     * @access public
     */
    function establecerDespuesDeComentarios($comentarios) {
        $this->comentarios_seguidos =& new zen_config_ini_Comentario($comentarios);

        return $this->comentarios_seguidos;
    }

    /**
    * establecerValor
    *
    * Establece el valor para esta llave
    *
    * @param str $valor
    * @access public
    */
    function establecerValor($valor)
    {
        $this->valor = $valor;
    }

    /**
    * obtener_Valor
    *
    * Devuelve el valor para la llave
    *
    * @return str valor de esta llave
    * @access public
    */
    function obtener_Valor()
    {
        return $this->valor;
    }

    /**
    * pasarAcadena
    *
    * Formatea el VALOR.
    * 
    * Además contiene los comentarios
    *
    * @return str cadena que representa el VALOR con sus comentarios
    * @access public
    */
    function pasarAcadena()
    {
        $s="";
        if (!empty($this->comentarios_en_llave))
            $s.=$this->comentarios_en_llave->pasarAcadena();

        $v=$this->llave."=".$this->valor.ZF_CONFIG_TERMINADOR_INI;
        $s.=$v;
        if (!empty($this->comentarios_seguidos)) {
            $p = strlen($v)+1;
            $s.=$this->comentarios_seguidos->pasarAcadena(1,$p);
        }
        else
            $s.=ZF_CONFIG_INI_LINEA;

        return $s;
    }
}

/**
* zen_config_ini_Seccion
*
* @author Juan Belon & James Barwick <jbarwick@sentienthealth.com
* @package  zen_config_ini_Archivo
*/
class zen_config_ini_Seccion {
    /**
    * Nombre del objeto Contenedor
    * @var  str
    */
    var $llave;
    /**
    * Un vector de valores indexado por el valor de la LLAVE
    * @var  str
    */
    var $valor;
    /**
     * comentarios para esta seccion
     * @var zen_config_ini_Comentario
     */
    var $comentarios;
    /**
    * Constructor
    *
    * @param  mixed   $padre  zen_config_ini_Archivo 
    * @param  str  $llave     Nombre de la Sección INI
    * @param  zen_config_ini_Comentario $comentarios
    * @access public
    */
    function zen_config_ini_Seccion($llave, $comentarios = '',$padre=null)
    {
        $this->llave = $llave;
        $this->valor = array(); //Valores de los atributos
        $this->comentarios = null;
        if ($comentarios=='')
            $this->anadir_Comentarios($comentarios);
    } // fin del constructor

    /**
    * insertarItem
    *
    * Inserta un item en ésta sección
    *
    * @param  str   $llave     la llave para el valor
    * @param  str   $valor     el valor especificado
    * @param  str   $comentarios   Opcional comentarios
    * @return zen_config_ini_Valor Valor modificado o creado
    * @access public
    */
    function insertarItem($llave,$valor,$comentarios='')
    {
        $llave = strtolower($llave);

        if (isset($this->valor[$llave])) {
            $objetoValor = $this->valor[$llave];
            $objetoValor->establecerValor($valor);
            $objetoValor->establecer_Comentarios($comentarios);
        }
        else {
            $objetoValor =& new zen_config_ini_Valor($llave,$valor,$comentarios);
            $this->valor[$llave] =& $objetoValor;
        }

        return $objetoValor;
    }

    /**
    * establecer_Comentarios
    *
    * Establece el objeto de comentarios en esta sección con los $comentarios pasados
    *
    * @param zen_config_ini_Comentario $comentarios Objeto zen_config_ini_Comentario
    * @access public
    */
    function establecer_Comentarios($comentarios) {
        //if ($comentarios instanceof zen_config_ini_Comentario)
        if (is_a($comentarios,'zen_config_ini_Comentario'))
            $this->comentarios = $comentarios;
    }

    /**
    * anadir_Comentarios
    *
    * Insertar comentarios en este item.
    * Método auxiliar para la función crearItem
    *
    * @param str $comentarios Comentarios para esta sección
    * @return zen_config_ini_Comentario Objeto comentarios creado
    * @access public
    */
    function anadir_Comentarios($comentarios)
    {
        if (empty($this->comentarios))
                $this->comentarios =& new zen_config_ini_Comentario($comentarios);
        else
                $this->comentarios->anadir_Comentarios($comentarios);

        return $this->comentarios;
    }

    /**
     * insertarValor
     *
     * Inserta el valor a la sección
     *
     * @param zen_config_ini_Valor $valor Valor a insertar a esta sección
     * @access public
     */
    function insertarValor($valor) {
        //if ($valor instanceof zen_config_ini_Valor) {
        if (is_a($valor,'zen_config_ini_Valor')){
            $llave = strtolower($valor->obtenerLlave());
            $this->valor[$llave]=$valor;
        }
    }

    /**
    * obtenerItem
    *
    * Obtiene un item de su llave
    * @example 
    * $objeto_valor = $seccion->obtenerItem('llave');
    * $valor = $seccion->obtenerItem('llave')->obtenerValor();
    * $valor = $fichero_ini->obtenerSeccion('seccion')->obtenerItem('llave')->obtenerValor();
    *
    * @param  str $llave La llave del item en esta sección a devolver
    * @return zen_config_ini_Valor El valor referencia por la $llave
    * @access public
    */
    function obtenerItem($llave)
    {
        $llave = strtolower($llave);

        if (IsSet($this->valor[$llave]))
            return $this->valor[$llave];

        return null;
    }

    /**
     * obtenerValores
     *
     * Devuelve los valores de esta sección con un array asociativo indexado por llave
     * @example $valores = array($llave => new zen_config_ini_Valor($llave,$valor));
     *
     * @return array() de objetos zen_config_ini_Valor en un array asociativo indexado por llave
     * @access public
     */
    function obtenerValores() {
        return $this->valor;
    }

    /**
    * obtenerLlave
    *
    * Obtiene el Nombre del esta sección
    *
    * @return str nombre del item
    * @access public
    */
    function obtenerLlave()
    {
        return $this->llave;
    }

    /**
    * pasarAcadena
    *
    * Devuelve una cadena representando la sección del fichero .INI
    *
    * @return str sección Formateada de un fichero INI
    * @access public
    */
    function pasarAcadena()
    {
        $s="";
        if (!empty($this->comentarios))
            $s.=$this->comentarios->pasarAcadena();
        if ($this->llave!=ZF_CONFIG_INI_SECCION_RAIZ)
            $s.="[".$this->llave."]".ZF_CONFIG_INI_LINEA;
        foreach ($this->valor as $valor)
            $s.=$valor->pasarAcadena();
        return $s;
    }
}

/**
* zen_config_ini_Archivo
*
* Esta clase permite parsear y editar ficheros .INI
*
* @author   Juan Belón & James Barwick
* @package  zen_config_ini_Archivo
*/
class zen_config_ini_Archivo {

    /**
    * Nombre del fichero para lectura/escritura
    *
    * @var str
    * @access private
    */
    var $fichero;

    /**
    * zen_config_ini_Seccion : lista.  Array asociativo indexado por
    * los nombres de las secciones.
    * 
    * @example:
    * 
    * $secciones = array($llave => new zen_config_ini_Seccion($llave,$valor);
    *
    * @var array() de objetos zen_config_ini_Seccion
    * @access private
    */
    var $secciones;

    /**
    * Constructor
    *
    * Crea un contenedor para los contenidos de zen_config_ini_Archivo
    *
    * @param str $fichero
    * @access public
    */
    function zen_config_ini_Archivo($fichero)
    {
        $this->fichero = $fichero;
        $this->secciones = array();
    }

    /**
    * obtenerSecciones
    *
    * Obtiene una lista de secciones. Siempre hay al menos una seccion con el nombre
    * ZF_CONFIG_INI_SECCION_RAIZ. Ésta sección debe estar vacia.
    *
    * @return array() Un vector asociativo de secciones del fichero .INI
    * @access public
    */
    function obtenerSecciones() {
        return $this->secciones;
    }

    /**
     * obtenerSeccion
     *
     * Devuelve el objeto zen_config_ini_Seccion de sección
     *
     * @param str $seccion Nombre o llave de la seccion a devolver
     * @return zen_config_ini_Seccion
     * @access public
     */
    function obtenerSeccion($seccion) {
        $seccion = strtolower($seccion);

        if (IsSet($this->secciones[$seccion])) {
            return $this->secciones[$seccion];
        }
        return null;
    }

    /**
     * obtenerItem
     *
     * Devuelve el valor de la sección, un valor vacio '' 
     * si no es una sección
     *
     * @param str $seccion  nombre de la sección de la que se devuelve el valor
     * @param str $llave    nombre del valor a devolver
     * @return zen_config_ini_Valor    valor identificado por el nombre pasado en $llave
     * @access public
     */
    function obtenerItem($seccion,$llave) {
        $seccion_Object = obtenerSeccion($seccion);

        if (empty($seccion_Object))
            return null;

        return $seccion_Object->obtenerItem($llave);
    }

    /**
     * obtenerValorItem
     *
     * Obtiene el valor del item de la sección especificada
     *
     * @param str $seccion Nombre de Seccion en el fichero.INI
     * @param str $llave el nombre de la llave
     * @return str valor de la llave
     * @access public
     */
    function obtenerValorItem($seccion,$llave) {
        $valor_object = obtenerItem($seccion,$llave);

        if (empty($valor_object))
            return '';

        return $valor_object->obtenerValor();
    }

    /**
    * leer_fichero
    *
    * Parsea los datos del fichero pasado en el constructor
    * @return str representa un código de error si está vacio 
    * el nombre de fichero o vacio si no hay error
    * @access public
    */
    function leer_fichero() {

        if (!file_exists($this->fichero))
            return ZF_CONFIG_INI_MSG_NOFICH.$this->fichero;

        $lineas = file($this->fichero);

        $this->parsear($lineas);

        return "";
    }

    /**
     * escribir_fichero
     *
     * Escribe el contenido del fichero.INI a disco
     *
     * @return str representando un código de error o vacio si no hay error
     * @access public
     */
    function escribir_fichero() {
        $result = "";

        if (!is_writable($this->fichero))
            return ZF_CONFIG_INI_MSG_NOESCRI.$this->fichero;

        $fl=fopen($this->fichero,"w");
        if (fputs($fl,$this->pasarAcadena())===false) {
            $result = ZF_CONFIG_INI_MSG_NOPERMI.$this->fichero;
        }
        fclose($fl);

        return $result;
    }

    /**
     * crearSeccion
     *
     * Añade la sección o reemplaza la sección especificada
     *
     * @param str $llave
     * @return zen_config_ini_Seccion
     * @access public
     */
    function crearSeccion($llave) {
        $llave = trim(strtolower($llave));
        $s =& new zen_config_ini_Seccion($llave);
        $this->secciones[$llave] = $s;

        return $s;
    }

    /**
     * parsear
     *
     * Parsea un array() de Lineas de un fichero INI
     *
     * @access public
     * @param str array() $lineas
     */
    function parsear($lineas) {
        $this->secciones = array();

        $seccionActual = $this->crearSeccion(ZF_CONFIG_INI_SECCION_RAIZ);
        $valorActual   = null;
        $comentariosActuales = null;

        foreach ($lineas as $linea) {

            if (($linea=='')||($linea[0]=='\t')||($linea[0]==' '))
               $empiezaConEspacios=true;
            else
               $empiezaConEspacios=false;

            $linea=trim($linea,"\x00..\x20");

            if ($linea=='') {
                $comentariosActuales=zen_config_ini_Comentario::crear_Comentarios($comentariosActuales,$linea);
                continue;
            }

            if ($linea[0]==ZF_CONFIG_INI_COMENTARIO) {
                if ($empiezaConEspacios) {
                    if (!empty($valorActual)) {
                        $valorActual->anadirDespuesDeComentarios($linea);
                        $comentariosActuales=null;
                        continue;
                    }
                }
                $comentariosActuales=zen_config_ini_Comentario::crear_Comentarios($comentariosActuales,$linea);
                continue;
            }

            // ¿Somos una sección?, si empieza con "[" se asume que asi es...y,
            // que ha de terminar con el caracter "]".  Resultado, corregir las cabeceras 
            // de sección erróneas del fichero .INI
            if ($linea[0]=='[') {
                $l=strlen(($linea))-1;
                if ($linea[$l]==']') $l--;
                $llave = substr($linea,1,$l);

                $seccionActual=$this->crearSeccion($llave);
                $seccionActual->establecer_Comentarios($comentariosActuales);
                $comentariosActuales=null;
                continue;
            }

            // Comprobar la llave
            $i=strpos($linea,'=');
            if ($i==0) {
                $comentariosActuales=zen_config_ini_Comentario::crear_Comentarios($comentariosActuales,$linea);
                continue;
            }

            $llave=trim(substr($linea,0,$i));
            $tam_linea=strlen($linea);
            $en_comillas=false; //Se encuentra entre comillas?
            $valor_inicial = $i+1;
            $valor_final = $valor_inicial;
            while ($valor_final<$tam_linea)
            {
                $c=$linea[$valor_final];
                if ($c=='"')
                {
                    if (!$en_comillas)
                        $en_comillas=true;
                    else
                        $en_comillas=false;
                }
                if (!$en_comillas) {
                    if (($c==';')||($c=='#'))
                      break;
                }
                $valor_final++;
            }

            $valor=substr($linea,$valor_inicial,$valor_final-$valor_inicial);

            if ($valor_final<$tam_linea)
                $comentarios=ltrim(substr($linea,$valor_final+1),"#,\x20");
            else
                $comentarios="";

            $valorActual=$seccionActual->insertarItem($llave,$valor,$comentarios);

            if (!empty($comentariosActuales)) {
                $valorActual->establecerAntesDeComentarios($comentariosActuales);
                $comentariosActuales=null;
            }
        }
    }

    /**
    * pasarAcadena
    *
    * Devuelve una cadena formateada del fichero
    *
    * @return str
    * @access public
    */
    function pasarAcadena()
    {
        $s="";
        foreach ($this->secciones as $seccion) {
                $s.=$seccion->pasarAcadena();
        }
        return $s;
    }
}
?>