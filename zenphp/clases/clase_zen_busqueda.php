<?php
/**
 * clase_zen_busqueda.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para buscar entre las tablas y campos pasados a ella devolviendo resultados como arrays de datos
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/*******************************************************************************
*                     EJEMPLO DE USO DE LA CLASE BUSQUEDA
*******************************************************************************
* zen___carga_clase('zen_busqueda');
* $b = new zen_busqueda($apl,"nombre,apellido1","profesores");
* $resultados = $b->buscar_con_MATCH("Juan","nombre,apellido1","personas","idpersona,nombre,apellido1,apellido2");
* //O BIEN:
* $resultados = $b->buscar_palabras("Juan,Belón","nombre,apellido1,apellido2","personas","","idpersona,nombre,apellido1,apellido2");
* //OTRA OPCION:
* $resultados = $z->buscar("Juan Belón","nombre,apellido1","personas","idpersona,nombre,apellido1,apellido2","where dni='$dni'");
*
*******************************************************************************
*/
class zen_busqueda {
	/**
	 * Aplicacion padre con la que se conecta...
	 *
	 * @var zen
	 */
	var $padre;
	/**
	 * BD utilizada para buscar en ella con instrucciones avanzadas tipo MATCH y LIKE
	 *
	 * @var zen_bd_mysql
	 */
	var $bd;
	/**
	 * Clase para mostrar la salida de las busquedas
	 *
	 * @var zen_html_busqueda
	 */
	var $html;
	/**
	 * Campos donde buscar
	 *
	 * @var str
	 */
	var $campos;
	/**
	 * Tablas donde buscar
	 *
	 * @var str
	 */
	var $tablas;
	/**
	 * Constructor
	 *
	 * @param zen $_padre
	 * @return zen_busqueda
	 */
	function zen_busqueda(&$_padre,$campos="",$tablas=""){
		/**
 		 * Carga la configuracion de la busqueda...-->/zenphp/configuracion/zen_busqueda.php
		 */
		zen___carga_configuracion('zen_busqueda');
		/**
		 * Carga las constantes de idiomas para la clase --> ej. /contenido/es/zen_busqueda.php
		 */
		//zen___carga_ctes_idioma('zen_busqueda'); //Arreglado: se ha eliminado cambiandolo por un fichero .po 
		// donde residen todas las constantes de zenphp; se extraen con _("cadena") y se editan con el programa poedit
		$this->padre =& $_padre;
		$this->bd    =& $this->padre->bd;
		$this->campos=  $campos;
		$this->tablas=  $tablas;
		if (!zen___carga_clase('zen_html_busqueda')){
			trigger_error(_('No se pudo cargar la clase zen_html_busqueda'),E_CORE_WARNING);
		} else {
			$this->html  =& new zen_html_busqueda($this);
		}
	}
	
	/**
	 * Se le pasa una $variable a serializar, esta se convierte en array si es texto. Siempre devuelve un texto (serializado).
	 *
	 * @param str||array &$variable
	 * @return str
	 */
	function preparar(&$variable) {
		if (is_array($variable)) {
			//Si es un array serializamos el array : de matriz a texto
			//dejando la variable innocua
			return zen_serializar($variable);
		} else {
			//En otro caso es una cadena y la convertimos en un array pero antes la copiamos
			$texto = $variable;
			$variable = zen_deserializar($variable);
			//ahora devolvemos el texto e intercambio realizado
			return $texto;
		}
	}

	function buscar($busqueda,$campos="",$tablas="",$seleccion="",$condicion_extra){
		if (empty($campos)) $campos = $this->campos;
		if (empty($tablas)) $tablas = $this->tablas;
		$sql = $seleccion." from ".$tablas." where 1 and ";
		if (!empty($condicion_extra)) $sql .= $condicion_extra." and ";
		$c = split(",",$campos);
		$r = array();
		$n = sizeof($c);
		for ($i=0; $i<$n; $i++){
		 $sql .= " LOWER(".$c[$i].") like '".strtolower(addslashes($busqueda))."%'";
		 if (($i+1)<$n) $sql .= ' or ';
		}
		$r1 = $this->bd->seleccion($sql);
		while ($r1 && $a = $this->bd->obtener_fila($r1)) {
		 array_push($r,$a);
		}
		return $r;
	}
	
	/**
	 * Realiza una $busqueda utilizando los $campos (campo1,campo2,...) y las $tablas (tabla1,tabla2,...) pasados como una cadena  devolviendo un $resultado como array()
	 * NOTA: Las tablas en las que se use la busqueda han de tener definidos indices: por ejemplo
	 * 	CREATE fulltext index indice_tabla ON tabla( titulo,descripcion,palabras,ciudad[,....] ) 
	 * 	Ademas de ser la tabla de la BD de tipo MyISAM. ;-)
	 * Los campos devueltos seran los de $seleccion dentro de un array,hasta un $limite (numero, si es 0, no existe limite)
	 * @param str $busqueda
	 * @param str $campos
	 * @param str $tablas
	 * @param str $seleccion
	 * @param int $limite
	 * @return array
	 */
	function buscar_con_MATCH($busqueda,$campos="",$tablas="",$seleccion="*",$limite=ZF_BUSQUEDA_LIMITE){
		$resultado = array();
		$busqueda  = strtoupper(addslashes($busqueda));
		$limite    = intval($limite);
		if (empty($campos)) $campos = $this->campos;
		if (empty($tablas)) $tablas = $this->tablas;
		//SELECT * , MATCH (TITULO,DESARROLLO) AGAINST ('$busqueda') AS puntuacion FROM ARTICULOS WHERE MATCH (TITULO, DESARROLLO) AGAINST ('$busqueda') ORDER BY puntuacion DESC LIMIT 50 
		$sql = "$seleccion , MATCH($campos) AGAINST ('%$busqueda%' IN BOOLEAN MODE) AS puntuacion FROM $tablas WHERE MATCH($campos) AGAINST ('%$busqueda%' IN BOOLEAN MODE) ORDER BY puntuacion DESC ";
		if ($limite>0) $sql .= "LIMIT $limite";
		$r = $this->bd->seleccion($sql);
		if (!$r) {
			syslog(E_CORE_WARNING,$this->bd->devolver_ultimo_error());
			return $resultado;
		}
		while ($resul = $this->bd->obtener_fila($r)) {			
			array_push($resultado,$resul);
		}
		@mysql_free_result($r);
		return $resultado;
	}
	
    /**
     * Devuelve una matriz de resultados de la busqueda con alguna de las palabras en la tabla con la condicion pasada
     * Necesita $seleccion para saber que va a seleccionar
     *
     * @param str||array<str> $palabras
     * @param str $campos
     * @param str $tabla
     * @param str $condicion
     * @param str $seleccion
     * @return array
     */
    function buscar_palabras($palabras,$campos="",$tablas="",$condicion="",$seleccion)
    {
    	if (!empty($tablas)) $this->tablas = $tablas;
    	if (!empty($campos)) $this->campos = $campos;
    	$_palabras = split(" ",strtolower($palabras));
        $campos    = $this->campos;
        $resultados=array();
    	if (!is_array($campos)) {
    		$campos = split(",",$this->campos);
    	}
		for ($i=0; $i<count($campos); $i++){
			for ($k=0; $k<sizeof($_palabras); $k++){
				$b1 = "LOWER(".$campos[$i].")";
				//$b2 = prepararCampo($_palabras[$k]);
				$b2 = $_palabras[$k];
				$sql = "distinct ".$seleccion." from ".$this->tablas." where 1 ";
				if (!empty($_palabras[$k]))
				 $sql .= "and ($b1 Like '%$b2 %' OR $b1 Like '% $b2%' OR $b1 Like ' %$b2% ' OR $b1 Like '% $b2 %' or $b1 like '%$b2%')";
		
				if (!empty($condicion)) $sql .= $condicion;
				$r = $this->bd->seleccion($sql);
				if (!$r)
				 $this->bd->mostrar_ultimo_error();
				while ($r && $resul = $this->bd->obtener_fila($r)) {
					
					if (!in_array($resul,$resultados,true)){
						array_push($resultados,$resul);
					}
				}
				
			}
		}

    	return $resultados;
    }
    
    
    
	/**
	 * Ejemplo del procesamiento simple de una busqueda en la misma clase. $seleccion contiene los campos que necesitamos obtener,no en los que vamos a buscar,ejemplo: identificador de producto.
	 *
	 * @param array $datos
	 * @param str $seleccion
	 * @return array
	 */
	function procesarBusqueda(&$datos,$seleccion){
		if (!isset($datos[ZF_CAMPO_BUSQUEDA])) return array();
		return $this->buscar_palabras($datos[ZF_CAMPO_BUSQUEDA],"","","",$seleccion);
	}
}
?>