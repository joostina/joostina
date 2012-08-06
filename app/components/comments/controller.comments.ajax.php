<?php defined('_JOOS_CORE') or die();

/**
 * Компонент Комментарии Добавляет возможность комментирования объектов
 * Контроллер ajax - функций
 *
 * @version    1.0
 * @package    Components\Comments
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAjaxComments extends joosControllerAjax
{
    public function add_comment()
    {
        $obj_option = joosRequest::post('obj_option');
        $obj_id = joosRequest::int('obj_id',0);
        $comment_text = joosRequest::post('comment_text');
        $parent_id = joosRequest::int('parent_id',0);

        $comment = new modelComments;

        $comment->obj_option = $obj_option;
        $comment->obj_id = $obj_id;
        $comment->comment_text = $comment_text;
        $comment->parent_id = $parent_id;

        $comment->store();

        return array();
    }

}
