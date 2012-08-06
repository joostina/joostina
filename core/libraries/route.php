<?php defined('_JOOS_CORE') or exit;

/**
 * Работа с ссылками и роутингом
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Route
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosRoute extends Route
{
    private $current_url;
	private $current_route;

	private $params;
	
    public function __construct()
    {
        /**
         *
         * @todo файл с пользовательскими роутами, должен конфигурироваться и подключаться в bootstrap.php
         */
        $routes = require(JPATH_APP_CONFIG . DS . 'routes.php');

        foreach ($routes as $route_name => $route) {
            self::set($route_name, $route['href'], (isset($route['params_rules']) ? $route['params_rules'] : null))->defaults($route['defaults']);
        }

        //$uri = $_SERVER['QUERY_STRING'] = rtrim($_SERVER['QUERY_STRING'], '/');
        $uri = $_SERVER['REQUEST_URI'] = trim($_SERVER['REQUEST_URI'], '/');
        $this->current_url = urldecode($uri);

    }

    public function route()
    {

        $routes = self::all();
        $params = NULL;

        foreach ($routes as $name => $route) {

            if (($params = $route->matches($this->current_url))) {
	            
				$this->params = $params;
	            $this->current_route = $name;
	            
	            return true;
            }
        }

        // если включена отладка - скажем что именно не так
        if (JDEBUG) {
            throw new joosException('Не найдено правило роутинга для ссылки :location', array(':location' => self::$current_url));
        } else {
            // отладка не включена - просто перекинем на 404 страницу с понятным текстом
            joosPages::page404('Такая ссылка на сайте невозможна');
        }
    }

    /**
     * Формирование ссылки
     *
     * @param  string $route_name название правила роутинга
     * @param  array  $params     массив параметров для формирования ссылки
     * @return string
     */
    public static function href($route_name, array $params = array())
    {
        return JPATH_SITE . '/' . self::get($route_name)->uri($params);
    }

    /**
     * Системный 301 редирект
     *
     * @param  string $url  ссылка, на которую надо перейти
     * @param  string $msg  текст сообщения, отображаемый после перехода
     * @param  string $type тип перехода - ошибка, предупреждение, сообщение и т.д.
     * @return void
     */
    public function redirect($url, $msg = '', $type = 'success')
    {
        $iFilter = joosInputFilter::instance();
        $url = $iFilter->process($url);

        empty($msg) ? null : joosFlashMessage::add($iFilter->process($msg), $type);

        $url = preg_split("/[\r\n]/", $url);
        $url = $url[0];

        if ($iFilter->badAttributeValue(array('href', $url))) {
            $url = JPATH_SITE;
        }

        if (headers_sent()) {
            echo "<script>document.location.href='$url';</script>\n";
        } else {
            !ob_get_level() ? : ob_end_clean();
            joosRequest::send_headers_by_code(301);
            joosRequest::send_headers("Location: " . $url);
        }

        exit;
    }

    /**
     * Получение название текущего активного правила роутинга
     *
     * @return string
     */
    public function get_current_route()
    {
        return $this->current_route;
    }

    /**
     * Получение текущий ссылки ( в адресной сроке браузера )
     *
     * @return string
     */
    public function get_current_url()
    {
        return $this->current_url == '' ? JPATH_SITE : JPATH_SITE . '/' . $this->current_url;
    }

	public function param($name,$default = null){
		return isset( $this->params[$name] ) ? $this->params[$name] : $default;
	}
	
}

/**
 * Базовый класс роутинга
 * Базируется на оригинальной работе Kohana Team
 */
class Route
{
    // Defines the pattern of a <segment>

    const REGEX_KEY = '<([a-zA-Z0-9_]++)>';

    // What can be part of a <segment> value
    const REGEX_SEGMENT = '[^/.,;?\n]++';

    // What must be escaped in the route regex
    const REGEX_ESCAPE = '[.\\+*?[^\\]${}=!|]';

    /**
     * @var  string  default protocol for all routes
     *
     * @tutorial  'http://'
     */
    public static $default_protocol = 'http://';

    /**
     * @var  string  default action for all routes
     */
    public static $default_action = 'index';

    /**
     * @var  bool Indicates whether routes are cached
     */
    public static $cache = FALSE;

    /**
     * @var  array
     */
    protected static $_routes = array();

    /**
     * Stores a named route and returns it. The "action" will always be set to
     * "index" if it is not defined.
     *
     *     self::set('default', '(<controller>(/<action>(/<id>)))')
     *         ->defaults(array(
     *             'controller' => 'welcome',
     *         ));
     *
     * @param   string   route name
     * @param   string   URI pattern
     * @param   array    regex patterns for route keys
     *
     * @return Route
     */
    protected static function set($name, $uri_callback = NULL, $regex = NULL)
    {
        return self::$_routes[$name] = new self($uri_callback, $regex);
    }

    /**
     * Retrieves a named route.
     *
     *     $route = self::get('default');
     *
     * @param   string  route name
     *
     * @return Route
     * @throws joosException
     */
    protected static function get($name)
    {
        if (!isset(self::$_routes[$name])) {
            throw new joosException('Не найдено правило роутинга: :route', array(':route' => $name));
        }

        return self::$_routes[$name];
    }

    /**
     * Retrieves all named routes.
     *
     *     $routes = self::all();
     *
     * @return array routes by name
     */
    protected static function all()
    {
        return self::$_routes;
    }

    /**
     * Returns the compiled regular expression for the route. This translates
     * keys and optional groups to a proper PCRE regular expression.
     *
     *     $compiled = self::compile(
     *        '<controller>(/<action>(/<id>))',
     *         array(
     *           'controller' => '[a-z]+',
     *           'id' => '\d+',
     *         )
     *     );
     *
     * @return string
     * @uses    self::REGEX_ESCAPE
     * @uses    self::REGEX_SEGMENT
     */
    private static function compile($uri, array $regex = NULL)
    {
        if (!is_string($uri)) {
            return;
        }

        // The URI should be considered literal except for keys and optional parts
        // Escape everything preg_quote would escape except for : ( ) < >
        $expression = preg_replace('#' . self::REGEX_ESCAPE . '#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== FALSE) {
            // Make optional parts of the URI non-capturing and optional
            $expression = str_replace(array('(', ')'), array('(?:', ')?'), $expression);
        }

        // Insert default regex for keys
        $expression = str_replace(array('<', '>'), array('(?P<', '>' . self::REGEX_SEGMENT . ')'), $expression);

        // правила краткой записи регулярок роутинга
        $rules = array(':any' => '.+?', ':maybe' => '.*?', ':digit' => '[\d]+', ':alpha' => '[a-zA-Z]+', ':rus_alpha' => '[a-zA-Zа-яА-ЯёЁ]+', ':word' => '[\w-_]+', ':slug' => '[a-zA-Zа-яА-ЯёЁ0-9\-]+',);

        if ($regex) {
            $search = $replace = array();
            foreach ($regex as $key => $value) {

                $value = strtr($value, $rules);

                $search[] = "<$key>" . self::REGEX_SEGMENT;
                $replace[] = "<$key>$value";
            }

            // Replace the default regex with the user-specified regex
            $expression = str_replace($search, $replace, $expression);
        }

        return '#^' . $expression . '$#uD';
    }

    /**
     * @var  string  route URI
     */
    protected $_uri = '';

    /**
     * @var  array
     */
    protected $_regex = array();

    /**
     * @var  array
     */
    protected $_defaults = array('action' => 'index', 'host' => FALSE);

    /**
     * @var  string
     */
    protected $_route_regex;

    /**
     * Creates a new route. Sets the URI and regular expressions for keys.
     * Routes should always be created with [self::set] or they will not
     * be properly stored.
     *
     *    $route = new Route($uri, $regex);
     *
     * @param   mixed    route URI pattern
     * @param   array    key patterns
     *
     * @return void
     * @uses    self::_compile
     */
    public function __construct($uri = NULL, $regex = NULL)
    {
        if ($uri === NULL) {
            // заморочка с кешем
            return;
        }

        if (!empty($uri)) {
            $this->_uri = $uri;
        }

        if (!empty($regex)) {
            $this->_regex = $regex;
        }

        // Store the compiled regex locally
        $this->_route_regex = self::compile($uri, $regex);
    }

    /**
     * Provides default values for keys when they are not present. The default
     * action will always be "index" unless it is overloaded here.
     *
     *     $route->defaults(array(
     *         'controller' => 'welcome',
     *         'action'     => 'index'
     *     ));
     *
     * @param   array  key values
     *
     * @return  $this
     */
    protected function defaults(array $defaults = NULL)
    {
        $this->_defaults = $defaults;

        return $this;
    }

    /**
     * Tests if the route matches a given URI. A successful match will return
     * all of the routed parameters as an array. A failed match will return
     * boolean FALSE.
     *
     *     // Params: controller = users, action = edit, id = 10
     *     $params = $route->matches('users/edit/10');
     *
     * This method should almost always be used within an if/else block:
     *
     *     if ($params = $route->matches($uri))
     *     {
     *         // Parse the parameters
     *     }
     *
     * @param   string  URI to match
     *
     * @return array on success
     * @return FALSE on failure
     */
    protected function matches($uri)
    {
        if (!preg_match($this->_route_regex, $uri, $matches)) {
            return FALSE;
        }

        $params = array();
        foreach ($matches as $key => $value) {
            if (is_int($key)) {
                // Skip all unnamed keys
                continue;
            }

            // Set the value for all matched keys
            $params[$key] = $value;
        }

        foreach ($this->_defaults as $key => $value) {
            if (!isset($params[$key]) OR $params[$key] === '') {
                // Set default values for any key that was not matched
                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * Generates a URI for the current route based on the parameters given.
     *
     *     // Using the "default" route: "users/profile/10"
     *     $route->uri(array(
     *         'controller' => 'users',
     *         'action'     => 'profile',
     *         'id'         => '10'
     *     ));
     *
     * @param   array   URI parameters
     *
     * @return string
     * @throws joosException
     * @uses    self::REGEX_Key
     */
    protected function uri(array $params = NULL)
    {
        // Start with the routed URI
        $uri = $this->_uri;

        // если в ссылке нет динамических параметров - сразу её возвратим
        if (strpos($uri, '<') === FALSE AND strpos($uri, '(') === FALSE) {
            return $uri;
        }

        while (preg_match('#' . self::REGEX_KEY . '#', $uri, $match)) {
            list($key, $param) = $match;
            if (!isset($params[$param])) {
                // Look for a default
                if (isset($this->_defaults[$param])) {
                    $params[$param] = $this->_defaults[$param];
                } else {
                    // отсутствуют требуемые параметры
                    throw new joosException('Требуемый параметр :param не найден в полученных данных для условия :uri', array(':param' => $param, ':uri' => joosFilter::htmlspecialchars($this->_uri)));
                }
            }

            $uri = str_replace($key, $params[$param], $uri);
        }

        // чистка от лишних и дублирующихся /
        $uri = preg_replace('#//+#', '/', rtrim($uri, '/'));

        return $uri;
    }

}
