<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Modules - Модель модулей
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Modules
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Modules extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var joosText
	 */
	public $title;
	/**
	 * @var joosText
	 */
	public $content;
	/**
	 * @var int(11)
	 */
	public $ordering;
	/**
	 * @var varchar(10)
	 */
	public $position = 'left';
	/**
	 * @var tinyint(1)
	 */
	public $state = 0;
	/**
	 * @var varchar(50)
	 */
	public $module;
	/**
	 * @var varchar(255)
	 */
	public $template;
	/**
	 * @var joosText
	 */
	public $params;
	/**
	 * @var tinyint(4)
	 */
	public $client_id = 0;
	/**
	 * @var int(11)
	 */
	public $cache_time;

	/*
	 * Constructor
	 */
	function __construct() {
		parent::__construct('#__modules', 'id');
	}

	public static function get_module_filename($item) {
		return $item->module ? $item->module . '.php' : 'содержимое пользователя';
	}

	public static function get_modules_positions($item) {
		$positions = new TemplatePositions;

		$opt = $positions->get_selector(array("key" => "position", "value" => "position"));
		return forms::dropdown('position', $opt, $item->position);
	}

	public static function get_modules_pages($item) {

		$pages = new ModulesPages;

		$pages_list = null;
		if ($item->id) {
			$pages_list = $pages->get_list(array('where' => 'moduleid = ' . $item->id));
		}

		$pages_list = $pages_list ? $pages_list : array($pages);

		$return = '';
		$i = 0;

		ob_start();
		?>
		<div id="modules_pages">
			<?php foreach ($pages_list as $page): ?>

				<div class="fields" title="<?php echo $i ?>">
					<div class="b b-left b-30">
						<label class="b">Контроллер</label>
						<input class="b-90" name="pages[<?php echo $i ?>][controller]" type="text"
							   value="<?php echo $page->controller ?>"/>
					</div>

					<div class="b b-left b-30">
						<label class="b">Метод</label>
						<input class="b-90" name="pages[<?php echo $i ?>][method]" type="text"
							   value="<?php echo $page->method ?>"/>
					</div>

					<div class="b b-left b-20">
						<label class="b">или правило</label>
						<input class="b-90" name="pages[<?php echo $i ?>][rule]" type="text" value="<?php echo $page->rule ?>"/>
					</div>

					<div class="b b-left b-20">
						<label class="b">&nbsp;</label>
						<span class="g-pseudolink module_page_copy">[+]</span>
						<span class="g-pseudolink module_page_del">[x]</span>
					</div>
				</div>
				<?php $i++;
			endforeach; ?>
		</div>

		<input type="hidden" name="fields_count" id="fields_count" value="<?php echo $i ?>"/>

		<?php
		return ob_get_clean();
	}

	public static function parce_params($item) {

		if (!$item->module) {
			return false;
		}

		$file = 'modules' . DS . $item->module . DS . $item->module . '.params.php';
		$file = $item->client_id ? JPATH_BASE . DS . JPATH_BASE_ADMIN . $file : JPATH_BASE . DS . $file;

		if (is_file($file)) {
			require($file);
			return $extension_params;
		} else {
			return false;
		}
	}

	public static function get_access_init($item) {
		return array(
			'section' => 'Module',
			'subsection' => $item->id
		);
	}

}

/**
 * Class ModulesPages
 * @package    ModulesPages
 * @subpackage    Joostina CMS
 * @created    2010-12-12 14:52:47
 */
class ModulesPages extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var int(11)
	 */
	public $moduleid;
	/**
	 * @var varchar(25)
	 */
	public $controller;
	/**
	 * @var varchar(50)
	 */
	public $method;
	/**
	 * @var varchar(50)
	 */
	public $rule;

	/*
	 * Constructor
	 */
	function __construct() {
		parent::__construct('#__modules_pages', 'id');
	}

}
