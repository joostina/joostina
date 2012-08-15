<?php defined('_JOOS_CORE') or exit;


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

	/**
	 * @return joosRoute
	 */
	public function get_router(){

		return $this->router;
	}
	
	/**
	 * Автоматическое определение и запуск метода действия
	 * @todo добавить сюда события events ДО, ПОСЛЕ и ВМЕСТО выполнения задачи контроллера
	 */
	public function run()
	{
		
		$router = $this->router = joosCore::instance()->get_router();

		$controller_class_name = 'actions' . ucfirst( $router->param('controller') );

		$controller = new $controller_class_name;
		$action = $router->param('action');

		JDEBUG ? joosDebug::add($controller_class_name . '->' . $action) : null;

		if (method_exists($controller_class_name, $action)) {

			$results = $controller->$action();
			$page_body = $this->views($results);

			// главное содержимое - стек вывода компонента - mainbody
			joosDocument::set_body( $page_body );

			return
				$this;

		} else {
			//  в контроллере нет запрашиваемого метода
			joosPages::page404('Метод не найден');
		}
	}

	public function render()
	{
		ob_start();

		// загрузка файла шаблона
		require_once (JPATH_BASE . '/app/templates/' . JTEMPLATE . '/index.php');

		return ob_get_clean();
	}

	private function views(array $variables)
	{
		//Инициализируем модули
		//joosModule::init();
		//joosModule::set_controller_data($params);

		ob_start();
		$this->as_html($variables);
		return ob_get_clean();
	}

	private function as_html(array $variables)
	{

		$controller = $this->router->param('controller');
		$action = $this->router->param('action');

		$template = isset($variables['template']) ? $variables['template'] : 'default';
		$view = isset($variables['view']) ? $variables['view'] : $action;

		extract($variables, EXTR_OVERWRITE);
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
	 * @param string $action     выполняемое действие
	 * @param string $template   название шаблона оформления
	 * @param array  $params     массив параметров, которые могут переданы в шаблон
	 * @param array  $params
	 */
	public static function get_view($controller, $action, $template = 'default', $params = array())
	{
		extract($params, EXTR_OVERWRITE);
		$viewfile = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $controller . DS . 'views' . DS . $action . DS . $template . '.php';
		joosFile::exists($viewfile) ? require ($viewfile) : null;
	}

	public static function show_debug()
	{
		// вывод лога отладки

		// подсчет израсходованной памяти
		if (defined('JOOS_MEMORY_START')) {

			$mem_usage = ( memory_get_usage() - JOOS_MEMORY_START );
			$mem_usage = joosFile::convert_size($mem_usage);
		} else {
			$mem_usage = 'Недоступно';
		}

		// подсчет времени генерации страницы
		joosDebug::add_top(round(( microtime(true) - JOOS_START), 5));
		joosDebug::add_top($mem_usage);

		// вывод итогового лога отлатчика
		joosDebug::get();
	}



}