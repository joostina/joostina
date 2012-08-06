<?php defined('_JOOS_CORE') or exit;

/**
 * Модель компонента управления пользователями
 * Модель панели управления
 *
 * @version    1.0
 * @package    Components\Users
 * @subpackage Models\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminUsers extends modelUsers
{
    public function get_fieldinfo()
    {
        return array(
            'id' => array(
                'name' => 'ID',
                'editable' => false,
                'sortable' => false,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(
                    'width' => '20px',
                    'align' => 'center'
                )
            ),
            'user_name' => array(
                'name' => 'Логин',
                'editable' => true,
                'sortable' => true,
                'in_admintable' => true,
                'html_edit_element' => 'edit',
                'html_table_element' => 'editlink',
            ),
            'real_name' => array(
                'name' => 'Настоящее имя',
                'editable' => true,
                'sortable' => true,
                'in_admintable' => true,
                'html_edit_element' => 'edit',
                'html_table_element' => 'value',),
            'state' => array(
                'name' => 'Разрешен',
                'editable' => true,
                'sortable' => true,
                'in_admintable' => true,
                'editlink' => true,
                'html_edit_element' => 'checkbox',
                'html_table_element' => 'status_change',
                'html_edit_element_param' => array(
                    'tooltip' => 'Активирован',
                ),
            ),
            'email' => array(
                'name' => 'email адрес',
                'editable' => true,
                'in_admintable' => true,
                'html_edit_element' => 'edit',
                'html_table_element' => 'value',
                'hide_on'=>'edit',
            ),
            'openid' => array(
                'name' => 'Адрес OpenID',
                'editable' => false,
                'in_admintable' => true,
                'html_edit_element' => 'edit',
                'html_table_element' => 'value',),
            'password' => array(
                'name' => 'Пароль',
                'editable' => true,
                'in_admintable' => true,
                'html_edit_element' => 'extra',
                'html_edit_element_param' => array(
                    'call_from' => function ($user) {
                        $name = $user->id ? 'new_password' : 'password';

                        return joosHtml::input(array('name' => $name, 'value' => '', 'class' => 'text_area'));
                    }
                ),
                'html_table_element' => 'value',
            ),
            'group_multi' => array(
                'name' => 'Состоит в группах',
                'editable' => true,
                'sortable' => true,
                'in_admintable' => false,
                'html_edit_element' => 'extra',
                'html_edit_element_param' => array(
                    'call_from' => 'modelAdminUsers::get_users_group_multi'
                ),
                'hide_on'=>'create',
            ),
            'register_date' => array(
                'name' => 'Дата регистрации',
                'editable' => true,
                'in_admintable' => true,
                'html_edit_element' => 'value',
                'html_table_element' => 'value',
                'hide_on'=>'create',
            ),
            'lastvisit_date' => array(
                'name' => 'Последнее посещение',
                'editable' => true,
                'sortable' => true,
                'in_admintable' => true,
                'html_edit_element' => 'value',
                'html_table_element' => 'date_format',
                'html_table_element_param' => array(
                    'date_format' => 'd F в H:m',
                    'width' => '200px',
                    'align' => 'center'
                ),
                'hide_on'=>'create',
            ),
            'activation' => array(
                'name' => 'Код активации',
                'editable' => false,
                'in_admintable' => false,
                'html_edit_element' => 'edit',
                'html_table_element' => 'value',
            ),
        );
    }

    public function get_tableinfo()
    {
        return array(
            'header_main'=>'Пользователи',
            'header_list' => 'Все пользователи',
            'header_new' => 'Создание пользователя',
            'header_edit' => 'Редактирование данных пользователя'
        );
    }

    public function get_extrainfo()
    {
        return array(
            'search' => array('user_name', 'real_name', 'email'),
        );
    }

    protected function before_store()
    {
        // выполняем сначала задачи родительского класа
        parent::before_store();

        // сохраняем группы пользователя
        $user_groops = joosRequest::array_param('user_groups');
        if ($user_groops !==null) {
            $this->save_one_to_many('#__users_acl_groups_users', 'user_id', 'group_id', $this->id, $user_groops);
        }
    }

    public function get_users_group_multi($current_obj)
    {
        if ($current_obj->id) {

            $g = new modelUsersAclGroupsUsers;
            $active_groups = $g->get_selector(
                    array('key' => 'group_id', 'value' => 'group_id'), array('where' => 'user_id=' . $current_obj->id)
            );
        } else {
            $active_groups = array();
        }

        return $current_obj->get_one_to_many_selectors('user_groups', '#__users_acl_groups', '#__users_acl_groups', 'user_id', 'group_id', $active_groups);
    }

}
