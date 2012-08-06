<?php defined('_JOOS_CORE') or exit;

/**
 * Работа с проверками на безопасность
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Security
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosSecurity
{
    /**
     * Проверка на флуд
     *
     * @param  string  $key      название ключа/события проверки
     * @param  int     $count    число допустимых запусков события
     * @param  int     $time     число секунд для лимита времени за которое разрешено число запусков события
     * @param  int     $ban_time время на котое ставится запрет для повторения события
     * @return boolean результат проверки
     */
    public static function is_flood($key, $count, $time, $ban_time)
    {
        $key_flood = md5('flud_' . md5($key));
        $key_still = md5('still' . md5($key));

        $cache = joosCache();

        if ($cache->get($key_still) == 1) {
            return false;
        }

        $tmp = $cache->get($key_flood);

        if ($tmp === false) {
            $cache->set($key_flood, 1, $time);
        } else {
            $cache->increment($key_flood);
        }

        // проверка не прошла, флуд.
        if ($tmp >= $count) {
            $cache->set($key_still, 1, $ban_time);

            return false;
        }

        return true;
    }

}
