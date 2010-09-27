<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

class attached extends JDBmodel {

    public $id;
    public $created_at;
    public $user_id;
    public $file_name;
    public $file_ext;
    public $file_mime;
    public $file_size;


    public function  __construct() {
        $this->JDBmodel('#__attached', 'id');
    }

    /**
     * Загрузка данных по файлам
     * @param int $id - номер файла
     * @return attached
     */
    public static function file( $id ){
        $file = new self;
        $file->load( $id );

        return  $file;
    }

    public static function add( $filename ) {
        mosMainFrame::addLib('files');

        $filedata = File::filedata($filename);

        $file = new self;
        $file->created_at = _CURRENT_SERVER_TIME;
        $file->user_id = User::current()->id;
        $file->file_ext = $filedata['ext'];
        $file->file_mime = $filedata['mime'];
        $file->file_name = $filedata['name'];
        $file->file_size = $filedata['size'];

        $file->store();

        return $file;
    }

    public static function myfiles($id){
        $files = new self;
        return $files->get_list( array('where' => 'user_id='.$id,'order'=>'id DESC' ) );
    }

}
