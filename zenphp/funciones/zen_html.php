<?php
/**
 * funciones/zen_html.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Conjunto de funciones para operar/construir/editar HTML usando cadenas
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_("No puedes ver este HTML,acceso directo no permitido"));
/**
 * Escribe los <scripts> para realizar validaciones en formularios,se necesita el $id_formulario y las $opciones_validacion, para conocerlas ver el manual,
 * ejemplo:{onFormValidate : tuFuncion_JavaScript, onElementValidate : tuFuncion_JavaScript}. 
 *
 * @param str||array('str','str',...,'str') $id_formulario
 * @param str $idioma el idioma para los mensajes de las validaciones
 * @param str $ruta directorio hasta el contenido de zenphp : zenphp/contenido/
 * @param bool $incluir_prototype
 * @param array({'str','str',...,'str'},{'str','str',...,'str'}) $opciones_validacion
 * @param str $funcion_javascript
 * @return str:html 
 */
function zen_validaciones_js($id_formularios,$idioma=ZF_IDIOMA_DEFECTO,$ruta="zenphp/contenido/",
        $incluir_prototype=true,$opciones_validacion=array("{immediate : true,useTitles : true}"),
        $funcion_javascript='zen_crear_validaciones')
{
    if (empty($opciones_validacion)) $opciones_validacion = array("{}");
    $html = '<link href="'.$ruta.'css/zen_validaciones.css" type="text/css" rel="stylesheet"/>';
    $html .= $incluir_prototype?'<script src="'.$ruta.'js/zen_protoculous.js" type="text/javascript"></script>':'';
    $html .= '<script src="'.$ruta.'js/'.$idioma.'/zen_validaciones.js" type="text/javascript"></script>';
    $html .= '    
        <script type="text/javascript">
        //Cada validacion con su variable:
        ';
    $forms = split(",",$id_formularios);
    $n = count($forms); 
    for ($i=0; $i<$n; $i++)
        $html .= 'var valida_'.$forms[$i].';
        ';
    $html .= '
        function '.$funcion_javascript.'(){';
            
    
    $m = count($opciones_validacion);
    for ($i=0; $i<$n; $i++){
          $html .= '
              valida_'.$forms[$i].' = new Validation(\''.$forms[$i].'\',';
          $html .= $n==$m?$opciones_validacion[$i]:$opciones_validacion[0];
          $html .= ');
          ';
    }
    $html .= '}
        </script>
    ';//O new Validation(document.forms[0]);
    
    
    return $html;
}
/**
 * Codifica nombres para colocarlos en direcciones URL, es decir, solo mantiene espacios, y otros caracteres como letras,numeros,"_" y ".". El resto se reemplazan por "-"
 *
 * @param str $url
 * @return str
 */
function zen_codifica_nombre_para_url($url){
	$url = htmlentities(strtolower(utf8_decode($url)));
	
	$url = str_replace(array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;"," "),array("a","e","i","o","u","n","-"),$url);
	return preg_replace('#(([^0-9a-zA-Z_])+)#', "-",$url);
}
/**
* Genera un enlace (link) HTML con el $titulo y el $enlace especificado, anteponiendo la ruta 
* definida como ZF_SITIO_WEB en zen.php
*
* @param str $titulo
* @param str $enlace
* @return str
*/
function zen_enlace($titulo,$enlace){
	$url = ZF_SITIO_WEB;
	if (substr($url,-1,1)!="/") $url.="/"; //Correcci�n de la barra del final
	return '<a href="'.$url.$enlace.'">'.$titulo.'</a>';
}
?>