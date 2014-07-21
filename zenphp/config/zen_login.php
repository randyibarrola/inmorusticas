<?php
/**
 * config/zen_login.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @magic Fichero de configuraciones para los login's automáticos
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined('ZF_SEGURO_ZEN')) die("No hay login sin config-in");
# Las lineas comentadas con '#' son comentarios
/*
CREATE TABLE `usuarios` (
`idu` INT NOT NULL AUTO_INCREMENT ,
`nombre` VARCHAR( 75 ) CHARACTER SET utf8 COLLATE utf8_bin,
`usuario` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`password` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`correo` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_bin,
PRIMARY KEY ( `idu` ) ,
`nivel` TINYINT( 2 ) DEFAULT 1 NOT NULL
) TYPE = MYISAM CHARACTER SET utf8 COLLATE utf8_bin
*/
#|---------------------------------------------------------------
#| Campos de la tabla de usuarios para la clase login
#EJEMPLO DE CREACION DE TABLA CON ZEN_PHP:
/*
$sql = '`usuarios` ('
        . ' `idu` INT NOT NULL AUTO_INCREMENT, '
        . ' `nombre` VARCHAR(75) CHARACTER SET utf8 COLLATE utf8_bin, '
        . ' `usuario` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, '
        . ' `password` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, '
        . ' `correo` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_bin,'
        . ' PRIMARY KEY (`idu`),'
        . ' `nivel` TINYINT(2) DEFAULT \'1\' NOT NULL,'
        . ' )'
        . ' TYPE = myisam'
        . ' CHARACTER SET utf8 COLLATE utf8_bin';
*/
#$aplicacion = new zen_aplicacion(
#$aplicacion->bd->crear_tabla($sql);
#|---------------------------------------------------------------
#
define('ZF_LOGIN_TABLA',"usuarios");
define('ZF_LOGIN_CAMPO_USUARIO','usuario'); #se utiliza en un formulario de login
define('ZF_LOGIN_CAMPO_CONTRASENA','password');
define('ZF_LOGIN_CAMPO_CORREO','correo');  #opcional
define('ZF_LOGIN_CAMPO_ID_USUARIO',"idu"); #obligatorio
define('ZF_LOGIN_CAMPO_NIVEL',"nivel"); #obligatorio -> para establecer jerarquias de acceso y ACL internos
#define('ZF_LOGIN_CAMPO_LISTA_IDGRUPOS',"idgrupos"); #opcional: nombre del campo de la lista de grupos que tiene acceso, dichos grupos irian en la tabla de grupos,configurar debajo
#
#---------------------------------------------------------------
#| Campos de la tabla de usuarios para la clase login
#|---------------------------------------------------------------
#
#define('ZF_LOGIN_TABLA_GRUPOS', "grupos"); #tabla para guardar ids de grupo y su nombre
#define('ZF_LOGIN_CAMPO_NOMBRE_GRUPO', "nombre_grupo"); # //nombre interno del grupo
?>