<?php
/**
 * clase_zen_basedatos.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase que contiene la capa de abstracción para cualquier tipo de base de datos
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/*******************************************************************************
*                     NOTA: La Clase BD de MySQL
*******************************************************************************
*      Autor original:     Micah Carrick
*      Email:      email@micahcarrick.com
*      Website:    http://www.micahcarrick.com
* __________________________________________________________
*      Modificada y mejorada por : Juan Belón, jbelon at correo.ugr.es (2005-2007)
*
*******************************************************************************
*/
/**
* Clase para operar con las bases de datos de cualquier tipo disponible desde PHP
*
* @author Generalizacion de bases de datos en una sola clase: Sven Wagener,Alan Blockley. Modificada,traducida y mejorada por Juan Belon @2007-2008
* 
*/
// constantes usadas por la clase
define('MYSQL_TIPOS_NUMERICOS', 'int real tinyint float double');
define('MYSQL_TIPOS_FECHA', 'datetime timestamp year date time ');
define('MYSQL_TIPOS_CADENA', 'string blob text ');
define('ZF_BD_CONTAR_CONSULTAS',false); //Si se activa se cuentan el num de consultas realizadas
/**
 * Clase de base de datos MySQL
 *
 */
class zen_basedatos {
    /**
     * Cadena de texto con el último error ocurrido en las consultas...guarda el último error, normalmente $this->obtener_error()
     *
     * @var string
     */
    var $ultimo_error;
    /**
     * Ultima consulta SQL: guarda la última consulta ejecutada
     *
     * @var string
     */
    var $ultima_consulta;
    /**
     * Servidor de mySQL
     *
     * @var str
     */
    var $servidor;
    /**
     * usuario de mySQL
     *
     * @var str
     */
    var $usuario;
    /**
     * password de mySQL
     *
     * @var str
     */
    var $password;
    /**
     * base de datos de mySQL
     *
     * @var str
     */
    var $basedatos;
    /**
     *  el último o el más actual de los identificadores de enlace a la BD
     *
     * @var array
     */
    var $id_conexion;
    /**
     * Bandera de comprobación para realizar addslashes() a la consulta antes de pasarla a la BD.
     *
     * @var boolean
     */
    var $auto_slashes;       // automáticamente la clase añade o quita las comillas cuando se pueda
    
    /**
     * Para mostrar errores automaticamente
     *
     * @var boolean
     */
    var $debug = false;
    /**
     * Para saber si la clase está actualmente conectada
     *
     * @var boolean
     */
    var $conectado = false;  //Indica si la clase está conectada o no.
    /**
     * Cuenta las consultas if (defined('ZF_BD_CONTAR_CONSULTAS'))
     *
     * @var int
     */
    var $consultas = 0;
    /**
     * Id de la consulta
     *
     * @var int
     */
    var $id_consulta;
    /**
     * Puerto de conexion
     *
     * @var int
     */
    var $puerto;
    /**
     * Constructor de la clase
     *
     * @param str $servidor
     * @param str $usuario
     * @param str $password
     * @param str $nombreBD
     * @param str $auto_slashes
     * @param str $persistente
     * @param str $puerto
     * @return zen_basedatos
     */
    function zen_basedatos($servidor='',$usuario='',$password='',$nombreBD='',$auto_slashes=false,$persistente=false,$puerto=false) {
        $this->servidor = $servidor;
        $this->usuario  = $usuario;
        $this->password = $password;
        $this->basedatos= $nombreBD;
        $this->debug    = ZF_MODO_DEPURACION?true:false;
        if (empty($servidor) && !empty($usuario) && !empty($password) && !empty($nombreBD)){
         $this->conectar('','','','',$persistente,$puerto);
        }
        $this->auto_slashes = $auto_slashes; //AUTO!
     }
    
    /**
     * Cambia la propiedad de auto-activar addslashes() antes de consultar,por motivos de seguridad
     *
     * @param bool $estado
     */
    function autoSlashes($estado){ 
        $this->auto_slashes = $estado; 
    }
    
    /**
     * Conecta a la base de datos devolviendo el resultado como true o false
     *
     * @param str $servidor
     * @param str $usuario
     * @param str $password
     * @param str $bd
     * @param bool $persistente
     * @param int $puerto
     * @return bool
     */
    function conectar($servidor='', $usuario='', $password='', $bd='', $persistente=false,$puerto=false) {        
        if (!empty($servidor)) $this->servidor = $servidor;
        if (!empty($usuario)) $this->usuario   = $usuario;
        if (!empty($password)) $this->password   = $password;
        if (!empty($bd)) $this->basedatos      = $bd;
		$this->puerto = $puerto;
        // Comprueba los errores producidos al realizar la conexión
        if (!$this->conectar_basedatos($persistente)) { //Conectar y seleccionar la base de datos
            $this->ultimo_error = mysql_error();
            //if ($this->debug) $this->mostrar_ultimo_error();
            return false;
        }
        $this->establecerIdioma();
        return $this->conectado;  // todo correcto
    }
    
    /**
    * @return boolean $esta_conectado Returns true if connection was successful otherwise false
    * @access private
    * @param bool $persistente
    * @desc Esta funcion se conecta a la base de datos
    */
    function conectar_basedatos($persistente=false){
        // Seleccionar el comando y conectar:
        if($this->id_conexion==""){
             $s = $this->servidor;
             $s.= $this->puerto?":".$this->puerto:'';
                // permanente
                if ($this->puerto){
                  @$this ->id_conexion = mysql_connect($s,$this->usuario,$this->password);
                } else{ // no permanente
                  @$this ->id_conexion = mysql_pconnect($s,$this->usuario,$this->password); 
                }
            

            if(!$this->id_conexion){
                //TODO: Manejador de excepción y logging de errores aqui --> se pone fuera de la clase...
                $this->detener(_("Datos de conexion erroneos! No se puede establecer una conexion con el servidor de BD."));
                return false;
            }else{
                //Seleccionar la base de datos
                    //TODO: Manejador de excepción y logging de errores aqui
                    if(!mysql_select_db($this->basedatos,$this->id_conexion)){
                        //TODO: Manejador de excepción y logging de errores aqui
                        $this->detener(_("Datos de la basedatos erroneos! No se puede seleccionar la BD."));
                        return false;
                    } else{
                        $this->conectado = true;
                        return true;
                    }

            }
        } else {
            //TODO: Manejador de excepción y logging de errores aqui
            $this->detener(_("Ya conectado a una base de datos."));
            return false;
        }
    }
    
    
    /**
    * Devuelve todas las tablas de una BD en un array
    * @return array $tablas tablas de una BD en un array
    */
    function obtener_tablas(){
            // Resto de bases de datos
            $tablas = "";
            $sql="SHOW TABLES";
            $r = $this->ejecutar($sql);
            for($i=0;$datos=$this->obtener_fila($r);$i++){
                $tablas[$i]=$datos['Tables_in_'.$this->basedatos];
            }
            return $tablas;
    }
    
    function obtener_comentario($nombre){
    	 $r = $this->ejecutar("SHOW TABLE STATUS LIKE '".addslashes($nombre)."'");
    	 $f = $this->obtener_fila_unica($r,MYSQL_ASSOC);
    	 
    	 return $f['Comment'];
    }
    
	/**
	 * Consulta la BD por el estado de una tabla y lo devuelve en forma de matriz:
	 * array( array('llave_externa' => str ,'tabla'=>str, 'campo'=>str) , ... )
	 * @param str $nombre
	 * @return array
	 */
    function obtener_estado_tabla($nombre){
		$r = $this->ejecutar("SHOW TABLE STATUS LIKE '".addslashes($nombre)."'");
		$f = $this->obtener_fila($r,MYSQL_BOTH);
		mysql_free_result($r);
		
		$matriz_comenta = preg_split('/; */', $f['Comment']);	
		$_llaveExterna = array(); //<-- Es lo que rellenamos para devolver
		foreach($matriz_comenta as $comentario) {
	   	//Solo para llave externa de InnoDB.
	   	if(preg_match('/\(`(.*)`\) REFER `(.*)\/(.*)`\(`(.*)`\)/',$comentario,$matrizEncontra)) {
	   		$_llavePrima = preg_split('/` `/', $matrizEncontra[1]);
	   		$_llaveExternaBD = $matrizEncontra[2];
	   		$_llaveExternaTabla = $matrizEncontra[3];
	   		$_llaveExternaCampo = preg_split('/` `/', $matrizEncontra[4]);
		   for($i = 0; $i < count($_llavePrima); $i++) {
	    	  $_llaveExterna[ $_llavePrima[$i] ] = array(
	         'llave_externa' => $_llaveExternaBD,
	         'tabla' => $_llaveExternaTabla,
	         'campo' => $_llaveExternaCampo[$i]);
	   		}
		}
    	$this->mostrar_ultima_consulta();
      }
      return $_llaveExterna;
    }
    
    /**
     * Obtiene los campos de las tablas pasadas en un array del tipo
     * array("NOMBRE_TABLA" => array( "nombre" => "nombre_campo" , "tipo" => "tipo_campo" ) )
     *
     * @param str|array $tablas puede ser una lista de tablas entre comas o un array de tablas
     * @return array
     */
    function obtener_campos($tablas){
    	if (empty($tablas)) return array();
    	if (!is_array($tablas)) $tablas = split(",",$tablas);
            	$campos = array();
            	$n = count($tablas);
            	for ($u=0; $u<$n; $u++){
            		$r = $this->ejecutar("DESCRIBE ".addslashes($tablas[$u]));
            		$lista  = array();
            		/*
            		 Field   	  Type   	  Null   	  Key   	  Default   	  Extra
					 id 	      int(11) 	  	PRI 	NULL 	auto_increment
					*/
            		while ($fila = $this->obtener_fila($r)) {
            			array_push($lista,array("nombre"=>$fila['Field'],"tipo"=>$fila["Type"]));
            		}
            		$campos[$tablas[$u]] = $lista;
            	}
            	return $campos;
    }

    /**
     * Establece el idioma de la base de datos como UTF8 para compatibilidad con el navegador en las consultas
     *
     */
    function establecerIdioma(){
                if(!@mysql_query('SET NAMES "UTF8"')) printf(_("zenBD:NAMES UTF8 no disponible"));
                if(!@mysql_query("SET collation_server='utf8_bin'")) printf(_("zenBD: collation_server utf8 no disponible"));
                if(!@mysql_query("SET character_set_client='utf8'")) printf(_("zenBD: character_set_client no disponible"));
                if(!@mysql_query("SET character_set_connection='utf8'")) printf(_("zenBD: character_set_connection no disponible"));
                if(!@mysql_query("SET character_set_results='utf8'")) printf(_("zenBD: character_set_results no disponible"));
                if(!@mysql_query("SET character_set_server='utf8'")) printf(_("zenBD: character_set_server no disponible"));
                if(!@mysql_query('set charset UTF8')) printf(_("zenBD: charset no disponible"));
                if(!@mysql_query('SET CHARACTER SET UTF8')) printf(_("zenBD: CHARACTER no disponible"));
                //ejemplo: if(!@mysql_query('SET COLLATION_CONNECTION="UTF8"')) die("BD: no disponible");

    }
    
    /**
     * Consulta de seleccion, devuelve el resultado de dicha consulta o false en caso de fallar.
     *
     * @param str $sql
     * @return array || false
     */
    function seleccion($sql) {

        // Ejecuta una consulta SQL en la que se seleccionan datos que han de almacenarse
        // en el puntero r. Devuelve el éxito o fracaso de la ejecución de la consulta.
        $sql = "SELECT ".$sql;
        $this->ultima_consulta = $sql;
        //$this->establecerIdioma();
        $r = $this->ejecutar();
        if (!$r) {
            $this->ultimo_error = mysql_error();
            //if ($this->debug) $this->mostrar_ultimo_error();
            return false;
        }
        return $r;
    }

    /**
     * Realiza una consulta de un unico campo y un unico resultado
     *
     * @param str $sql
     * @return unknown
     */
    function seleccion_unica($sql) {

        // Realiza una consulta SQL asumiendo que sólo se almacena en el puntero $r una columna
        // , solo existe un resultado.
        // Devuelve el resultado del éxito o fracaso de la consulta.
        $sql = "SELECT ".$sql;
        $this->ultima_consulta = $sql;
        //$this->establecerIdioma();
        $r = $this->ejecutar();
        if (!$r) {
            $this->ultimo_error = mysql_error();
            if ($this->debug) $this->mostrar_ultimo_error();
            return false;
        }
        if (mysql_num_rows($r) > 1 || mysql_num_rows($r) == 0) {
            $this->ultimo_error = _("La consulta de un &uacute;nico resultado ha devuelto m&aacute;s de uno o ninguno.");
            return false;
        }
        $ret = mysql_result($r, 0);
        mysql_free_result($r);
        if ($this->auto_slashes) return stripslashes($ret);
        else return $ret;
    }
    /**
     * Crea una tabla
     *
     * @param str $sql
     * @return int
     */
    function crear_tabla($sql){
        if ($this->auto_slashes) $sql = addslashes($sql);
        $r = $this->ejecutar("CREATE TABLE ".$sql);
        if (!$r) $this->ultimo_error = mysql_error();
        return $r;
    }
    /**
     * Intenta borrar una tabla y devuelve el resultado...
     *
     * @param str $tabla
     * @return int
     */
    function borrar_tabla($tabla){
        if ($this->auto_slashes) $sql = addslashes($tabla);
        $r = $this->ejecutar("DROP TABLE IF EXISTS ".$tabla);
        if (!$r) $this->ultimo_error = mysql_error();
        return $r;
    }
    /**
     * Toma un resultado de una seleccion (funcion seleccionar()) y devuelve la siguiente fila o tupla de la consulta
     *
     * @param array $resul
     * @param int $tipo
     * @return array
     */
    function obtener_fila($resul=false, $tipo=MYSQL_ASSOC) {
        // Devuelve una fila con datos desde el resulta de la consulta $resul.
        // Se usa en lugar de un típico: while($fila=mysql_fetch_array($r)).
        // Con esta clase se utiliza :   while($fila=$bd->obtener_fila($r))
        // La razón principal es el poder utilizar la opción auto_slashes más tarde.        
        if (!$resul) {
            $resul =& $this->id_consulta;
            if (!$resul){
             $this->mostrar_ultimo_error(true);
             return false;
            }
        }
        if ($this->num_filas_resultantes()==0) return false;    
        if (!empty($tipo)){
         $fila = mysql_fetch_array($resul ,$tipo);
        } else {
         $fila = mysql_fetch_assoc($resul); 
        }
        if (!$fila) return false;
        if ($this->auto_slashes) {
            // Hacemos el recorte de comillas para cada campo de la consulta...
            foreach ($fila as $clave => $valor) {
                $fila[$clave] = stripslashes($valor);
            }
        }
        return $fila;
    }
    
    /**
     * Toma un solo resultado de una seleccion (funcion seleccionar()) y devuelve la primera fila o tupla de la consulta,libera la consulta despues
     *
     * @param array $resul
     * @param int $tipo
     * @return array
     */
    function obtener_fila_unica($resul=False,$tipo=MYSQL_ASSOC){
        $fila = $this->obtener_fila($resul,$tipo);
        if (!$fila) return false;
        if ($this->auto_slashes) {
            // Hacemos el recorte de comillas para cada campo de la consulta...
            foreach ($fila as $clave => $valor) {
                $fila[$clave] = stripslashes($valor);
            }
        }
        //$this->liberar_resultado($resul);
        //        
        return $fila;
    }
    
    /**
     * Realiza una consulta y muestra informacion de depuracion de la misma.Devuelve false si la consulta no es correcta
     *
     * @param str $sql
     * @return bool
     */
    function consulta_depurada($sql) {

        // Muy útil para el tiempo de desarrollo de páginas web. Simplemente muestra
        // una consutla por pantalla usando una <table>.

        $r = $this->seleccion($sql); //Realiza la seleccion de múltiples campos:
        if (!$r) return false;
        echo "<div style=\"border: 1px solid blue; font-family: sans-serif; margin: 8px;\">\n";
        echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";

        $i = 0;
        while ($fila = $this->obtener_fila($r)) {
            if ($i == 0) {
                echo "<tr><td colspan=\"".sizeof($fila)."\"><span style=\"font-face: monospace; font-size: 9pt;\">$sql</span></td></tr>\n";
                echo "<tr>\n";
                foreach ($fila as $col => $valor) {
                    echo "<td bgcolor=\"#E6E5FF\"><span style=\"font-face: sans-serif; font-size: 9pt; font-weight: bold;\">$col</span></td>\n";
                }
                echo "</tr>\n";
            }
            $i++;
            if ($i % 2 == 0) $bg = '#E3E3E3';
            else $bg = '#F3F3F3';
            echo "<tr>\n";
            foreach ($fila as $valor) {
                echo "<td bgcolor=\"$bg\"><span style=\"font-face: sans-serif; font-size: 9pt;\">$valor</span></td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table></div>\n";
        return true;
    }

    /**
     * Realiza una consulta de insercion de datos devolviendo el identificador nuevo
     *
     * @param str $sql
     * @return int
     */
    function insertar($sql) {

        // Inserta los datos en la BD por medio del comando insert de la consulta $sql.
        // Devuelve el id de la inserción o verdadero(true) si no existe ningún campo
        // en la tabla que se autoincremente.  Falso si ocurrió algun error.
        $sql = "INSERT INTO ".$sql;
        $this->ultima_consulta = $sql;

        $r = $this->ejecutar($sql);
        if (!$r) {
            $this->ultimo_error = mysql_error();
            if ($this->debug) $this->mostrar_ultimo_error();
            return false;
        }

        $id = mysql_insert_id();
        if ($id == 0) return true;
        else return $id;
    }

    /**
     * Realiza una consulta de actualizacion o Update() devolviendo falso en caso de error o el numero de registros afectados en otro caso
     *
     * @param str $sql
     * @return unknown
     */
    function actualizar($sql) {

        // Realiza una consulta UPDATE por medio del comando de $sql .
        // Devuelve las filas afectadas o true si no se necesita actualizar nada.
        // Devuelve falso (false) si ocurrió un error.
        $sql = "UPDATE ".$sql;
        $this->ultima_consulta = $sql;

        $r = $this->ejecutar($sql);
        if (!$r) {
            $this->ultimo_error = mysql_error();
            if ($this->debug) $this->mostrar_ultimo_error();
            return false;
        }

        $filas = mysql_affected_rows();
        if ($filas == 0) return true;  // ninguna fila actualizada
        else return $filas;
    }

    /**
     * Realiza un consulta de borrado y devuelve false en caso de error o el numero de filas borradas en otro caso
     *
     * @param str $sql
     * @return bool || int
     */
    function borrar($sql){
        $sql = "DELETE FROM ".$sql;
        $this->ultima_consulta = $sql;        

        $r = $this->ejecutar($sql);
        if (!$r) {
            $this->ultimo_error = mysql_error();
            if ($this->debug) $this->mostrar_ultimo_error();
            return false;
        }

        $filas = mysql_affected_rows();
        if ($filas == 0) return true;  // ninguna fila actualizada
        else return $filas;

    }

    
    /**
    * Ejecuta una consulta SQL
    * @param string $consulta_sql la consulta en lenguaje SQL
    * @return boolean $exito devuelve falso cuando hay errores o true en caso contrario
    * @desc Ejecuta una consulta SQL
    */
    function ejecutar($consulta_sql=""){
        if (!empty($consulta_sql)) {
            $this->ultima_consulta=$consulta_sql;
        }
      
            // Resto de bases de datos
            //$this->id_consulta = mysql_query($this->ultima_consulta);
            if( !$this->id_consulta=mysql_query($this->ultima_consulta,$this->id_conexion)){
                $this->detener(_("No hay conexion de base de datos o la consulta es invalida"));
            } else {
                if (!$this->id_consulta) {
                    $this->detener(_("Consulta SQL erronea"));
                    return false;
                }else{
                    if (defined('ZF_BD_CONTAR_CONSULTAS') && ZF_BD_CONTAR_CONSULTAS) $this->consultas++;
                    return $this->id_consulta;
                }
            }
        
    }
    /**
     * Devuelve el tipo de columna de un campo de una tabla
     *
     * @param str $tabla
     * @param str $campo
     * @return str
     */
    function tipo_columna($tabla, $campo) {

        // Obtiene información acerca de un $campo en particular usando mysql_fetch_field
        // Devuelve un array con la información de la columna de la $tabla o false si
        // ocurre un error.

        $r = $this->ejecutar("SELECT $campo FROM $tabla LIMIT 1");
        if (!$r) {
            $this->ultimo_error = mysql_error();
            if ($this->debug) $this->mostrar_ultimo_error();
            return false;
        }
        $ret = mysql_field_type($r, 0);
        if (!$ret) {
            $this->ultimo_error = sprintf(_("No se pudo obtener la informaci&oacute;n de la tabla %s y campo %s"),$tabla,$campo);
            if ($this->debug) $this->mostrar_ultimo_error();
            mysql_free_result($r);
            return false;
        }
        mysql_free_result($r);
        return $ret;

    }
    /**
     * Libera recursos de la consulta realizada
     *
     * @param int $resul
     * @return unknown
     */
    function liberar_resultado(&$resul){
       
                @mysql_free_result($resul);
       
    }
    
    /**
    * Devuelve el numero de resultados obtenidos
    * @return int $numero_filas de resultados    
    */
    function num_filas_resultantes(){
    	if (!$this->id_consulta) return 0;
        @$cuenta=mysql_num_rows($this->id_consulta);
        if($cuenta>=0){
            return $cuenta;
        }else{
            $this->detener(_("No se pudo contar el numero de filas resultantes"));
            return false;
        }
        
    }
    /**
     * Extrae los elementos de un set
     *
     * @param str $tabla
     * @param str $columna
     * @return array
     */
    function extraer_elementos_set($tabla,$columna){
        $sql = "SHOW COLUMNS FROM  $tabla LIKE '$columna'";
        if (!($ret = $this->ejecutar($sql))){
            $this->ultimo_error = mysql_error();
            return array();
        }
        $linea= $this->obtener_fila($ret);
        $set  = $linea['Type'];
        $set  = substr($set,5,strlen($set)-7); // Quitar el texto "set(" al principio y ");" al final
        foreach (preg_split("/','/",$set) as $elem){// Cortar por / para formar un array
         $r[] = str_replace("'","",$elem);
        }
        return $r;
    }

        /**
    * Cambia el modo de depuracion
    * @param boolean $cambio
    */
    function modo_debug($debug=true){
        $this->debug=$debug;
    }

    /**
    * Devuelve el numero de campos de un resultado de una consulta
    * @return int (numero de campos de este resultset actual)
    */
    function cuenta_campos(){
            $num_campos=mysql_num_fields($this->id_consulta);
            if($num_campos>=0){
                return $num_campos;
            }else{
                $this->detener(_("No hay ninguna consulta ejecutada"));
                return false;
            }
    }

    /**
    * Devuelve el campo con el nombre pasado del resultset de la ultima consulta
    * @return str 
    */
    function obtener_nombre_campo($ptr){
            $nombre=mysql_field_name($this->id_consulta,$ptr);
            if($nombre){
                return $nombre;
            } else {
                $this->detener(_("Error con el Comando de nombre de campo"));
                return false;
            }
    }
    /**
     * Devuelve una lista de valores de un campo enumerado de una tabla
     *
     * @param str $campo
     * @param str $tabla
     * @return array
     */
    function obtener_enumerado($campo,$tabla){
        $sql = "SHOW COLUMNS FROM  $tabla LIKE '$campo'";
        if (!($ret = $this->ejecutar($sql))){
            $this->ultimo_error = mysql_error();
            return array();
        }
        $linea= mysql_fetch_assoc($ret);
        $enum = $linea['Type'];
        $enum = substr($enum,6,strlen($enum)-8); // Quitar el texto "enum(" al principio y ");" al final
        return preg_split("/','/",$enum); // Cortar por / para formar un array
    }
    /**
     * Devuelve una lista de valores de un campo tipo SET de una tabla
     *
     * @param str $campo
     * @param str $tabla
     * @return array
     */
    function obtener_set($campo,$tabla){
        $sql = "SHOW COLUMNS FROM  $tabla LIKE '$campo'";
        if (!($ret = $this->ejecutar($sql))){
            $this->ultimo_error = mysql_error();
            return array();
        }
        $linea= mysql_fetch_assoc($ret);
        $enum = $linea['Type'];
        $enum = substr($enum,5,strlen($enum)-7); // Quitar el texto "enum(" al principio y ");" al final
        return preg_split("/','/",$enum); // Cortar por / para formar un array
    }
    
    /**
     * Establece el formato de una fecha para consultar correctamente
     *
     * @param date $valor
     * @param str $tipo
     * @return date
     */
    function formatear_datetime($valor, $tipo='') {
        // Devuelve la fecha pasada como $valor en un formato de entrada para la BD. Puede pasarse
        // esta función un valor de 'timestamp' como puede ser time() o una cadena
        // como '04/14/2003 5:13 AM'.

        if (gettype($valor) == 'string') $valor = strtotime($valor);
        if (!empty($tipo)) return date($tipo, $valor);
        else return date('Y-m-d H:i:s', $valor);

    }
    /**
     * Escribe por pantalla la ultima consulta con error ,el codigo SQL se muestra si $mostrar_consulta es true
     *
     * @param bool $mostrar_consulta
     */
    function mostrar_ultimo_error($mostrar_consulta=true) {
      // Muestra por pantalla el último error en un formato determinado.
      // Si $mostrar_consulta es true, entonces la última consulta que fue ejecutada
      // también es mostrada.
      $this->ultimo_error = mysql_error();
      if ($this->ultimo_error){
       echo '<div style="border: 1px solid red; font-size: 9pt; font-family: monospace; color: red; padding: .5em; margin: 8px; background-color: #FFE2E2">
            <span style="font-weight: bold">'._("Error de ").'clase_zen_basedatos.php</span><br>'.sprintf(_("Error: %d (%s)\n"),mysql_errno(),$this->ultimo_error).
            '</div>';
       if ($mostrar_consulta && (!empty($this->ultima_consulta))) {
          $this->mostrar_ultima_consulta();
       }
      }
    }

    /**
     * Devuelve el HTML
     *
     */
    function devolver_ultimo_error(){
        return '<div style="border: 1px solid red; font-size: 9pt; font-family: monospace; color: red; padding: .5em; margin: 8px; background-color: #FFE2E2">
         <span style="font-weight: bold">'._("Error de ").'clase_zen_basedatos.php :</span><br>'.$this->ultimo_error.'
      </div>'.$this->devolver_ultima_consulta();
    }
    
    
    /**
     * Devuelve el texto de la ultima consulta
     *
     */
    function devolver_ultima_consulta(){
        return '<div style="border: 1px solid blue; font-size: 9pt; font-family: monospace; color: blue; padding: .5em; margin: 8px; background-color: #E6E5FF">
         <span style="font-weight: bold">'._("&Uacute;ltima consulta SQL").':</span><br>'.str_replace("\n", '<br>', $this->ultima_consulta).'</div>';
    }
    
    /**
     * Muestra el codigo SQL de la ultima consulta ejecutada
     *
     */
    function mostrar_ultima_consulta() {
      // Muestra por pantalla la última consulta que fue ejecutada mediante una caja con divisiones <div>
      echo $this->devolver_ultima_consulta();
    }
    /**
     * Comando que optimiza la base de datos
     * @return bool
     */
    function optimizar_tablas(){
     //Ejemplo: "OPTIMIZE TABLE `noticias` , `reservas` , `restaurantes` , `usuarios`"
     return $this->ejecutar("OPTIMIZE TABLE ".implode(",",$this->obtener_tablas()));
    }   
    /**
    * Imprime un mensaje de error
    * @param str $mensaje todos los errores ocurridos
    * @desc Devuelve los errores
    */
    function detener($mensaje=""){
        if($this->debug){
            if(mysql_error()!="" || $this->ultimo_error!="" && mysql_errno()){
                $this->ultimo_error.=$mensaje;
             //   $this->mostrar_ultimo_error();
            }
        }
    }
    function desconectar(){
         return mysql_close($this->id_conexion);
    }
}
?>