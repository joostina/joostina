<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
defined('_JOOS_CORE') or die();

class Image {

    private $id = null;
    private $name = '';
    private $field_name = 'file';
    private $directory = 'images';
    private $valid_types = array("jpg", "gif", "png");
    private $file_prefix = '';
    private $return_params = '';
    private $max_size = 1048576;
    private $url = '';

    public function upload($resize_options=0) {
        global $_FILES;

        $name_file = $this->field_name;
        $up = 0;

        $dir_name = JPATH_BASE . DS . $this->directory;
        if (!is_dir($dir_name)) {
            mkdir($dir_name, 0700);
        }

        $filename_new = mktime();
        $filename_pref = 'file_';
        if ($this->file_prefix) {
            $filename_pref = $this->file_prefix;
        }

        $name = 0;

        $file_params = array();
        if (isset($_FILES[$name_file])) {
            $filename_tmp = $_FILES[$name_file]['tmp_name'];
            if (is_uploaded_file($filename_tmp)) {

                $ext = substr($_FILES[$name_file]['name'], 1 + strrpos($_FILES[$name_file]['name'], "."));
                $file_params['ext'] = $ext;

                if ($_FILES[$name_file]['size'] > $this->max_size) {
                    echo "<script> alert('Ошибка при добавлении файла. Проверьте размер документа.');  </script>\n";
                    exit();
                } else if (!in_array($ext, $this->valid_types)) {
                    echo "<script> alert('Ошибка при добавлении файла. Проверьте  тип документа.');  </script>\n";
                    exit();
                } else {
                    $size = $_FILES[$name_file]['size'];
                    $file_params['size'] = $size;
                    if ($size) {

                        if (move_uploaded_file($filename_tmp, $dir_name . '/' . $filename_pref . $filename_new . '.' . $ext)) {
                            chmod($dir_name . '/' . $filename_pref . $filename_new . '.' . $ext, 0644);
                            $name = $filename_pref . $filename_new . '.' . $ext;
                            $file_params['name'] = $name;
                            $return = $name;
                            if ($this->return_params == 'array') {
                                $return = $file_params;
                            }

                            //Ресайз фото
                            if ($resize_options) {

                                if ($resize_options['output_file'] == 'thumb') {
                                    if (!is_dir($dir_name . '/thumb')) {
                                        mkdir($dir_name . '/thumb', 0700);
                                    }
                                    if (!Thumbnail::output($dir_name . '/' . $name, $dir_name . '/thumb/' . $name, $resize_options)) {
                                        echo "<script> alert('Ошибка при добавлении файла. Возможно, файл поврежден');  </script>\n";
                                        exit();
                                    }
                                } else {
                                    if (!Thumbnail::output($dir_name . '/' . $name, $dir_name . '/' . $name, $resize_options)) {
                                        echo "<script> alert('Ошибка при добавлении файла. Возможно, файл поврежден.');  </script>\n";
                                        exit();
                                    }
                                }
                            }

                            return $return;
                        } else {
                            echo "<script> alert('Файл не может быть перемещен в директорию назначения. Попробуйте еще раз, при повторении ошибки сообщите администратору сайта');  </script>\n";
                            exit();
                        }
                    } else {
                        echo "<script> alert('Ошибка при добавлении файла. Возможно, файл поврежден');  </script>\n";
                        exit();
                    }
                }
            } else {
                echo "<script> alert('Файл не может быть загружен. Попробуйте еще раз, при повторении ошибки сообщите администратору сайта');  </script>\n";
                exit();
            }
        } else {
            echo "<script> alert('Ошибка при добавлении файла. Файл не существует или поврежден'); window.history.go(-1); </script>\n";
            exit();
        }


        return false;
    }

    private static function isFileIsset($file) {
        $file_name = JPATH_BASE . DS . $file;
        return (bool) file_exists($file_name);
    }

    public function delFile(&$file) {
        $file_name = $file->directory . DS . $file->name;

        if (self::isFileIsset($file_name)) {
            unlink(JPATH_BASE . DS . $file_name);
            return true;
        }
        return false;
    }

    public static function get_image_from_text($text, $type = 'img', $default_image = null) {
        return ($type == 'mosimage') ? self::get_mosimage($text, $default_image) : self::get_image($text, $default_image);
    }

    public static function get_mosimage($images, $default_image = null) {
        $images = explode("\n", $images);
        $total = count($images);
        $image = '';
        for ($i = 0; $i < $total; $i++) {
            $image = trim($images[$i]);
            if ($image) {
                $filename = explode('|', $image);
                $image = $filename[0];
                break;
            }
        }

        if ($image) {
            return '/images/stories/' . $image;
        } elseif ($default_image) {
            return '/images/noimage.jpg';
        } else {
            return false;
        }
    }

    public static function get_image($text, $default_image = null) {

        $matches = array();
        $regex = '#<img[^>]*src=(["\'])([^"\']*)\1[^>]*>#is';
        if (preg_match($regex, $text, $matches)) {
            $img = $matches[2];
            $img = self::check_href($img);
            return $img;
        } elseif ($default_image) {
            return '/images/noimage.jpg';
        } else {
            return false;
        }
    }

    function check_href($href) {
        if (!(substr($href, 0, 4) == 'http') && !(substr($href, 0, 1) == '/')) {
            $href = '/' . $href;
        }

        return $href;
    }

}

/**
 * This is a driver for the thumbnail creating
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * The PHP License, version 3.0
 *
 * Copyright (c) 1997-2005 The PHP Group
 *
 * This source file is subject to version 3.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available through the world-wide-web at the following url:
 * http://www.php.net/license/3_0.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @author	  Ildar N. Shaimordanov <ildar-sh@mail.ru>
 * @license	 http://www.php.net/license/3_0.txt
 * 			  The PHP License, version 3.0
 */
// {{{

/**
 * Maximal scaling
 */
define('THUMBNAIL_METHOD_SCALE_MAX', 0);

/**
 * Minimal scaling
 */
define('THUMBNAIL_METHOD_SCALE_MIN', 1);

/**
 * Cropping of fragment
 */
define('THUMBNAIL_METHOD_CROP', 2);

/**
 * Align constants
 */
define('THUMBNAIL_ALIGN_CENTER', 0);
define('THUMBNAIL_ALIGN_LEFT', -1);
define('THUMBNAIL_ALIGN_RIGHT', +1);
define('THUMBNAIL_ALIGN_TOP', -1);
define('THUMBNAIL_ALIGN_BOTTOM', +1);

// }}}
// {{{

class Thumbnail {
    // {{{

    /**
     * Create a GD image resource from given input.
     *
     * This method tried to detect what the input, if it is a file the
     * createImageFromFile will be called, otherwise createImageFromString().
     *
     * @param  mixed $input The input for creating an image resource. The value
     * 					  may a string of filename, string of image data or
     * 					  GD image resource.
     *
     * @return resource	 An GD image resource on success or false
     * @access public
     * @static
     * @see	Thumbnail::imageCreateFromFile(), Thumbnail::imageCreateFromString()
     */
    function imageCreate($input) {
        if (is_file($input)) {
            return Thumbnail::imageCreateFromFile($input);
        } else if (is_string($input)) {
            return Thumbnail::imageCreateFromString($input);
        } else {
            return $input;
        }
    }

    // }}}
    // {{{

    /**
     * Create a GD image resource from file (JPEG, PNG support).
     *
     * @param  string $filename The image filename.
     *
     * @return mixed			GD image resource on success, FALSE on failure.
     * @access public
     * @static
     */
    function imageCreateFromFile($filename) {
        if (!is_file($filename) || !is_readable($filename)) {
            user_error('Unable to open file "' . $filename . '"', E_USER_NOTICE);
            return false;
        }

        // determine image format
        list(,, $type) = getimagesize($filename);

        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_PNG:
                return imagecreatefrompng($filename);
                break;
        }
        user_error('Unsupport image type', E_USER_NOTICE);
        return false;
    }

    // }}}
    // {{{

    /**
     * Create a GD image resource from a string data.
     *
     * @param  string $string The string image data.
     *
     * @return mixed		  GD image resource on success, FALSE on failure.
     * @access public
     * @static
     */
    function imageCreateFromString($string) {
        if (!is_string($string) || empty($string)) {
            user_error('Invalid image value in string', E_USER_NOTICE);
            return false;
        }

        return imagecreatefromstring($string);
    }

    // }}}
    // {{{

    /**
     * Display rendered image (send it to browser or to file).
     * This method is a common implementation to render and output an image.
     * The method calls the render() method automatically and outputs the
     * image to the browser or to the file.
     *
     * @param  mixed   $input   Destination image, a filename or an image string data or a GD image resource
     * @param  array   $options Thumbnail options
     * 		 <pre>
     * 		 width   int	Width of thumbnail
     * 		 height  int	Height of thumbnail
     * 		 percent number Size of thumbnail per size of original image
     * 		 method  int	Method of thumbnail creating
     * 		 halign  int	Horizontal align
     * 		 valign  int	Vertical align
     * 		 </pre>
     *
     * @return boolean		  TRUE on success or FALSE on failure.
     * @access public
     */
    function output($input, $output=null, $options=array()) {
        // Load source file and render image
        $renderImage = Thumbnail::render($input, $options);
        if (!$renderImage) {
            user_error('Error rendering image', E_USER_NOTICE);
            return false;
        }

        // Set output image type
        // By default PNG image
        $type = isset($options['type']) ? $options['type'] : IMAGETYPE_PNG;

        // Before output to browsers send appropriate headers
        if (empty($output)) {
            $content_type = image_type_to_mime_type($type);
            if (!headers_sent()) {
                header('Content-Type: ' . $content_type);
            } else {
                user_error('Headers have already been sent. Could not display image.', E_USER_NOTICE);
                return false;
            }
        }

        // Define outputing function
        switch ($type) {
            case IMAGETYPE_PNG:
                $result = empty($output) ? imagepng($renderImage) : imagepng($renderImage, $output);
                break;
            case IMAGETYPE_JPEG:
                $result = empty($output) ? imagejpeg($renderImage) : imagejpeg($renderImage, $output);
                break;
            default:
                user_error('Image type ' . $content_type . ' not supported by PHP', E_USER_NOTICE);
                return false;
        }

        // Output image (to browser or to file)
        if (!$result) {
            user_error('Error output image', E_USER_NOTICE);
            return false;
        }

        // Free a memory from the target image
        imagedestroy($renderImage);

        return true;
    }

    // }}}
    // {{{ render()

    /**
     * Draw thumbnail result to resource.
     *
     * @param  mixed   $input   Destination image, a filename or an image string data or a GD image resource
     * @param  array   $options Thumbnail options
     *
     * @return boolean TRUE on success or FALSE on failure.
     * @access public
     * @see	Thumbnail::output()
     */
    function render($input, $options=array()) {
        // Create the source image
        $sourceImage = Thumbnail::imageCreate($input);
        if (!is_resource($sourceImage)) {
            user_error('Invalid image resource', E_USER_NOTICE);
            return false;
        }
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        // Set default options
        static $defOptions = array(
    'width' => 150,
    'height' => 150,
    'method' => THUMBNAIL_METHOD_SCALE_MAX,
    'percent' => 0,
    'halign' => THUMBNAIL_ALIGN_CENTER,
    'valign' => THUMBNAIL_ALIGN_CENTER,
        );
        foreach ($defOptions as $k => $v) {
            if (!isset($options[$k])) {
                $options[$k] = $v;
            }
        }

        // Estimate a rectangular portion of the source image and a size of the target image
        if ($options['method'] == THUMBNAIL_METHOD_CROP) {
            if ($options['percent']) {
                $W = floor($options['percent'] * $sourceWidth);
                $H = floor($options['percent'] * $sourceHeight);
            } else {
                $W = $options['width'];
                $H = $options['height'];
            }

            $width = $W;
            $height = $H;

            $Y = Thumbnail::_coord($options['valign'], $sourceHeight, $H);
            $X = Thumbnail::_coord($options['halign'], $sourceWidth, $W);
        } else {
            $X = 0;
            $Y = 0;

            $W = $sourceWidth;
            $H = $sourceHeight;

            if ($options['percent']) {
                $width = floor($options['percent'] * $W);
                $height = floor($options['percent'] * $H);
            } else {
                $width = $options['width'];
                $height = $options['height'];

                if ($options['method'] == THUMBNAIL_METHOD_SCALE_MIN) {
                    $Ww = $W / $width;
                    $Hh = $H / $height;
                    if ($Ww > $Hh) {
                        $W = floor($width * $Hh);
                        $X = Thumbnail::_coord($options['halign'], $sourceWidth, $W);
                    } else {
                        $H = floor($height * $Ww);
                        $Y = Thumbnail::_coord($options['valign'], $sourceHeight, $H);
                    }
                } else {
                    if ($H > $W) {
                        $width = floor($height / $H * $W);
                    } else {
                        $height = floor($width / $W * $H);
                    }
                }
            }
        }

        // Create the target image
        if (function_exists('imagecreatetruecolor')) {
            $targetImage = imagecreatetruecolor($width, $height);
        } else {
            $targetImage = imagecreate($width, $height);
        }
        if (!is_resource($targetImage)) {
            user_error('Cannot initialize new GD image stream', E_USER_NOTICE);
            return false;
        }

        // Copy the source image to the target image
        if ($options['method'] == THUMBNAIL_METHOD_CROP) {
            $result = imagecopy($targetImage, $sourceImage, 0, 0, $X, $Y, $W, $H);
        } elseif (function_exists('imagecopyresampled')) {
            $result = imagecopyresampled($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
        } else {
            $result = imagecopyresized($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
        }
        if (!$result) {
            user_error('Cannot resize image', E_USER_NOTICE);
            return false;
        }

        // Free a memory from the source image
        imagedestroy($sourceImage);

        // Save the resulting thumbnail
        return $targetImage;
    }

    // }}}
    // {{{ _coord()

    function _coord($align, $param, $src) {
        if ($align < THUMBNAIL_ALIGN_CENTER) {
            $result = 0;
        } elseif ($align > THUMBNAIL_ALIGN_CENTER) {
            $result = $param - $src;
        } else {
            $result = ($param - $src) >> 1;
        }
        return $result;
    }

}