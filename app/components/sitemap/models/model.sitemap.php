<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Sitemap - Модель карты сайта
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Sitemap
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Sitemap {

	/**
	 * @var $spaces array
	 * Пространство ссылок - ссылки одного компонента
	 */
	public $spaces = array();
	/**
	 * @var $blocks array
	 * Блоки ссылок - участки ссылок внутри родительского пространства
	 */
	/**
	 * @var $nodes array
	 * Узлы ссылок - основной массив с ссылками
	 */
	public $nodes = array();
	/**
	 * @var $xml_nodes array
	 * Узлы элементов xml
	 */
	private $xml_nodes = array();
	/**
	 * Счетчик элементов карты
	 * @var $counters int
	 */
	private $counters = 0;
	/**
	 * Параметры конфигурации построения карты
	 * @var array
	 */
	private $config = array(
		// максимальное число элементов в карте
		'max_elemets_in_map' => 50000,
	);

	/**
	 * Sitemap::__construct()
	 */
	public function __construct() {

		ini_set('memory_limit', '500M');
		ini_set("max_execution_time", "16000");
		set_time_limit(16000);

		//Сейчас пространства задаются вручную
		//В дальнейшем, ессно будем брать из настроек компонента
		//Каждое пространство - суть компонент
		$this->spaces = array('news', 'content', 'contacts');
		//$this->spaces = array('users');
	}

	/**
	 * Построение карты
	 *
	 * @return $map Объект карты
	 */
	public static function get_map($xml = false) {

		//Создаем экземпляр класса
		$map = new self;

		//Проходимся по всем пространствам карты (каждое пространство - компонент)
		foreach ($map->spaces as $space) {

			//Подключаем плагин для компонента
			require_once JPATH_PLUGINS_BASE . DS . 'sitemap' . DS . $space . '.php';

			//По несложному правилу определяем имя модельки
			$model = $space . 'Map';

			//Настройки плагина
			$params = $model::get_params();
			$params['xml'] = $xml;

			//Получаем схему (массив с блоками), согласно которой перебираем блоки
			//(блоки отличаются, в основном, правилами формирования ссылок и аттрибутами)
			foreach ($model::get_mapdata_scheme($params) as $map_block) {

				//У каждого блока задан тип: 'single' - одиночная ссылка, 'list' - набор однотипных ссылок
				//Если тип  = 'list' - получаем массив объектов с ссылками из соответствующего метода плагина
				// - название метода, с помощью которого мы получаем массив содержится в элемента массива, описывающего блок (ключ элемента - 'id')
				$data = $map_block['type'] == 'single' ? null : call_user_func($map_block['call_from'], isset($map_block['call_params']) ? $map_block['call_params'] : null);

				//Добавляем блок в общий массив с ссылками карты сайта
				$map->add_mapblock($space, $map_block, $data);
			}
		}

		return $map;
	}

	/**
	 * Sitemap::add_mapblock()
	 * Добавление блока в карту сайта
	 *
	 * @param $block array  Массив, описывающий текущий mapblock
	 * @param $data array  Массив c объектами ссылок (если тип блока 'list')
	 * @param $dspace str  Текущее пространство ссылок (компонент)
	 */
	public function add_mapblock($space, $block, $data = null) {
		if ($data) {
			$level = $block['level'];

			if ($block['type'] == 'single_children') {
				$this->nodes[$space][] = '<a class="maplevel-' . $level . ' mapblock_id-' . $block['id'] . '" href="' . $block['link'] . '">' . $block['title'] . '</a>';
				$block['level'] = $block['level'] + 1;
			}

			foreach ($data as $item) {
				if (is_array($item)) {
					//_xdump($item);
					$this->add_mapblock($space, $block, $item);
				} else {
					$level = isset($item->level_up) ? ($block['level'] + $item->level_up) : $block['level'];
					$level = isset($item->level) ? ($item->level) : $level;

					$this->nodes[$space][] = '<a class="maplevel-' . $level . ' mapblock_id-' . $block['id'] . '" href="' . $item->loc . '">' . $item->title . '</a>';

					$this->xml_nodes[$space][] = '
				<url>
					<loc>' . $item->loc . '</loc>
					<lastmod>' . $item->lastmod . '</lastmod>
					<changefreq>' . $block['changefreq'] . '</changefreq>
					<priority>' . $block['priority'] . '</priority>
				</url>';
				}
				++$this->counters;
			}
		} else {
			$level = isset($item->level_up) ? ($block['level'] + $item->level_up) : $block['level'];
			$level = isset($item->level) ? ($item->level) : $level;

			if ($block['link']) {
				$this->nodes[$space][] = '<a class="maplevel-' . $level . ' mapblock_id-' . $block['id'] . '" href="' . $block['link'] . '">' . $block['title'] . '</a>';
			} else {
				$this->nodes[$space][] = '<span class="maplevel-' . $level . ' mapblock_id-' . $block['id'] . '" >' . $block['title'] . '</span>';
			}


			$this->xml_nodes[$space][] = '
			<url>
				<loc>' . $block['link'] . '</loc>
				<lastmod>' . date('Y-m-d') . '</lastmod>
				<changefreq>' . $block['changefreq'] . '</changefreq>
				<priority>' . $block['priority'] . '</priority>
			</url>';
		}
		++$this->counters;
	}

	/**
	 * Sitemap::xml_output()
	 *
	 * Генерация XML-файла карты сайта в формате протокола Sitemap
	 *
	 * <?xml version="1.0" encoding="UTF-8"?>
	 * <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	 *     <url>
	 *         <loc>http://www.example.com/</loc>
	 *         <lastmod>2010-01-01</lastmod>
	 *         <changefreq>monthly</changefreq>
	 *         <priority>0.8</priority>
	 *     </url>
	 * </urlset>
	 */
	public function xml_output() {

		is_dir(JPATH_BASE . '/cache/sitemaps') ? null : mkdir(JPATH_BASE . '/cache/sitemaps', 0777, true);

		$counter = 0;
		$map_num = 0;
		//$maps = array();
		$elemets = array();
		foreach ($this->xml_nodes as $nodes) {
			foreach ($nodes as $node) {
				if ($counter == $this->config['max_elemets_in_map']) {
					++$map_num;
					$this->xml_create($elemets, $map_num);
					$elemets = array();
					$counter = 0;
				}
				$elemets[] = $node;
				++$counter;
			}
		}

		$this->xml_create($elemets, $map_num + 1);

		if ($this->counters > $this->config['max_elemets_in_map']) {
			$this->root_xml_create($this->counters);
		}
	}

	private function xml_create(array $sitemap, $num = 0) {
		$filename = ($num == 0 || $this->counters < $this->config['max_elemets_in_map']) ? JPATH_BASE . DS . 'sitemap.xml' : JPATH_BASE . DS . 'cache/sitemaps/sitemap-' . $num . '.xml';

		$xml = array();
		$xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$xml[] = implode('', $sitemap);
		$xml[] = "\n</urlset>";

		$xml = new SimpleXMLElement(implode("\n", $xml));
		$xml->asXML($filename);
	}

	private function root_xml_create($map_number) {

		//echo $this->counters.':'.$this->config['max_elemets_in_map'].'::';

		$map_count = ceil($this->counters / $this->config['max_elemets_in_map']);

		$sitemap = array();
		for ($index = 1; $index < $map_count + 1; $index++) {
			$sitemap[] = sprintf('<sitemap><loc>%s/cache/sitemaps/sitemap-%s.xml</loc></sitemap>', JPATH_SITE, $index);
		}

		$xml = array();
		$xml[] = '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$xml[] = implode("\n", $sitemap);
		$xml[] = "</sitemapindex>";

		//is_file( JPATH_BASE . DS . 'sitemap.xml' ) ? unlink(JPATH_BASE . DS . 'sitemap.xml') : null;

		$xml = new SimpleXMLElement(implode("\n", $xml));
		$xml->asXML(JPATH_BASE . DS . 'sitemap.xml');
	}

}