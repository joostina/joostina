<?php

namespace Base\Debug;

use Exception;
use ErrorException;

/**
 * Ловит все непойманные исключения и ошибки, передает их обработчику
 *
 * @package ExceptionHandler
 * @version $id$
 * @author Alexey Karapetov <karapetov@gmail.com>
 */
abstract class ExceptionHandler
{
    /**
     * Последняя ошибка, прошедшая через handleError
     *
     * @var array
     */
    private $lastHandledError = null;

    private $isRunning = false;

    /**
     * Установить как системный обработчик ошибок и исключений
     *
     * @param callable $handler Коллбек, которому будет передано пойманное исключение
     * @param int $errorTypes Типы ошибок, которые надо ловить
     * @return void
     */
    public function install($errorTypes = NULL)
    {
        if (is_null($errorTypes))
        {
            $errorTypes = E_ALL | E_STRICT;
        }
        set_exception_handler(array($this, 'exceptionCallback'));
        set_error_handler(array($this, 'errorCallback'), $errorTypes);
        register_shutdown_function(array($this, 'shutdownHandler'));
    }

    /**
     * Системный обработчик исключения.
     * Устанавливается вызовом set_exception_handler()
     *
     * @param Exception     $e  Никем не пойманное исключение
     * @return void
     */
    public function exceptionCallback(Exception $e)
    {
        if ($this->isRunning)
        {
            error_log('Exception occured inside handler');
            return;
        }
        $this->isRunning = true;
        $this->handleException($e);
        $this->isRunning = false;
    }

    /**
     * Системный обработчик ошибок.
     * Устанавливается вызовом set_error_handler()
     *
     * @param int       $errno      Код ошибки
     * @param string    $errstr     Текстовое описание ошибки
     * @param string    $errfile    Имя файла, где она произошла
     * @param int       $errline    Номер строки в файле
     * @param array     $errcontext Контекст
     * @return true
     */
    public function errorCallback($errno, $errstr, $errfile = null, $errline = null, array $errcontext = array())
    {
        $this->lastHandledError = array(
            'type'      => $errno,
            'message'   => $errstr,
            'file'      => $errfile,
            'line'      => $errline,
        );
        if (error_reporting() & $errno)
        {
            require_once(__DIR__ . DIRECTORY_SEPARATOR . 'ContextAwareErrorException.php');
            $error = new ContextAwareErrorException($errstr, $errno, $errno, $errfile, $errline);
            $error->setContext($errcontext);
            // формат тот же, что у error_get_last()
            $this->exceptionCallback($error);
        }
        return true;
    }

    /**
     * Получить последнюю обработанную ошибку
     *
     * @return array|null
     */
    public function getLastHandledError()
    {
        return $this->lastHandledError;
    }

    /**
     * Системная функция, вызываемая при завершении скрипта.
     * Реагирует только на на ошибки, не обработанные прежде.
     *
     * @access public
     * @return void
     */
    public function shutdownHandler()
    {
        $error = error_get_last();
        if ($error && $error != $this->getLastHandledError())
        {
            $this->errorCallback($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * handleException
     *
     * @param Exception $e
     * @return void
     */
    abstract function handleException(Exception $e);
}
