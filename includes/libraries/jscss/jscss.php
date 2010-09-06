<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 *
 * Библиотека базируется на работах  Гринкевич Евгений Вадимович (c) http://www.ewgenij.net/
 *  Оригинальная документация и описание: http://www.ewgenij.net/javascript-and-css-compressor.html
 */

/*

  Скрипт для сжатия загружаемых пользователем JS и CSS файлов.
  Автор:		Гринкевич Евгений Вадимович.
  Источник:	http://www.ewgenij.net/
  Надеюсь, Вам не нужно объяснять, что интеллектуальная собственность,
  это Вам не [eq собачий.
  /* */

/* НАСТРОЙКИ */

// 0 - не сжимать, 9 - максимальное сжатие.
$iEncodingLevel = 9;

// Файлы можно кэшировать и на стороне клиента.
$iExpiresOffset = 60 * 60 * 24; // 60сек*60мин*24часа

$root = dirname(dirname(dirname(dirname(__FILE__))));

// Куда сохранять сжатые файлы. Поставьте права на запись для каталога.
// Если каталога не существует, он скорее всего будет создан, если
// ничего не перемудрить. Лучше оставить как есть сейчас.
$sCachePath = $root . '/cache/jscss/';

/* ######### */

// Будьте любезны, дальше ничего не исправляйте самостоятельно :). Спасибо!
error_reporting(0);

// По умолчанию здесь файл, относительно DOCUMENT_ROOT
// не по умолчанию за деньги :)
$file_to_cache = explode('?', $_SERVER['REQUEST_URI']);
$sURL = $file_to_cache[0];
$sDR = $root;

$sourceFile = $sDR . $sURL;

file_exists($sourceFile) ? null : die();   // Не найден исходник для кэширования.

$sCachedName = str_replace('/', '%', $sURL);   // Новое имя в кэше
$bGzip = false;
$sEnc = '';

$ct = preg_match('/\.css/i', $sURL) ? 'text/css' : 'text/javascript';
header('Content-type: ' . $ct);
header('Vary: Accept-Encoding');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $iExpiresOffset) . ' GMT');

$date = date('YmdHis', filemtime($sourceFile));
$cacheFile = $sCachePath . $date . '_v_' . $file_to_cache[1] . '_' . $sCachedName;

// если указано, что браузер принимает что-то нестандартное
if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
	$sEncodings = strtolower($_SERVER['HTTP_ACCEPT_ENCODING']);
	if (strpos($sEncodings, 'gzip') !== false) {
		// если дальше все упакуется и не пакуется по умолчанию из-за настроек
		if (function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
			$bGzip = true;
			header('Content-Encoding: gzip');
		}
	}
}

if ($bGzip) {
	// если нет кэша (возможно из-за изменения даты модификации файла)
	if (!file_exists($cacheFile)) {
		// удаляем возможные остатки предыдущих версий, выполняем операцию один раз из 5
		(rand(0, 10) == 5) ? removeOldCache($sCachedName) : null;
		// проверяем путь к папке кэша и пакуем
		@mkdir($sPath, $chmod, true);
		$cacheData = gzencode(file_get_contents($sourceFile), $iEncodingLevel, FORCE_GZIP);
		file_put_contents($cacheFile, $cacheData);
	}
	// отдаем запакованную версию
	echo file_get_contents($cacheFile);
	die();
}

// если дошли до этого места, то gzip скорее всего не поддерживается
echo file_get_contents($sourceFile);


/* Внутренние Функции */

function removeOldCache($sFileName) {
	global $sCachePath;
	if ($dir = opendir($sCachePath)) {
		while (($file = readdir($dir)) !== false) {
			if (strpos($file, $sFileName) !== false)
				@unlink($sCachePath . $file);
		}
	}
}

/**
 * Для активации необходимо добавить  в файл * следующие строки
# JSCSS-Packer.
RewriteRule ^(.*\.(js|css))$ includes/libraries/jscss/jscss.php?$1
 *
 */