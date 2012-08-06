<?php defined('_JOOS_CORE') or exit;

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Components\Test
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsTest extends joosController
{
    /**
     * Метод контроллера, запускаемый по умолчанию
     *
     * @static
     * @return array
     */
    public function index()
    {

        return array(
	        'time'=>time()
        );
    }

    /**
     * Тестирование загрузчика
     */
    public function upload()
    {
        return array();
    }

    /**
        * Тестирование конфигурации
        */
       public function config()
       {
           return array();
       }

    /**
     * Для тестирования вёрстки
     *
     */
    public function layouts()
    {
        $tpl = self::$param['tpl'];

        return array(
            'template' => $tpl
        );
    }

}

/**
 *
 */
class rulesValidation
{
    public static $params = array(
        // разрешенные расширения
        'allowed_ext' => array('jpg', 'gif', 'png'),
        // разрешённые типы файлов
        'allowed_mime'=>array('images/jpeg'),
        // масимальный размер файла
        'max_size'=>'10mb',
        // минимальный размер файла
        'min_size'=>'1mb',
        // переименовывать файл в порядковый номер
        'rename'=>true,
        // транслитерировать имя файла
        'transliterate_name'=>'true',
        // использовать системную работу с аттачами
        'use_attached'=>true,
        // подкаталог для размещения аттачей
        'dir'=>'picsiki',
        // максимальное число файлов для выбора, 1 для отключения мультиселекта
        'max_multi_select'=>5,
        // использовать загрузку через перетаскивание файла в браузер
        'use_drag_drop'=>true,
        // показывать прогресс-бар
        'show_progress'=>true,
        // расширенные настройки js плагина, по правилам https://github.com/blueimp/jQuery-File-Upload/wiki/Options
        'extra_options'=>array(
            'replaceFileInput'=>true
        ),
        // параметры изменения размера изображений и создания превьюшек
        'resize_images'=>array(
            'small'=>array(
                'w'=>100,
                'h'=>150,
                'method'=>'scall_min',
                'quality'=>90
            ),
            'big'=>array(
                'w'=>500,
                'h'=>350,
                'method'=>'scall_max',
                'quality'=>90
            ),
            // удалять оригинальный файл изображения
            'delete_original'=>'true'
        )
    );

}
