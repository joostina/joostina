<?php
/**
 *
 **/
//Запрет прямого доступа
defined('_JOOS_CORE') or die();
	
class paramsFaq {

    public static function get_install(){
        
    } 
    
    public static function get_access(){
        
    }  
    
    public static function get_info(){
        
    }
    
    public static function get_params_scheme($type = ''){ 
    	$params = array();
    	
         $params['global'] =  array(
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