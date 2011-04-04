<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
/**
 * Основывается на kohana-memcacheimp: http://github.com/Kolger/kohana-memcacheimp
 * Авторское описание: http://www.kolger.ru/2009/10/memcached-i-metki-realizatsiya-dlya-freimvorka-kohana
 */

/**
 * Cache_Memcacheimp_Driver — усовершенствованный в части работы с тэгами драйвер для Memcached
 *
 * @author Kolger
 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
 */
class Memcacheimp {

	protected $backend;
	protected $flags;

	/**
	 * Конструктор
	 */
	public function __construct() {

		$this->backend = new Memcache;

		// компрессия
		$this->flags = false ? MEMCACHE_COMPRESSED : FALSE;

		$cache_config = joosConfig::get('cache');
		$server = array('host' => $cache_config['memcache_host'], 'port' => $cache_config['memcache_port'], 'persistent' => FALSE);

		// Добавляем соединение с сервером
		$this->backend->addServer($server['host'], $server['port'], (bool) $server['persistent'])
				or die('Cache: Connection failed: ' . $server['host']);
	}

	/**
	 * Возвращает значение ключа. В случае, если ключ не найден, или значения тэгов не совпадают (ключ сброшен) возвращает NULL.
	 * Проверяет значения тэгов, хранящихся в ключах. В случае, если значения различаются ключ считается сброшенным.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 *
	 * @param $id
	 * @return NULL or data
	 */
	public function get($id) {

		if (!joosConfig::get2('cache', 'enable', false)) {
			return NULL;
		}

		$value = $this->backend->get($id);

		// Если ключ не найден - завершаемся и возвращает NULL
		if ($value === FALSE) {
			return NULL;
		}

		// Если у значения есть тэги - обрабатываем им и проверяем, не изменилось ли их значение
		if (!empty($value['tags']) && count($value['tags']) > 0) {
			$expired = false;

			foreach ($value['tags'] as $tag => $tag_stored_value) {
				// Получаем значение текущее значение тэга
				$tag_current_value = $this->get_tag_value($tag);

				// И сравниваем это значение с тем, которое хранится в теле элемента кэша
				if ($tag_current_value != $tag_stored_value) {
					// Если значение изменилось - считаем ключ не валидным
					$expired = true;
					break;
				}
			}

			// Если ключ не валидный - возвращаем NULL
			if ($expired) {
				return NULL;
			}
		}

		return $value['data'];
	}

	/**
	 * "Удаляет" тэг. А именно, увеличивает значение ключа tag_$tag на 1.
	 * Используется для сброса всех ключей с тэгом $tag.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 *
	 * @param $tag
	 * @return
	 */
	public function delete_tag($tag) {
		$key = "tag_" . $tag;
		$tag_value = $this->get_tag_value($tag);

		$this->set($key, microtime(true), NULL, 60 * 60 * 24 * 30);

		return true;
	}

	/**
	 * Возвращает текущее значение тэга. В случае, если тэг не найден, создает его и возвращает значение 1.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 *
	 * @param $tag
	 * @return int
	 */
	private function get_tag_value($tag) {
		$key = "tag_" . $tag;

		$tag_value = $this->get($key);

		if ($tag_value === NULL) {
			$tag_value = microtime(true);
			$this->set($key, $tag_value, NULL, 60 * 60 * 24 * 30);
		}

		return $tag_value;
	}

	/**
	 * Добавляет ключ id со значением data, метками tags.
	 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
	 *
	 * @param $id ключ
	 * @param $data данные
	 * @param $tags метки
	 * @param $lifetime время жизни в секундах
	 * @return bool
	 */
	public function set($id, $data, array $tags = NULL, $lifetime = false) {
		// Если заданы тэги — получаем их текущие значения в $key_tags
		if (!empty($tags)) {
			$key_tags = array();

			foreach ($tags as $tag) {
				$key_tags[$tag] = $this->get_tag_value($tag);
			}

			// Запоминаем $key_tags в элемент tags массива
			$key['tags'] = $key_tags;
		}

		$key['data'] = $data;

		if ($lifetime !== 0) {
			$lifetime += time();
		}

		return $this->backend->set($id, $key, $this->flags, $lifetime);
	}

	/**
	 * Удаляет ключ $id
	 *
	 * @param $id ID ключа
	 * @param $tag Не используется, но предусмотрен интерфейсом
	 * @return bool
	 */
	public function delete($id, $tag = FALSE) {
		if ($id == TRUE) {
			return $this->backend->flush();
		}
		// Шлем запрос на удаление в драйвер memcached
		return $this->backend->delete($id);
	}

	/**
	 * Метод delete_expired не поддеживается, но предусмотрен интерфейсом
	 *
	 * @param $tag
	 * @return exception
	 */
	public function delete_expired() {
		// Метод не поддерживается
		throw new BadMethodCallException();
	}

}

/**
 *  Пример использования:
 *     public static function memcacheimp() {
  echo time() . '<br />';

  joosLoader::lib('memcacheimp');

  $cache = new Memcacheimp();

  $value = $cache->get('data-1');

  if ($value == NULL) {
  $cache->set('data-1', 'post content - 1 - ' . date('H:m:s'), array('posts', 'time', 'data'), 60 * 10);
  }

  $value = $cache->get('data-2');

  if ($value == NULL) {
  $cache->set('data-2', 'post content - 2 - ' . date('H:m:s'), array('posts', 'data'), 60 * 10);
  }


  echo $value = 'PS1: ' . $cache->get('data-1');
  echo $value = 'PS2: ' . $cache->get('data-2');


  $cache->delete_tag('time');
  }

 *
 */