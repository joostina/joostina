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
// импортер контента из стандартного com_content

joosLoader::lib('joiadmin', 'system');
JoiAdmin::dispatch();

ini_set('memory_limit', '100M');
ini_set("max_execution_time", "16000");
set_time_limit(16000);

class actionsDataimport {

	public static function index() {
		//self::blogs();
		//self::news();
		//self::users();
		//self::comments();
	}

	// блоги
	private static function blogs() {
		joosLoader::lib('text');

		$contents_sql = 'SELECT id, sectionid, title, introtext, `fulltext`, created_by as user_id, catid, created, state FROM old__content WHERE state=1';
		$contents_sql .= ' ORDER BY id ASC';
		$contens = database::instance()->set_query($contents_sql)->load_object_list();

		joosLoader::model('blog');
		$blog = new Blog;

		joosLoader::lib('jevix', 'text');
		$jevix = new JJevix();

		foreach ($contens as $conten) {

			$blog->id = $conten->id;
			$blog->title = Jstring::trim($conten->title);
			$blog->slug = Text::str_to_url($conten->title);
			$blog->introtext = $jevix->JevixParser( self::text_preparser($conten->introtext) );
			$blog->fulltext = $jevix->JevixParser( self::text_preparser($conten->fulltext) );
			$blog->user_id = $conten->user_id;
			$blog->category_id = 1;
			$blog->created_at = $conten->created;
			$blog->state = $conten->state;
		
			database::instance()->insert_object('#__blog', $blog);
			$blog->reset();
		}
	}

	// новости
	private static function news() {
		joosLoader::lib('images');

		$contents_sql = 'SELECT id, title, introtext,`fulltext`, created, state FROM old_www_content WHERE catid=1 AND state=1';
		$contents_sql .= ' ORDER BY id ASC';
		$contens = database::instance()->set_query($contents_sql)->load_object_list();

		joosLoader::model('news');
		$new = new News;

		joosLoader::lib('text');
		joosLoader::lib('jevix', 'text');
		$jevix = new JJevix();

		foreach ($contens as $conten) {
			$new->title = Jstring::trim($conten->title);
			$new->slug = Text::str_to_url($conten->title);
			$new->introtext = $jevix->JevixParser( self::text_preparser($conten->introtext) );
			$new->fulltext = $jevix->JevixParser( self::text_preparser($conten->fulltext) );
			$new->type_id = 1;
			$new->created_at = $conten->created;
			$new->state = $conten->state;

			$new->store();
			$new->reset();
		}
	}

	// пользователи
	public static function users() {

		$users_sql = 'SELECT * FROM old__users';
		$users = database::instance()->set_query($users_sql)->load_object_list();

		$juser = new User;
		$extra = new UserExtra;

		foreach ($users as $user) {
			// переносим данные пользователя
			$juser->id = $user->id;
			$juser->username = $user->username;
			$juser->username_canonikal = UserHelper::get_canonikal($juser->username);
			$juser->realname = $user->name;
			$juser->email = $user->email;
			$juser->password = $user->password;
			$juser->registerDate = $user->registerDate;
			$juser->gid = $user->gid;
			$juser->state = 1;
			database::instance()->insert_object('#__users', $juser);

			$extra = new UserExtra;
			$extra->user_id = $juser->id;
			database::instance()->insert_object('#__users_extra', $extra);

			$juser->reset();
			$extra->reset();
		}
	}

	// комментарии
	public static function comments() {

		joosLoader::model('comments');

		$sql = 'SELECT * FROM old__jcomments';
		$old_comments = database::instance()->set_query($sql)->load_object_list();

		$comment = new Comments();

		foreach ($old_comments as $old_comment) {
			// переносим данные пользователя
			$comment->comment_text = $old_comment->comment;
			$comment->obj_id = $old_comment->object_id;
			$comment->obj_option = 'Blog';
			$comment->created_at = $old_comment->date;
			$comment->parent_id = $old_comment->parent;
			$comment->user_ip = $old_comment->ip;
			$comment->user_id = $old_comment->userid;
			$comment->user_name = $old_comment->name;
			$comment->user_email = $old_comment->email;
			$comment->state = $old_comment->published;
			$comment->path = 0;
			$comment->level = 0;

			database::instance()->insert_object('#__comments', $comment);

			$comment->reset();
		}
	}

	// копирование изображений с удалённого сервера
	private static function copy_image($from, $to) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 160);
		curl_setopt($ch, CURLOPT_URL, $from);
		$data = curl_exec($ch);
		curl_close($ch);

		if (strlen($data) > 0) {
			@mkdir(dirname($to), 077, true);
			return file_put_contents($to, $data, 0755);
		} else {
			return false;
		}
	}

	private static function update_canonnikal() {
		$users_sql = 'SELECT id, username FROM #__users';
		$users = database::instance()->set_query($users_sql)->load_assoc_list();
		$sqls = array();
		foreach ($users as $user) {
			$canonnikal = UserHelper::get_canonikal($user['username']);
			$sqls[] = "UPDATE `jos_users` SET `username_canonikal` = '{$canonnikal}' WHERE `id`={$user['id']}";
		}
		$sql_code = implode(";\n", $sqls);
		file_put_contents(JPATH_BASE . '/tmp/username_canon.sql', $sql_code, 0755);
	}

	private static function text_preparser($text) {

		$text = str_replace('&nbsp;', ' ', $text);
		$text = str_replace(array("\n", "\r", "\t"), ' ', $text);
		$text = str_replace("'", '"', $text);

		return $text;
	}

}