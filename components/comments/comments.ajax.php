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

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('class'));

class actionsComments {

	/**
	 * Вывод списка комментариев
	 */
	public static function comments_first_load($option, $id, $page, $task) {

		$comments = new Comments;
		$comments->obj_option = mosGetParam($_GET, 'obj_option', '');
		$comments->obj_id = (int) mosGetParam($_GET, 'obj_id', '');

		//Определяем общее количество комментариев
		$comments_count = $comments->count('WHERE obj_option = \'' . $comments->obj_option . '\' AND obj_id=' . $comments->obj_id);

		/* для этого проекта нам не нужна постраничная аякс-навигация  вкомментариях
		  //первая страница
		  $page = 1;
		  //Подключаем библиотеку ajax-пагинации
		  mosMainFrame::addLib('ajaxpager');
		  $pager = new AjaxPager;
		  $pager->first_load($into = 'comments_list',
		  $callback = array(
		  'option' => 'com_comments',
		  'task' => 'get_comments',
		  'obj_option' => $comments->obj_option,
		  'obj_id' => $comments->obj_id
		  ),
		  $comments_count, (int) mosGetParam($_GET, 'limit', 10), (int) mosGetParam($_GET, 'display', 5), 'comments_pagenav');

		  $pager->ajaxPaginate($page);

		  $comments_list = $comments->get_comments($pager->offset, $pager->limit);
		 */
		$comments_list = $comments->get_comments();



		if ($comments_list) {
			//Область с пагинацией нам необходимо вывести всего один раз,
			//поэтому исключаем её из шаблона
			//Выводим пагинацию
			//CommentsHTML::pagination($pager);
			//Выводим список комментариев
			CommentsHTML::lists($comments_list);
		}

		CommentsHTML::addform();
	}

	public static function get_comments($option, $id, $page, $task) {

		$comments = new Comments;
		$comments->obj_option = mosGetParam($_GET, 'obj_option', '');
		$comments->obj_id = mosGetParam($_GET, 'obj_id', '');

		//Подключаем библиотеку ajax-пагинации
		mosMainFrame::addLib('ajaxpager');

		$pager = new AjaxPager;
		$pager->other_load($_GET);

		$comments_list = $comments->get_comments($pager->offset, $pager->limit);

		//Выводим список комментариев
		CommentsHTML::lists($comments_list);
	}

	/**
	 * Добавление комментария
	 */
	public static function add_comment($option, $task, $obj_id) {
		global $my;

		$comment_arr = array();

		if ($my->id) {
			$comment_arr['error'] = 'Комментарии могут оставлять только авторизованные пользователи';
			echo json_encode($comment_arr);
			return false;
		}

		mosMainFrame::addLib('text');

		$comment = new Comments;
		$comment->obj_option = mosGetParam($_POST, 'obj_option', '');
		$comment->obj_id = (int) mosGetParam($_POST, 'obj_id', '');
		$comment->comment_text = mosGetParam($_POST, 'comment_text', '');
		$comment->comment_text = Text::word_limiter(Text::strip_tags_smart($comment->comment_text), 200);
		$comment->user_id = $my->id;
		$comment->user_name = $my->id ? $my->username : _GUEST_USER;
		$comment->created_at = _CURRENT_SERVER_TIME;
		$comment->state = 1;

		$comment->parent_id = mosGetParam($_POST, 'parent_id', 0);

		if ($comment->parent_id > 0) {
			$parent = new Comments();
			$parent->load($comment->parent_id);
			$comment->level = $parent->level + 1;
			$comment->path = $parent->path . ',' . $parent->id;
		} else {
			$comment->path = 0;
		}

		mosMainFrame::addLib('ip');
		$comment->user_ip = IP::get_full_ip();

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
		$comment_data = mosGetParam($_POST, 'comment_data', array());
		$comment = new Comments;
		$comment->bind($comment_data);

		$comment->votesresults = 0;
?><div class="comment_item" id="comment-item-<?php echo $comment->id; ?>"><?php CommentsHTML::comment($comment); ?>	</div><?php
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

		if (!$comment->load((int) mosGetParam($_GET, 'id', ''))) {
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