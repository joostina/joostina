<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

mosMainFrame::addLib('voter');

class actionsVote {

    public static function comment($option, $id, $page, $task) {
        $my = User::current();

        if (!$my->id) { // пользователь аноним
            echo json_encode(array('state' => 'error', 'message' => 'Такие вещи доступны только авторизованных пользователям'));
            return;
        }
        
        require_once mosMainFrame::getInstance()->getPath('class', 'com_comments');

        $obj_id = (int) mosGetParam($_POST, 'obj_id', 0);
        
        $obj = new Comments;
        $obj->load($obj_id);
        
        if (!$obj->id) { // нет такого комментария
            echo json_encode(array('state' => 'error', 'message' => 'Комментария за который ты пробуешь проголосовать - не существует! Ой, беда, огорчение!'));
            return;
        }
        
        if ($obj->user_id == $my->id) { // свой комментарий
            echo json_encode(array('state' => 'error', 'message' => 'Мы почему-то не разрешаем голосовать за свои комментарии ( Ээээх'));
            return;
        }
                
        
        $ball = (int) mosGetParam($_POST, 'ball', 1);
        // в голосах только +/- , соответственно увеличиваем или уменьшаем голоса        
        if ($my->level > 6){
        	$ball = $ball == 1 ? 10 : -10;	
        }
        else if ($my->level > 3){
        	$ball = $ball == 1 ? 3 : -3;	
        }
        else{
        	$ball = $ball == 1 ? 1 : -1;	
        }

        $vote_result = Voter::getInstance('comment')->add_from_user($obj, $my, $ball);

        if ($vote_result->message) {
            echo json_encode(array('state' => 'yahrr', 'message' => $vote_result->message, 'counter' => $vote_result->counter));
        } else {
            echo json_encode(array('state' => 'error', 'message' => $vote_result->error));
        }
        return;
    }

    public static function user($option, $id, $page, $task) {
        $my = User::current();

        if (!$my->id) { // пользователь аноним
            echo json_encode(array('state' => 'error', 'message' => 'Такие вещи доступны только авторизованных пользователям'));
            return;
        }
        if ($my->level<3 && $my->gid!=8) { // пользователь аноним
            echo json_encode(array('state' => 'error', 'message' => 'Доступно на уровне 3'));
            return;
        }

        $obj_id = (int) mosGetParam($_POST, 'obj_id', 0);

        $obj = new User;
        $obj->load($obj_id);

        if (!$obj->id) { // материал отсутствует
            echo json_encode(array('state' => 'error', 'message' => 'Пользователь за который ты пробуешь проголосовать - еще или уже не существует'));
            return;
        }

        if ($obj->state == 0) { // материал в черновиках
            echo json_encode(array('state' => 'error', 'message' => 'Этот пользователь заблокирован'));
            return;
        }

        if ($obj->id == $my->id) { // сам за себя
            echo json_encode(array('state' => 'error', 'message' => 'Голосовать за себя нельзя'));
            return;
        }

        $ball = (int) mosGetParam($_POST, 'ball', 1);

        // в голосах только +/- , соответственно увеличиваем или уменьшаем голоса        
        if ($my->level > 6){
        	$ball = $ball == 1 ? 10 : -10;	
        }
        else if ($my->level > 3){
        	$ball = $ball == 1 ? 3 : -3;	
        }
        else{
        	$ball = $ball == 1 ? 1 : -1;	
        }

        // записываем результат голосования и получаем рейтинг
        $vote_result = Voter::getInstance('users')->add_from_user($obj, $my, $ball);
        // число голосов
        $voters_count = Voter::getInstance('users')->get_count_voters($obj);
 
		
        mosMainFrame::addLib('text');
        $voters_count = $voters_count . ' ' . Text::declension((int) $voters_count, array('голос', 'голоса', 'голосов'));

        if ($vote_result->message) {
            UserRatings::add_user( $obj , $ball);
            
			//Полный рейтинг пользователя
			$full_rate = UserRatings::get_full_rate($obj->id);
			
            echo json_encode(array('state' => 'yahrr', 'message' => $vote_result->message, 'counter' => $vote_result->counter, 'voters_count' => $voters_count, 'full_rate' => $full_rate));
        } else {
            echo json_encode(array('state' => 'error', 'message' => $vote_result->error));
        }
        return;
    }
}