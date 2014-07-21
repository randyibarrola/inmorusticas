<?php
/**
 * clase_zen_resultado_validacion.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @li zen_resultado_validacion usa @a clase_zen_validaciones
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para devolver resultados al validar la entrada de datos
 * @see zen_validaciones
 */
/* +----------------------------------------------------------------------
   | 
   |                          |          
   | _  /  _ \ __ \  __ \  __ \  __ \ 
   |   /   __/ |   | |   | | | | |   |
   | ___|\___|_|  _| .__/ _| |_| .__/ 
   |                _|          _|    
   | zenphp.es
   +----------------------------------------------------------------------*/
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/**
 * Encapsula los resultados de las validaciones de la clase zen_validaciones
 */
class zen_resultado_validacion {
    
    /**
     * Errores coleccionados
     *
     * @var array
     */
    var $_errores = array();
    
    /**
     * Datos validados/filtrados
     *
     * @var array
     */
    var $_datos;
    
    /**
     * Comprobar si hay errores
     *
     * @return bool TRUE si hay errores, FALSE en otro caso
     */
    function ok()
    {
        return (0 == count($this->_errores));
    }
    
    /**
     * Lo contrario de la funcion ok()
     */
    function hayErrores()
    {
        return (0 != count($this->_errores));
    }
    /**
     * Pone vacios los errores y los datos para volver a utilizar la clase
     *
     */
    function restablecer()
    {
        $this->_datos = null;
        $this->_errores = array();
    }
    /**
     * Inserta un error
     *
     * @param unknown_type $error
     * @return zen_resultado_validacion
     */
    function anadirError($error)
    {
        $this->_errores[] = $error;
        return $this;
    }
    /**
     * Devuelve la lista de errores
     *
     * @return array
     */
    function obtenerListaErrores()
    {
        return $this->_errores;
    }
    /**
     * Importa la lista de datos
     *
     * @param array $datos
     */
    function importar(&$datos)
    {
        $this->_datos &= $datos;
    }
    /**
     * Exporta la lista de datos
     *
     * @return array
     */
    function &exportar()
    {
        return (array) $this->_datos;
    }
}
?>