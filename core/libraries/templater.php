<?php defined('_JOOS_CORE') or exit;

/**
 * Класс-родитель для шаблонизаторов
 */
class joosTemplater
{
    protected static $instance = NULL;

    protected static $engines = array(

        'default' => 'joosTemplater');

    /**
     * @param  string        $template_engine Движок шаблонизатора
     * @return joosTemplater объект шаблонизатора
     */
    public static function instance($template_engine = 'default')
    {
        if (!isset(joosTemplater::$instance[$template_engine]) || joosTemplater::$instance[$template_engine] === NULL) {

            $class = isset(joosTemplater::$engines[$template_engine]) ? joosTemplater::$engines[$template_engine] : joosTemplater::$engines['default'];

            joosTemplater::$instance[$template_engine] = new $class();
        }

        return joosTemplater::$instance[$template_engine];
    }

    /**
     * Регистрация обработчика шаблонов
     */
    public static function register_templater($key, $class)
    {
        joosTemplater::$engines[$key] = $class;
    }

    /**
     * Исполнение указанного файла шаблона с указанными параметрами
     */
    public function render_file($template_file_name, $params = array())
    {
        extract($params, EXTR_OVERWRITE);

        if (!joosFile::exists($template_file_name)) {
            throw new joosModulesException("Не найден файл шаблона {$template_file_name}");
        }

        ob_start();
        require ($template_file_name);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
