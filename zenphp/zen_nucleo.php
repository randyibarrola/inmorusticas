<?php
/* +----------------PARAMETROS DEL SISTEMA------------------------------
 + Lo primero es iniciar una sesión:
 */
session_start();
/*
| Ahora queremos que guarde todo en el búfer ya que usamos plantillas 
| pero guardaremos nuestro búfer comprimido para que sea más eficiente:
*/
if (function_exists('zlib_get_coding_type')) {
    ob_start("ob_gzhandler");
    define('ZF_MANEJADOR_BUFFER', 'ob_gzhandler');
    header('Accept-Encoding: gzip, deflate');
    header('Content-Encoding: gzip');
}
// calcula un offset de 24 horas y la cadena en GMT (no localtime):
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 86400) . " GMT"); //(3600 * 24)=86400
//ultima modificacion de hoy
header("Last-Modified: " . gmdate('D, d M Y H:i:s', time() ) . ' GMT');
//colocar la caché
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); 
//----------------------------------------------------------------------
//---------- A PARTIR DE AQUI PODEMOS MOSTRAR INFORMACIÓN DE SALIDA -
//----------------------------------------------------------------------
if (ZF_MODO_DEPURACION)
    error_reporting(
        E_WARNING | E_ERROR | E_USER_ERROR | E_USER_NOTICE | E_USER_WARNING | E_CORE_ERROR | E_CORE_WARNING | E_PARSE);
else
    error_reporting(E_WARNING | E_ERROR | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
/*
|---------------------------------------------------------------
| DIRECTORIO PARA EL FRAMEWORK ZEN PHP Y LAS PLANTILLAS :
|---------------------------------------------------------------
*
| Nota: Nombre del almacén del contenido multimedia sin / final
*
*/
define('ZF_CARPETA_PLANTILLAS', "media" . DIRECTORY_SEPARATOR . "plantillas");
/* _______________ PARAMETROS DEL SISTEMA [FIN] ________________ */
/*
|---------------------------------------------------------------
| CONFIGURANDO LA RUTA DESDE DEL SERVIDOR AL SISTEMA
|---------------------------------------------------------------
*
*/
$cwd = getcwd();
/**
 | @const ZF_DIR_PPAL_ZEN - Directorio para almacenar todo el contenido del framework zenphp
 */
define('ZF_DIR_PPAL_ZEN',dirname(__FILE__).DIRECTORY_SEPARATOR);
//Ruta al fichero de traducciones de zenphp/contenido/idiomas/es_ES.mo:
if (function_exists("bindtextdomain")){
 bindtextdomain(ZF_IDIOMA_LOCAL, ZF_DIR_PPAL_ZEN."contenido".DIRECTORY_SEPARATOR."idiomas");
 //Función para seleccionar el dominio de las constantes de idiomas a dicho fichero:
 textdomain(ZF_IDIOMA_LOCAL);
} else {
 /** @TODO: logear el error --> falta la compilación de PHP con la opción gettext activada! */
 function _($v){return $v;}
}
if (!chdir(ZF_DIR_PPAL_ZEN)) die(_("No puedo leer el directorio de zenphp"));
//Ir al directorio anterior para tomar la ruta de /zenphp/
if (!chdir("..")) die(_("zenphp no puede leer el directorio donde reside."));
/**
 | @const ZF_DIR_ZENPHP - Directorio para almacenar todo el contenido del framework zenphp
 */
define('ZF_DIR_PPAL', getcwd().DIRECTORY_SEPARATOR);
//Volver al directorio donde estábamos
chdir($cwd);
/**
 | ZF_DIR_ZENPHP     - Directorio para almacenar todo el contenido del framework zenphp
 */
//Ahora comprobaremos si es la ruta real y no un enlace u otro tipo:
if (function_exists('realpath') AND @realpath(ZF_DIR_ZENPHP) !== FALSE){
 $carpeta_zen=str_replace("\\", DIRECTORY_SEPARATOR, realpath($carpeta_zen));
}
/**
 * Historia zen para amenizar el trabajo:
 * Digen dijo a su maestro Nyojo: "Abandoné mi cuerpo y mi espíritu." 
 * Esto significa que a través de zazen, 
 * uno puede emanciparse de la conciencia del pasado y que se vuelve,
 * en cuerpo y en espíritu a la auténtica conciencia de antes de la existencia humana. 
 * La conciencia del pasado ya no es un problema, su cuerpo y su espíritu anteriores se resuelven en zazen. 
 * Ud.crea su verdadera vida, en donde la sabiduria se engendra naturalmente.:)"
|---------------------------------------------------------------
| CONSTANTES PARA LAS APLICACIONES :: TODAS EMPIEZAN POR ZF (de ZenFrameWork)
|---------------------------------------------------------------
*
| ZF_PREFIJO_CLASE    - Nombre dado a las clases en singular : clase
| ZF_PREFIJO_CLASES   - Nombre dado a las clases en plural   : clases
| ZF_DIR_CONFIG       - Directorio de los ficheros de configuracion
| ZF_DIR_CLASES       - Directorio principal de los ficheros de clases del __SISTEMA__! (todos los ficheros de clases han de llamarse clase_zen_NOMBRECLASE.php)
| ZF_DIR_LIBRERIAS    - Directorio principal de los ficheros de clases (todos los ficheros de clases han de llamarse clase_NOMBRECLASE.php), son externos al sistema pero se usan dentro de el...
| ZF_DIR_JSCRIPT      - Directorio de los ficheros javascript
| ZF_DIR_FUNCIONES    - Directorio de los ficheros de funciones del sistema zen php
| ZF_DIR_APLICACIONES - Directorio de los ficheros de aplicaciones del usuario que use el SDK
| ZF_DIR_PLANTILLAS   - Directorio de los ficheros de plantillas del usuario que lo use ;-)
| ZF_DIR_CONTENIDO_ZEN- Directorio de los ficheros de plantillas de zenphp
| ZF_DIR_FUENTES      - Directorio para guardar las fuentes true type con las que operan en las imagenes y otros elementos...
| ZF_DIR_MEDIA        - Directorio para guardar todo el multimedia, imágenes,idiomas,javascripts,etc. propios de CADA PROYECTO...
| ZF_DIR_IDIOMAS      - Directorio de idiomas dentro de lenguajes para almacenar las constantes ajenas al núcleo de zenphp
| ZF_PALABRA_SECRETA  - Constante para generar partes privadas como sesiones seguras y otras...
| ZF_IDIOMAS          - Idiomas de la web
| ZF_IDIOMA_COOKIE    - Constante para utilizar como nombre de variable de cookie de sesion para guardar el idioma utilizado en la web
| ZF_VERSION          - Version de Zen PHP Framework :: llevando la meditacion al codigo ;-)
| ZF_SEGURO_ZEN       - Constante para asegurarnos que el sistema ha sido cargado desde el principio correctamente
| ZF_DIR_ERROR_LOG    - Directorio para guardar el log de los errores ocurridos en tiempo de ejecución de zenphp
| ZF_DIR_CACHE        - Directorio para guardar el contenido de la caché de zenphp
| ZF_USAR_ENRUTADOR   - Usar un manejador de ruta que ejecuta los controladores de las aplicaciones...
| ZF_CONTROLADOR_DEFECTO - Usar un controlador por defecto, en paginas web suele ser HTML
| ZF_USAR_COMPACTADOR - Usar el compactador reduce el tamaño de los ficheros al cargarlos con la clase zen_plantilla
*/
define('ZF_PREFIJO_CLASE', 'clase');
define('ZF_PREFIJO_CLASES', 'clases');
/**
* @desc ZF_PREFIJO_CLASE    - Nombre dado a las clases en singular : clase
*/
define('ZF_DIR_CONFIG', ZF_DIR_PPAL_ZEN . 'config' . DIRECTORY_SEPARATOR);
define('ZF_DIR_CLASES', ZF_DIR_PPAL_ZEN . ZF_PREFIJO_CLASES . DIRECTORY_SEPARATOR);
define('ZF_DIR_LIBRERIAS', ZF_DIR_PPAL_ZEN . 'librerias' . DIRECTORY_SEPARATOR);
define('ZF_DIR_JSCRIPT', ZF_DIR_PPAL_ZEN . 'js' . DIRECTORY_SEPARATOR);
define('ZF_DIR_FUNCIONES', ZF_DIR_PPAL_ZEN . 'funciones' . DIRECTORY_SEPARATOR);
define('ZF_DIR_APLICACIONES', ZF_DIR_PPAL . 'aplicaciones' . DIRECTORY_SEPARATOR);
define('ZF_DIR_PLANTILLAS', ZF_DIR_PPAL . ZF_CARPETA_PLANTILLAS . DIRECTORY_SEPARATOR);
define('ZF_DIR_CONTENIDO_ZEN', ZF_DIR_PPAL_ZEN . 'contenido' . DIRECTORY_SEPARATOR);
define('ZF_DIR_FUENTES', ZF_DIR_PPAL_ZEN . 'fuentes' . DIRECTORY_SEPARATOR);
define('ZF_DIR_MEDIA', ZF_DIR_PPAL . 'media' . DIRECTORY_SEPARATOR);
define('ZF_DIR_IDIOMAS', ZF_DIR_MEDIA . 'idiomas' . DIRECTORY_SEPARATOR);
define('ZF_PALABRA_SECRETA', 'ABANDONAR_');
define('ZF_IDIOMAS', 'es,en,de');
define('ZF_IDIOMA_COOKIE', 'len');
define('ZF_VERSION', '0.1.1.270');
define('ZF_SEGURO_ZEN', true);
define('ZF_DIR_ERROR_LOG', ZF_DIR_PPAL_ZEN . 'error_log' . DIRECTORY_SEPARATOR);
define('ZF_DIR_CACHE', ZF_DIR_PPAL.'media'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR);  
define('ZF_USAR_ENRUTADOR', true);
define('ZF_CONTROLADOR_DEFECTO', 'html');
define('ZF_USAR_COMPACTADOR',false);
/*
 Al ejecutarse la clase principal aparecen nuevas constantes que no están en este fichero sino que 
 son definidas dinámicamente desde el constructor de la clase principal del SDK: zen() en clases/clase_zen.php
 | ZF_DIR_IDIOMA      - Directorio del idioma para la configuracion de ejecucion actual
 | ZF_DIR_PLAN_IDI    - Directorio de las plantillas para la configuracion de ejecucion actual
 | por eso hace falta definir este nombre del directorio de plantillas para el idioma en ejecucion actual:
*/
/**
 | ZF_NOM_DIR_PLAN_IDI- Constante: nombre del directorio de plantillas para el idioma en ejecucion actual (sin /)
 */
define('ZF_NOMBRE_DIR_PL_IDI', 'plantillas');
//Ahora hacemos comprobaciones de existencia del directorio de aplicaciones:
if (!is_dir(ZF_DIR_APLICACIONES))
    die(printf(_('El directorio de aplicaciones %s no existe.'), ZF_DIR_APLICACIONES));
/*
|---------------------------------------------------------------
| PARA VERSIONES DE PHP ANTIGUAS :: DEFINIR E_STRICT
|---------------------------------------------------------------
*
| Definir la constante para que no de error.
*
*/
if (!defined('E_STRICT'))
    {
    define('E_STRICT', 2048);
    }
// Sin magic quotes mejor :D usamos addslashes() y otras funciones...
set_magic_quotes_runtime(0);
//Cargar las funciones del núcleo de zenphp,para poder realizar operaciones más "interesantes":
require_once(ZF_DIR_FUNCIONES . 'zen_nucleo.php');
//Cargar las funciones generales,entre ellas la de sanar (para ejecutar comandos shell...quitar HTML, comillas)...por lo que quitamos "magic quotes"
zen___carga_funciones('zen_general');
//Por defecto cargaremos las funciones HTML y de fechas para representar la información
zen___carga_funciones('zen_html');
//Fechas:
zen___carga_funciones('zen_fechas');
/*
 * ------------------------------------------------------
 * Además, ahora podemos definir el manejador de errores:
 * ------------------------------------------------------
 */
set_error_handler('zen__manejador_de_errores');
//Comprobar que se está haciendo la salida del búfer:
if (!defined('ZF_MANEJADOR_BUFFER'))
    {
    zen___carga_clase('zen_buffer');
    define('ZF_MANEJADOR_BUFFER', 'zen_buffer');
    }
/**
 * NOTAS:
 * - El resto de configuracion se encuentra en zenphp/configuracion
 * - La carpeta de plantillas debe de estar al mismo nivel que /zenphp/ y ha de contener plantillas generales asi como para cada idioma:
 *   -> es  //carpeta para guardar constantes del idioma español
 *      ->  plantillas // para guardar ficheros .html de las plantillas españolas
 *   -> en  //directorio para guarda ctes. en ingles
 *      ->   plantillas //plantillas en ingles
 * ...etc...
 *   -> general // donde se buscaran las plantillas en el caso de que no se encuentren en los idiomas
 * - Recordar no ponerle el mismo nombre a una plantilla o directorio de plantillas dentro de idiomas y general...se coge primero la general ;-)
**/
//Preparar el sistema
zen___carga_clase ('zen');
?>