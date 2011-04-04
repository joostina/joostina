<?php
/**
 * Extrafields - Компонент для управления дополнительными полями
 * Информация и параметры компонента
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Extrafields
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 **/
//Запрет прямого доступа
defined('_JOOS_CORE') or die();
	
class paramsExtrafields {

    public static function get_install(){
        
    } 
    
    public static function get_access(){
        
    }  
    
    public static function get_info(){
        
    }
    
    public static function get_params_scheme($type = ''){ 
    	$params = array();
    	
        $params['global'] =  array(
			'param1' => array(
				'name' => 'Первый параметр',
				'editable' => true,
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array()
			)
		); 
		
        $params['item'] =  array(
			$params['global']['param1']
		);
		
		
		if(!$type || $type == 'default'){
			return array_merge ($params['global']);	
		}
		
		else if(isset($params[$type])){
			return $params[$type];
		}
						      
		else{
			return false;
		}
    }

   
}