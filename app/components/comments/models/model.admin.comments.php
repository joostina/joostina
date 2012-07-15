<?php defined('_JOOS_CORE') or exit();
/**
 * Модель панели управления компонента Comments
 *
 * @package Components\Comments
 * @subpackage Models\Admin
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @created 2012-05-04 15:44:22
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelAdminComments extends modelComments
{
    public function get_fieldinfo()
    {
        return array(
            'id' => array(
                'name' => 'id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'parent_id' => array(
                'name' => 'parent_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'path' => array(
                'name' => 'path',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'level' => array(
                'name' => 'level',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'obj_id' => array(
                'name' => 'obj_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'obj_option' => array(
                'name' => 'obj_option',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'obj_option_hash' => array(
                'name' => 'obj_option_hash',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'user_id' => array(
                'name' => 'user_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'user_ip' => array(
                'name' => 'user_ip',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'comment_text' => array(
                'name' => 'comment_text',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'state' => array(
                'name' => 'state',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'created_at' => array(
                'name' => 'created_at',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
        );
    }

    public function get_tableinfo()
    {
        return array(
            'header_main' => 'Comments',
            'header_list' => 'Comments',
            'header_new' => 'Создание Comments',
            'header_edit' => 'Редактирование Comments'
        );
    }

    public function get_extrainfo()
    {
        return array(
            'search' => array(),
            'filter' => array(),
            'extrafilter' => array()
        );
    }

}

/**
 * Модель панели управления компонента CommentsCounter
 *
 * @package Components\CommentsCounter
 * @subpackage Models\Admin
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @created 2012-05-04 15:44:22
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelAdminCommentsCounter extends modelCommentsCounter
{
    public function get_fieldinfo()
    {
        return array(
            'obj_id' => array(
                'name' => 'obj_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'obj_option' => array(
                'name' => 'obj_option',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'obj_option_hash' => array(
                'name' => 'obj_option_hash',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'last_user_id' => array(
                'name' => 'last_user_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'last_comment_id' => array(
                'name' => 'last_comment_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'counter' => array(
                'name' => 'counter',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
        );
    }

    public function get_tableinfo()
    {
        return array(
            'header_main' => 'CommentsCounter',
            'header_list' => 'CommentsCounter',
            'header_new' => 'Создание CommentsCounter',
            'header_edit' => 'Редактирование CommentsCounter'
        );
    }

    public function get_extrainfo()
    {
        return array(
            'search' => array(),
            'filter' => array(),
            'extrafilter' => array()
        );
    }

}
