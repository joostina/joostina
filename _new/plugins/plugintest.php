<?php

header('Content-type: text/html; charset=UTF-8');

class KungFu {

	// Эээ... Конструктор
	public function __construct() {
		spl_autoload_register(array($this, '_AutoLoader'));
	}

	// Эээ... Метод автозагрузки
	public function _AutoLoader($sClass) {
		if (false == class_exists($sClass)) {
			include($sClass . '.php');
		}
	}

	// после регистрации плагинов примет вид
	// Array ( [Generic] => Array ( [0] => PluginFoo [1] => PluginBar ) )
	private $_aPlugins = array();

	// Метод регистрации плагинов, заполняющий массив, что описан выше
	public function RegisterPlugin($sClass, $sPlugin) {
		if (false == isset($this->_aPlugins[$sClass])) {
			$this->_aPlugins[$sClass] = array();
		}
		array_push($this->_aPlugins[$sClass], $sPlugin);
	}

	// Метод вернет объект указанного класса. Если для этого класса указаны плагины, то разумеется объект будет по пути плагинизирован.
	public function Load($sClass) {
		if (false == isset($this->_aPlugins[$sClass])) {
			return new $sClass();
		} else {
			$aPlugins = array_reverse($this->_aPlugins[$sClass]);

			$sPrev = null;

			foreach ($aPlugins as $sPlugin) {
				if (null != $sPrev) {
					$aBranch[$sPrev] = $sPlugin;
				}
				$aBranch[$sPlugin] = null;
				$sPrev = $sPlugin;
			}

			$aBranch[$sPrev] = $sClass;

			foreach (array_reverse($aBranch) as $sPlugin => $sParent) {
				class_alias($sParent, $sClass . '_' . $sPlugin);
			}

			if (class_exists($sPlugin)) {
				return new $sPlugin;
			}
		}
	}

}

class Generic {

	public function Hello() {
		echo 'Первый нах!';
	}

}

// создаём объект (Ваш Капитан Очевидность)
$KungFu = new KungFu();

// Регистрируем плагин PluginFoo для базового класса Generic
$KungFu->RegisterPlugin('Generic', 'PluginFoo');

// Регистрируем плагин PluginBar для базового класса Generic
$KungFu->RegisterPlugin('Generic', 'PluginBar');

$KungFu->RegisterPlugin('Generic', 'bosbal');

// Загружаем <i>отплагинизированный</i> объект класса Generic
$Generic = $KungFu->Load('Generic');

$Generic->Hello();