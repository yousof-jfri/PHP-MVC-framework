<?php

namespace Core;

use Core\View;
use App\Config;

class Errors
{
    public static function errorHandler($level, $message, $file, $line)
    {
        if(error_reporting() !== 0){
            throw new \ErrorException($message, 0, $level, $file, $level);
        }
    }

    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if($code != 404){
            $code = 500;
        }

        http_response_code($code);

        if(Config::APP_DEBUG){
            echo "<h1>Fatal Error</h1>";

            echo "<p> Uncaught exception : '" . get_class($exception) . "'</p>";
            
            echo "<p>Message : '" . $exception->getMessage() . "'</p>";

            echo "<p> Stack trace : <pre> {$exception->getTraceAsString()} </pre> </p>";

            echo "<p>thrown in '" . $exception->getFile() . "'</p>";

            echo "<p> On Line : ' " . $exception->getLine() . "' </p>";
        } else {
            $log = dirname(__DIR__) . '/Storage/Logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "<h1>Fatal Error</h1>";

            $message .= "<p> Uncaught exception : '" . get_class($exception) . "'</p>\n";
            
            $message .= "<p>Message : '" . $exception->getMessage() . "'</p>\n";

            $message .= "<p> Stack trace : <pre> {$exception->getTraceAsString()} </pre> </p>\n";

            $message .= "<p>thrown in '" . $exception->getFile() . "'</p>\n";

            $message .= "<p> On Line : ' " . $exception->getLine() . "' </p>\n";

            error_log($message);

            // view
            View::render('errors.notFound');
        }
    }
}