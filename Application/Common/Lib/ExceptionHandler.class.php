<?php

namespace Common\Lib;

use Exception;
use ErrorException;
use Think\Log;

/**
 * 处理接口异常,错误等信息
 * Class ExceptionHandler
 * @package Common\Lib
 */
class ExceptionHandler
{


    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param  int  $level
     * @param  string  $message
     * @param  string  $file
     * @param  int  $line
     * @param  array  $context
     * @return void
     *
     * @throws \ErrorException
     */
    public static function handleError($level=0, $message=0, $file = '', $line = 0, $context = [])
    {
        if(self::isFatal($level))
        {
            IF(APP_DEBUG)
            {
                $e['message']   = $message;
                $e['file']      = $file;
                $e['line']      =$line;
                exit(iconv('UTF-8','gbk',$message).PHP_EOL.'FILE: '.$file.'('.$line.')');
            }
            else
            {
                Log::write(iconv('UTF-8','gbk',$message).PHP_EOL.'FILE: '.$file.'('.$line.')');
                IE('系统错误，请联系管理员！','');
            }
            exit();
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public static function handleException($e)
    {
        var_dump($e);
        $code = $e->getCode();
        IF($e instanceof  \Error)
        {
            $code = E_ERROR;
        }
        self::handleError($code,$e->getMessage(). '<BR>' . $e->getTraceAsString(),$e->getFile(),$e->getLine());
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public static function handleShutdown()
    {
        if ($error = error_get_last()) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }   
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     * @return bool
     */
    private static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }
    

}
