<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::view('comments');

class actionsComments extends joosController {

	/**
	 * Вывод списка комментариев
	 */
	public static function comments_first_load($option, $id, $page, $task) {

		$comments = new Comments;
		$comments->obj_option = joosRequest::get('obj_option', '');
		$comments->obj_id = joosRequest::int('obj_id', 0, $_GET);

		$comments_list = $comments->get_comments();

		if ($comments_list) {
			CommentsjoosHtml::lists($comments_list);
		}

		CommentsjoosHtml::addform();
	}

	public static function get_comments($option, $id, $page, $task) {

		$comments = new Comments;
		$comments->obj_option = joosRequest::get('obj_option', '');
		$comments->obj_id = joosRequest::int('obj_id', 0, $_GET);

		//Подключаем библиотеку ajax-пагинации
		joosLoader::lib('ajaxpager');

		$pager = new AjaxPager;
		$pager->other_load($_GET);

		$comments_list = $comments->get_comments($pager->offset, $pager->limit);

		//Выводим список комментариев
		CommentsjoosHtml::lists($comments_list);
	}

	/**
	 * Добавление комментария
	 */
	public static function add_comment() {

		$comment_arr = array();

		if (!Users::instance()->id) {
			$comment_arr['error'] = __('Комментарии могут оставлять только авторизованные пользователи');
			echo json_encode($comment_arr);
			return false;
		}

		$jevix = new JJevix();

		$comment = new Comments;
		$comment->obj_option = joosRequest::get('obj_option', '');
		$comment->obj_id = joosRequest::int('obj_id', 0, $_GET);
		$comment->comment_text = joosRequest::post('comment_text');
		$comment->comment_text = joosText::word_limiter(joosText::strip_tags_smart($comment->comment_text), 200);
		$comment->comment_text = $jevix->Parser($comment->comment_text);
		$comment->user_id = Users::instance()->id;
		$comment->user_name = Users::instance()->id ? Users::instance()->username : _GUEST_USER;
		$comment->created_at = _CURRENT_SERVER_TIME;

		$comment->state = 1;

		$comment->parent_id = joosRequest::int('parent_id', 0, $_POST);

		if ($comment->parent_id > 0) {
			$parent = new Comments();
			$parent->load($comment->parent_id);
			$comment->level = $parent->level + 1;
			$comment->path = $parent->path . ',' . $parent->id;
		} else {
			$comment->path = 0;
		}

		$comment->user_ip = joosRequest::user_ip();

		$comment_arr = array();
		$comment_arr['parent_id'] = $comment->parent_id;
		$comment_arr['user_name'] = $comment->user_name;
		$comment_arr['comment_text'] = $comment->comment_text;
		$comment_arr['user_id'] = $comment->user_id;
		$comment_arr['created_at'] = $comment->created_at;

		if (trim($comment->comment_text == '')) {
			$comment_arr['error'] = 'Введите текст комментария';
			echo json_encode($comment_arr);
			return false;
		} else {
			if ($comment->check()) {
				$comment->store();
				$comment_arr['id'] = $comment->id;
				echo json_encode($comment_arr);
			} else {
				$comment_arr['error'] = 'Упс';
				echo json_encode($comment_arr);
			}
		}

		return false;
	}

	//Вывод одного комментария
	public static function print_comment() {
		$comment_data = joosRequest::array_param('comment_data', array(), $_POST);
		$comment = new Comments;
		$comment->bind($comment_data);
		?><div class="comment_item" id="comment-item-<?php echo $comment->id; ?>"><?php CommentsjoosHtml::comment($comment); ?></div><?php
	}

	/**
	 * Удаление комментария
	 */
	public static function del_comment($option, $task, $id) {
		global $my;

		$comment_arr = array();

		if (!$my->admin) {
			$comment_arr['error'] = 'Это могут только админы!';
			echo json_encode($comment_arr);
			return false;
		}

		$comment = new Comments;

		$id = joosRequest::int('id', 0, $_GET);
		if (!$comment->load($id)) {
			$comment_arr['error'] = 'Нет такого комментария';
			echo json_encode($comment_arr);
			return false;
		} else {
			$comment->delete();
			echo json_encode($comment_arr);
		}

		return false;
	}

}