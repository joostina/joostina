<?php defined('_JOOS_CORE') or exit();

/**
 * Системная корзина покупок
 *
 *
 * @version    1.0
 * @package    Extra\Libraries
 * @subpackage Basket
 * @category   Libraries\Extra
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 *
 * При первом добавлении товара в корзину запускаются сессии (если не запущены еще), пользователю ставится кука
 * Индекс по сесси+тип+id - уникальный, при дублирвоании - увеличивать число на +1 или на +count
 *
 * @todo добавить поддержку плагинов для разных типов объектов
 * @todo плагины должны отслеживать число товара, высчитывать цену, скидки и доступность
 * */
class joosBasket
{
    /**
     * Кнопка добавления товара к корзину
     *
     * @static
     * @param $obj объект который необходимо добавить в корзину
     * @return string
     */
    public static function button( $obj )
    {
        $button = '';

        return $button;
    }

    /**
     * Добавление товара в корзину
     *
     * @static
     * @param $obj_type тип объекта - название модели
     * @param $obj_id идентификатор объекта товара
     * @param $count число единиц товара в корзине
     * @return array результат добалвения товара в корзину
     */
    public static function add($obj_type, $obj_id, $count = 1)
    {
        if ( !class_exists($obj_type) ) {
            return array(
                'success'=>false,
                'message'=>'Тип товара не определён'
            );
        }

        $purchase = new modelBasket;

        $purchase->obj_type = $obj_type;
        $purchase->obj_id = $obj_id;
        $purchase->count = $count;

        $purchase->store();

        return array(
            'success'=>true,
            'message'=>'Товар добавлен в корзину'
        );
    }

    /**
     * Получение числа товаров в текущей корзине
     *
     * @static
     * @return int
     */
    public static function get_items_count()
    {
        return 1;
    }

    /**
     * Получение полной стоимости всех товаров в текущей корзине
     *
     * @static
     * @return float
     */
    public static function get_items_price()
    {
        return 1.1;

    }

    /**
     * Получение полного списка товаров в корзине с ценой и количеством
     *
     * @static
     * @return array
     */
    public static function get_items_list()
    {
        return array(
            0 => array(
                'id' => 'ID товара',
                'title' => 'Название товара',
                'image' => 'URL картинки товара',
                'desc' => 'Краткое описание товара',
                'href' => 'Ссылка на товар',
                'count' => 'Количество товара этого типа в корзине',
                'price' => 'Стоимость товаров этого типа в корзине'
            )
        );
    }

}

/**
 * Модель сайта библиотеки Basket
 *
 * @package Libraries\Extra\Basket
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @created 2012-05-19 20:43:40
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelBasket extends joosModel
{
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $id;
    /**
     * @field varchar(250)
     * @type string
     */
    public $obj_type;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $obj_id;
    /**
     * @field float unsigned
     * @type float
     */
    public $price;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $count;
    /**
     * @field datetime
     * @type datetime
     */
    public $created_at;

    /*
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct('#__basket', 'id');
    }

    public function check()
    {
        $this->filter();

        return true;
    }

}

/**
 *
CREATE TABLE IF NOT EXISTS `jos_basket` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`session_id` varchar(32) NOT NULL COMMENT 'Идентификтаор сессии покупателя',
`obj_type` varchar(250) NOT NULL COMMENT 'Тип объекта',
`obj_id` int(11) unsigned NOT NULL COMMENT 'ID объекта',
`price` float unsigned NOT NULL COMMENT 'Цена торвара',
`count` int(11) unsigned NOT NULL COMMENT 'Количество товара',
`created_at` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 *
 */
