<?php defined('_JOOS_CORE') or exit;

require_once __DIR__ . '/Email/SimpleMail.php';
require_once __DIR__ . '/Email/MimeType.php';
require_once __DIR__ . '/Compression/GzCompression.php';

/**
 * Библиотека расширенной работы с отправкой email сообщений
 * Реализует прослойку для работы с классом SimpleMail https://github.com/cnicodeme/PHP5-SimpleMail
 *
 * @version    1.0
 * @package    Vendors\Libraries
 * @subpackage Email
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosSimpleMail
{
    /**
     * Расширенная функция отправки сообщения на email
     *
     * @tutorial joosSimpleMail::send_email('admin@examle.com','Hello!','From Russia!');
     *
     * @param string|array $to      email получателя
     * @param string       $title   заголовк сообщения
     * @param string       $message текст сообщения
     * @param string|bool  $from    email отправителя, по умолчанию используется системный параметр
     *
     * @return bool|Void
     */
    public static function send_email( $to , $title , $message, $from = false )
    {
        try {
            $email_obj = new SimpleMail ();

            $email_obj->From = $from ? $from : joosConfig::get2('mail','system_email');
            $email_obj->To = is_array($to) ? $to : array ( $to );
            $email_obj->Subject = $title;

            $body = strip_tags($message);
            $email_obj->addBody ( $body );
            if ($body !== $message) {
                $email_obj->addBody ($message, 'text/html');
            }

            $email_obj->send ();

            return true;
        } catch (joosSimpleMailException $e) {

            return false;
        }
    }

}

class joosSimpleMailException extends  joosException
{
}
