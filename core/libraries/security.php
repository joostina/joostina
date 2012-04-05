<?php

class joosSecurity {

    /**
     * Проверка на флуд
     *
     * @param string $key название ключа/события проверки
     * @param int $count число допустимых запусков события
     * @param int $time число секунд для лимита времени за которое разрешено число запусков события
     * @param int $ban_time время на котое ставится запрет для повторения события
     * @return boolean результат проверки
     */
    public static function is_flood($key, $count, $time, $ban_time) {

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
