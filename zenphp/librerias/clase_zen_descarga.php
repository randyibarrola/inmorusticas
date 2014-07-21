<?php
/**
 * @file clase_zen_descarga.php
 * @author Juan Belon
 * @access public
 * @copyright LGPL, GPL
 * @package zenphp
 * @version 0.1.1
 * @uses zenphp FrameWork
 * @link https://forja.rediris.es/projects/csl2-zenphp/
 * @brief Clase zen_descarga para descargar ficheros usando PHP
 * @example
 * $descarga = new zen_descarga('/var/www/htdocs/miweb/ficheros/jamon.pdf');
 * $descarga -> descargar();
 */
// +----------------------------------------------------------------------
// | zenphp.es
// +----------------------------------------------------------------------
if (!defined("ZF_SEGURO_ZEN"))
    die (_("No se puede acceder"));
/**
 * @package    zenphp::librerias
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class zen_descarga
    {
    /**
     * @var int
     * @access public
     */
    var $archivos      = 0;
    /**
     * @var array
     * @access public
     */
    var $tipos_fichero = array();
    /**
     * @var array
     * @access private
     */
    var $tipos_mime    = array();
    /**
    * @desc Ruta del fichero pasada al constructor
    * @var str
    */
    var $ruta;
    /**
    * @desc Tipo de fichero obtenido por las operaciones mime
    * @var str
    */
    var $tipo;
    /**
    * @desc Fichero de configuraciones mime-type.ini
    */
    var $ini;
    /**
     *  Constructor
     * 
     * @param str
     * @access public
     */
    function zen_descarga($ruta)
        {
        $this->tipos_fichero=array
            (
            'jpeg',
            'jpg',
            'ico',
            'png',
            'gif',
            'bmp'
            );

        $this->ini=ZF_DIR_PPAL_ZEN . "config" . DIRECTORY_SEPARATOR . "mime-type.ini";

        if (is_readable($this->ini))
            $this->tipos_mime=parse_ini_file($this->ini, false);
        else
            trigger_error (_("No encuentro el fichero zenphp/config/mime-type.ini"));
        $this->ruta = $ruta;
        }
    /**
    *  Obtener el tipo de mime
    *
    * @access private
    * @return str
    */
    function obtener_tipo_mime() { return (!$this->comprobar_tipo_mime())
        ? 'application/force-download' : $this->tipos_mime[$this->extension_fichero]; }
    /**
     *  Comprobar el tipo de mime
     *
     * @access private
     * @return bool
     */
    function comprobar_tipo_mime() { return isset($this->tipos_mime[$this->extension_fichero]) ? true : false; }
    /**
     *  obtener el fichero
     *
     * @param str
     * @access public
     */
    function obtener($ruta)
        {
        $this->ruta=$ruta;

        //preg_match("/^[\.\-\s#_a-zA-Z\d]+$/", $ruta, $fichero);
        //$this->fichero = $fichero[0];
        //if(!file_exists($this->ruta.$this->fichero)) die("El Fichero no existe");
        if (!file_exists($this->ruta))
            die (sprintf(_("El Fichero %s no existe"),$this->ruta));

        $this->partes           =pathinfo($this->ruta);
        $this->nombre           =$this->partes['basename'];
        $this->extension_fichero=strtolower($this->partes['extension']);
        $this->tamanio          =filesize($this->ruta);
        $this->tipo             =$this->obtener_tipo_mime();

        // $this->descargar();
        }
    /**
   *  Función interna para enviar el fichero
   *
   * @access private
   * @return binary
   */
    function descargar()
        {
        $this->obtener($this->ruta);

        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header ("Pragma: public");
        header ("Expires: 0");
        header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header ("Content-type: " . $this->tipo . "");
        header ("Content-Disposition: attachment; filename=\"" . $this->nombre . "\"");
        header ("Content-Transfer-Encoding: Binary");
        header ("Content-length: " . $this->tamanio . "");
        //set_time_limit (0);
        readfile ($this->ruta);
        ini_set('zlib.output_compression', 'On');
        exit();
        }
    /**
     *  Obtiene el tipo de fichero
     *
     * @param str
     * @access private
     * @return str
     */
    function _tipo($fichero)
        {
        $dotpos=strrpos($fichero, ".");
        return strtolower(substr($fichero, $dotpos + 1));
        }
    /**
     *  Escribir un formulario html con un select
     *
     * @param str
     * @param str
     * @param str
     * @param int
     * @access public
     * @return mixed
     */
    function formulario_html($nombre_select = 'archivos', $etiqueta = '', $actual = '', $num_caracteres = 30)
        {
        if ($manejador=opendir($this->ruta))
            {
            $select='<form method="post" action="' . $_SERVER['PHP_SELF'] . '">' . "\n";
            $select.=($etiqueta != '') ? '<label for="' . $nombre_select . '">' . $etiqueta . '</label>' . "\n" : '';
            $select.='<select name="' . $nombre_select . '" id="' . $nombre_select . '">' . "\n";
            $actual=(isset($_REQUEST[$nombre_select])) ? $_REQUEST[$nombre_select] : $actual;
            $select.=($actual == "") ? "  <option value=\"\" selected>...\n" : "<option value=\"\">...\n";

            while (false !== ($fichero=readdir($manejador)))
                {
                $array[]=$fichero;
                }

            closedir ($manejador);
            sort ($array);

            foreach ($array as $val)
                {
                $ext = $this->_tipo($val);

                if (is_file($this->ruta . $val) && array_search($ext, $this->tipos_fichero) !== false)
                    {
                    $select.='    <option value="' . $val . '">';
                    $select.=(strlen($val) > $num_caracteres) ? substr($val,
                                                                       0,
                                                                       $num_caracteres) . '..' . "</option>\n"
                        : $val . "</option>\n";
                    $this->archivos++;
                    }
                }

            $select.='</select>' . "\n";
            $select.='<input type="submit" name="download" value="'._("Download").'" />' . "\n";
            $select.='</form>' . "\n";
            }

        return ($this->archivos == 0) ? _("No hay archivos!") : $select;
        }
    }
?>