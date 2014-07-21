<?php
/**
 * clase_zen_bd_backup_mysql.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Clase para exportar una base de datos mySQL a un fichero.
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die(_('Acceso directo no permitido'));
/**
 * @example 
 Crea una instancia de zen_bd_backup_mysql y exporta sus datos asociados en un fichero
 comprimido de tipo base-16:
 $bd = new zen_basedatos(#datos de conexion aqui#);
 zen___carga_clase('zen_bd_backup_mysql');
 $copia = new zen_bd_backup_mysql($bd,'fichero.sql',false,false);
 $copia->escribirEstructura();
 */
class zen_bd_backup_mysql {
	/**
	 * Clase Base de datos de zenphp
	 *
	 * @var zen_basedatos
	 */
	var $bd;
	/**
	 * Fichero donde se escribe la exportación
	 *
	 * @var str
	 */
	var $fichero;
	/**
	 * Bandera para saber si todo se escribió correctamente...
	 *
	 * @var bool
	 */
	var $escrito = false;
	/**
	 * Constructor
	 *
	 * @param zen_basedatos $bd
	 * @param str $fichero_exportado
	 * @param bool $comprimir
	 * @return zen_bd_backup_mysql
	 */
	function zen_bd_backup_mysql(&$bd, $fichero_exportado = 'backup.sql', $comprimir = false){
		if ($bd->tipo!='mysql') die(_("Esta clase solo exporta bases de datos de mysql"));
		$this->comprimir = $comprimir;
		$this->bd =& $bd;
		$this->fichero = $fichero_exportado;
		if ( !$this->establecer_fichero_salida($fichero_exportado) )
		 return false;
		else 
		 return true;
	}
	/**
	 * Obtiene la estructura de tablas de una base de datos como cadena para reconstruirla con comandos
	 * y la escribe en el fichero pasado en el constructor.
	 *
	 * @return bool
	 */
	function escribirEstructura() {
		$registros = $this->bd->ejecutar('SHOW TABLES');
		if ( $this->bd->num_filas_resultantes($registros) == 0 )
		return false;
		$estructura = "";
		while ( $registros && $tupla = $this->bd->obtener_fila($registros,MYSQL_NUM) ) {
		 $estructura .= $this->escribirEstructuraTabla($tupla[0]);
		}
		return true;
	}
	/**
	 * Función que escribe la estructura de la tabla en una cadena que luego
	 * se guarda en el fichero especificado por el constructor
	 *
	 * @param str $tabla
	 * @return bool
	 */
	function escribirEstructuraTabla($tabla){
		if (!$this->bd->conectado) return false;
		// Cabecera
		$estructura = "-- \n";
		$estructura .= "-- Estructura de la tabla `{$tabla}` \n";
		$estructura .= "-- \n\n";
		// Exportar estructura
		$estructura .= 'DROP TABLE IF EXISTS `'.$tabla.'`;'."\n";
		$estructura .= "CREATE TABLE `".$tabla."` (\n";
		$registros = $this->bd->ejecutar('SHOW FIELDS FROM `'.$tabla.'`');
		if ( $this->bd->num_filas_resultantes($registros) == 0 ) return false;
		while ($registros && $tupla = $this->bd->obtener_fila($registros) ) {
			$estructura .= '`'.$tupla['Field'].'` '.$tupla['Type'];
			if ( !empty($tupla['Default']) )
			$estructura .= ' DEFAULT \''.$tupla['Default'].'\'';
			if ( @strcmp($tupla['Null'],'YES') != 0 )
			$estructura .= ' NOT NULL';
			if ( !empty($tupla['Extra']) )
			$estructura .= ' '.$tupla['Extra'];
			$estructura .= ",\n";
		}
		$estructura = @ereg_replace(",\n$", null, $estructura);

		// Guardar los indices...
		$estructura .= $this->obtenerIndicesTabla($tabla);
		$estructura .= "\n)";

		//Motor de la tabla:
		$registros = $this->bd->ejecutar("SHOW TABLE STATUS LIKE '".$tabla."'");
		if ($registros && $tupla = $this->bd->obtener_fila($registros) ) {
			if ( !empty($tupla['Engine']) )
			$estructura .= ' ENGINE='.$tupla['Engine'];
			if ( !empty($tupla['Auto_increment']) )
			$estructura .= ' AUTO_INCREMENT='.$tupla['Auto_increment'];
		}

		$estructura .= ";\n\n-- --------------------------------------------------------\n\n";
		$this->_guardarFichero($this->fichero,$estructura);
	}
	/**
	 * Guarda en el fichero los datos obtenidos de la estructura de tablas
	 *
	 * @param str $fichero
	 * @param str $datos
	 */
	function _guardarFichero($fichero, $datos) {
		if ( $this->comprimir )
		 @gzwrite($fichero, $datos);
		else
		 @fwrite($fichero, $datos);
		$this->escrito = true;
	}
	/**
	 * Establece el fichero de salida para escribir en el...
	 *
	 * @param str $fichero_exportado
	 * @return resource
	 */
	function establecer_fichero_salida($fichero_exportado=""){
		if ($this->escrito) return false;
		if (!empty($fichero_exportado) && $this->fichero!=$fichero_exportado) $this->fichero = $fichero_exportado;
		$fd = $this->abrir_fichero($this->fichero);
		return $fd;
	}
	/**
	 * Abre el fichero especificado devolviendo su manejador dependiendo del tipo
	 *
	 * @param str $_fichero
	 * @return resource
	 */
	function abrir_fichero($_fichero) {
		$fichero = false;
		if ( $this->comprimir )
		 $fichero = @gzopen($_fichero, "w9");
		else
		 $fichero = @fopen($_fichero, "w");
		return $fichero;
	}
	/**
	 * Obtiene los índices de la $tabla como comandos de SQL
	 *
	 * @param str $tabla
	 * @return str
	 */
	function obtenerIndicesTabla ($tabla) {
		$primaria = "";
		unset($unico);
		unset($indice);
		unset($t_completo); //fulltext->para búsqueda ,etc.
		$resultados = $this->bd->ejecutar("SHOW KEYS FROM `{$tabla}`");
		if ($this->bd->num_filas_resultantes($resultados) == 0) return false;
		while($fila = mysql_fetch_object($resultados)) {
			if (($fila->Key_name == 'PRIMARY') && ($fila->Index_type == 'BTREE')) {
				if ( $primaria == "" )
				 $primaria = "  PRIMARY KEY  (`{$fila->Column_name}`";
				else
				 $primaria .= ", `{$fila->Column_name}`";
			}
			if (($fila->Key_name != 'PRIMARY') && ($fila->Non_unique == '0') && ($fila->Index_type == 'BTREE')) {
				if ( (!is_array($unico)) || ($unico[$fila->Key_name]=="") )
				 $unico[$fila->Key_name] = "  UNIQUE KEY `{$fila->Key_name}` (`{$fila->Column_name}`";
				else
				 $unico[$fila->Key_name] .= ", `{$fila->Column_name}`";
			}
			if (($fila->Key_name != 'PRIMARY') && ($fila->Non_unique == '1') && ($fila->Index_type == 'BTREE')) {
				if ( (!is_array($indice)) || ($indice[$fila->Key_name]=="") )
				 $indice[$fila->Key_name] = "  KEY `{$fila->Key_name}` (`{$fila->Column_name}`";
				else
				 $indice[$fila->Key_name] .= ", `{$fila->Column_name}`";
			}
			if (($fila->Key_name != 'PRIMARY') && ($fila->Non_unique == '1') && ($fila->Index_type == 'FULLTEXT')) {
				if ( (!is_array($t_completo)) || ($t_completo[$fila->Key_name]=="") )
				 $t_completo[$fila->Key_name] = "  FULLTEXT `{$fila->Key_name}` (`{$fila->Column_name}`";
				else
				 $t_completo[$fila->Key_name] .= ", `{$fila->Column_name}`";
			}
		}
		$sql_llaves = '';
		// genera instrucciones para llaves primaria, unicas, y texto completo (fulltext)
		if ( $primaria != "" ) {
			$sql_llaves .= ",\n";
			$primaria .= ")";
			$sql_llaves .= $primaria;
		}
		if (is_array($unico)) {
			foreach ($unico as $_llave => $_defLlave) {
				$sql_llaves .= ",\n";
				$_defLlave .= ")";
				$sql_llaves .= $_defLlave;

			}
		}
		if (is_array($indice)) {
			foreach ($indice as $_llave => $_defLlave) {
				$sql_llaves .= ",\n";
				$_defLlave .= ")";
				$sql_llaves .= $_defLlave;
			}
		}
		if (is_array($t_completo)) {
			foreach ($t_completo as $_llave => $_defLlave) {
				$sql_llaves .= ",\n";
				$_defLlave .= ")";
				$sql_llaves .= $_defLlave;
			}
		}
		return $sql_llaves;
	}
}
?>