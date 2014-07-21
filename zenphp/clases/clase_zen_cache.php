<?php
/**
 * clase_zen_cache.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que contiene se ocupa de mantener la cache
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Que poco cach&eacute; intentar entrar aqui...Acceso directo no permitido'));
/** 
 * Clase Zen Cache
 * Una clase muy simple para operar con la cache
 * 
 * IDEA ORIGINAL: 
 * _______________
 * Copyright 2007 Rob Searles 
 * http://www.ibrow.com 
 * Please give me credit if you use this. Thanks 
 * _______________
 * MODIFICADA por Juan Belon @ 2007*2008
 * Licenced under the GNU Lesser General Public License (Version 3) 
 * http://www.gnu.org/licenses/lgpl.html 
 */ 
/** 
 * Establece la ruta al directorio de cache
 */ 
if (!defined('ZF_DIR_CACHE')) define('ZF_DIR_CACHE', ZF_DIR_PPAL.DIRECTORY_SEPARATOR.'cache'); 
/** 
 * Establecer la extension de los ficheros para guardar la cache
 */ 
define('ZF_EXTENSION_CACHE', 'cache'); 
/** 
 * zen_cache
 * Una clase muy simple para guardar cache de contenidos
 * 
 * Uso: 
 * 
 * $cache = new zen_cache('micache'); 
 * $contenido = $cache->obtener(); 
 * if(!$contenido) { 
 *  // obtener el contenido
 *  $contenido = zen_plantilla::cargar('fichero'); //...u otro
 *  $cache->guardar($contenido); 
 * } 
 * echo $contenido; 
 */ 
class zen_cache { 
    /**
     * Contenido a almacenar en cache.Un fichero HTML,XML,etc
     *
     * @var str
     */
    var $contenido; 
    /**
     * Identificador para el fichero de cache,un nombre
     *
     * @var str
     */
    var $id; 
    /**
     * Tiempo en el que expira la cache en horas,1h por defecto
     *
     * @var str
     */
    var $limite_horas = 1; 
    /**
     * Ruta para escribir el fichero de cache
     *
     * @var str
     */
    var $ruta_cache; 
 
    /** 
     * zen_cache::Constructor 
     * Inicializa la clase, si un se un ID fue especificado,se establece como propiedad de la clase
     * @param string $id 
     * @access protected 
     * @return zen_cache
     */ 
    function zen_cache($id = false) { 
        if($id) { 
            $this->establecer_id($id); 
        } 
    } 
    /** 
     * zen_cache::obtener_cache() 
     * Intenta obtener la version de la cache especificada por la id.
     * Si se obtiene ,se devuelve el contenido del fichero de cache como una cadena,pero 
     * si la cache a expirado o no existe el fichero, se devuelve falso.
     * as a string. If cache has expired, limpiars cache and returns false, if 
     * @param str $id
     * @param int $limite_horas
     * @return string|false
     */ 
    function obtener($id = false, $limite_horas = false){ 
        //Obtiene la ruta completa del fichero de cache:
        $this->obtener_ruta_cache($id);  //Guarda la ruta en $this->ruta_cache
        //Existe el fichero?
        if(!file_exists($this->ruta_cache)) { 
            return false; 
        } 
        //Ha expirado la cache? En caso afirmativo,se ha de limpiar y devolver false:
        if(filemtime($this->ruta_cache) < $this->obtener_expiracion($limite_horas)) { 
            $this->limpiar($id); 
            return false; 
        }
        //Si es valida, se devuelve el contenido:
        $this->contenido = file_get_contents($this->ruta_cache); 
        return $this->contenido; 
    } 
    /**      
     * Intenta guardar el contenido pasado como argumento en el fichero de cache.
     * @param str $contenido
     * @param str $id
     * @return true si todo fue correcto
     */ 
    function guardar($contenido = false, $id = false){ 
        //Con el id de cache obtiene la ruta y la guarda en $this->ruta_cache
        $this->obtener_ruta_cache($id); 
        //Establecer el contenido de la cache
        $this->establecer_contenido($contenido); 
        //Escribir el contenido en el fichero de cache
        if(!file_put_contents($this->ruta_cache, $this->contenido)) { 
            trigger_error(_('No se puede escribir contenido en la cache')); 
        } 
        return true; 
    } 
    /** 
     * zen_cache::limpiar_cache() 
     * Limpia la cache - i.e.: borra el fichero de cache 
     * @param str $id
     * @return bool
     */ 
    function limpiar($id = false){ 
        //Obtener la ruta con el identificador pasado (la guarda $this->ruta_cache)
        $this->obtener_ruta_cache($id); 
        //Devuelve el resultado de borrar el fichero
        return unlink($this->ruta_cache); 
    } 
    /** 
     * zen_cache::establecer_contenido() 
     * Establece el contenido para esta cache
     * @param string $content 
     * @access protected 
     */ 
    function establecer_contenido($contenido = false) { 
        if(!$contenido) { 
            trigger_error(_('No hay contenido para almacenar en la cache')); 
        } 
        $this->contenido = $contenido; 
    } 
    /** 
     * zen_cache::establecer_id() 
     * Establece la propiedad de ID (identificador unico) para el fichero de cache
     * @param string $id 
     * @access protected 
     */ 
    function establecer_id($id = false) { 
        if(!$id) { 
            trigger_error(_('Debes especificar un ID para el fichero de cache')); 
        } 
        $this->id = $id; 
    } 
    /** 
     * zen_cache::establecer_limite_horas() 
     * Establece el tiempo limite (en horas) para que el fichero caduque
     * @param int $limite_horas 
     * @access protected 
     */ 
    function establecer_limite_horas($limite_horas = false) { 
        if(!$limite_horas||!is_numeric($limite_horas)) { 
            trigger_error(_('Debes especificar el limite de horas como un numero'));
        } 
        $this->limite_horas = $limite_horas; 
    } 
    /** 
     * zen_cache::obtener_ruta_cache() 
     * Obtiene la ruta del fichero de cache con el $id especificado
     * @param str $id
     * @return string 
     */ 
    function obtener_ruta_cache($id = false) { 
        if($id) { 
        	$this->establecer_id($id); 
        } 
        $this->ruta_cache = ZF_DIR_CACHE.DIRECTORY_SEPARATOR.$this->id.'.'.ZF_EXTENSION_CACHE; 
        return $this->ruta_cache; 
    } 
    /** 
     * zen_cache::obtener_expiracion() 
     * Obtiene una marca de tiempo de la expiracion de la cache,i.e.:obtiene el dato tiempo en que se creo el 
     * fichero antes de que expire.Si se crea antes de que expire, es correcto.
     * 
     * @param int $limite_horas
     * @return timestamp 
     */ 
    function obtener_expiracion($limite_horas = false) { 
        //Si se ha pasado un $limite_horas entonces se establece el mismo ;)
        if($limite_horas) { 
            $this->establecer_limite_horas($limite_horas); 
        } 
        return time() - $this->limite_horas * 60 * 60; 
    }
}
?>