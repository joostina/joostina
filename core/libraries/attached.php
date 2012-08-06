<?php defined('_JOOS_CORE') or exit;

/**
 * Работа с вложениями, загрузками, аттачами
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Attached
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosAttached extends joosModel
{
    /**
     * @var int(11) unsigned
     */
    public $id;
    /**
     * @var timestamp
     */
    public $created_at;
    /**
     * @var int(11) unsigned
     */
    public $user_id;
    /**
     * @var varchar(200)
     */
    public $file_name;
    /**
     * @var varchar(25)
     */
    public $file_ext;
    /**
     * @var varchar(50)
     */
    public $file_mime;
    /**
     * @var int(11) unsigned
     */
    public $file_size;

    /*
           * Constructor
           */
    public function __construct()
    {
        parent::__construct('#__attached', 'id');
    }

    /**
     * Загрузка данных по номеру файла
     *
     * @param int $id - номер файла
     *
     * @return joosAttached
     */
    public static function file($id)
    {
        $file = new self;
        $file->load($id);

        return $file;
    }

    /**
     * Добавление информации о файле в базу данных
     *
     * @param string $filename полный путь к файлу
     *
     * @return self
     */
    public static function add($filename)
    {
        $filedata = joosFile::file_info($filename);

        $attached_obj = new self;

        $attached_obj->user_id = joosCore::user()->id;
        $attached_obj->file_ext = $filedata['ext'];
        $attached_obj->file_mime = $filedata['mime'];
        $attached_obj->file_name = $filedata['name'];
        $attached_obj->file_size = $filedata['size'];

        $attached_obj->store();

        return $attached_obj;
    }

}
