<?php defined('_JOOS_CORE') or exit();

/**
 * Подсобный класс управления общих функций и методов для компонента комментариев
 *
 * @version    1.0
 * @package    Components\Comments
 * @subpackage Helpers
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class helperComments
{
    /**
     * Вывод древовидного представления комментариев
     *
     * @var $obj Объект комментирования
     */
    public static function load_comments_tree($obj)
    {
        joosDocument::instance()
            ->add_js_file(JPATH_SITE_APP . '/components/comments/media/js/comments.js');

        $obj_option = get_class($obj);
        $obj_id = $obj->id;

        $comments_list = modelComments::get_comments($obj_option,$obj_id);

        // список текущих комментариев
        self::render_lists($comments_list, $obj);

        // форма добавления нового комментария
        self::render_form($obj_option,$obj_id);
    }

    public static function render_lists(array $comments_list,$obj)
    {
        require_once dirname(__DIR__ ) .'/views/list/default.php';
    }

    public static function render_comment( $comment = false )
    {
        require dirname(__DIR__ ).'/views/comment/default.php';
    }

    public static function render_form( $obj_option,$obj_id )
    {
        require_once dirname(__DIR__ ).'/views/form/default.php';
    }

}
