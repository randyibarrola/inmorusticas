<?php
/**
 * zen_ficheros.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link http://csl2-zenphp.forja.rediris.es
 * @link http://www.zenphp.es
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @brief Conjunto de funciones para operar/crear/modificar ficheros usando PHP
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
/**
 * Guardar un fichero en una carpeta concreta. Devuelve false si ha habido un error,lo guarda en $errores.
 * En otro caso devuelve el fichero tal como quedaria almacenado en el hdd del servidor (sin la ruta).
 *
 * @param str $nombre
 * @param str $directorio_destino
 * @param str $errores
 * @param str $prefijo
 * @return str
 */
function zen_guardarFichero($nombre='fichero',$directorio_destino,&$errores,$prefijo=""){
	if (!isset($_FILES[$nombre]['name'])) return "";
	$fichero = ereg_replace('(([^0-9a-zA-Z_\.])+)', "",basename($_FILES[$nombre]['name']));
	
	if(empty($fichero)){
		$errores = _("El nombre de fichero est&aacute; vacio.");
		return false;
	}
	if (!is_dir($directorio_destino)) {
		$directorio_destino = ZF_DIR_PPAL . $directorio_destino;
		if (!is_dir($directorio_destino)) {
			$errores = _("El directorio de destino no existe.");
			return false;
		}
	}
	$destino = $directorio_destino . $prefijo.$fichero;
	if (!move_uploaded_file($_FILES[$nombre]['tmp_name'],$destino)) {
		$errores = _("Error subiendo fichero.");
		return false;
	}
	chmod($destino,0777);
	return $prefijo.$fichero;
}
/**
 * Toma un array de <input type="file" name="ficheros[]"> y los guarda en $destino devolviendo un array de nombres y en $errores lo que no funcion
 * @param str $nombre
 * @param str $destino
 * @param str $errores
 * @param str $prefijo
 * @param bool $crear_thumbs
 * @param int $tam_thumb_ancho
 * @return array
 */
function zen_guardarFicheros($nombre="ficheros",$destino,&$errores,$prefijo="",$crear_thumbs=false,$tam_thumb_ancho=100){
	$errores = "";
	$listado = array(); //ficheros guardados
	
	if (!isset($_FILES[$nombre])) return $listado;
	$f =& $_FILES[$nombre]['name'];
	$t =& $_FILES[$nombre]['type'];
	$n = count($f);
	if ($crear_thumbs){
	 require_once(ZF_DIR_LIBRERIAS. 'class.image-resize.php'); //incluir la clase en el archivo
	 $obj = new img_opt(); // Crear un objeto nuevo
	 $obj->max_width(800); // Decidir cual es el ancho maximo
	 $obj->max_height(600); // Decidir el alto maximo
	}
	for ($i=0; $i<$n; $i++){
		if (empty($f[$i])) continue;
		$fichero = ereg_replace('(([^0-9a-zA-Z_\.])+)', "",basename($f[$i]));
		if (!move_uploaded_file($_FILES[$nombre]['tmp_name'][$i],$destino.$prefijo.$fichero)){
		 $errores .= _("Error subiendo fichero.")." (".$f[$i].") ";
		 continue;
	 	}
		if ($crear_thumbs){
		 if (!in_array($t[$i],array("image/jpeg","image/png", "image/pjpeg", "image/gif") ) ) {
		 	continue;
		 }
		 $obj->image_path($destino.$prefijo.$fichero); // Seleccionar el archivo en cuestion
 		 $obj->image_resize(); // Y finalmente cambiar el tamaño
		}
		array_push($listado,$prefijo.$fichero);
		chmod($destino.$prefijo.$fichero,0777);
		
	}
	if ($crear_thumbs){
	 zen_crearThumbs($destino,$destino."thumbs/",$tam_thumb_ancho);
	}
	return $listado;
}
/**
 * Crea los thumbs para todas las imagenes de un $directorio en el $directorioThumbs
 *  con un $anchoThumb especificado
 *
 * @param str $directorio
 * @param str $directorioThumbs
 * @param int $anchoThumb
 */
function zen_crearThumbs( $directorio, $directorioThumbs, $anchoThumb )
{
  // Abrir el directorio para crear los thumbs
  $dir = opendir( $directorio );
  // iterar el directorio para extraer todas las imagenes
  while (false !== ($fichero = readdir( $dir ))) {
    // analizar la ruta
    $info = pathinfo($directorio . $fichero);
    // continuar si es de la extension que se usan:
    if ( isset($info['extension']) && ( strtolower($info['extension']) == 'jpg' || strtolower($info['extension']) == 'gif' 
      || strtolower($info['extension']) == 'png' || strtolower($info['extension']) == 'jpeg'))
    {
      // cargar imagen y ver su tamaño
      switch (strtolower($info['extension'])){
      	case 'jpg': case 'jpeg':
      	 @$img = imagecreatefromjpeg( "{$directorio}{$fichero}" );
      	 break;
      	case 'png':
      	 @$img = imagecreatefrompng("{$directorio}{$fichero}" );
      	 break;
      	case 'gif':
      	 @$img = imagecreatefromgif("{$directorio}{$fichero}" );
      	 break;
      }
      if (!$img) return false;
      $width = imagesx( $img );
      $height = imagesy( $img );
      // calcular tamaño thumbnail
      $new_width = $anchoThumb;
      $new_height = floor( $height * ( $anchoThumb / $width ) );
      // crear imagen temporal
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );
      // copiar y redimensionar la imagen anterior en la nueva
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
      // guardar imagen:
	  switch (strtolower($info['extension'])){
      	case 'jpg': case 'jpeg':
      	 imagejpeg( $tmp_img, "{$directorioThumbs}{$fichero}" );
      	 break;
      	case 'png':
      	 imagepng( $tmp_img, "{$directorioThumbs}{$fichero}" );
      	 break;
      	case 'gif':
      	 imagegif( $tmp_img, "{$directorioThumbs}{$fichero}" );
      	 break;
      }
    }
  }
  // cerrar directorio
  @closedir( $dir );
}
?>