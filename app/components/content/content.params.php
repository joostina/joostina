<?php
/**

 */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();
	
class paramsContent {

    public static function get_install(){
        
    } 
    
    public static function get_access(){
        
    }  
    
    public static function get_info(){
        
    }
    
    public static function get_params_scheme($type = ''){ 
    	$params = array();
    	
        /*$params['global'] =  array(
			'param1' => array(
				'name' => 'Глобальный Параметр #1',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
			'param2' => array(
				'name' => 'Глобальный Параметр #2',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			)
		); */
		
        $params['category'] =  array(
			'item_image_size_big' => array(
				'name' => 'Большое изображение',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
			'item_image_size_medium' => array(
				'name' => 'Средний эскиз',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
			'item_image_size_thumb' => array(
				'name' => 'Превью',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
			'item_sort' => array(
				'name' => 'Сортировка (1-по дате, 2-по порядку)',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			)		
		);
		
        $params['item'] =  array(
			'item_dateformat' => array(
				'name' => 'Статья: формат вывод даты',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
		);
		
		
		
		if(!$type || $type == 'default'){
			return array_merge ( $params['category'], $params['item'] );	
		}
		
		else if(isset($params[$type])){
			return $params[$type];
		}
						      
		else{
			return false;
		}
    }

   
}