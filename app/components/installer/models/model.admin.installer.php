<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminFaq - Модель компонента установки и обновления расширений
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Faq
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Installer {

	private $extension;
	private $temp_path;

	public function __construct($extension = array(), $temp_path) {
		$this->extension = $extension;
		$this->temp_path = $temp_path;
	}

	public function run() {

		switch ($this->extension['type']) {
			case 'module':
			default:
				return $this->install_module();
				break;
		}
	}

	private function install_module() {

		$result = array('success' => true);

		$module = new Modules;

		//Проверяем, нет ли уже такого модуля
		$module->module = $this->extension['module'];
		if (!$module->find()) {
			$_file = new File(0755);

			if ($_file->move($this->temp_path, JPATH_BASE . DS . 'modules' . DS . $module->module) == true) {

				//Сохраняем в БД
				$module->save($this->extension);

				$result['message'] = __('Модуль успешно установлен');
			} else {
				$result['message'] = __('Не удалось установить модуль');
				$result['success'] = false;
			}
		} else {
			$result['message'] = 'Такой модуль уже есть';
			$result['success'] = false;
		}

		return $result;
	}

}