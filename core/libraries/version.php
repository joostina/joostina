<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosVersion - Информационная библиотека данных о системе
 *
 * @version    1.0
 * @package    Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
final class joosVersion {

	public static /** @var строка CMS */
	$CMS = 'Joostina' , /** @var версия */
	$CMS_ver = '2' , /** @var int Номер сборки */
	$BUILD = '$: 2***' , /** @var string Дата */
	$RELDATE = '04:01:2012' , /** @var string Время */
	$RELTIME = 'xx:xx:xx' , /** @var string Текст авторских прав */
	$COPYRIGHT = 'Авторские права &copy; 2007-2012 Joostina Team. Все права защищены.' , /** @var string URL */
	$URL = '<a href="http://www.joostina.ru" target="_blank" title="Система создания и управления сайтами Joostina CMS">Joostina!</a> - бесплатное и свободное программное обеспечение для создания сайтов, распространяемое по лицензии GNU/GPL.' , /** @var string ссылки на сайты поддержки */
	$SUPPORT = 'Поддержка: <a href="http://forum.joostina.ru" target="_blank" title="Официальный форум CMS Joostina">forum.joostina.ru</a>';

	// получение переменных окружения информации осистеме
	public static function get( $name ) {
		return self::$$name;
	}

}
