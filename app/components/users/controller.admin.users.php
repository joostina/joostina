<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Компонент управления пользователями, группами и правами доступа
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Users
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminUsers extends joosAdminController{
    
	/**
	 * Подменю
	 */
    protected static $submenu = array(
		'default' => array(
			'name' => 'Пользователи',
			'model' => 'modelAdminUsers',
			'fields' => array('id', 'user_name', 'lastvisit_date', 'state'),
			'active' => false
		),
        'groups' => array(
            'name' => 'Группы',
            'model' => 'modelAdminAclGroups',
            'fields' => array('title'),
            'active' => false
        ),
        'acls' => array(
            'name' => 'Права',
            'model' => 'modelAdminAclList',
            'fields' => array('title'),
            'active' => false
        ),
        'acl_table' => array(
            'name' => 'Рапределение прав',
            'href' => 'index2.php?option=users&menu=acl_table&task=acl_table',
            'model' => false,
            'active' => false
        ),
	);

    public static function acl_table() {

        $group_obj = new modelAclGroups;
        $groups = $group_obj->find_all();

        $acl_list_obj = new modelAclList;
        $acls = $acl_list_obj->find_all();

        $acl_list = array();
        foreach ($acls as $acl) {
            $acl_list[$acl->acl_group][sprintf('%s::%s', $acl->acl_group, $acl->acl_name)] = $acl;
        }

        $acl_groups = array_keys($acl_list);

        sort($acl_groups);
        sort($acls);

        $sql = 'SELECT ag.id AS group_id, al.id AS list_id FROM  #__acl_access AS aa INNER JOIN #__user_groups AS ag ON ( ag.id=aa.group_id ) INNER JOIN #__acl_rules AS al ON ( al.id=aa.task_id )';
        $acl_rules_array = joosDatabase::instance()->set_query($sql)->load_assoc_list();

        $acl_rules = array();
        foreach ($acl_rules_array as $value) {
            $acl_rules[$value['group_id']][$value['list_id']] = true;
        }

        return array(
            'groups' => $groups,
            'acl_groups' => $acl_groups,
            'acl_list' => $acl_list,
            'acls' => $acls,
            'acl_rules' => $acl_rules
        );
    }


    public static function get_actions() {

        $location = JPATH_BASE . '/app/components/';

        $Directory = new RecursiveDirectoryIterator($location);
        $Iterator = new RecursiveIteratorIterator($Directory);
        $Regex = new RegexIterator($Iterator, '/^.+controller.+/i', RecursiveRegexIterator::GET_MATCH);

        joosLoader::lib('Reflect', 'Reflect');

        $options = array(
            'properties' => array(
                'class' => array(
                    'methods'
                ),
            )
        );
        $reflect = new PHP_Reflect($options);

        $classes = array();
        foreach ($Regex as $path) {
            $source = $path[0];
            $reflect->scan($source);
            $cl = $reflect->getClasses();
            foreach ($cl['\\'] as $k => $cc) {
                foreach ($cc['methods'] as $km => $m) {
                    $classes[$k . $km] = array(
                        'title' => sprintf('%s::%s', $k, $km),
                        'acl_group' => $k,
                        'acl_name' => $km,
                        'created_at' => JCURRENT_SERVER_TIME
                    );
                }
            }
        }

        //_xdump($classes);

        $acls_list = new modelAclList;
        $acls_list->insert_array($classes);

        echo sprintf('Вставлено %s правил', count($classes));
    }
    
}