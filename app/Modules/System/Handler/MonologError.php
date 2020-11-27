<?php

namespace App\Modules\System\Handler;

use App\Traits\CoreTrait;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// Az alap: https://akrabat.com/logging-errors-in-slim-3/
//   - backtrace kiiras lehetne szebb
//     (http://php.net/manual/en/exception.gettraceasstring.php)
//   - nem supportol $exception->getPrevious wrapped exceptionoket
class MonologError extends \Slim\Handlers\AbstractError
{
    use CoreTrait;

    public function __invoke(Request $request, Response $response, \Throwable $exception)
    {

        $context        = $this->formatException($exception);
        $viewSettings   = $this->settings['settings']['System']['View'];
        $loggerSettings =
               $this->settings['settings']['System']['Logger']['error']
            ?? $this->settings['settings']['System']['logger']['error']
            ?? $this->settings['settings']          ['Logger']['error']
            ?? $this->settings['settings']          ['logger']['error'];

        $logger = new \Monolog\Logger($loggerSettings['name']);

        // APP Metadata
        $uidProcessor = new \Monolog\Processor\UidProcessor();
        $logger->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());
        $logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor());
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        $logger->pushProcessor(new \Monolog\Processor\ProcessIdProcessor());
        $logger->pushProcessor($uidProcessor);

        $logger->pushHandler(new \Monolog\Handler\StreamHandler($loggerSettings['path'], \Monolog\Logger::DEBUG));

        $logger->critical('DEBUG', ['error' => $context]);

        $responseBody = $_SERVER['SERVER_NAME'] .': '. $uidProcessor->getUid() .'-'. getmypid();

        if (!$request->isXhr() && class_exists('\Slim\Views\Twig')) {

            // Twig Extension   
            $view = new \Slim\Views\Twig([ '__main__' => __DIR__ ], $viewSettings['twig']);
            return $view->render($response, '/Resources/templates/500.twig', [ 'data' => $responseBody ]);

        }

        return $response->withJson([
            'status'  => 'error',
            'message' => $responseBody,
        ], 500);

    }

    private function formatException( $exception )
    {   
        $errorArray = array();

        $errorArray['class']   = 'CAUGHT '. get_class($exception) .' EXCEPTION';
        $errorArray['code']    = $exception->getCode();
        $errorArray['file']    = $exception->getFile();
        $errorArray['line']    = $exception->getLine();
        $errorArray['message'] = $exception->getMessage();
        $errorArray['trace']   = $exception->getTraceAsString();

        if ($exception instanceof \ErrorException)
        {
            $errorArray['severity'] = $this->severityToString($exception->getSeverity());
        }

        $globalsArr = [
            '_GET',
            '_POST',
            '_FILES',
            '_COOKIE',
            '_SESSION'
        ];

        if (isset($GLOBALS))
        {
            foreach($globalsArr as $field)
            {
                if (isset($GLOBALS[$field])) {

                    $varExport = var_export($GLOBALS[$field], true);
                    $varExport = str_replace("\n", '', $varExport);
                    $varExport = preg_replace('/\s+/', ' ', $varExport);

                    $errorArray['globals'][$field] = $varExport;

                }
            }
        }

        return $errorArray;
    }

    private function severityToString( $severity )
    {
        switch ($severity) {
            case E_USER_ERROR:        $ret = 'E_USER_ERROR';        break;
            case E_USER_WARNING:      $ret = 'E_USER_WARNING';      break;
            case E_USER_NOTICE:       $ret = 'E_USER_NOTICE';       break;
            case E_USER_DEPRECATED:   $ret = 'E_USER_DEPRECATED';   break;
            case E_PARSE:             $ret = 'E_PARSE';             break;
            case E_NOTICE:            $ret = 'E_NOTICE';            break;
            case E_ERROR:             $ret = 'E_ERROR';             break;
            case E_WARNING:           $ret = 'E_WARNING';           break;
            case E_RECOVERABLE_ERROR: $ret = 'E_RECOVERABLE_ERROR'; break;
            case E_DEPRECATED:        $ret = 'E_DEPRECATED';        break;
            case E_STRICT:            $ret = 'E_STRICT';            break;
            default:                  $ret = "E_UNKNOWN-$severity"; break;
        }

        return $ret;
    }
}