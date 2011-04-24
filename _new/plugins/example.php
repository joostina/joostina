<?php

class pluginExample implements joosPlugins {

	function on_install() {
		
	}

	function on_uninstall() {
		
	}

	function get_info() {
		
	}

}

interface joosPlugins {

	function on_install() {
		
	}

	function on_activate() {
		
	}

	function on_uninstall() {
		
	}

	function on_deactivate() {
		
	}

	function get_info() {
		
	}

	function get_params() {
		// joosAutoAdmin
		return array(
		);
	}

}

// плагин обработки всех моделей
class pluginAutodates {

	// при вставке
	public function beforeInsert() {
		$this->created_by_id = AuthUser::getId();
		$this->created_on = date('Y-m-d H:i:s');
		return true;
	}

	// при обновлении 
	public function beforeUpdate() {
		$this->updated_by_id = AuthUser::getId();
		$this->updated_on = date('Y-m-d H:i:s');
		return true;
	}

}