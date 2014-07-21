<?php
/**
 * clase_zen_modelo_datos.php
 * @author Juan Belon
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que genera un modelo de datos a partir de una lista de campos y de tablas
 * @see zen_html_modelo_datos , zen_andamio
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
class zen_modelo_datos {
    /**
     * Aplicacion web
     *
     * @var zen
     */
    var $padre;
    /**
     * Listado de campos de las tablas separados por comas, poner el identificador principal el primero
     *
     * @var str
     */
    var $campos;
    /**
     * Nombre de las tablas asociadas al modelo de datos
     *
     * @var str
     */
    var $tablas;
    /**
     * ??ltimo array obtenido de datos
     *
     * @var array
     */
    var $tuplas;
    /**
     * Condiciones para filtrar los datos en los listados
     *
     * @var str
     */
    var $condiciones_where;
    /**
     * Un array con pares llave_campo=>nombre_funcion, es decir, array('campo'=>'zen_funcion_a_aplicar',...)
     * que se procesan antes de llamar a la consulta SQL contra la BD
     *
     * @var array
     */
    var $filtros_preprocesamiento = null;
    /**
     * Igual que $this->filtros_preprocesamiento pero después...es decir:
     * Un array con pares llave_campo=>nombre_funcion, es decir, array('campo'=>'zen_funcion_a_aplicar',...)
     * que se procesan después de llamar a la consulta SQL contra la BD
     *
     * @var array
     */
    var $filtros_postprocesamiento = null;
    /**
     * Clase de BD MySQL
     *
     * @var zen_basedatos
     */
    var $bd;
    /**
     * Clase para manejar productos de categorias si la hubiera
     *
     * @var zen_modelo_datos
     */
    var $subcategoria = null;
    /**
     * clase html que usa el modelo
     *
     * @var zen_html_modelo_datos
     */
    var $html;
    /**
     * Controlador del modelo de datos
     *
     * @var zen_controlador_modelo_datos
     */
    var $controlador;
    /**
     * Modelo de datos para mostrar en la web
     *
     * @param zen $_padre
     * @param str $campos
     * @param str $tablas
     * @param str $subcategoria
     * @param str $condiciones_where
     * @return zen_modelo_datos
     */
    function zen_modelo_datos(&$_padre,$campos="",$tablas="",$subcategoria=null,$condiciones_where=""){
        $this->padre  =& $_padre;
        $this->bd     =& $_padre->bd;
        $this->campos =  $campos;
        $this->tablas =  $tablas;
        $this->condiciones_where = $condiciones_where;
        if (!zen___carga_clase('zen_html_modelo_datos')) {
        	trigger_error(_("No se puede cargar zen_html_modelo_datos."),E_USER_NOTICE);
        	return false;
        }
        $nombre_clase =  get_class($this);
        $nombre_visualizador = ZF_CONTROLADOR_DEFECTO."_".$nombre_clase;
        if ($nombre_clase!='zen_modelo_datos'){ //es una clase de usuario,comprobar si tiene un visualizador propio
        	 if (!zen___carga_vista($nombre_visualizador)){
        	  if (ZF_MODO_DEPURACION)
        	   trigger_error(_("Error en la Carga de clase visualizador de usuario ").$nombre_visualizador);

        	  $this->html   = new zen_html_modelo_datos($this);
        	 } else {
        	  $this->html  = new $nombre_visualizador($this);
        	 }
        } else{
        	 $this->html   = new zen_html_modelo_datos($this);
        }
        //Ahora el controlador viene incluido en la vista, es un "visualizador"
        if ($subcategoria!=''){
         if (zen___carga_clase($subcategoria))
          eval('$this->subcategoria =& new '.$subcategoria.'($this)');
        }
        if (empty($this->campos)) $this->poner_campos_al_modelo();
    }
     /**
      * Devuelve una lista de categorias con las opciones pasadas...son todas opcionales
      *
      * @param str $where
      * @param str $tablas
      * @param str $from
      * @param str $order
      * @param str $limit
      * @return array
      */
    function &obtener($campos="",$tablas="",$where="",$order="",$limit="",$num_campos=0){
        if (empty($campos)) $campos = $this->campos;
        if (empty($tablas)) $tablas = $this->tablas;
        if (empty($where))  $where  = $this->condiciones_where;
        if (is_numeric($limit)) $limit = "limit $limit";
        $r = $this->bd->seleccion($campos." from ".$tablas." ".$where." ".$order." ".$limit);
        $b = false;
        if (!$r) return $b;
        $this->tuplas = array();
        while ($tupla = $this->bd->obtener_fila($r)) {
            //Hay que dejar el valor campo como estaba cuando lo insertamos y lo actualizamos poniéndole addslashes,
            // ahora usamos stripslashes
            $this->tuplas[] = array_map('stripslashes',$tupla);
            if (is_array($this->filtros_postprocesamiento)){
             @$this->aplicar_filtros_postprocesamiento($this->tuplas[count($this->tuplas)-1]);
            }
        }
        $this->bd->liberar_resultado($r);
        return $this->tuplas;
    }
    /**
     * Obtiene el primer dato del modelo de datos
     *
     * @param str $campos
     * @param str $tablas
     * @param str $where
     * @param str $orden
     * @return array
     */
    function obtener_primero($campos="",$tablas="",$where="",$orden=""){
        $dato = $this->obtener($campos,$tablas,$where,$orden,"limit 1");
        return $dato[0];
    }
    /**
     * Devuelve el valor de un campo de la tabla dada
     *
     * @param str $campo
     * @param str $tabla
     * @param str $where
     * @param str $orden
     * @return unknown
     */
    function obtener_valor_campo($campo="",$tabla="",$where="",$orden=""){
        if (empty($campo)) { //Si no se especifica  campo simplemente cogemos el primero de todos
            $campos = split(",",trim($this->campos),1);
            $campo  = $campos[0]; //primer campo, debe de ser el identificador seguramente...
        }
        if (empty($campo)) return false; //si no hay campo no hay consulta,fuera!
        $dato = $this->obtener_primero($campo,$tabla,$where,$orden);
        if (is_array($this->filtros_postprocesamiento))
         $this->aplicar_filtros_postprocesamiento($dato);

        //Tenemos la primera tupla,devolvemos el primer campo de esta
        return $dato[$campo];
    }

    /**
     * Si necesitamos usar otra BD distinta o no crear la clase zen_modelo_datos podemos usar esta funcion
     *
     * @param zen_basedatos $bd
     * @param str $campos
     * @param str $tablas
     * @param str $where
     * @param str $order
     * @param str $limit
     * @return array
     */
    function obtener_BD(&$bd,$campos,$tablas,$where,$order,$limit){
        static $tuplas;
        $tuplas = array();
        $this->tuplas =& $tuplas;
        if (is_numeric($limit)) $limit = "limit $limit";
        // TODO: Cambiar el die() por una excepcion lanzada
        if (!is_a($bd,'zen_basedatos')) die(_("La clase pasada a obtener_BD no es una clase zen_basedatos"));
        $r = $bd->seleccion($campos." from ".$tablas." ".$where." ".$order." ".$limit);
        if (!$r) return false;
        while ($tupla = $bd->obtener_fila($r)) {
            if (is_array($this->filtros_postprocesamiento))
                $this->aplicar_filtros($tupla);
            array_push($tuplas,$tupla);
        }
        $bd->liberar_resultado($r);
        return $tuplas;
    }
    /**
     * Devuelve el entero de $id si corresponde con la BD,Si se rellenan las $condiciones_where, han de ser como
     * esta: " AND CONDICION "
     * o   : " OR  CONDICION "
     *
     * @param int $id
     * @param str $condiciones_where
     * @return int
     */
    function existe($id,$condiciones_where="")
    {
    	$this->condiciones_where = $condiciones_where;
        $c = str_replace("`","",split(",",trim($this->campos)));
        return intval($this->bd->seleccion_unica($c[0]." from ".$this->tablas." where ".$c[0]."=".intval($id)
        		." ".$condiciones_where));
    }
    /**
     * Cuenta el numero de tuplas de la tabla/s
     *
     * @return int
     */
    function num_registros(){
        return intval($this->bd->seleccion_unica("COUNT(*) from ".$this->tablas));
    }
    /**
     * Procesa un filtro antes de usarlo en una consulta u otro proceso
     *
     * @param str $nombre
     * @param tipo_desconocido $valor
     * @return desconocido
     */
    function procesar_filtro_pre($nombre,&$valor){
        if (is_array($this->filtros_preprocesamiento) &&
            array_key_exists($nombre,$this->filtros_preprocesamiento))
        {
            if (is_callable($this->filtros_preprocesamiento[$nombre])){
                $valor = call_user_func($this->filtros_preprocesamiento[$nombre],$valor);
            } else {
                trigger_error("Error llamando al filtro ".$this->filtros_preprocesamiento[$nombre]."=".$valor);
            }
        } else {

            $valor = addslashes($valor);
        }

        return $valor;
    }
    /**
     * Inserta datos desde el array pasado con los campos pasados y devuelve el nuevo indentificador pasado
     *
     * @param array $datos
     * @return int
     */
    function insertar(&$datos){
        $c = split(",",$this->campos);
        $sql = $this->tablas."(".$this->campos.") values(";
        $n = sizeof($c);
        //Solo insertamos los campos especificados por el usuario, evitamos problemas...
        for($i=0; $i<$n;$i++){
            $nombre = str_replace("`","",$c[$i]);
            if ($i==0) $sql .= (isset($datos[$nombre]))?intval($datos[$nombre]):0; //Autonumerico
            else {
                //if ($i<=$n) $sql .=",";
                $sql .=",'";
                $sql .= isset($datos[$nombre])?$this->procesar_filtro_pre($nombre,$datos[$nombre]):'';
                $sql .= "'";
            }

        }
        $sql .=");";
        return $this->bd->insertar($sql);
    }
    /**
     * Actualiza los datos pasados en el array $datos y devuelve el numero de tuplas afectadas
     *
     * @param array $datos
     * @return int
     */
    function actualizar($datos){
        $c   = split(",",$this->campos);
        $sql = $this->tablas." SET ";
        $n   = count($c);
        $coma= false;
        //El id se deja para el final,como condicion del where
        for ($i=1; $i<$n; $i++){
            $nombre = str_replace("`","",$c[$i]); //Arreglo: quitamos los caracteres de campo tipo mySQL
            if (isset($datos[$nombre])){
             if ($coma /*&& ($i<sizeof($datos)-1)*/) $sql .= ",";
             else $coma=true;
             $sql .= $c[$i]."='".$this->procesar_filtro_pre($nombre,$datos[$nombre])."'"; //Aplicar el/los filtros para dicho campo?
            }
        }
        $sql .= " where ".$c[0]."=".intval($datos[str_replace("`","",$c[0])]); //Mismo Arreglo: Para el identificador tambien
        return $this->bd->actualizar($sql);
    }
    /**
     * Borra una tupla con un id especifico y devuelve el numero de campos borrados como resultado
     *
     * @param int $id
     * @return int
     */
    function borrar($id){
        $c = split(",",$this->campos);
        return $this->bd->borrar($this->tablas." where ".$c[0]."='".addslashes($id)."';");
    }
    /**
     * Aplica los filtros establecidos en $this->filtros_preprocesamiento a las $variables pasadas en un array
     * y devuelve el resultado
     *
     * @param array $variables
     * @return bool
     */
    function aplicar_filtros_preprocesamiento(&$variables){
        return $this->aplicar_filtros($this->filtros_preprocesamiento,$variables);
    }
    /**
     * Aplica los filtros establecidos en $this->filtros_postprocesamiento a las $variables pasadas en un array
     * y devuelve el resultado
     *
     * @param array $variables
     * @return bool
     */
    function aplicar_filtros_postprocesamiento(&$variables){
        return $this->aplicar_filtros($this->filtros_postprocesamiento,$variables);
    }
    /**
    * Aplica los $filtros a los $campos devolviendo true si los parametros son correctos
    *
    * @param array $filtros
    * @param array $campos
    * @return bool
        */
    function aplicar_filtros(&$filtros,&$campos){
      if (!is_array($filtros) || !is_array($campos)) return false;
      foreach ($campos as $campo => $valor) {
            //está el filtro en la lista de procesamiento?
                if (!array_key_exists($campo,$filtros)) continue; //no está
            //si está:
                if (is_callable($filtros[$campo])){ //Podemos llamar a la función del filtro?
                  //Llama a la función de filtro adecuada
                  if (is_array($filtros[$campo]))
                      $campos[$campo] = call_user_method($filtros[$campo][1],$filtros[$campo][0],$valor);
                  else
                      $campos[$campo] = call_user_func($filtros[$campo],$valor);
                } else {
                  trigger_error(sprintf(_('Es posible que la funci&oacute;n %s necesite cargarse; ejemplo: zen___carga_funciones("zen_ficheros")'),$filtros[$campo]),E_USER_NOTICE);
                }
      }

      return true;
    }
    /**
	 * Devuelve un campo especificando un id unico
	 *
	 * @param str $id
	 * @param str $campo
	 * @param bool $addslashes
	 * @return unknown
	 */
	function obtener_campo($id,$campo,$addslashes=true){
		if ($addslashes) {
			$campo = addslashes($campo);
			$id    = addslashes($id);
		}
		$c = split(",",$this->campos);
		return $this->bd->seleccion_unica($campo." from ".$this->tablas." where ".
			   addslashes($c[0])."='".$id."' limit 1");
	}
    /**
     * Lee los campos de la tabla y los coloca pegados con una coma en $this->campos
     *
     */
    function poner_campos_al_modelo(){
    	if (empty($this->tablas)) return false;
		$this->campos = ""; //Campos del modelo!!

		$info_tablas = $this->bd->obtener_campos($this->tablas);
		foreach ($info_tablas as $tabla => $campos){//el array
			$n = count($campos);
			for ($i=0; $i<$n; $i++){
				$this->campos .= $campos[$i]['nombre'];
				if ($i<$n-1) $this->campos .= ',';
			}
		}
		return true;
	}
}
?>