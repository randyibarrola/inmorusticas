<?php
/**
 * clase_zen_plantilla.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic La clase más simple que se me ha ocurrido para operar con plantillas
 */
/* +----------------------------------------------------------------------
   |                           ______          
   | _____________________________  /_________ 
   | ___  /_  _ \_  __ \__  __ \_  __ \__  __ \
   | __  /_/  __/  / / /_  /_/ /  / / /_  /_/ /
   | _____/\___//_/ /_/_  .___//_/ /_/_  .___/ 
   |                   /_/            /_/      
   | 
   | zenphp.es
   +----------------------------------------------------------------------*/   
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
class zen_plantilla {
	/**
 	 * Contenido de la pagina actual
 	 *
 	 * @var str
 	 */
	var $contenido;
	/**
	 * Si compactar es true entonces la salida se realiza compactada gracias al uso de la clase zen_compactador
	 *
	 * @var bool
	 */
	var $compactar = false;
	/**
 	 * Constructor: si se le pasa un fichero lo carga directamente
 	 *
 	 * @param str $fichero
 	 * @return plantilla
 	 */
	function zen_plantilla($fichero="",$compactar=ZF_USAR_COMPACTADOR){
		$this->compactar = $compactar;
		$this->contenido = "";
		if (!empty($fichero) && !is_a($fichero,'zen')){ //No queremos asociarla a ninguna aplicacion
			if (!$this->cargar($fichero)){
				//Error de tipo plantilla
				zen__manejador_de_errores(E_WARNING,"<img src='zenphp/contenido/img/e_plantilla.png'> &nbsp; ".
                                    sprintf(_("No se pudo cargar el fichero de plantilla %s de los directorios de plantillas<br>"),$fichero)." &middot; ".
				ZF_DIR_IDIOMA." <br> &middot; ".ZF_DIR_PLANTILLAS,'zen_php/clases/zen_clase_plantilla.php','<b>'.__LINE__.'</b>');
			}
		}
		if ($this->compactar){
			if (!zen___carga_clase('zen_compactador')){
				$this->compactar = false;
				trigger_error(_("No se pudo cargar el zen_compactador"),E_USER_WARNING);
			}
		}
	}

	/**
 	 * Carga un fichero de plantilla y lo almacena en $this->contenido y devuelve el resultado de la operacion
 	 * Si la ruta esta vacia lo buscara en los directorios de plantillas,en otro caso intenta abrirlo desde
 	 * la ruta pasada y devuelve el resultado como un booleano
 	 *
 	 * @param str $fichero
 	 * @param str $ruta
 	 * @return bool 
 	 */
	function cargar($fichero,$ruta="") {
        if (empty($ruta)){
            if (is_readable($fichero)){
             $this->contenido = file_get_contents($fichero);
             if ($this->compactar) $this->compactar_html();
             return true;
            }
            //Directorio principal de plantillas
            if (!is_readable(ZF_DIR_PLANTILLAS.$fichero)) {
                if (is_readable(ZF_DIR_IDIOMA.$fichero)){
                  $this->contenido = file_get_contents(ZF_DIR_IDIOMA.$fichero);
                } elseif(!is_readable(ZF_DIR_CONTENIDO_ZEN.$fichero)){
                    //Directorio de plantillas de zenphp, lo mÃ¡s interior: en el idioma de la web por defecto,claro
                    $f = ZF_DIR_CONTENIDO_ZEN.'plantillas'.DIRECTORY_SEPARATOR.ZF_IDIOMA_DEFECTO.DIRECTORY_SEPARATOR.$fichero;
                    if (!is_readable($f)){
                     trigger_error(_('No se puede leer el fichero ').$fichero);
                     return false;
                     } else {
                     $this->contenido = file_get_contents($f);
                     if ($this->compactar) $this->compactar_html();
                     return true;
                    }
                } else{
                    //Devolver
                    $this->contenido = file_get_contents(ZF_DIR_CONTENIDO_ZEN.$fichero);
                    if ($this->compactar) $this->compactar_html();
                    return true;
                }
            } else {
                //Devolver
                $this->contenido = file_get_contents(ZF_DIR_PLANTILLAS.$fichero);
                if ($this->compactar) $this->compactar_html();
                return true;
            }
        } else {
            if (is_readable($ruta.$fichero)){
             $this->contenido = file_get_contents($ruta.$fichero);
             if ($this->compactar) $this->compactar_html();
             return true;
            }
            //Ruta proporcionada:
            if (substr($ruta,-1,1)!=DIRECTORY_SEPARATOR) $ruta .= DIRECTORY_SEPARATOR;
            if (!is_readable($ruta.$fichero)) {
                //Error
                trigger_error(_('No se puede leer el fichero ').$fichero);
                return false;
            }
            else { //Devolver
                $this->contenido = file_get_contents($ruta.$fichero);
                if ($this->compactar) $this->compactar_html();
                return true;
            }
        }
        if ($this->compactar) $this->compactar_html();
        return true;
    }
	/**
	 * Compacta el contenido cargado para disminuir su tamaño
	 *
	 */
    function compactar_html(){
    	static $compactador;
    	$compactador = new zen_compactador(array(
  			'usar_buffer'		     => false,
  			'mostrar_buffer'		 => false,
  			'compactar_en_destructor'=> false
  		));
  		$this->contenido = $compactador->destructor($this->contenido);
    }
	/**
 	 * Reemplaza las etiquetas de la plantilla dadas por $var con $contenido en $this->contenido: la plantilla HTML
 	 *
 	 * @param str $var
 	 * @param str $contenido
 	 */
	function reemplazar($var, $contenido) {
		$this->contenido = str_replace("#$var#", $contenido, $this->contenido);
	}

	/**
   	 * Reemplaza el contenido por texto y lo muestra, borra de memoria la plantilla HTML de la clase $this->contenido
   	 *
   	 */
	function mostrar() {
		eval("?>".$this->contenido."<?");
		unset($this->contenido);
	}

	/**
	 * Reemplaza las constantes de la plantilla por los contenidos y devuelve el resultado en HTML
	 *
	 * @param str $fichero
	 * @param array $reemplazos
	 * @return str : html
	 */
	function rellena_HTML($fichero,$reemplazos=array()){
		$this->cargar($fichero);
		$this->pasarAplantilla($reemplazos);
		return $this->contenido;
	}

	/**
	 * Usando la clase plantilla pasada reemplaza los valores de la matriz del argumento
	 *
	 * @param array $array
	 * @return bool
	 */
	function pasarAplantilla($array) {
		if (!is_array($array)) return false;
		foreach ($array as $llave => $valor) {
			$this->reemplazar($llave,$valor);
		}
		return true;
	}
	/**
  * Devuelve el contenido, si se pasa un fichero se carga previamente
  *
  * @param str $f
  * @return str
  */
	function devolver_contenido($f=''){
		if (!empty($f)) $this->cargar($f);
		else $this->contenido = "";
		return $this->contenido;
	}
 /**
  * Devuelve el contenido del fichero si se pasa (sino se usa el que tenga),previamente se reemplaza $reemplazos
  * Es la versión de $this->rellena_HTML que mejora la eficiencia en espacio gracias a la referencia &$reemplazos
  * @param str $f
  * @param array $reemplazos
  * @return str
  */
	function contenido_reemplaza($f='',&$reemplazos){
		if (!empty($f)) $this->cargar($f);
		$this->pasarAplantilla($reemplazos);
		return $this->contenido;
	}
}
?>