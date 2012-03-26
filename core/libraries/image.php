<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
  * Библиотека работы с изображениями
 * Системная библиотека
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @subpackage joosImage
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosImage {

	/**
	 * Image::get_image_from_text()
	 * Получение ссылки на ресурс первого изображения в тексте
	 *
	 * @param string $text
	 * @param int    $default_image
	 *
	 * @return stirng
	 */
	public static function get_image_from_text($text, $default_image = null) {

		$matches = array();
		$regex = '#<img[^>]*src=(["\'])([^"\']*)\1[^>]*>#is';
		if (preg_match($regex, $text, $matches)) {
			$img = $matches[2];
			return $img;
		} elseif ($default_image) {
			return '/images/noimage.jpg';
		} else {
			return false;
		}
	}

	/**
	 * Генерация HTML-представления изображения
	 *
	 * @param string $dir        Директория
	 * @param int    $image_id   ID изображения
	 * @param str    $size       Размер изображения
	 * @param array  $image_attr Аттрибуты изображения
	 *
	 * @return stirng
	 */
	public static function get_image($dir = '', $image_id = 0, $size = '', $image_attr = array()) {
		if ($image_id && $dir) {

			$location = joosFile::make_file_location($image_id);
			$size = $size ? 'image_' . $size . '.png' : 'image.png';
			$file_location = '/attachments/' . $dir . '/' . $location . '/' . $size;
			$image_attr += array('src' => JPATH_SITE . '/' . $file_location);
			return is_file(JPATH_BASE . DS . $file_location) ? joosHtml::image($image_attr) : false;
		}
		return false;
	}

	public static function get_image_default($image_attr = array()) {
		$file_location = JPATH_SITE . '/media/images/nomp3s.jpg';
		$image_attr += array('src' => $file_location,
			'alt' => '');
		return joosHtml::image($image_attr);
	}

}

/**
 * Maximal scaling
 * Изображение уменьшается по бОльшей стороне
 */
define('THUMBNAIL_METHOD_SCALE_MAX', 0);

/**
 * Minimal scaling
 * Изображение уменьшается по меньшей стороне
 */
define('THUMBNAIL_METHOD_SCALE_MIN', 1);

/**
 * Cropping of fragment
 * Изображение вырезается до точных размеров
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

/**
  * Библиотека работы с изображениями
 * Системная библиотека
 *
 * @version     1.0
 * @package     Libraries
 * @subpackage  Libraries
 * @subpackage  joosImage
 * @author      Joostina Team <info@joostina.ru>
 * @copyright   (C) 2007-2012 Joostina Team
 * @license     MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * @author      Ildar N. Shaimordanov <ildar-sh@mail.ru>
 * @license     http://www.php.net/license/3_0.txt  The PHP License, version 3.0
 *
 * */
class Thumbnail {

	private static $debug = array();

	public static function get_debug() {
		return self::$debug;
	}

	/**
	 * Создание GD-ресурса
	 *
	 * Метод пытается определить, какие данные пришли - если это файл,
	 * будет вызван метод createImageFromFile(), иначе - createImageFromString()
	 *
	 * @param  mixed $input      Входящие данные для создания изображения.
	 *                             Это может быть строка-имя файла, строка - данные изображения
	 *                             или GD-ресур изображения
	 *
	 * @return resource             GD-ресур изображения или false в случае неудачи
	 * @access public
	 * @static
	 * @see    Thumbnail::imageCreateFromFile(), Thumbnail::imageCreateFromString()
	 */
	public static function imageCreate($input) {
		if (is_file($input)) {
			return Thumbnail::imageCreateFromFile($input);
		} else if (is_string($input)) {
			return Thumbnail::imageCreateFromString($input);
		} else {
			return $input;
		}
	}

	/**
	 * Создание GD-ресурса из файла
	 *
	 * @param  string $filename Имя файла.
	 *
	 * @return mixed GD image resource или FALSE при неудаче.
	 * @access public
	 * @static
	 */
	public static function imageCreateFromFile($filename) {
		if (!is_file($filename) || !is_readable($filename)) {
			user_error('Unable to open file "' . $filename . '"', E_USER_NOTICE);
			return false;
		}

		// determine image format
		list(,, $type ) = getimagesize($filename);

		switch ($type) {

			case IMAGETYPE_JPEG:
				return imagecreatefromjpeg($filename);
				break;

			case IMAGETYPE_GIF:
				return imagecreatefromgif($filename);
				break;

			case IMAGETYPE_PNG:
				return imagecreatefrompng($filename);
				break;
		}
		user_error('Unsupport image type', E_USER_NOTICE);
		return false;
	}

	/**
	 * Создание GD-ресурса из строки
	 *
	 * @param  string $string Картинка-строка.
	 *
	 * @return mixed GD image resource или FALSE при неудаче.
	 * @access public
	 * @static
	 */
	public static function imageCreateFromString($string) {
		if (!is_string($string) || empty($string)) {
			user_error('Invalid image value in string', E_USER_NOTICE);
			return false;
		}

		return imagecreatefromstring($string);
	}

	/**
	 * Вывод сгенерированного изображения
	 * Данный метод вызывает метод render() и выводит сгенерированное изображение в браузер или файл
	 *
	 * @param  mixed   $input   Имя файла, изображение-строка или GD-resource
	 * @param  mixed   $output  Имя файла-результата. Если null - будет выведено в браузер
	 * @param  array   $options Массив настроек
	 *          <pre>
	 *          width   int    Ширина изображения-результата
	 *          height  int    Высота изображения-результата
	 *          percent number Размер изображения-результата в процентах от исходного
	 *          method  int    Метод ресайза
	 *          halign  int    Горизонтальное выравнивание
	 *          valign  int    Вертикальное выравнивание
	 *          check_size int Производить проверку размеров (в этом случае изображение не ресайзится, если необходимый размер больше исходного)
	 *          quality int    Качество выдаваемого изображения. 0-100
	 *          x int        Растояние в пикселях от левого края, для обрезания
	 *          y int        Растояние в пикселях от верхнего края, для обрезания
	 *         </pre>
	 *
	 * @return boolean          TRUE on success or FALSE on failure.
	 * @access public
	 */
	public static function output($input, $output = null, $options = array()) {

		// Load source file and render image
		$renderImage = Thumbnail::render($input, $options);
		if (!$renderImage) {
			user_error('Error rendering image', E_USER_NOTICE);
			return false;
		}

		// Set output image type
		// By default PNG image
		$type = isset($options['type']) ? $options['type'] : IMAGETYPE_PNG;
		$quality = isset($options['quality']) ? $options['quality'] : ( $type == IMAGETYPE_PNG ? 8 : 80 );
		$quality = ( $type == IMAGETYPE_PNG ? (int) $quality / 10 : $quality ); // что бы не указывать в параметрах 0-100 для JPG и 0-9 для PNG - можно всегда 0-100, а тут подправим
		// Before output to browsers send appropriate headers
		if (empty($output)) {
			$content_type = image_type_to_mime_type($type);
			if (!headers_sent()) {
				header('Content-Type: ' . $content_type);
			} else {
				user_error('Headers have already been sent. Could not display image.', E_USER_NOTICE);
				return false;
			}
		} else {
			$content_type = 'ERROR';
		}


		switch ($type) {
			case IMAGETYPE_GIF:
				$result = empty($output) ? imagegif($renderImage) : imagegif($renderImage, $output);
				break;

			case IMAGETYPE_PNG:
				$result = empty($output) ? imagepng($renderImage) : imagepng($renderImage, $output, $quality);
				break;

			case IMAGETYPE_JPEG:
				$result = empty($output) ? imagejpeg($renderImage) : imagejpeg($renderImage, $output, $quality);
				break;
			default:
				user_error('Image type ' . $content_type . ' not supported by PHP', E_USER_NOTICE);
				return false;
		}


		if (!$result) {
			user_error('Error output image', E_USER_NOTICE);
			return false;
		}

		// освобождаем память, выделенную для изображения
		imagedestroy($renderImage);

		return true;
	}

	/**
	 * Процесс создания копии изображения с заданными параметрами
	 *
	 * @param  mixed   $input    Имя файла, изображение-строка или GD-resource
	 * @param  array   $options  Массив настроек
	 *
	 * @return resource|boolean TRUE или FALSE.
	 * @access public
	 * @see    Thumbnail::output()
	 */
	public static function render($input, $options = array()) {

		// Создаем ресурс
		$sourceImage = Thumbnail::imageCreate($input);
		if (!is_resource($sourceImage)) {
			user_error('Invalid image resource', E_USER_NOTICE);
			return false;
		}
		$sourceWidth = imagesx($sourceImage);
		$sourceHeight = imagesy($sourceImage);

		// Устанавливаем настройки по-умолчанию
		static $defOptions = array('width' => 150,
	'height' => 150,
	'method' => THUMBNAIL_METHOD_SCALE_MAX,
	'percent' => 0,
	'halign' => THUMBNAIL_ALIGN_CENTER,
	'valign' => THUMBNAIL_ALIGN_CENTER,
	'check_size' => 0,
	'resize' => 1);
		foreach ($defOptions as $k => $v) {
			if (!isset($options[$k])) {
				$options[$k] = $v;
			}
		}

		$resize = 1;
		if (( $options['check_size'] == 1 && $sourceWidth <= $options['width'] && $sourceHeight <= $options['height'] ) || $options['resize'] == 0) {
			$resize = 0;
		}

		if ($resize) {

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
		} else {
			$W = $sourceWidth;
			$H = $sourceHeight;
			$width = $sourceWidth;
			$height = $sourceHeight;
			$X = 0;
			$Y = 0;
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

		if ($options['method'] == THUMBNAIL_METHOD_CROP && isset($options['x']) && isset($options['y'])) {
			$X = $options['x'];
			$Y = $options['y'];
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

		// освобождаем память, выделенную для изображения
		imagedestroy($sourceImage);

		return $targetImage;
	}

	private static function _coord($align, $param, $src) {
		if ($align < THUMBNAIL_ALIGN_CENTER) {
			$result = 0;
		} elseif ($align > THUMBNAIL_ALIGN_CENTER) {
			$result = $param - $src;
		} else {
			$result = ( $param - $src ) >> 1;
		}
		return $result;
	}

	/**
	 * Пакетное создание превью
	 *
	 * @param string $original Полный путь до оригинального изображения
	 * @param stirng $path     Путь до папки назначения
	 * @param array  $params   Массив параметров
	 * @param stirng $ext      Расширение изображений-результатов
	 */
	public static function create_thumbs($original, $path, $params, $ext = 'jpg', $quality = 80) {

		//определим ориентацию изображения - портретная или альбомная
		list( $width, $height ) = getimagesize($original);
		if ($width > $height) {
			$o = 'album'; //альбомная ориентация
		} else if ($height > $width) {
			$o = 'portret'; //портретная ориентация
		} else {
			$o = 'square'; //квадратное
		}

		$thumb_params = array();
		$thumb_params['check_size'] = 1;
		$thumb_params['quality'] = $quality;
		$thumb_params['type'] = IMAGETYPE_JPEG;


		foreach ($params as $key => $thumb) {
			$thumb_params['resize'] = 1;

			if ($thumb[0] && $thumb[0] > 0) {
				$thumb_params['width'] = $thumb[0];
			}

			if (isset($thumb[1])) {
				$thumb_params['height'] = $thumb[1];
			}

			//если указана только ширина
			if (isset($thumb_params['width']) && !isset($thumb_params['height'])) {

				//если исходная ширина меньше требуемой - не изменяем размеры
				if ($width <= $thumb_params['width']) {
					$thumb_params['resize'] = 0;
				}

				switch ($o) {
					case 'album':
					case 'square':
					default:
						//уменьшаем по большей стороне
						$thumb_params['method'] = THUMBNAIL_METHOD_SCALE_MAX;
						break;

					case 'portret':
						//уменьшаем по меньшей стороне
						$thumb_params['method'] = THUMBNAIL_METHOD_SCALE_MIN;
						$thumb_params['height'] = floor(( $height * $thumb_params['width'] ) / $width);
						break;
				}
			}

			//если указана только высота
			else if (isset($thumb_params['height']) && !isset($thumb_params['width'])) {

				//если исходная высота меньше требуемой - не изменяем размеры
				if ($height <= $thumb_params['height']) {
					$thumb_params['resize'] = 0;
				}

				switch ($o) {
					case 'album':
					case 'square':
					default:
						//уменьшаем по меньшей стороне
						$thumb_params['method'] = THUMBNAIL_METHOD_SCALE_MIN;

						break;

					case 'portret':
						//уменьшаем по большей стороне
						$thumb_params['method'] = THUMBNAIL_METHOD_SCALE_MAX;
						break;
				}
			}

			//если указаны точные размеры
			else if (isset($thumb_params['width']) && isset($thumb_params['height'])) {

				switch ($o) {
					case 'album':
					case 'square':
					default:
						if ($width > $thumb_params['width']) {
							//уменьшаем по большей стороне
							$thumb_params['method'] = THUMBNAIL_METHOD_SCALE_MIN;
							Thumbnail::output($original, $path . '/' . $key . '.' . $ext, $thumb_params);
						} else {
							$thumb_params['resize'] = 0;
						}

						break;

					case 'portret':
						if ($height > $thumb_params['height']) {
							//уменьшаем по меньшей стороне
							$thumb_params['method'] = THUMBNAIL_METHOD_SCALE_MIN;
							Thumbnail::output($original, $path . '/' . $key . '.' . $ext, $thumb_params);
						} else {
							$thumb_params['resize'] = 0;
						}
						break;
				}

				// обрезаем
				$thumb_params['method'] = THUMBNAIL_METHOD_CROP;

				if ($thumb_params['resize'] == 1) {
					$original = $path . '/' . $key . '.' . $ext;
				}
			}

			Thumbnail::output($original, $path . '/' . $key . '.' . $ext, $thumb_params);
		}
	}

}
