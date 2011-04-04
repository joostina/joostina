<?php
/**
 * News - компонент новостей
 * Информация и параметры компонента
 *
 * @version 1.0
 * @package Components
 * @subpackage News
 * @author JoostinaTeam <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 *
 **/
//Запрет прямого доступа
defined('_JOOS_CORE') or die();
	
class paramsNews {

    public static function get_install(){
        
    } 
    
    public static function get_access(){
        
    }  
    
    public static function get_info(){
        
    }
    
    public static function get_params_scheme($type = ''){ 
    	$params = array();
    	
         $params['global'] =  array(
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
				'name' => 'Маленькое изображение',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
			'item_image_quality' => array(
				'name' => 'Степень сжатия (от 0 до 100)',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
			'item_image_ext' => array(
				'name' => 'Формат изображений-результатов',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			),
	        'archive_eyars' => array(
				'name' => 'Года для архива',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			)
		);


		if(!$type || $type == 'default'){
			return $params['global'];
		}
		
		else if(isset($params[$type])){
			return $params[$type];
		}
						      
		else{
			return false;
		}
    }

   
}