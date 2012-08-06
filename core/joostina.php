<?php defined('_JOOS_CORE') or exit;

/**
 * Ядро
 *
 * @package   Core
 * @author    JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */

/**
 * Короткий синоним разделителя каталогов
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Корень системы, на уровень выше текущего каталога
 */
define('JPATH_BASE', dirname(__DIR__));

// Обработчик ошибок
require JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . 'exception.php';
// Автозагрузчик
require JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . 'autoloader.php';
// предстартовые конфигурации
require JPATH_BASE . DS . 'app' . DS . 'bootstrap.php';

/**
 * Главное ядро Joostina CMS
 *
 * Описывать класс можно более чем подробно
 * - Первое.
 * - Второе, много разного текста
 *   даже нна несколкьо строк.
 * - И третье.
 *
 * @package    Joostina
 * @subpackage Core
 */
class joosCore
{
    /**
     * Флаг работы ядра в режиме FALSE - сайт, TRUE - панель управления
     *
     * @var bool
     */
    private static $is_admin = false;

    /**
     * Получение инстанции текущего авторизованного пользователя
     * Функция поддерживает работу и на фронте и в панели управления сайта
     *
     * @tutorial joosCore::user() => Объект пользователя modelUsers
     *
     * @return modelUsers
     */
    public static function user()
    {
        return self::$is_admin ? joosCoreAdmin::user() : modelUsers::instance();
    }

    public static function set_admin_mode()
    {
        self::$is_admin = TRUE;
    }

    public static function is_admin()
    {
        return (bool) self::$is_admin;
    }

    /**
     * Вычисление пути расположений файлов
     *
     * @param string $name название объекта
     * @param string $type тип объекта, компонент, модуль
     * @param string $cat  категория ( для библиотек )
     *
     * @return bool|string
     */
    public static function path($name, $type, $cat = '')
    {
        (JDEBUG && $name != 'debug') ? joosDebug::inc(sprintf('joosCore::%s - <b>%s</b>', $type, $name)) : null;

        switch ($type) {
            case 'controller':
                $file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.php';
                break;

            case 'admin_controller':
                $file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.admin.' . $name . '.php';
                break;

            case 'ajax_controller':
                $file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'controller.' . $name . '.ajax.php';
                break;

            case 'model':
                $file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.' . $name . '.php';
                break;

            case 'admin_model':
                $file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'models' . DS . 'model.admin.' . $name . '.php';
                break;

            case 'view':
                $file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'views' . DS . $cat . DS . 'default.php';
                break;

            case 'admin_view':
                $file = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $name . DS . 'views_admin' . DS . $cat . DS . 'default.php';
                break;

            case 'admin_template_html':
                $file = JPATH_BASE . DS . 'app' . DS . 'templates' . DS . JTEMPLATE . DS . 'html' . DS . $name . '.php';
                break;

            case 'module_helper':
                $file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'helper.' . $name . '.php';
                break;

            case 'module_admin_helper':
                $file = JPATH_BASE . DS . 'app' . DS . 'modules' . DS . $name . DS . 'helper.' . $name . '.php';
                break;

            case 'lib':
                $file = JPATH_BASE . DS . 'core' . DS . 'libraries' . DS . $name . '.php';
                break;

            case 'lib-vendor':
                $file = JPATH_BASE . DS . 'app' . DS . 'vendors' . DS . $cat . DS . $name . DS . $name . '.php';
                break;

            default:
                throw new joosCoreException('Не найдено определние для типа файла :file_type', array(':file_type' => $type));
                break;
        }

        if (JDEBUG && !joosFile::exists($file)) {
            throw new joosCoreException('Не найден требуемый файл :file для типа :name', array(':file' => $file, ':name' => ($cat ? sprintf('%s ( %s )', $name, $type) : $name)));
        }

        return $file;
    }

}

/**
 * Класс работы со страницой выдаваемой в браузер
 * @package    Joostina
 * @subpackage Document
 */
class joosDocument
{
    private static $instance;
    public static $page_body;
    public static $data = array('title' => array(), 'meta' => array(), 'custom' => array(), //JS-файлы
        'js_files' => array(), //Исполняемый код, подключаемый ПОСЛЕ js-файлов
        'js_code' => array(), 'js_onready' => array(), 'css' => array(), 'header' => array(), 'pathway' => array(), 'pagetitle' => false, 'page_body' => false, 'html_body' => false, 'footer' => array(),);
    public static $config = array('favicon' => true, 'seotag' => true,);
    public static $seotag = array('distribution' => 'global', 'rating' => 'General', 'document-state' => 'Dynamic', 'documentType' => 'WebDocument', 'audience' => 'all', 'revisit' => '5 days', 'revisit-after' => '5 days', 'allow-search' => 'yes', 'language' => 'russian', 'robots' => 'index, follow');
    // время кэширования страницы браузером, в секундах
    public static $cache_header_time = false;

    private function __construct()
    {
    }

    /**
     *
     * @return joosDocument
     */
    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
            self::$data['title'] = array(joosConfig::get2('info', 'title'));
        }

        return self::$instance;
    }

    public static function get_data($name)
    {
        return isset(self::$data[$name]) ? self::$data[$name] : false;
    }

    public static function set_body($body)
    {
        self::$data['page_body'] = $body;
    }

    public static function get_body()
    {
        return self::$data['page_body'];
    }

    /**
     * Полностью заменяет заголовок страницы на переданный
     *
     * @param string $title     Заголовок страницы
     * @param string $pagetitle Название страницы
     *
     * @return joosDocument
     */
    public function set_page_title($title = '', $pagetitle = '')
    {
        // title страницы
        $title = $title ? $title : joosConfig::get2('info', 'title');
        self::$data['title'] = array($title);

        // название страницы, не title!
        self::$data['pagetitle'] = $pagetitle ? $pagetitle : $title;

        return $this;
    }

    /**
     * Добавляет строку в массив с фрагментами заголовка
     *
     * @param string $title Фрагмент заголовка страницы
     *
     * @return joosDocument
     */
    public function add_title($title = '')
    {
        self::$data['title'][] = $title;
    }

    /**
     * Возвращает заголовок страницы, собранный из фрагментов, отсортированных в обратном порядке
     * @return string Заголовок
     */
    public static function get_title()
    {
        $title = array_reverse(self::$data['title']);

        return implode(' / ', $title);
    }

    public function add_meta_tag($name, $content)
    {
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        self::$data['meta'][] = array($name, $content);

        return $this;
    }

    public function append_meta_tag($name, $content)
    {
        $n = count(self::$data['meta']);
        for ($i = 0; $i < $n; $i++) {
            if (self::$data['meta'][$i][0] == $name) {
                $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
                if ($content != '' & self::$data['meta'][$i][1] == "") {
                    self::$data['meta'][$i][1] .= ' ' . $content;
                }
                ;

                return;
            }
        }

        $this->add_meta_tag($name, $content);
    }

    public function prepend_meta_tag($name, $content)
    {
        $name = joosString::trim(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
        $n = count(self::$data['meta']);
        for ($i = 0; $i < $n; $i++) {
            if (self::$data['meta'][$i][0] == $name) {
                $content = joosString::trim(htmlspecialchars($content, ENT_QUOTES, 'UTF-8'));
                self::$data['meta'][$i][1] = $content . self::$data['meta'][$i][1];

                return;
            }
        }
        self::instance()->add_meta_tag($name, $content);
    }

    public function add_custom_head_tag($html)
    {
        self::$data['custom'][] = trim($html);

        return $this;
    }

    public function add_custom_footer_tag($html)
    {
        self::$data['custom'][] = trim($html);

        return $this;
    }

    public function get_head()
    {
        $head = array();
        $head[] = isset(self::$data['title']) ? "\t" . '<title>' . self::get_title() . '</title>' : false;

        foreach (self::$data['meta'] as $meta) {
            $head[] = '<meta name="' . $meta[0] . '" content="' . $meta[1] . '" />';
        }

        foreach (self::$data['custom'] as $html) {
            $head[] = $html;
        }

        return implode("\n\t", $head) . "\n";
    }

    /**
     * Подключение JS файла
     *
     * @param string $path   полный путь до файла
     * @param array  $params массив дополнительных параметров подключения файла
     *
     * @return joosDocument
     */
    public function add_js_file($path, $params = array('first' => false))
    {
        if (isset($params['first']) && $params['first'] == true) {
            array_unshift(self::$data['js_files'], $path);
        } else {
            self::$data['js_files'][] = $path;
        }

        /**
        @var $this self */

        return $this;
    }

    public function add_js_code($code)
    {
        self::$data['js_code'][] = $code;

        return $this;
    }

    public function add_js_vars($code)
    {
        self::$data['js_vars'][] = $code;

        return $this;
    }

    public function add_css($path, $params = array('media' => 'all'))
    {
        self::$data['css'][] = array($path, $params);

        return $this;
    }

    public function seo_tag($name, $value)
    {
        self::$seotag[$name] = $value;

        return $this;
    }

    public static function javascript()
    {
        return self::js_files() . self::js_code();
    }

    /**
     * Подготовка JS файлов к выводу
     * Если включено кэширование, файлы будут минимизированы и склеены
     */
    public static function js_files()
    {
        //Если включено кэширование JS-файлов, оптимизируем и склеиваем
        if (joosConfig::get2('cache', 'js_cache')) {
            $js_file = joosJSOptimizer::optimize_and_save(self::$data['js_files']);

            return joosHtml::js_file($js_file['live'] . (JDEBUG ? '?' . time() : JFILE_ANTICACHE));
        }

        //иначе, отдаём файлы как есть
        else {
            $result = array();
            foreach (self::$data['js_files'] as $js_file) {
                $result[] = joosHtml::js_file($js_file . (JDEBUG ? '?' . time() : false));
            }

            return implode("\n\t", $result) . "\n";
        }
    }

    public static function js_code()
    {
        $c = array();
        foreach (self::$data['js_code'] as $js_code) {
            $c[] = $js_code . ";\n";
        }
        $result = joosHtml::js_code(implode("", $c));

        return $result;
    }

    public static function stylesheet()
    {
        $result = array();

        foreach (self::$data['css'] as $css_file) {
            // если включена отладка - то будет добавлять онтикеш к имени файла
            $result[] = joosHtml::css_file($css_file[0] . (JDEBUG ? '?' . time() : JFILE_ANTICACHE), $css_file[1]['media']);
        }

        return implode("\n\t", $result) . "\n";
    }

    public static function head()
    {
        $jdocument = self::instance();

        $meta = joosDocument::get_data('meta');
        $n = count($meta);

        $description = $keywords = false;

        for ($i = 0; $i < $n; $i++) {
            if ($meta[$i][0] == 'keywords') {
                $keywords = $meta[$i][1];
            } else {
                if ($meta[$i][0] == 'description') {
                    $description = $meta[$i][1];
                }
            }
        }

        $description ? null : $jdocument->append_meta_tag('description', joosConfig::get2('info', 'description'));
        $keywords ? null : $jdocument->append_meta_tag('keywords', joosConfig::get2('info', 'keywords'));

        if (joosDocument::$config['seotag'] == true) {
            foreach (self::$seotag as $key => $value) {
                $value != false ? $jdocument->add_meta_tag($key, $value) : null;
            }
        }

        echo $jdocument->get_head();

        // favourites icon
        if (self::$config['favicon'] == true) {
            $icon = JPATH_SITE . '/media/favicon.ico?v=2';
            echo "\t" . '<link rel="shortcut icon" href="' . $icon . '" />' . "\n\t";
        }
    }

    public static function body()
    {
        echo self::$data['page_body'];
    }

    public static function footer_data()
    {
        return implode("\n", self::$data['footer']);
    }

    public static function head_data()
    {
        return implode("\n", self::$data['header']);
    }

    public static function header()
    {
        if (!headers_sent()) {
            if (self::$cache_header_time) {
                header_remove('Pragma');
                joosRequest::send_headers('Cache-Control: max-age=' . self::$cache_header_time);
                joosRequest::send_headers('Expires: ' . gmdate('r', time() + self::$cache_header_time));
            } else {
                joosRequest::send_headers('Pragma: no-cache');
                joosRequest::send_headers('Cache-Control: no-cache, must-revalidate');
            }
            joosRequest::send_headers('X-Powered-By: Joostina CMS');
            joosRequest::send_headers('Content-type: text/html; charset=UTF-8');
        }
    }

}

/**
 * Базовый контроллер Joostina CMS
 * @package    Joostina
 * @subpackage Controller
 *
 * @todo разделить/расширить инициализации контроллера для front, front-ajax, admin, admin-ajax
 */
class joosController
{

    public static $action;
    public static $param;
    public static $error = false;

	/**
	 * @var joosRoute
	 */
	protected $router;

	/**
	 * @var joosController
	 */
	protected static  $instance;

	/**
	 * @static
	 * @return joosController
	 */
	public static function instance(){
		
		if( self::$instance === NULL ){
			self::$instance = new joosController();
		}
		
		return self::$instance;
	}
	
	private function __construct(){
		$this->init();
	}
	
    public function init()
    {
        joosDocument::header();
	    
        $this->router = new joosRoute();
	    $this->router->route();
	    
        $events_name = 'core.init';
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name) : null;
    }

    /**
     * Автоматическое определение и запуск метода действия
     * @todo добавить сюда события events ДО, ПОСЛЕ и ВМЕСТО выполнения задчи контроллера
     */
    public function run()
    {

        $events_name = 'core.run';
        joosEvents::has_events($events_name) ? joosEvents::fire_events($events_name) : null;

        ob_start();

        $controller_class_name = 'actions' . ucfirst( $this->router->param('controller') );

        $controller = new $controller_class_name;
        $action = $this->router->param('action');

	    JDEBUG ? joosDebug::add($controller_class_name . '->' . $action) : null;


	    if (method_exists($controller_class_name, $action)) {


            // в контроллере можно прописать общие действия необходимые при любых действиях контроллера - они будут вызваны первыми, например подключение моделей, скриптов и т.д.
            method_exists($controller_class_name, 'action_before') ? call_user_func_array($controller_class_name . '::action_before', array(self::$action)) : null;

		    $results = $controller->$action();
		    
            // действия контроллера вызываемые после работы основного действия, на вход принимает результат работы основного действия
            method_exists($controller_class_name, 'action_after') ? call_user_func_array($controller_class_name . '::action_after', array(self::$action, $results)) : null;

            if (is_array($results)) {
                $this->views($results);
            } elseif (is_string($results)) {
                echo $results;
            }

            // главное содержимое - стек вывода компонента - mainbody
            joosDocument::set_body(ob_get_clean());
        } else {
            //  в контроллере нет запрашиваемого метода
            joosPages::page404('Метод не найден');
        }
    }

    public static function render()
    {
        ob_start();
        // загрузка файла шаблона
        require_once (JPATH_BASE . '/app/templates/' . JTEMPLATE . '/index.php');

        return ob_get_clean();
    }

    private function views(array $params)
    {
        //Инициализируем модули
        //joosModule::init();
        //joosModule::set_controller_data($params);

	    ob_start();
        $this->as_html($params);
	    return ob_get_clean();
    }

    private function as_html(array $params)
    {
	    
	    $controller = $this->router->param('controller');
	    $action = $this->router->param('action');
	    
        $template = isset($params['template']) ? $params['template'] : 'default';
        $view = isset($params['view']) ? $params['view'] : $action;

        extract($params, EXTR_OVERWRITE);
        $viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $controller . DS . 'views' . DS . $view . DS . $template . '.php';

        joosFile::exists($viewfile) ? require ($viewfile) : null;
    }

    public static function ajax_error404()
    {
        joosRequest::send_headers_by_code(404);

        return array('error'=>404);
    }

    /**
     * Подключение шаблона
     *
     * @static
     * @param string $controller название контроллера
     * @param string $task       выполняемая задача
     * @param string $template   название шаблона оформления
     * @param array  $params     массив параметров, которые могут переданы в шаблон
     * @param array  $params
     */
    public static function get_view($controller, $task, $template = 'default', $params = array())
    {
        extract($params, EXTR_OVERWRITE);
        $viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $controller . DS . 'views' . DS . $task . DS . $template . '.php';
        joosFile::exists($viewfile) ? require ($viewfile) : null;
    }

    public static function debug($sysstart)
    {
        // вывод лога отладки

            // подсчет израсходованной памяти
            if (defined('_JOOS_MEM_USAGE')) {

                $mem_usage = ( memory_get_usage() - _JOOS_MEM_USAGE );
                $mem_usage = joosFile::convert_size($mem_usage);
            } else {
                $mem_usage = 'Недоступно';
            }

            // подсчет времени генерации страницы
            joosDebug::add_top(round(( microtime(true) - $sysstart), 5));
            joosDebug::add_top($mem_usage);

            // вывод итогового лога отлатчика
            joosDebug::get();
    }

	public function get_router(){
		return $this->router;
	}
	
}

/**
 * Базовый ajax - контроллер Joostina CMS
 *
 * @package    Joostina
 * @subpackage Controller
 *
 */
class joosControllerAjax extends joosController
{
}

/**
 * Убрать, заменить везде и использовать как joosDebug::dump($var);
 * @deprecated
 */
function _xdump($var)
{
    joosDebug::dump($var);
}

/**
 * Обработка исключение уровня ядра
 *
 */
class joosCoreException extends joosException
{
}
