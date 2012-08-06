<?php defined('_JOOS_CORE') or exit;

/**
 * Модуль входа и выхода пользователя на сайт
 */
class moduleActionsLogin extends moduleActions
{
    /**
     * Некоторые данные, которыми мы заполняем в JS модельку
     */
    private static function get_login_info()
    {
        $user = joosCore::user();

        return array(
            'is_logged' =>  ($user->id > 0 ? 1 : 0),
            'id' => (int) $user->id,
            'uid_code' =>  base_convert($user->id, 10, 36)
        );

    }

    /**
     * Метод входа
     */
    public static function default_action()
    {
        if (joosCore::user()->id) {
            return self::logged();
        } else {

            return self::not_logged();
        }
    }

    /**
     * Если пользователь не авторизован, показываем форму входа/регистрации
     */
    public static function not_logged()
    {
        return array(
            'state' => 'not_logged',
            'view' => 'not_logged',
            'user' => joosCore::user(),
            'user_login_information' => self::get_login_info()
        );
    }

    /**
     * Менюшка и кнопка выхода
     */
    public static function logged()
    {
        return array(
            'state' => 'logged',
            'view' => 'logged',
            'user' => joosCore::user(),
            'user_login_information' => self::get_login_info()
        );
    }
}
