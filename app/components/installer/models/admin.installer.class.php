<?php
/**
 * Installer - компонент-установщик расширений
 * Модель
 *
 * @version 1.0
 * @package ComponentsAdmin
 * @subpackage Installer
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 **/
 
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::lib('files');
class Installer {
	
	private $extension;
	private $temp_path;
	
	
	public function __construct($extension = array(), $temp_path){
		$this->extension = 	$extension;
		$this->temp_path = 	$temp_path;
	}
	

	public function run(){		
		
		switch($this->extension['type']){
			case 'module':
			default:
				return $this->install_module();				
			break;
		}
				
	}
	
	private function install_module(){
		
		$result = array('success'=>true);
		
		joosLoader::admin_model('modules');
		$module = new Modules;   
		
		//Проверяем, нет ли уже такого модуля
		$module->module = $this->extension['module'];
		if(!$module->find()){
			$_file = new File(0755);
			
			if($_file->move($this->temp_path, JPATH_BASE . DS .'modules' . DS . $module->module)==true){   							
				 
				//Сохраняем в БД 							
				$module->save($this->extension);
				
				$result['message'] = 'Модуль успешно установлен';		
			}
			else{
				$result['message'] = 'Не удалось установить модуль';
				$result['success'] = false;		
			}								
		} 
		else{
			$result['message'] = 'Такой модуль уже есть';
			$result['success'] = false;		
		}
		
		return $result;		
	}	
	
}