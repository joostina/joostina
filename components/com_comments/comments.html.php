<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

/*
 * Класс формирования представлений
 */

class commentsHTML {

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

		$linkuser = $comment->user_id ? sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $comment->user_id, $comment->user_name)) : '#';
		$user_name = $comment->user_id ? sprintf('<a class="username user" id="%s" href="%s">%s</a>', $comment->user_id, $linkuser, $comment->user_name) : $comment->user_name;
		$parent_id = (isset($comment->parent_id)) ? $comment->parent_id : $comment->parent;
?>
		<ul class="comment_menu">
			<li class="comment_avatar">
				<a class="avatar_small" href="<?php echo $linkuser; ?>">
					<img src="<?php echo User::avatar('_45x45', $comment->user_id); ?>" alt="<?php echo $comment->user_name ?>" />
				</a>
			</li>
			<li class="comment_username">
<?php echo $user_name ?>
	</li>
	<li class="comment_date"><span class="date"><?php echo $comment->created_at; ?></span></li>
	<li class="comment_href">
		<a href="#comment<?php echo $comment->id ?>" id="comment<?php echo $comment->id ?>">#<?php echo $comment->id ?></a>
	</li>

<?php if ($parent_id > 0): ?>
			<li class="comment_to_parent">
				<a href="#comment<?php echo $parent_id ?>" class="comment_to_parent" title="Ответ на">↑</a>
			</li>
			<li class="comment_to_child hidden">
				<a href="#comment<?php echo $comment->id ?>" class="comment_to_child" title="Обратно">↓</a>
			</li>
<?php endif; ?>
</ul>

<p><?php echo $comment->comment_text; ?></p>
<a class="comment_reply" href="#<?php echo $comment->id ?>">ответить</a>
<?php
		}

		/**
		 * Форма добавления комментария
		 */
		public static function addform() {
			global $my;
			require_once 'views/form/default.php';
		}

		/**
		 * Пагинация
		 */
		public static function pagination() {
?><div class="pagenav comments_pagenav"></div><?php
		}

	}

