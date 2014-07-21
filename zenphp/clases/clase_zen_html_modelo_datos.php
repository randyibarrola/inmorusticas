<?php
/**
 * clase_zen_html_modelo_datos.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que genera un visualizador HTML para un modelo de datos
 * @see zen_andamio La clase scaffolding de zenphp que expande esta
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
class zen_html_modelo_datos {
    /**
     * Clase modelo de datos asociada a la clase HTML
     *
     * @var zen_modelo_datos
     */
    var $padre;
    /**
     * Constructor
     *
     * @param zen_modelo_datos $_padre
     * @return zen_html_modelo_datos
     */
    function zen_html_modelo_datos(&$_padre){
        $this->padre =& $_padre;
        //Esta clase depende sobre todo de las plantillas
        if (!zen___carga_clase('zen_plantilla')) die(_('No se pudo cargar la clase plantillas de zenphp'));
    }
    /**
     * Utilizando una clase plantilla y un fichero HTML devuelve el contenido relleno con los datos.
     * Si rellenamos $fichero_base ,se colocan los datos del listado dentro de la base ,sustituyendolo por la $marca_base
     * Si alguna de $reemplazos_base o $reemplazos_datos tiene contenido, se reemplazan previamente en su correspondiente plantilla
     *
     * @param str $fichero_elementos
     * @param str $fichero_base
     * @param str $marca_base
     * @param array|null $reemplazos_base
     * @param array|null $reemplazos_datos
     * @return str : html
     */
    function listado($fichero_elementos,$fichero_base="",$marca_base="",$reemplazos_base=null,$reemplazos_datos=null){
        $p     =& new zen_plantilla($fichero_elementos);
        $datos = $this->padre->obtener();
        if (is_array($reemplazos_datos)) $p->pasarAplantilla($reemplazos_datos);
        $aux   = $p->contenido;
        $n     = sizeof($datos);
        if ($n==0) $html = _("No hay ning&uacute;n elemento para el modelo de datos ").get_class($this->padre);
        else $html  = "";
        for ($i=0; $i<$n; $i++){
            $p->contenido = $aux;
            $p->pasarAplantilla($datos[$i]);
            $html .= $p->contenido;
        }

        if (!empty($fichero_base) && !empty($marca_base)){
            $p2= $p;
            $p2->cargar($fichero_base);
            if (is_array($reemplazos_base)) $p2->pasarAplantilla($reemplazos_base);
            $p2->reemplazar($marca_base , $html);
            return $p2->contenido;
        } else return $html;
    }
    /**
     * Obtiene un registro de la BD con id de la lista de campos de las tablas y devuelve el HTML. Necesita la plantilla donde se reemplazan los datos...
     * El $fichero_plantilla se carga para meterle los datos del modelo_datos y los $reemplazos son un array que se reemplaza previamente en la plantilla por si le falta algo extra al modelo_datos
     *
     * @param str $fichero_plantilla
     * @param array $reemplazos
     * @param int $id
     * @return str :html
     */
    function editar($fichero_plantilla,&$reemplazos,$id=null){
        $p =& new zen_plantilla();
        $c = split(",",$this->padre->campos);
        $tupla = array();
        $nombre = str_replace("`","",$c[0]); //Fix: nombres de campo con `
        if (!$p->cargar($fichero_plantilla)) return false;
        if (!is_null($id))
        { //Para los arrays asociativos se eliminan los caracteres ` de campos, en SQL no
            $tupla = $this->padre->obtener($this->padre->campos,""," where ".
                     $c[0]."=".intval($id),""," limit 1");
            if (!$tupla) return _("No hay datos");
        }
        //Primero los reemplazos de usuario
        $p->pasarAplantilla($reemplazos);
        //Despues los datos del modelo de datos:
        if (count($tupla)>0)
         $p->pasarAplantilla($tupla[0]);
        return $p->contenido;
    }
    /**
     * Muestra un registro en concreto usando una plantilla
     *
     * @param int $id
     * @param str $fichero_plantilla
     * @param array|null $reemplazos
     * @return str : html
     */
    function mostrar($id,$fichero_plantilla,$reemplazos=null){
        if (!$this->padre->existe($id)) {
            return sprintf(_("No hay datos para el id %s"),$id); //cte en zenphp/contenido/{idioma}.po/
        }

        $p =& new zen_plantilla();
        $campos = split(",",$this->padre->campos);
        if ($reemplazos==null || !is_array($reemplazos)) $reemplazos = array();
        return $p->contenido_reemplaza($fichero_plantilla, //Mete en la plantilla el contenido
        array_merge( //de los arrays de datos con el identificador y los reemplazos previos juntos
            $this->padre->obtener_primero("","","where ".$campos[0]."=".intval($id),"","limit 1"),
            $reemplazos
        ));
    }
    /**
     * Toma el nombre del modelo padre del visualizador y lo usa para buscar las plantillas siguientes
     * media/plantillas/[idioma=es]/[nombre_modelo]/indice.html
     * media/plantillas/[idioma=es]/[nombre_modelo]/elementos.html
     * donde en indice.html hay una marca (etiqueta #elementos#) para los elementos obtenidos del modelo
     *
     * @return str El HTML de un listado por defecto usando una plantilla que se coloca en otra y se devuelve para incrustarla en una "base", i.e.,otra etiqueta.
     */
    function index(){
        //Listado por defecto, para ello vamos a tomar la plantilla
        $nombre_plantilla = get_class($this->padre); //clase noticias por ejemplo --> "noticias/",de donde se toman las plantillas-> /media/plantillas/es/noticias/
        $directorio = ZF_DIR_PLANTILLAS . $this->padre->padre->idioma . DIRECTORY_SEPARATOR.$nombre_plantilla.DIRECTORY_SEPARATOR;
        $dir = $nombre_plantilla.DIRECTORY_SEPARATOR; //Para el listado
    	if (!is_dir($directorio) || !is_readable($directorio."elementos.html") ||!is_readable($directorio."indice.html")){
            trigger_error(_("No se pueden leer los ficheros de plantillas para mostrar el listado de este modelo: ").$nombre_plantilla." (elementos.html , indice.html)",E_USER_NOTICE);
        } else {
         //Muestra el listado con la plantilla base /media/plantillas/es/noticias/indice.html y la plantilla elementos.html,del mismo dir.
         return $this->listado($dir."elementos.html",$dir."indice.html","elementos");
        }
    }
}
?>