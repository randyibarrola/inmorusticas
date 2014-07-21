<?php
/**
 * clase_zen_generador_rss.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zen_rss
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @brief Genera canales RSS definibles fácilmente
 * @link http://www.w3schools.com/rss
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
define ('ZF_FECHA_RFC822',"D, d M Y G:i:s O");
/**
 * 
Clases en el paquete:
class zen_generador_rss
class zen_rss_canal
class zen_rss_imagen
class zen_rss_texto_entrada
class zen_rss_item
* @abstract Genera Feeds RSS con las clases :)
* @example 
* <?php
* function rss(){
		zen___carga_clase('zen_generador_rss');
		$canal_rss = new zen_rss_canal();
		$canal_rss->enlaceAtom = '';
		$canal_rss->titulo = 'Mis Noticias';
		$canal_rss->enlace = 'http://misitio.es/noticias.php';
		$canal_rss->descripcion = '�?ltimas noticias de Mi Sitio web.'; //no usar HTML o bien usar zen_borraHTML()
		$canal_rss->idioma = 'es-es';
		$canal_rss->generador = 'zenPHP Generador RSS';
		$canal_rss->editor = 'juaxix';
		$canal_rss->webMaster = 'zenphp';
		
		$item = new zen_rss_item();
		$item->titulo = 'Nuevo generador publicado';
		$item->descripcion = 'Hoy por fin he publicado el generador';
		$item->enlace = 'http://blog.zenphp.es';
		$item->guid = 'identificador_unico_generador';
		$item->fecha_publicacion = strftime("%a, %d %b %Y %H:%M:%S %z"); //'Tue, 21 Feb 2008 00:00:01 GMT';
		$canal_rss->items[] = $item;
		
		$item = new zen_rss_item();
		$item->titulo = 'Otro sitio web lanzado';
		$item->descripcion = 'Otro sitio web nuevo zen lanzado.';
		$item->enlace = 'http://aza.granadazen.com';
		$item->guid = 'identificador_unico_aza_granadazen';
		$item->fecha_publicacion = strftime("%a, %d %b %Y %H:%M:%S %z"); //'Wed, 19 Feb 2008 00:00:01 GMT';
		$canal_rss->items[] = $item;
		
		$rss_feed = new zen_generador_rss();
		$rss_feed->codificacion = 'UTF-8';
		$rss_feed->version = '2.0';
		header('Content-Type: text/xml');
		echo $rss_feed->crearRSS($canal_rss);
	}
 * ?>
*/
class zen_generador_rss
{
	/**
	 * Versión del RSS
	 *
	 * @var str
	 */
	var $version_RSS = '2.0';
	/**
	 * Codificación de caracteres del RSS
	 *
	 * @var str
	 */
	var $codificacion = '';
	/**
	 * Devuelve la etiqueta <![CDATA[$cadena]]>
	 *
	 * @param str $cadena
	 * @return str
	 */
	function cData($cadena)
	{
		return '<![CDATA[ ' . $cadena . ' ]]>';
	}
	/**
   * Genera un RSS a partir de la información del canal
   *
   * @param zen_rss_canal $canal
   * @return str
   */
	function crearRSS($canal)
	{
		$miUrl = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http://' : 'https://');
		$miUrl .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

		$rss = '<?xml version="1.0"';
		if (!empty($this->codificacion))
		{
			$rss .= ' encoding="' . $this->codificacion . '"';
		}
		$rss .= '?>' . "\n";
		$rss .= '<!-- '.sprintf(_("Generado el %s "),date('r')).' -->' . "\n";
		$rss .= '<rss version="' . $this->version_RSS . '" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
		$rss .= '  <channel>' . "\n";
		$rss .= '    <atom:link href="' . ($canal->enlaceAtom ? $canal->enlaceAtom : $miUrl) . '" rel="self" type="application/rss+xml" />' . "\n";
		$rss .= '    <title>' . $canal->titulo . '</title>' . "\n";
		$rss .= '    <link>' . $canal->enlace . '</link>' . "\n";
		$rss .= '    <description>' . $canal->descripcion . '</description>' . "\n";
		if (!empty($canal->idioma))
		{
			$rss .= '    <language>' . $canal->idioma . '</language>' . "\n";
		}
		if (!empty($canal->copyright))
		{
			$rss .= '    <copyright>' . $canal->copyright . '</copyright>' . "\n";
		}
		if (!empty($canal->editor))
		{
			$rss .= '    <managingEditor>' . $canal->editor . '</managingEditor>' . "\n";
		}
		if (!empty($canal->webMaster))
		{
			$rss .= '    <webMaster>' . $canal->webMaster . '</webMaster>' . "\n";
		}
		if (!empty($canal->fecha_publicacion))
		{
			$rss .= '    <pubDate>' . $canal->fecha_publicacion . '</pubDate>' . "\n";
		}
		if (!empty($canal->ultima_modificacion))
		{
			$rss .= '    <lastBuildDate>' . $canal->ultima_modificacion . '</lastBuildDate>' . "\n";
		}
		if (isset($canal->categorias)&& is_array($canal->categorias))
		foreach ($canal->categorias as $category)
		{
			$rss .= '    <category';
			if (!empty($category['domain']))
			{
				$rss .= ' domain="' . $category['domain'] . '"';
			}
			$rss .= '>' . $category['name'] . '</category>' . "\n";
		}
		if (!empty($canal->generador))
		{
			$rss .= '    <generator>' . $canal->generador . '</generator>' . "\n";
		}
		if (!empty($canal->docs))
		{
			$rss .= '    <docs>' . $canal->docs . '</docs>' . "\n";
		}
		if (!empty($canal->ttl))
		{
			$rss .= '    <ttl>' . $canal->ttl . '</ttl>' . "\n";
		}
		if (isset($canal->skipHours) && sizeof($canal->skipHours))
		{
			$rss .= '    <skipHours>' . "\n";
			foreach ($canal->skipHours as $hour)
			{
				$rss .= '      <hour>' . $hour . '</hour>' . "\n";
			}
			$rss .= '    </skipHours>' . "\n";
		}
		if (isset($canal->skipHours) && sizeof($canal->skipDays))
		{
			$rss .= '    <skipDays>' . "\n";
			foreach ($canal->skipDays as $day)
			{
				$rss .= '      <day>' . $day . '</day>' . "\n";
			}
			$rss .= '    </skipDays>' . "\n";
		}
		if (!empty($canal->imagen))
		{
			$imagen = $canal->imagen;
			$rss .= '    <image>' . "\n";
			$rss .= '      <url>' . $imagen->url . '</url>' . "\n";
			$rss .= '      <title>' . $imagen->titulo . '</title>' . "\n";
			$rss .= '      <link>' . $imagen->enlace . '</link>' . "\n";
			if (image.width)
			{
				$rss .= '      <width>' . $imagen->width . '</width>' . "\n";
			}
			if ($imagen.height)
			{
				$rss .= '      <height>' . $imagen->height . '</height>' . "\n";
			}
			if (!empty($imagen->descripcion))
			{
				$rss .= '      <description>' . $imagen->descripcion . '</description>' . "\n";
			}
			$rss .= '    </image>' . "\n";
		}
		if (!empty($canal->texto_entrada))
		{
			$texto_entrada = $canal->texto_entrada;
			$rss .= '    <textInput>' . "\n";
			$rss .= '      <title>' . $texto_entrada->titulo . '</title>' . "\n";
			$rss .= '      <description>' . $texto_entrada->descripcion . '</description>' . "\n";
			$rss .= '      <name>' . $texto_entrada->nombre . '</name>' . "\n";
			$rss .= '      <link>' . $texto_entrada->enlace . '</link>' . "\n";
			$rss .= '    </textInput>' . "\n";
		}
		if (!empty($canal->cloud_domain) || !empty($canal->cloud_path) ||
		!empty($canal->cloud_registerProcedure) || !empty($canal->cloud_protocol))
		{
			$rss .= '    <cloud domain="' . $canal->cloud_domain . '" ';
			$rss .= 'port="' . $canal->cloud_port . '" path="' . $canal->cloud_path . '" ';
			$rss .= 'registerProcedure="' . $canal->cloud_registerProcedure . '" ';
			$rss .= 'protocol="' . $canal->cloud_protocol . '" />' . "\n";
		}
		if (!empty($canal->extraXML))
		{
			$rss .= $canal->extraXML . "\n";
		}
		foreach ($canal->items as $item)
		{
			$rss .= '    <item>' . "\n";
			if (!empty($item->titulo))
			{
				$rss .= '      <title>' . $item->titulo . '</title>' . "\n";
			}
			if (!empty($item->descripcion))
			{
				$rss .= '      <description>' . $item->descripcion . '</description>' . "\n";
			}
			if (!empty($item->enlace))
			{
				$rss .= '      <link>' . $item->enlace . '</link>' . "\n";
			}
			if (!empty($item->fecha_publicacion))
			{
				$rss .= '      <pubDate>' . $item->fecha_publicacion . '</pubDate>' . "\n";
			}
			if (!empty($item->autor))
			{
				$rss .= '      <author>' . $item->autor . '</author>' . "\n";
			}
			if (!empty($item->comentarios))
			{
				$rss .= '      <comments>' . $item->comentarios . '</comments>' . "\n";
			}
			if (!empty($item->guid))
			{
				$rss .= '      <guid isPermaLink="';
				$rss .= ($item->guid_esEnlacePermanente ? 'true' : 'false') . '">';
				$rss .= $item->guid . '</guid>' . "\n";
			}
			if (!empty($item->origen))
			{
				$rss .= '      <source url="' . $item->origen_url . '">';
				$rss .= $item->origen . '</source>' . "\n";
			}
			if (!empty($item->enclosure_url) || !empty($item->enclosure_type))
			{
				$rss .= '      <enclosure url="' . $item->enclosure_url . '" ';
				$rss .= 'length="' . $item->enclosure_length . '" ';
				$rss .= 'type="' . $item->enclosure_type . '" />' . "\n";
			}
			foreach ($item->categorias as $category)
			{
				$rss .= '      <category';
				if (!empty($category['domain']))
				{
					$rss .= ' domain="' . $category['domain'] . '"';
				}
				$rss .= '>' . $category['name'] . '</category>' . "\n";
			}
			$rss .= '    </item>' . "\n";
		}
		$rss .= '  </channel>' . "\r";
		return $rss .= '</rss>';
	}

}

class zen_rss_canal
{
	/**
	 * Href
	 *
	 * @var str
	 */
	var $enlaceAtom = '';
	/**
	 * Título del canal
	 *
	 * @var str
	 */
	var $titulo = '';
	/**
	 * Enlace a la web
	 *
	 * @var str
	 */
	var $enlace = '';
	/**
	 * Descripción del canal
	 *
	 * @var str
	 */
	var $descripcion = '';
	/**
	 * Idioma del canal
	 *
	 * @var str
	 */
	var $idioma = '';
	/**
	 * Info de copyright
	 *
	 * @var str
	 */
	var $copyright = '';
	/**
	 * Editor
	 *
	 * @var str
	 */
	var $editor = '';
	/**
	 * Nombre del webmaster
	 *
	 * @var str
	 */
	var $webMaster = '';
	/**
	 * Fecha de publicación
	 *
	 * @var str
	 */
	var $fecha_publicacion = '';
	/**
	 * Fecha de la última modificación
	 *
	 * @var str
	 */
	var $ultima_modificacion = '';
	/**
	 * Lista de categorías asociadas
	 *
	 * @var array
	 */
	var $categorias = array();
	/**
	 * Generador del RSS
	 *
	 * @var str
	 */
	var $generador = '';
	/**
	 * Documentos
	 *
	 * @var str
	 */
	var $docs = '';
	/**
	 * TTL
	 *
	 * @var str
	 */
	var $ttl = '';
	/**
	 * Imagen
	 *
	 * @var str
	 */
	var $imagen = '';
	/**
	 * Texto de entrada
	 *
	 * @var str
	 */
	var $texto_entrada = '';
	/**
	 * Campo: skipHours: saltar horas en las que los agregadores no actualizarán los contenidos del Feed
	 * @see http://www.w3schools.com/rss/rss_tag_skipHours.asp
	 *
	 * @var array
	 */
	var $skipHours = array();
	/**
	 * Campo SkipDays : dias en los que los agregadores no actualizarán el contenido
	 *
	 * @var unknown_type
	 */
	var $skipDays = array();
	/**
	 * Dominio de nube: registra procesos con una nube para notificar inmediatamente de actualizaciones del canal
	 * @see http://www.w3schools.com/rss/rss_tag_cloud.asp
	 *
	 * @var str
	 */
	var $nube_domain = '';
	/**
	 * Puerto de la nube
	 * @see http://www.w3schools.com/rss/rss_tag_cloud.asp
	 *
	 * @var str
	 */
	var $nube_port = '80';
	/**
	 * Ruta de la nube
	 * @see http://www.w3schools.com/rss/rss_tag_cloud.asp
	 *
	 * @var str
	 */
	var $nube_path = '';
	/**
	 * Procedimiento a registrar
	 * @see http://www.w3schools.com/rss/rss_tag_cloud.asp
	 *
	 * @var str
	 */
	var $nube_registerProcedure = '';
	/**
	 * Protocolo para la nube
	 * @see http://www.w3schools.com/rss/rss_tag_cloud.asp
	 *
	 * @var str
	 */
	var $nube_protocol = '';
	/**
	 * Conjunto de items asociados al canal
	 *
	 * @var array
	 */
	var $items = array();
	/**
	 * XML extra a añadir al canal
	 *
	 * @var str
	 */
	var $extraXML = '';
}

class zen_rss_imagen
{
	/**
	 * URL de la imagen
	 *
	 * @var str
	 */
	var $url = '';
	/**
	 * Titulo a usar para la imagen
	 *
	 * @var str
	 */
	var $titulo = '';
	/**
	 * Enlace de la imagen a vincular a la url
	 *
	 * @var str
	 */
	var $enlace = '';
	/**
	 * Ancho de la imagen
	 *
	 * @var str
	 */
	var $width = '88';
	/**
	 * Alto de la imagen
	 *
	 * @var str
	 */
	var $height = '31';
	/**
	 * Descripción de la imagen
	 *
	 * @var str
	 */
	var $descripcion = '';
}

class zen_rss_texto_entrada
{
	/**
	 * Título de la entrada
	 *
	 * @var str
	 */
	var $titulo = '';
	/**
	 * Texto descriptivo
	 *
	 * @var str
	 */
	var $descripcion = '';
	/**
	 * Nombre de la entrada
	 *
	 * @var str
	 */
	var $nombre = '';
	/**
	 * Enlace asociado a la entrada
	 *
	 * @var str
	 */
	var $enlace = '';
}

class zen_rss_item
{
	/**
	 * Título asociado al item
	 *
	 * @var str
	 */
	var $titulo = '';
	/**
	 * Descripción del item
	 *
	 * @var str
	 */
	var $descripcion = '';
	/**
	 * Enlace vinculado al item
	 *
	 * @var str
	 */
	var $enlace = '';
	/**
	 * Autor del item
	 *
	 * @var str
	 */
	var $autor = '';
	/**
	 * Fecha de publicación del item
	 * Formato: Tue, 21 Feb 2008 00:00:01 GMT
	 *
	 * @var str
	 */
	var $fecha_publicacion = '';
	/**
	 * Comentarios
	 *
	 * @var str
	 */
	var $comentarios = '';
	/**
	 * El elemento <guid> define un identificador único para el item
	 *
	 * @var str
	 */
	var $guid = '';
	/**
	 * Enlace permanente al <guid>
	 * @see http://www.w3schools.com/rss/rss_tag_guid.asp
	 *
	 * @var bool
	 */
	var $guid_esEnlacePermanente = true;
	/**
	 * Origen del item (SRC), el contenido
	 *
	 * @var str
	 */
	var $origen = '';
	/**
	 * URL de origen del ITEM, el enlace
	 *
	 * @var str
	 */
	var $origen_url = '';
	/**
     * Ver http://en.wikipedia.org/wiki/RSS_Enclosures
     *
     * @var str
     */
	var $enclosure_url = '';
	/**
	 * Longitud del fichero
	 *
	 * @var str
	 */
	var $enclosure_length = '0';
	/**
	 * Tipo de fichero multimedia
	 *
	 * @var str
	 */
	var $enclosure_type = '';
	/**
	 * Lista de categorías
	 *
	 * @var array
	 */
	var $categorias = array();
}
?>