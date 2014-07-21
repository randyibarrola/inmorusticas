<?php
/**
 * clase_zen_ajax.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @uses zen_andamio
 * @see DOM_XML_PHP
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
if (defined("ZF_AJAX_NOMBRE_JS")) return true; //Ya se ha declarado la libreria
/**
 * Nombre de la variable para saber si es una llamada de zen_ajax con uso de una petición AJAX
 *
 */
define('ZF_AJAX_NOMBRE_JS','zen_ajax'); /* no lo cambies si no sabes ;)*/
/**
 * Intenta usar la función de PHP5 json_enconde, si no existe,carga la clase zen_JSON
 */
if ( !is_callable('json_encode') ) {
    zen___carga_clase('zen_JSON');
    
    function json_encode($obj)  {
        $json = new zen_JSON();
        return $json->serializar($obj);
    }
}
     

/**
 * Clase para generar AJAX de forma automática
 *
 */
class zen_ajax 
{
    /**
     *    División donde colocar la información
     *
     *    @var str
     *    @access public
     */
    var $div_destino; 
    /**
     *    True si la page was asked by an Ajax method
     *
     *    @var bool
     *    @access private
     */
    var $es_peticion_ajax;
    /**
     *    Contenidos AJAX se guardan aqui
     *
     *    @var array
     *    @access private
     */
    var $contenido;
    /**
     *    Ruta a la pagina que con todo nuestro $this->contenido
     *
     *
     *    @var array
     *    @access private
     */
    var $pagina_principal;
    /**
     *    Destinacion del buffer de salida
     *    @access private
     *    @var array str
     */
    var $_destino;
    /**
     * Informacion de la peticion
     *
     * @var array
     */
    var $peticion_ajax;
    /**
     * Constructor
     *
     * @param str $ruta
     * @return zen_ajax
     */
    function zen_ajax($ruta='.') {
        $this->ruta = $ruta;
        $this->es_peticion_ajax = isset($_REQUEST[ZF_AJAX_NOMBRE_JS]) && intval($_REQUEST[ZF_AJAX_NOMBRE_JS]) == 1;
    }
    
    /**
     *    Coloca el inicio de la página en AJAX
     *    
     *    @access public
     */
    function inicio() {
       ob_end_flush(); //terminar el primer buffer comprimido
       ob_start( array($this,'zen_manejador_pagina') ); 
    }

    /**
     *  Finalizar la página actual
     *
     *  @access public
     */
    function fin($texto="") {
        ob_end_flush();
        unset( $this->_destino[ count($this->_destino) - 1] );
 
        if (!$this->es_peticion_ajax && !$this->seIncluyePrincipal()) {
            require($this->pagina_principal);
        }
        $obj = new stdClass;
        $obj->contenido = $texto;
        $obj->destino = $this->_destino[count($this->_destino) - 1];
        $obj->antes = "";
        $obj->despues = "";

        return json_encode( $obj );
    }
    
    /** 
     *   Añadir un nuevo destino para el buffer
     *
     *    @param str $texto
     *    @access private
     */
    function poner_destino($texto) {
         $this->_destino[] = $texto;
    }
    
    /**
     *    Manejar el buffer de salida
     *
     *    @param $texto
     *    @access private
     */
    function zen_manejador_pagina($texto) {
        if (!$this->es_peticion_ajax) {         
        //Salida normal del fichero tal cual:
            $this->peticion_ajax = array();
            $this->peticion_ajax[$this->_destino[count($this->_destino)-1]] = $texto;        
            if ( !$this->seIncluyePrincipal()  ) 
                return 'nada';
            else
                return $this->peticion_ajax[$this->div_destino];        
        }
        //En otro caso vamos a devolver el objeto serializado con JSON
        $obj = new stdClass;
        $obj->contenido = $texto;
        $obj->destino = $this->_destino[count($this->_destino) - 1];
        $obj->antes = "";
        $obj->despues = "";

        return json_encode( $obj );
    }
    /**
    * @desc Mostrar el contenido con JSON y salida con echo, $antes y $despues evaluados en javascript
    * @param str $contenido
    * @param str $antes
    * @param str $despues
    * 
    */
    function mostrar($contenido,$antes="",$despues=""){
        $obj = new stdClass;
        $obj->contenido = $contenido;
        $obj->destino = $this->_destino[count($this->_destino) - 1];
        $obj->antes = $antes;
        $obj->despues = $despues;
        echo json_encode($obj);
    }
    
    /**
     *    Devuelve las inclusiones de los ficheros javascript en HTML
     *
     *    @access public.
     */
    function html_javascripts($incluir_prototype=true) {
        $html ="";
        $html =$incluir_prototype?"<script src='".ZF_SITIO_WEB."zenphp/contenido/js/zen_protoculous.js' type='text/javascript'></script>\r\n":"";
        $html.="<script src='".ZF_SITIO_WEB."zenphp/contenido/macros/zen_ajax.js' type='text/javascript'></script>\r\n";
        return $html;
    }
    
    /**    
     *    Define a new AJAX section or container
     *
     *    @param str $nombre Nombre de la seccion para ajax
     *    @param str $pagina Nombre de la contenedora de la pagina
     *    @param array  $propiedades propiedades extra en HTML para el contenedor de la <DIV>
     *    @access public
     */
    function anadir($nombre, $pagina, $propiedades='') {
        $this->contenido[$nombre] = array('defecto'=>$pagina, 'propiedades' => $propiedades);
    }
    
    /**
     *    Crear una seccion Ajax
     *
     *    @param str $nombre Nombre de la seccion
     *    @access private
     */
    function obtener_seccion_ajax($nombre) {
        if ( ! isset($this->contenido[$nombre] ) ) {
            trigger_error("No existe la seccion pedida $nombre",E_USER_WARNING);
            return false;
        }
        $actual =& $this->contenido[$nombre]; 
        $str    = "";
        $propiedades = "";
        $html   = "";
        if ( is_array($actual['propiedades']) ) {
            foreach($actual['propiedades'] as $k => $v){
                $propiedades.=" ${k}=\"$v\"";
            }
        }
        $peticion_ajax =& $this->peticion_ajax;
        $html .= "<div id=\"${nombre}\"${propiedades}>";
            if ( isset($peticion_ajax) && is_array($peticion_ajax) && isset($peticion_ajax[$nombre]) ) {
                $html .= $peticion_ajax[$nombre];
            } else {
                return $this->_ejecutar($actual['defecto']);
            }
        return $html.'</div>';
    }
    
    /**
     *    True si se incluye la página principal en get_included_files() de PHP
     *
     *    @access private
     *    @return bool
     */
    function seIncluyePrincipal() {
        // ver http://es.php.net/get_included_files
        $principal = realpath($this->pagina_principal);
        foreach( get_included_files() as $fichero) {
            if ( $principal == realpath ($fichero)) {
                return true;
            }
        }
        return false;
    }
    /**
     *    Ejecutar la función PHP o incluir el fichero $f
     *
     *    @access private
     *    @param str $f Fichero a incluir o función a llamar
     */
    function _ejecutar($f) {
        if ( is_callable($f) ) {
            return call_user_func($f);
        } else {
            if (file_exists($f))
             require $f;
            else 
             trigger_error(_("No se puede incluir el fichero o llamar a la funci&oacute;n: "). $f,E_USER_WARNING);
        }
    } 

}
?>