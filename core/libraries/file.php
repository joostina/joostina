<?php defined('_JOOS_CORE') or die();

/**
 * Работа с файлами
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage File
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * @todo оценить необходимость использования clearstatcache
 *
 * */
class joosFile {

	/**
	 * Логическое представление размера файлов, памяти и прочив байтовых данных
	 *
	 * @tutorial joosFile::convert_size(123);
	 * @tutorial joosFile::convert_size(123456);
	 *
	 * @param string $num исходные строка или число для форматирования
	 * @return string|int
	 */
	public static function convert_size($num) {

        $num = (int) $num;

        if ($num >= 1073741824) {
            $num = round($num / 1073741824 * 100) / 100 . ' ' . 'gb';
        } else if ($num >= 1048576) {
            $num = round($num / 1048576 * 100) / 100 . ' ' . 'mb';
        } else if ($num >= 1024) {
            $num = round($num / 1024 * 100) / 100 . ' ' . 'kb';
        } else {
            $num .= ' ' . 'byte';
        }
        return $num;
    }

    /**
     * Удаление файла
     *
     * @tutorial joosFile::delete( JPATH_BASE . DS. '_to_delete.php' );
     *
     * @param string $file_name полный путь к файлу, либо массив полный путей к удаляемым файлам
     *
     * @return bool результат удаления
     */
    public static function delete($file_name) {

        self::exception_if_file_not_exists($file_name);

        return unlink((string) $file_name);
    }

    /**
     * Проверка существования файла
     *
     * @tutorial joosFile::exists( JPATH_BASE . DS. 'index.php' );
     *
     * @param string $file_name
     *
     * @return bool результат проверки
     */
    public static function exists($file_name) {
        return (bool) ( file_exists($file_name) && is_file($file_name) );
    }

    /**
     * Получение MIME типа файла
     *
     * @tutorial  joosFile::get_mime_content_type( __FILE__ );
     * @tutorial  joosFile::get_mime_content_type( JPATH_BASE . DS . 'media' . DS . 'favicon.ico' );
     * @tutorial  joosFile::get_mime_content_type( JPATH_BASE . DS . 'media' . DS . 'js' . DS . 'jquery.js');
     *
     * @param string $file_name
     *
     * @return string
     */
    public static function get_mime_content_type($file_name) {

        if(function_exists('finfo_open')){
            $options=defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME;
            $info= finfo_open($options);

            if($info && ($result=finfo_file($info,$file_name))!==false){
                return $result;
            }
        }

        if(function_exists('mime_content_type') && ($result=mime_content_type($file_name))!==false){
            return $result;
        }

        return self::get_mime_content_type_by_extension($file_name);

    }

    /**
     * Получение MIME типа файла по расширению
     *
     * @tutorial  joosFile::get_mime_content_type_by_extension( __FILE__ );
     * @tutorial  joosFile::get_mime_content_type_by_extension( JPATH_BASE . DS . 'media' . DS . 'favicon.ico' );
     * @tutorial  joosFile::get_mime_content_type_by_extension( JPATH_BASE . DS . 'media' . DS . 'js' . DS . 'jquery.js');
     *
     * @param string $file_name
     *
     * @return string
     */
    public static function get_mime_content_type_by_extension($file_name) {
        $mime_types = array(// all
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            'sql' => 'text/x-sql',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'tga' => 'image/x-targa',
            'psd' => 'image/vnd.adobe.photoshop',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            'gz' => 'application/x-gzip',
            'tgz' => 'application/x-gzip',
            'bz' => 'application/x-bzip2',
            'bz2' => 'application/x-bzip2',
            'tbz' => 'application/x-bzip2',
            'tar' => 'application/x-tar',
            '7z' => 'application/x-7z-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'avi' => 'video/x-msvideo',
            'dv' => 'video/x-dv',
            'mp4' => 'video/mp4',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'wm' => 'video/x-ms-wmv',
            'mkv' => 'video/x-matroska',
            // adobe
            'pdf' => 'application/pdf',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
        );

        $file_info = pathinfo($file_name);
        $ext = $file_info['extension'];
        if (isset($mime_types[$ext])) {
            return $mime_types[$ext];
        } else {
            return 'application/octet-stream';
        }
    }

    /**
     * Формирование вложенного пути к файлу с учетом разделения по каталогам
     *
     * @tutorial joosFile::make_file_location( 1 );
     * @tutorial joosFile::make_file_location( 123 );
     * @tutorial joosFile::make_file_location( 123456789123456789 );
     *
     * @param integer $id - номер файла в БД
     *
     * @return string - путь к файлу в структуре подкаталогов
     *
     * @todo задокументировать новые параметры
     */
    public static function make_file_location($id, $split_by = 3, $capacity = 9, $round = false) {

        // округляем
        $id = $round ? (int) round($id / $round) : $id;

        if (!is_integer($id)) {
            throw new joosFileLibrariesException('Параметр $id должен иметь цельночисленное значение');
        }

        if (!is_integer($split_by)) {
            throw new joosFileLibrariesException('Параметр $split_by должен иметь цельночисленное значение');
        }

        if (!is_integer($capacity)) {
            throw new joosFileLibrariesException('Параметр $capacity должен иметь цельночисленное значение');
        }

        $p = sprintf('%0' . $capacity . 'd', $id);
        $h = str_split($p, $split_by);
        return implode('/', $h);
    }

    /**
     * Получение полной информации о файле
     *
     * @tutorial joosFile::file_info( __FILE__ );
     * @tutorial joosFile::file_info( JPATH_BASE . DS. 'index.php'  );
     * @tutorial joosFile::file_info( 'index.html' );
     *
     * @param string $file_name абсолюютный или относительный путь до файла
     *
     * @return array массив информации о файле
     * 		 mime - mime тип файла
     * 		 size - размер файла в байтах
     * 		 ext - расширение файла
     * 		 name - имя файла с расширением
     *
     * @todo переделать на SplFileInfo
     */
    public static function file_info($file_name) {

        self::exception_if_file_not_exists($file_name);

        $f = pathinfo($file_name);

        $r = array();
        $r['mime'] = self::mime_content_type($file_name);
        $r['size'] = filesize($file_name);
        $r['ext'] = $f['extension'];
        $r['name'] = $f['basename'];

        return $r;
    }

    /**
     * Преобразование имени файла к безопасному для файлвоой системы виду
     * Из строки удаляются все спецсимволы, кирилические символы транслитерируются
     *
     * @tutorial  joosFile::get_safe_name('имя файла номер 1 - ( раз)');
     * @tutorial  joosFile::get_safe_name(' eminem feat dr.dre i need a doctor.mp3 ');
     *
     * @param type $file_name
     *
     * @return type
     */
    public static function make_safe_name($file_name) {
        // убираем непроизносимые русские мязкие звуки
        $file_name = str_ireplace(array('ь', 'ъ'), '', $file_name);
        // переводим в транслит
        $file_name = joosText::russian_transliterate($file_name);
        // в нижний регистр
        $file_name = strtolower($file_name);
        // заменям все ненужное нам на "-"
        $file_name = str_replace(array("'", '-'), ' ', $file_name);
        $file_name = preg_replace('/[^\-a-z0-9\._]+/u', '-', $file_name);
        return trim($file_name, '-');
    }

    /**
     * Получение даты последнего изменения файла
     *
     * @param string $file_name абсолюютный или относительный путь до файла
     * @return bool
     * @throws joosFileLibrariesException
     */
    public static function get_modified_date($file_name) {

        self::exception_if_file_not_exists($file_name);

        return filemtime($file_name);
    }

    /**
     * Получение размера файла ( в байта )
     *
     * @param string $file_name абсолюютный или относительный путь до файла
     * @return type
     * @throws joosFileLibrariesException
     */
    public static function get_size($file_name) {

        self::exception_if_file_not_exists($file_name);

        return filesize($file_name);
    }

    /**
     * Получение типа файла
     *
     * @param string $file_name абсолюютный или относительный путь до файла
     * @return type
     * @throws joosFileLibrariesException
     */
    public static function get_type($file_name) {

        self::exception_if_file_not_exists($file_name);

        return filetype($file_name);
    }

    /**
     * Записи данных в файл
     *
     * @param string $file_name абсолюютный или относительный путь до файла
     * @param string $data данные для записи в файл
     * @return type
     * @throws joosFileLibrariesException
     */
    public static function put_content($file_name, $data) {

        self::exception_if_file_not_exists($file_name);

        if (!joosFile::is_writable($file_name)) {
            throw new joosFileLibrariesException('Файл :file не доступен для записи', array(':file' => $file_name));
        }

        return file_put_contents($file_name, $data, LOCK_EX);
    }

    /**
     * Получение содержимого файла
     *
     * @param string $file_name абсолюютный или относительный путь до файла
     * @return type
     * @throws joosFileLibrariesException
     */
    public static function get_content($file_name) {

        self::exception_if_file_not_exists($file_name);

        if (!joosFile::is_writable($file_name)) {
            throw new joosFileLibrariesException('Файл :file не доступен для чтения', array(':file' => $file_name));
        }

        return file_get_contents($file_name);
    }

    /**
     * Проверка прав доступа на запись в файл
     *
     * @param string $file_location полный путь к каталогу
     *
     * @return bool результат проверки доступа на запись в указанный каталог
     */
    public static function is_writable($file_location) {
        return (bool) is_writable($file_location);
    }

    /**
     * Проверка прав доступа на чтение файла
     *
     * @param string $file_location полный путь к каталогу
     *
     * @return bool результат проверки доступа на запись в указанный каталог
     */
    public static function is_readable($file_location) {
        return (bool) is_readable($file_location);
    }

    /**
     * Внутренний метод проверки существования файла
     * В случае ошибки выбрасывает исключение joosFileLibrariesException
     *
     * @static
     * @param $file_name путь к файлу
     * @throws joosFileLibrariesException
     */
    private static function exception_if_file_not_exists($file_name){
        if (!joosFile::exists($file_name)) {
            throw new joosFileLibrariesException('Файл :file не существует', array(':file' => $file_name));
        }
    }
}

/**
 * Обработчик ошибок для библиотеки joosFile
 */
class joosFileLibrariesException extends joosException {

}
