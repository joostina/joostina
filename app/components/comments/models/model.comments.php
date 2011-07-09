<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Comments - Модель комментариев
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Comments
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Comments extends joosModel {

	/**
	 * @var int(11) unsigned
	 */
	public $id;
	/**
	 * @var int(11) unsigned
	 */
	public $parent_id;
	/**
	 * @var varchar(255)
	 */
	public $path;
	/**
	 * @var tinyint(1)
	 */
	public $level;
	/**
	 * @var int(11) unsigned
	 */
	public $obj_id;
	/**
	 * @var varchar(30)
	 */
	public $obj_option;
	/**
	 * @var int(11) unsigned
	 */
	public $user_id;
	/**
	 * @var varchar(100)
	 */
	public $user_name;
	/**
	 * @var varchar(50)
	 */
	public $user_email;
	/**
	 * @var varchar(50)
	 */
	public $user_ip;
	/**
	 * @var mediumtext
	 */
	public $comment_text;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var longtext
	 */
	public $params;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		parent::__construct('#__comments', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function before_store() {
		$this->params = array();

		$plugin_file = __DIR__ . '/plugins/' . strtolower($this->obj_option) . '.php';
		if (is_file($plugin_file)) {
			require_once $plugin_file;
			$plugin_class = 'comments' . $this->obj_option;
			$this->params += array('href' => $plugin_class::href($this));
		}

		$this->params = json_encode($this->params);
	}

	/**
	 * После сохранения комментария в БД
	 */
	public function after_insert() {

		$this->update_counters();
	}

	/**
	 * Первая загрузка комментариев
	 * Метод используется при использовании постраничной навигации, комментарии - списком
	 * Загружаем первую страницу с комментариями и инициализируем пагинацию
	 * @var string $obj_option Тип объекта (компонент)
	 * @var integer $obj_id ID комментируемого объекта
	 * @var integer $limit Количество комменариев на страницу
	 * @var integer $visible_pages Количество кнопок с номерами страниц в видимой части пагинатора
	 */
	public function load_comments($obj, $limit = 10, $visible_pages = 10) {

		$this->obj_option = get_class($obj);
		$this->obj_id = $obj->{$obj->_tbl_key}; // настоящая уличная магия
		//
        //Подключаем пагинацию
		joosDocument::instance()
				->add_js_file(JPATH_SITE . '/includes/libraries/ajaxpager/media/js/jquery.paginate.js')
				->add_css(JPATH_SITE . '/includes/libraries/ajaxpager/media/css/ajaxpager.css')
				->add_custom_head_tag(joosHtml::js_code("var _comments_objoption = '$this->obj_option';var _comments_objid = $this->obj_id;var _comments_limit = $limit;var _comments_display = $visible_pages;"))
				->add_js_file(JPATH_SITE . '/components/comments/media/js/comments.js');
	}

	/**
	 * Вывод древовидного представления комментариев
	 * @var $obj Объект комментирования
	 */
	public function load_comments_tree($obj) {

		$this->obj_option = get_class($obj);
		$this->obj_id = $obj->{$obj->_tbl_key}; // настоящая уличная магия
		//
		//JS объявления, необходимые для загрузки первой страницы комментариев
		$script = joosHtml::js_code("var _comments_objoption = '$this->obj_option';var _comments_objid = $this->obj_id;");

		if (joosRequest::is_ajax()) {
			echo $script;
		} else {
			joosDocument::instance()
					->add_custom_head_tag($script)
					->add_js_file(JPATH_SITE . '/components/comments/media/js/comments_tree.js');
		}

		$comments_list = $this->get_comments();

		//Выводим список комментариев если они есть
		$comments_list ? CommentsHTML::lists($comments_list) : CommentsHTML::emptylists();

		CommentsHTML::addform();
	}

	/**
	 * Получение списка комментариев
	 * @param integer $offset смещение
	 * @param integer $limit лимит для постранички
	 * @return array массив объектов комментариев
	 */
	public function get_comments($offset = 0, $limit = 0) {
		$comment = new Comments;
		return $comment->get_list(array(
			'select' => 'c.*,c.parent_id as parent, u.username as user_name',
			'join' => 'AS c LEFT JOIN `#__users` AS u ON (u.id = c.user_id)',
			'where' => 'c.state=1 AND c.obj_option = \'' . $this->obj_option . '\' AND c.obj_id = \'' . $this->obj_id . '\'',
			'order' => 'c.parent_id, c.created_at ASC',
			'limit' => $limit,
			'offset' => $offset,
				));
	}

	/**
	 * Изменение счётчиков общего количества комментариев по объекту и по пользователю
	 */
	private function update_counters() {
		$sql = sprintf("INSERT INTO `#__comments_counter` (`obj_id`, `obj_option`, `last_user_id`, `last_comment_id`,`counter`)" .
				" VALUES (%s, '%s', %s, %s,1)" .
				" ON DUPLICATE KEY UPDATE counter=counter+1,last_user_id=%s,last_comment_id=%s ;", $this->obj_id, $this->obj_option, $this->user_id, $this->id, $this->user_id, $this->id);
		return $this->_db->set_query($sql)->query();
	}

	public static function get_counters($obj) {
		$r = new stdClass(); // new self
		$r->count = rand(1, 1000);
		$r->last_user_id = rand(1, 10);
		$r->last_comment_id = rand(1, 1000);

		$r->count_text = joosText::declension($r->count, array('комментарий', 'комментария', 'комментариев'));

		return $r;
	}

}

/**
 * @deprecated
 */
class CommentsHTML {

	/**
	 * Вывод списка комментариев для заданного объекта
	 * @param array $comments_list массив объектов комментариев
	 * @param DooPager $pagenav объект постраничной навигации
	 */
	public static function lists(array $comments_list) {
		require_once 'views/comments/tree/default.php';
	}

	public static function emptylists() {
		echo '<div class="comments-list" id="comments-list-0"></div>';
	}

	/**
	 * Вывод комментария
	 * @param $comment объект комментария
	 */
	public static function comment($comment) {
		$linkuser = $comment->user_id ? joosRoute::href('user_view', array('username' => $comment->user_name, 'id' => $comment->user_id)) : '#';
		$user_name = $comment->user_id ? sprintf('<a class="username user" id="%s" href="%s">%s</a>', $comment->user_id, $linkuser, $comment->user_name) : $comment->user_name;
		$parent_id = (isset($comment->parent_id)) ? $comment->parent_id : $comment->parent;

		$params = json_decode($comment->params);
		$link_comment = isset($params->href) ? JPATH_SITE . $params->href . '#comment' . $comment->id : '#';
		?>
		<ul class="comment_menu">
			<li class="comment_avatar">
				<a class="avatar_small" href="<?php echo $linkuser; ?>">
					<img class="g-thumb_40 g-user_avatar" src="<?php echo Users::avatar($comment->user_id, '40x40') ?>"
						 alt="<?php echo $comment->user_name ?>"/>
				</a>
			</li>
			<li class="comment_username">
		<?php echo $user_name ?>
			</li>
			<li class="comment_date"><span class="date"><?php echo $comment->created_at; ?></span></li>
			<li class="comment_href">
				<a name="comment<?php echo $comment->id ?>" class="unajax" href="<?php echo $link_comment ?>"
				   id="comment<?php echo $comment->id ?>">#<?php echo $comment->id ?></a>
			</li>

		<?php if ($parent_id > 0): ?>
				<li class="comment_to_parent">
					<a href="#comment<?php echo $parent_id ?>" class="comment_to_parent unajax" title="Ответ на">↑</a>
				</li>
				<li class="comment_to_child hidden">
					<a href="#comment<?php echo $comment->id ?>" class="comment_to_child unajax" title="Обратно">↓</a>
				</li>
		<?php endif; ?>
		</ul>

		<p><?php echo $comment->comment_text; ?></p>
		<span class="comment_reply g-pseudolink" comment="#<?php echo $comment->id ?>">ответить</span>
		<?php
	}

	/**
	 * Форма добавления комментария
	 * @deprecated
	 */
	public static function addform() {
		require_once dirname(dirname(__FILE__)).'/views/form/default.php';
	}

	/**
	 * Пагинация
	 */
	public static function pagination() {
		?><div class="pagenav comments_pagenav"></div><?php
	}

}

