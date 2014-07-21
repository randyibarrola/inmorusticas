<?php 
/**
 * clase_zen_buffer.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que contiene la capa de abstracción del búfer de salida de PHP
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/**
 * Secuencias de escape:
 * En programación, es un conjunto de caracteres en los textos que son interpretados con algún fin.
 * Por ejemplo, en lenguaje C la secuencia de escape \n. La barra invertida \ se denomina caracter de escape, 
 * el cual indica que debe interpretarse de otra manera el carácter que le sigue a la derecha, en este caso n. 
 * El compilador interpreta a la secuencia de escape \n como un salto de línea o nueva línea (un ENTER al final de la cadena). 
 *
 */
/**
 * Comilla simple
 *
 */
define("ZF_ESCAPE_COMILLA_SIMPLE", "'"); 
/**
 * Comilla doble
 *
 */
define("ZF_ESCAPE_COMILLA_DOBLE", '"'); 
/**
 * Clase para operar con el buffer de salida de PHP (OB)
 *
 */
class zen_buffer { 
    /**
     * Manejadores
     *
     * @var array
     */
    var $manejadores = array(); 
    /**
     * Variables
     *
     * @var array
     */
    var $variables = array(); 
    /**
     * Constructor
     * Establece el manejador del búfer de salida de PHP con esta clase,de forma 
     * que ejecuta las funciones correspondientes por medio de zen_ejecutarFunciones
     *
     * @return zen_buffer
     */
    function zen_buffer() 
    { 
        ob_start( array( &$this, 'zen_ejecutarFunciones' ) ); 
    } 
    /**
     * Establece una variable como global dentro de la clase, para permitir el acceso fácil a 
     * los objetos por medio de una referencia a dicha clase con su $nombre
     *
     * @param desconocido $nombre
     */
    function guardarVariableGlobal(&$nombre) 
    { 
        array_push($this->variables, $nombre); 
    } 
     
    /**
     * Inserta un manejador (gracias a expresiones PHP) 
     * Todas las ocurrencias de %texto% se convierten en la variable que contiene la entrada
     * Toma una expresión PHP como entrada. Es evaluada con la función que la llama y devuelve un valor. (FILTRO)
     * Para acceder desde fuera se ha de añadir la función con guardarVariableGlobal
     * 
     * @param str $php_exp
     * @param str $escape
     */
    function anadirManejador($php_exp, $escape = ZF_ESCAPE_COMILLA_SIMPLE) 
    { 
        array_push($this->manejadores, array('exp' => $php_exp, 'esc' => $escape)); 
        
    } 
    /**
     * Como la función anadirManajedor pero usar array_unshift para insertar los valores al principio del array
     *
     * @param str $php_exp
     * @param str $escape
     */
    function anadirManejadorAlPrincipio($php_exp, $escape = ZF_ESCAPE_COMILLA_SIMPLE) 
    { 
        array_unshift($this->manejadores, array('exp' => $php_exp, 'esc' => $escape)); 
    } 
     
    /**
     * Ejecuta los manejadores establecidos
     *
     * @param tipo_desconocido $entrada
     * @return desconocido
     */
    function zen_ejecutarFunciones($entrada) 
    { 
        //Lo primero que hay que hacer es hacer las variables globales:
        /*for ($o = 0; $o < count($this->variables); $o++) 
        { 
            global ${$this->variables[$o]}; 
        } */
        //Después establecemos la variable $entrada para que cada una de las funciones devuelva lo que se le pasa con la entrada "escapada" por ' o " por medio de eval:
        for ($i = 0; $i < count($this->manejadores); $i++) 
        { 
            eval('$entrada = '.str_replace('%entrada%', 
            	str_replace($this->manejadores[$i]['esc'], '\\'.$this->manejadores[$i]['esc'], $entrada),
            	$this->manejadores[$i]['exp']).';'); 
        } 
        //Finalmente devolvemos la variable $entrada modificada a la función que sea que necesitara el búfer de salida 
        //(probablemente ob_end_flush() que se establece implícitamente al final del script PHP)
        return $entrada; 
    }
}; 
?>