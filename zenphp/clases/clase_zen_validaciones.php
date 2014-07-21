<?php
/**
 * clase_zen_validaciones.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para validar la entrada de datos
 * @see zen_resultado_validacion
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/**
 * zen_validacion class (clase_zen_validacion.php, Noviembre 2007)
 * Filtrado PHP para validaciones simples, se toma por defecto la clase 'zen_resultado_validacion' para construir el resultado
 * 
 * ejemplo de uso:
 * $v = new zen_validacion();
 * $v->anadirObligatorio('dominio', 'El dominio es obligatorio')
 *        ->anadirRegla('dominio', 
 *            array('filtro' => FILTER_VALIDATE_URL, 'banderas' => array(FILTER_FLAG_HOST_REQUIRED)), 
 *            'El dominio debe ser una URL valida')
 *        ->anadirRegla('domain', 
 *            array('filtro' => FILTER_CALLBACK, 'opciones' => array($this, 'filterUniqueDomain')), 
 *            'El nombre de dominio debe ser unico en la base de datos')
 *        ->anadirRegla('enabled', FILTER_VALIDATE_BOOLEAN, 'El campo "Enabled" ha de tener un valor booleano');
 *
 *   $resultado = $v->comprobar($MIS_DATOS);//un array de datos asociativo como $_GET o $_POST ($_REQUEST)
 *   var_dump($resultado->ok());
 *   var_dump($resultado->exportar());
 *   var_dump($resultado->obtenerListaErrores());
 */
class zen_validaciones {
    /**
     * Reglas para las validaciones
     *
     * @var array
     */
    var $_reglas = array();
    /**
     * Mensajes a devolver de los resultados de validaciones
     *
     * @var array
     */
    var $_mensajes = array();
    /**
     * Campos obligatorios
     *
     * @var array
     */
    var $_obligatorios = array();
    /**
     * Comparaciones a realizar
     *
     * @var array
     */
    var $_comparaciones = array();
    /**
     * Clase para devolver resultados en el formato que sea...
     *
     * @var zen_resultado
     */
    var $_resultado;
    /**
     * Constructor
     *
     * @return zen_validaciones
     */
    function zen_validaciones($resultado='zen_resultado_validacion')
    {
    	if (!class_exists($resultado)) zen___carga_clase($resultado) or die(printf(_("No se pudo cargar la clase %s."),$resultado));
    	//Crear aqui la clase de resultado:
        $this->_resultado =& new $resultado();
    }
    
    /**
     * Inserta un campo obligatorio
     *
     * @param str $llave
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirObligatorio($llave, $mensaje)
    {
        $this->_obligatorios[$llave] = $mensaje;
        return $this;
    }
    
    /**
     * Insertar campos de comparacion. Para confirmaciones de password,por ejemplo.
     *
     * @param str $llave1
     * @param str $llave2
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirComparacion($llave1, $llave2, $mensaje)
    {
        $this->_comparaciones[] = array($llave1, $llave2, $mensaje);
        return $this;
    }
    
    /**
     * Insertar regla
     *
     * @param str $llave
     * @param array | int filtro @see filter_* functions ( http://php.net/filter )
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirRegla($llave, $filtro, $mensaje)
    {
        foreach ($this->_reglas as $indice => $pila)
            if (! array_key_exists($llave, $pila))
                return $this->_procesarAnadirRegla($llave, $filtro, $mensaje, $indice);
        
        return $this->_procesarAnadirRegla($llave, $filtro, $mensaje, null);
    }
    
    /**
     * @param str $llave
     * @param str $mensaje
     * @param int $banderas
     * @return zen_validaciones
     */
    function anadirReglaCadena($llave, $mensaje, $banderas = null)
    {
        if (null === $banderas)
            $banderas = FILTER_FLAG_STRIP_LOW;
        
        return $this->anadirRegla($llave, array('filtro' => FILTER_SANITIZE_STRING, 'banderas' => $banderas), $mensaje);
    }
    
    /**
     * Para cadenas "RAW"
     * @param str $llave
     * @param str $mensaje
     * @param int $banderas
     * @return zen_validaciones
     */
    function anadirReglaCadenaDelimitada($llave, $mensaje, $banderas = null)
    {
        return $this->anadirRegla($llave, array('filtro' => FILTER_UNSAFE_RAW, 'banderas' => $banderas), $mensaje);
    }
    
    /**
     * @param str $llave
     * @param str $mensaje
     * @param str $expresion_regular
     * @return zen_validaciones
     */
    function anadirReglaExpresionRegular($llave, $mensaje, $expresion_regular)
    {
        return $this->anadirRegla($llave, 
            array('filtro' => FILTER_VALIDATE_REGEXP, 
            	  'opciones' => array('expresion_regular' => $expresion_regular)
            ),$mensaje);
    }
    
    /**
     * @param str $llave
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirReglaCorreo($llave, $mensaje)
    {
        return $this->anadirRegla($llave, FILTER_VALIDATE_EMAIL, $mensaje);
    }
    
    /**
     * @param str $llave
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirReglaEntero($llave, $mensaje, $opciones = null)
    {
        return $this->anadirRegla($llave, array('filtro' => FILTER_VALIDATE_INT, 'opciones' => $opciones), $mensaje);
    }
    
    /**
     * @param str $llave
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirReglaFlotante($llave, $mensaje, $banderas = null)
    {
        return $this->anadirRegla($llave, array('filtro' => FILTER_VALIDATE_FLOAT, 'banderas' => $banderas), $mensaje);
    }
    
    /**
     * @param str $llave
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirReglaBooleano($llave, $mensaje)
    {
        return $this->anadirRegla($llave, FILTER_VALIDATE_BOOLEAN, $mensaje);
    }
    
    /**
     * @param str $llave
     * @param callback $funcion
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirReglaFuncion($llave, $funcion, $mensaje)
    {
        return $this->anadirRegla($llave, array('filtro' => FILTER_CALLBACK, 'opciones' => $funcion), $mensaje);
    }
    
    /**
     * @param str $llave
     * @param callback $funcion
     * @param str $mensaje
     * @return zen_validaciones
     */
    function anadirReglaFecha($llave, $mensaje)
    {
        return $this->anadirRegla($llave, array('filtro' => FILTER_CALLBACK, 'opciones' => array($this, '_validarFecha')), $mensaje);
    }
    
    /**
     * Validar $valor de la cadena como una especificacion de fecha
     *
     * @param str $valor
     * @return str o bool FALSE si falla
     */
    function _validarFecha($valor) {
        if (! ($tiempo = strtotime($valor))) return false;        
        $d = date('Y-m-d H:i:s', $tiempo);
        $datetime = explode(' ', $d);
        if ('00:00:00' == $datetime[1]){
            return $datetime[0];
        } else {
            return $d;
        }
    }
    
    /**
     * Realiza las comprobaciones de las validaciones
     *
     * @param array $entrada
     * @return zen_resultado
     */
    function comprobar($entrada)
    {
        array_walk_recursive($entrada, array($this, '_prepararEntrada'));

        $this->_resultado->restablecer();
        if ($this->_noCumpleObligatorio($entrada) || 
            $this->_noCumpleComparacion($entrada) || 
            $this->_noCumpleRegla($entrada))
        {
            ;
        }
        
        return $this->_resultado;
    }
    
    /**
     * @return zen_resultado
     */
    function resultado()
    {
        return $this->_resultado;
    }
    
    function _noCumpleObligatorio($entrada)
    {
        foreach ($this->_obligatorios as $llave => $mensaje)
            if (empty($entrada[$llave]))
                $this->_resultado->anadirError($mensaje);
        
        return ! $this->_resultado->ok();
    }
    
    function _noCumpleComparacion($entrada)
    {
        foreach ($this->_comparaciones as $compara)
            if (@$entrada[$compara[0]] != @$entrada[$compara[1]])
                $this->_resultado->anadirError($compara[2]);
        
        return ! $this->_resultado->ok();
    }
    
    function _noCumpleRegla($entrada)
    {
        $salida = array();
        foreach ($entrada as $llave => $valor)
            if (! empty($valor))
                $salida[$llave] = $valor;

        foreach ($this->_reglas as $indice => $pila)
        {
            $salida = filter_var_array($salida, array_intersect_key($pila, $salida));
            foreach ($salida as $llave => $valor)
            {
                if (empty($this->_reglas[$indice][$llave])) continue;
                
                if (FILTER_VALIDATE_BOOLEAN == $this->_reglas[$indice][$llave]['filtro'])
                {
                    $salida[$llave] = (bool) $valor;
                }
                elseif (false === $valor)
                {
                    $this->_resultado->anadirError($this->_mensajes[$indice][$llave]);
                }
            }
            
        }
        
        $salida = array_merge($entrada, $salida);
        if ($ok = $this->_resultado->ok())
            $this->_resultado->importar($salida);
        
        return ! $ok;
    }
    
    function _prepararEntrada(&$valor)
    {
        if (is_string($valor)) $valor = trim($valor);
    }
    
    /**
     * Inserta una regla
     *
     * @param str $llave
     * @param int $filtro
     * @param str $mensaje
     * @param int $indice
     * @return zen_validaciones
     */
    function _procesarAnadirRegla($llave, $filtro, $mensaje, $indice)
    {
        if (null === $indice)
        {
            $indice = count($this->_reglas);
            $this->_reglas[$indice] = array();
            $this->_mensajes[$indice] = array();
        }
        
        if (is_int($filtro))
        {//hacer los filtros uniformes
            $filtro = array('filtro' => $filtro);
        }
        
        $this->_reglas[$indice][$llave] = $filtro;
        $this->_mensajes[$indice][$llave] = $mensaje;
        return $this;
    }
}
?>