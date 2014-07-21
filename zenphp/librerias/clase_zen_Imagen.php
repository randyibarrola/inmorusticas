<?php
/**
* Clase para operar con las imágenes usando gd 2.0.
*/
class zen_Imagen {
	/**
	 * @var str
	 */
	var $fichero_modificado;
	/**
	 * @var str
	 */
	var $nuevoAncho;
	/**
	 * @var str
	 */
	var $nuevoAlto;
	/**
	 * @var str
	 */
	var $origen;
	/**
	 * @var str
	 */
	var $destino;
	/**
	 * Contenido de la imagen leida
	 *
	 * @var resource
	 */
	var $img;
	/**
	 * Especifica el tipo para realizar unas operaciones u otras
	 *
	 * @var str
	 */
	var $tipo;
	/**
	 * Constructor
	 *
	 * @param str $origen
	 * @param str $destino
	 * @return zen_Imagen
	 */
	function zen_Imagen($origen,$destino){
	 if (is_readable($origen)){
	  $this->origen = $origen;
	  $this->destino= $destino;
	  $extension = explode(".", $this->origen);
	  if (preg_match("/jpg|JPG|jpeg|JPEG/", end($extension))) {
	   $this->tipo= "jpg";
       $this->img = imagecreatefromjpeg($this->origen);
	  } elseif(preg_match("/png|PNG/", end($extension))) {
	   $this->tipo= "png";
	   $this->img = imagecreatefrompng($this->origen);
      }elseif(preg_match("/png|PNG/", end($extension))){
       $this->tipo= "gif";
	   $this->img = imagecreatefromgif($this->origen);
      }
	 } else {
	  trigger_error(_("No se pudo leer el fichero de origen ").$origen,E_USER_ERROR);
	 }
	}
	/**
	 * Función para redimensionar la imagen que se guarda en $this->destino
	 *
	 * @param int $nuevoAncho
	 * @param int $nuevoAlto
	 */
	function redimensionar($nuevoAncho,$nuevoAlto){
		$this->nuevoAncho= $nuevoAncho;
		$this->nuevoAlto = $nuevoAlto;
		//Anteriores valores
	    $ant_x = imagesx($this->origen);
	    $ant_y = imagesy($this->origen);
	    
	    //Tamaños de las miniaturas
	    if($ant_x > $ant_y)
	    {
	        $mini_an = $this->nuevoAncho;
	        $mini_al = $ant_y*($this->nuevoAlto/$ant_x);
	    }
	    //Imagen miniatura
	    if($ant_x < $ant_y)
	    {
	        $mini_an = $ant_x*($this->nuevoAncho/$ant_y);
	        $mini_al = $this->nuevoAlto;
	    }
	    
	    if($ant_x == $ant_y)
	    {
	        $mini_an = $this->nuevoAncho;
	        $mini_al = $this->nuevoAlto;
	    }
	    
	    $this->fichero_modificado = imagecreatetruecolor($mini_an, $mini_al);
	    imagecopyresized($this->destino, $this->origen, 0, 0, 0, 0, $mini_an, $mini_al, $ant_x, $ant_y);
	}
	
	function devolver_imagen(){
	 switch ($this->tipo){
	 	case 'jpg':
			imagejpeg($this->destino, $this->fichero_modificado);
			break;
	 	case 'png':
 		    imagepng($this->destino, $this->fichero_modificado);
 		    break; 
	 }
	}
}
?> 