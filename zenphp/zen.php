<?php
/**
 * zen.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1.270
 * @uses zenphp FrameWork
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Genera el sistema y define todas las constantes
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
//PARAMETROS CONFIGURABLES POR EL USUARIO [PRINCIPIANTE] SIN PELIRGRO 
/**
 * ZF_SITIO_WEB          - Sitio web donde se encuentra la aplicacion principal: http://localhost/
 */
define('ZF_SITIO_WEB', 'http://inmorusticas.local');
/**
 * ZF_NOMBRE_SITIO     - Nombre del sitio web actual
 */
define('ZF_NOMBRE_SITIO', 'Rural Via');
/**
 * ZF_CORREO_ADMIN     - Correo desde el que se envian los mails en el sistema
 */
define('ZF_CORREO_ADMIN', 'alejandro@ruralpromo.com'); //substituir
/**
 * ZF_IDIOMA_DEFECTO   - Idioma por defecto para los directorios y zenphp
 */
define('ZF_IDIOMA_DEFECTO', 'es');
/**
 * ZF_IDIOMA_LOCAL     - Idioma en el formato de gettext()
 */
define('ZF_IDIOMA_LOCAL',"es_ES");
//Establecer el idioma en el SO, ver más abajo el fichero a utilizar
setlocale(LC_ALL, ZF_IDIOMA_LOCAL);
/*
 +----------------------------------------------------------------------
 |A partir de aquí es más peligroso modificar los parámetros del sistema...: 
 |lee el manual http://csl2-zenphp.forja.rediris.es en la sección de documentos.
 +----------------------------------------------------------------------
 */
 /*
|-----------------------------------------------------------------------
| NIVEL DE REPORTE DE ERRORES -> MODO DEPURACION  y MODO GUARDAR LOGS
|-----------------------------------------------------------------------
*
| Una vez que el sitio sea online se puede pasar de todos los tipos de error a mostrar a E_WARNING|E_ERROR y ZF_MODO_DEPURACION a false
| El modo guardar log es para almacenar los errores ocurridos en tiempo de ejecución en el directorio ZF_DIR_ERROR_LOG
*/
//----------------------------------------------------------------------
define('ZF_MODO_DEPURACION', true); //<-- para mostrar errores y las consultas que fallan con detalle
define('ZF_MODO_GUARDAR_LOG', false); //<-- para guardar los errores y eventos que marque el programador en un fichero de log (ver nucleo_zen.php)
include_once("zen_nucleo.php"); //<--donde se define el núcleo del sistema
?>