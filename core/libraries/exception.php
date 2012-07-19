<?php defined('_JOOS_CORE') or exit();

/**
 * Обработка исключений
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage Exception
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or exit();

/**
 * Обработка всех уровней ошибок
 *
 */
class joosException extends Exception
{
    const CONTEXT_RADIUS = 10;

    public function __construct($message = '', array $params = array())
    {
        joosRequest::send_headers_by_code(503);

        parent::__construct(strtr($message, $params));

        if (isset($params[':error_file'])) {
            $this->file = $params[':error_file'];
        }

        if (isset($params[':error_line'])) {
            $this->line = $params[':error_line'];
        }

        if (isset($params[':error_code'])) {
            $this->code = $params[':error_code'];
        }

        //$this->__toString();
    }

    private function get_file_context()
    {
        $file = $this->getFile();
        $line_number = $this->getLine();

        $context = array();
        $i = 0;
        foreach (file($file) as $line) {
            $i++;
            if ($i >= $line_number - self::CONTEXT_RADIUS && $i <= $line_number + self::CONTEXT_RADIUS) {
                if ($i == $line_number) {
                    $context[] = ' >>   ' . $i . "\t" . $line;
                } else {
                    $context[] = "\t" . $i . "\t" . $line;
                }
            }
            if ($i > $line_number + self::CONTEXT_RADIUS) {
                break;
            }
        }

        return "\n" . implode("", $context);
    }

    public function __toString()
    {
        // очистим всю вышестоящую буферизацию без вывода её в браузер
        !ob_get_level() ? : ob_end_clean();

        parent::__toString();
        echo joosRequest::is_ajax() ? $this->to_json() : $this->show();
        die();
    }

    private function show()
    {
        return JDEBUG ? $this->create() : $this->render();
    }

    private function create()
    {
        if (headers_sent()) {
            !ob_get_level() ? : ob_end_clean();
        } else {
            joosRequest::send_headers('Content-type: text/html; charset=UTF-8');
        }

        $message = nl2br($this->getMessage());
        $result = <<<HTML
  <style>
    body { background-color: #fff; color: #333; }
    body, p, ol, ul, td { font-family: verdana, arial, helvetica, sans-serif; font-size: 13px; line-height: 25px; }
    pre { background-color: #eee; padding: 10px; font-size: 11px; line-height: 18px; }
    a { color: #000; }
    a:visited { color: #666; }
    a:hover { color: #fff; background-color:#000; }
  </style>
<div style="width:99%; position:relative">
<h2 id='Title'>{$message}</h2>
<div id="Context" style="display: block;"><h3>Ошибка с кодом {$this->getCode()} в файле '{$this->getFile()}' в строке {$this->getLine()}:</h3><pre>{$this->prepare($this->get_file_context())}</pre></div>
<div id="Trace"><h2>Стэк вызовов</h2><pre>{$this->getTraceAsString()}</pre></div>
HTML;
        $result .= "</div></div>";

        return $result;
    }

    protected function prepare($content)
    {
        return htmlspecialchars($content, ENT_NOQUOTES, 'UTF-8');
    }

    /**
     * Возврат информации об ошибки в JSON-сериализованном виде
     *
     * @return string строка в json с кодом ошибки закодированная в JSON
     */
    private function to_json()
    {
        $response = array('code' => ($this->getCode() != 0) ? $this->getCode() : 503, 'message' => $this->getMessage());

        return joosText::json_encode($response);
    }

    private function render()
    {
        $message = $this->message;

        $file = (!JDEBUG && $this->code != 503) ? sprintf('%s/app/templates/system/500.php', JPATH_BASE) : sprintf('%s/app/templates/system/exception.php', JPATH_BASE);

        require $file;
    }

    public static function error_handler($code, $message, $file, $line)
    {

	if ( 0 == error_reporting () ) {
		return;
	}

        throw new joosException('Ошибка :message! <br /> Код: <pre>:error_code</pre> Файл: :error_file<br />Строка :error_line', array(':message' => $message, ':error_code' => $code, ':error_file' => $file, ':error_line' => $line));
    }

}

/**
 * Класс обработки общих ошибок пользователя, без уточнений
 *
 */
class joosUserException extends joosException
{
    public function __construct($message = '', array $params = array())
    {
        $this->code = 503;

        parent::__construct(strtr($message, $params));

        $this->__toString();
    }

}
