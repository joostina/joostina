<?php
/**
 * Модуль вывода последних новостей сайта
 */
defined( '_JOOS_CORE' ) or exit();

class moduleActionsNews extends moduleActions {


	public static function default_action() {

        $news = new modelNews();

        $news = $news->get_list(array(
            'where' => 'state = 1',
            'order' => 'id DESC',
            'limit' => 3
        ));

		return array('news' => $news);
	}


}

