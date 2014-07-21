<?php
/**
 * clase_zen_xml.php
 * @author Juan Belon
 * @access public
 * @copyright Juan Belon
 * @package zenphp
 * @version 0.1.1
 * @license http://www.gnu.org/licenses/lgpl.txt GNU GENERAL LICENSE 
 * @uses zenphp FrameWork
 * @link http://www.zenphp.es
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @see zen, zen_html, zen_modelo_datos, zen_controlador_modelo_datos
 * @magic Genera la clase XML usando las funciones SimpleXML del servidor,get_class($this) se usa porque puede ser heredada
 */
/*+----------------------------------------------------------------------
  | 	                            .__            
  | 	________ ____   ____ ______ |  |__ ______  
  | 	\___   // __ \ /    \\____ \|  |  \\____ \ 
  | 	 /    /\  ___/|   |  \  |_> >   Y  \  |_> >
  | 	/_____ \\___  >___|  /   __/|___|  /   __/ 
  | 	      \/    \/     \/|__|        \/|__|    
  | 
  | 
  | zenphp.es
  +----------------------------------------------------------------------*/
if (!defined('ZF_SEGURO_ZEN')) die(_("No puedes acceder aqui directamente"));
/**
 * Ejemplo:
 * @example fichero ejemplo.xml: <?xml version="1.0" encoding="utf8"?><libreria><archivos anio="2000" type="zen"><archivo lenguaje="es"><libro isbn="123123123123"><autor>Maestros zen</autor><titulo>365 Meditaciones</titulo><anyo>2000</anyo></libro></archivo></archivos></libreria>
 * @example  
 * zen___carga_clase('zen_xml'); 
 * $xml=new zen_xml(); 
 * $xml->cargar_file('ejemplo.xml'); 
 * $matriz=$xml->xml_A_array();  
 * echo "<pre>"; 
 * var_dump($array); 
 */
class zen_xml {
    /**
      * Aplicacion padre
      *
      * @var zen
      */
    var $padre;
    /**
      * Devuelve un objeto XML cargado con { object simplexml_load ( string filename [, string class_name [, int options]] ) }
      *
      * @var SimpleXMLElement
      */
    var $documento;
    /**
     * Localización donde buscar el fichero para operar con el XML
     *
     * @var str
     */
      var $fichero;
    /**
    * Resultado de las operaciones con el XML
    *
    * @var bool
    */
      var $resultado;
      /**
     * Nombre de la raiz del documento
     *
     * @var str
     */
	var $nombre_raiz;
     /**
     * Constructor
     *
     * @param zen $_padre
     * @return xml
     */
	function zen_xml(&$_padre){
		$this->padre =& $_padre;
		if (!$this->comprobar_requerimientos()){die();}
		$this->resultado=false;
	}
	/**
 	 * Comprueba que las funciones necesarias para operar con XML están disponibles y devuelve el resultado
 	 *
 	 * @return bool
 	 */
	function comprobar_requerimientos(){
		$resultado=true;
		$comprobaciones=array('simplexml_load_file','simplexml_load_string');
		foreach ($comprobaciones as $nombre){
			if (!function_exists($nombre)) {
				$resultado=false;
                                trigger_error(sprintf(_('zen_xml error: la clase %s necesita de funciones como %s'),get_class($this),$nombre),E_USER_WARNING);
			}
		}
                if (!$resultado) trigger_error(sprintf(_('zen_xml error : %s no puede continuar'),get_class($this)),E_USER_ERROR);
		return $resultado;
	}
     /**
     * Carga el contenido de un $fichero XML pasado y lo almacena en $this->documento
     *
     * @param str $fichero
     */
	function cargar($fichero){
		if (is_readable($fichero)){
			$this->fichero   = $fichero;
			$this->documento = simplexml_cargar($fichero);
		} else {
                  trigger_error(sprintf(_("zen_xml error :(%s) el fichero %s no tiene permisos de lectura!"),get_class($this),$fichero),E_USER_WARNING);
		}
	}
     /**
     * Genera un documento XML a partir de una cadena y lo guarda en $this->documento
     *
     * @param str $cadena
     */
	function cargar_cadena($cadena){
		if ($cadena!=''){
			$this->documento=simplexml_cargar_cadena($cadena);
			if (!is_object($this->documento)){
                          trigger_error(sprintf( _("zen_xml error (%s): no se pudo crear el objeto SimpleXMLElement desde la cadena [%s]"),get_class($this),$cadena),E_USER_WARNING);}
		}
		else {
                  trigger_error(sprintf(_("zen_xml error ( %s )- el argumento \$cadena est&aacute; vacio"),get_class($this)),E_USER_WARNING);
		}
	}
	/**
	 * Recorre el árbol XML desde la $raiz (objeto simple_xml) y construye el array pertinente en &$resultado,llamando al nodo raiz con $nombre_raiz
	 *
	 * @param array $resultado
	 * @param object $raiz
	 * @param str $nombre_raiz
	 */
	function convertir_objeto_simplexml_EnArray(&$resultado,$raiz,$nombre_raiz='root'){
		//Iteramos todos los hijos?
		$n=count($raiz->children());
		if ($n>0){
			//Tiene atributos el elemento?
			if (!isset($resultado[$nombre_raiz]['@attributes'])){
				$resultado[$nombre_raiz]['@attributes']=array();
				//Recorrer los atributos para insertarlos en el array
				foreach ($raiz->attributes() as $atr=>$valor)
				$resultado[$nombre_raiz]['@attributes'][$atr]=(string)$valor;
			}
			//Bucle para iterar
			foreach ($raiz->children() as $hijo){
				$nombre=$hijo->getName();
				//Llamada recursiva que no depende del nombre de la función
				$this->{__FUNCTION__}($resultado[$nombre_raiz][],$hijo,$nombre);
			}
		} else {
			//Si no tiene hijos devolvemos un array de un sólo elemento,la raiz
			$resultado[$nombre_raiz]=(array) $raiz;
			//Es posible que dicha raiz tenga elementos atributos?
			if (!isset($resultado[$nombre_raiz]['@attributes']))
			$resultado[$nombre_raiz]['@attributes']=array();
		}
	}
	/**
	 * Convierte una matriz (array de elementos) en un objeto simplexml
	 *
	 * @param array $matriz
	 * @param object $documento
	 */
	function convertir_array_En_objeto_simplexml($matriz,$documento=''){
		//Es realmente un array?
		if (is_array($matriz)){
			//Es realmente un XML el $documento? : $this->documento?
			if (!is_object($documento)) $documento=$this->documento;
			//Tiene atributos?
			if ((isset($matriz['@attributes'])) && (count($matriz['@attributes'])>0)){
				//Añadirlos:
				foreach ($matriz['@attributes'] as $attribute=>$valor){
					$documento->addAttribute($attribute, utf8_encode($valor));
				}
				unset($matriz['@attributes']);
			}
			//Bucle para iterar el array y hacer la llamada recursiva
			foreach ($matriz as $llave=>$valor){
				//Que tipo de valor es la llave?
				if (is_numeric($llave) && is_array($valor)){
					list($hijo)=array_keys($valor);
					if (is_array($valor[$hijo][0])){
						$nuevo_hijo=$documento->addChild($hijo);
					}
					else {
						//añadimos el nuevo hijo con el sistema de caracteres UTF-8
						$nuevo_hijo=$documento->addChild($hijo,utf8_encode($valor[$hijo][0]));
					}
					//Llamada recursiva que no depende del nombre de la función
					$this->{__FUNCTION__}($valor[$hijo],$nuevo_hijo);
				}
			}
		}
	}
	/**
	 * Devuelve un vector de elementos construido a partir de $this->documento y lo guarda en $this->resultado si $guardar_resultado es true
	 *
	 * @param bool $guardar_resultado
	 * @return array
	 */
	function xml_A_array($guardar_resultado=false){
		static $resultado = false;
		if (is_object($this->documento)){
			$resultado = array();
			$this->nombre_raiz = $this->documento->getName();
			$this->convertir_objeto_simplexml_EnArray($resultado,$this->documento,$this->nombre_raiz);
			(isset($resultado[$this->nombre_raiz])) ? ($resultado=$resultado[$this->nombre_raiz]) : ($resultado=false);
		}
		if ($guardar_resultado) {
			$this->resultado =& $resultado;
		}
		return $resultado;
	}
	/**
	 * Devuelve un objeto de simple_xml construido a partir de $matriz,con el $nombre_raiz. Si $guardar_resultado es true ,se almacena dicho array en $this->resultado
	 *
	 * @param array $matriz
	 * @param str $nombre_raiz
	 * @param bool $guardar_resultado
	 * @return unknown
	 */
	function array_A_xml($matriz,$nombre_raiz,$guardar_resultado=false){
		static $resultado;
		$cadena_xml='<?xml version=\'1.0\' encoding=\'utf8\'?'.'>'.PHP_EOL;
		$cadena_xml.='<'.$nombre_raiz.'>'.PHP_EOL;
		$cadena_xml.='</'.$nombre_raiz.'>';
		$this->cargar_cadena($cadena_xml);
		$this->convertir_array_En_objeto_simplexml($matriz);
		$resultado=$this->documento->asXML();
		if ($guardar_resultado) {
			$this->resultado =& $resultado;
		}
		return $resultado;
		
	}

}
?>