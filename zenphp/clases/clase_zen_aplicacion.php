<?php
/**
 * clase_zen_aplicacion.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para generar aplicaciones CLIENTE
 * TODO: elaborar tareas básicas de una aplicación que se necesiten...y añadir los métodos para ello...como usar AJAX,scaffolding,etc.
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/**
 * 
 *    Los inicializadores de la clase sirven para cargar clases y añadirlas a la clase de la aplicación como una variable en la instancia
 *     de esta y asi poder realizar las llamadas pertinentes,ya que si se está usando PHP4 no se puede hacer la llamada a la carga automática
 *  de clases pues no está añadida hasta la versión 5. Es una solución intermedia que permite el control de errores por parte del usuario.
 *  Se usan metiendo el nombre de la clase (de la clase en si no el fichero aunque si se quiere puede hacerse con nombre de fichero...) 
 *  separando las clases con una coma.
 */
class zen_aplicacion extends zen {
    /**
     * Constructor
     *
     * @param str $inicializadores
     * @return zen_aplicacion
     */
    function zen_aplicacion($inicializadores=""){
        parent::zen($inicializadores);
        //Mecanismo para cargar los datos de configuración de la base de datos
        $ego = get_class($this);
        if (!zen___carga_aplicacion("$ego.config")) echo _("No se pudo cargar"); //Leer configuración
        //Intentamos conectarnos:
        if (defined($ego.'_usuario') && defined($ego.'_contrasena') &&  defined($ego.'_servidor') && defined($ego.'_bd')){
            if (!$this->bd->conectar(
                constant($ego.'_servidor'),constant($ego.'_usuario'),constant($ego.'_contrasena'),
                constant($ego.'_bd'),defined($ego.'_persistente')?constant($ego.'_persistente'):false,
                defined($ego.'_tipo')?constant($ego.'_tipo'):'mysql',
                defined($ego.'_puerto')?constant($ego.'_puerto'):false
                ))
            {
                /*echo constant($ego.'_servidor'),constant($ego.'_usuario'),constant($ego.'_contrasena'),
                constant($ego.'_servidor')," ",defined($ego.'_persistente')?constant($ego.'_persistente'):false," ",
                defined($ego.'_tipo')?constant($ego.'_tipo'):'mysql';*/
                trigger_error(_('No se pudo conectar a la base de datos,revise su configuraci&oacute;n!'));
            }
        }
    }
    
}
?>