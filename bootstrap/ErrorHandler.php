<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace Bootstrap;

use App\Plugins\LocaleError as LocaleErrorPlugin;
use Phalcon\Config\Config;
use Phalcon\Di\Injectable;
use Phalcon\Logger\Logger;
use Throwable;

abstract class ErrorHandler extends Injectable
{

    public function __construct()
    {
        set_exception_handler([$this, 'handleException']);

        set_error_handler([$this, 'handleError']);

        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError(int $errNo, string $errStr, string $errFile, int $errLine): bool
    {
        if (in_array($errNo, [E_WARNING, E_NOTICE, E_DEPRECATED, E_USER_WARNING, E_USER_NOTICE, E_USER_DEPRECATED])) {
            return true;
        }

        $logger = $this->getLogger();

        $logger->error("Error [{$errNo}]: {$errStr} in {$errFile} on line {$errLine}");

        return false;
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {

            $logger = $this->getLogger();

            $logger->error("Fatal Error [{$error['type']}]: {$error['message']} in {$error['file']} on line {$error['line']}");
        }
    }

    protected function translate(Throwable $e): array
    {
        $locale = new LocaleErrorPlugin();

        $translator = $locale->getTranslator();

        $code = $e->getMessage();

        $msg = $translator->query($code) ?: $code;

        return [
            'code' => $code,
            'msg' => $msg,
        ];
    }

    protected function getConfig(): Config
    {
        return $this->getDI()->getShared('config');
    }

    abstract public function handleException(Throwable $e): void;

    abstract protected function getLogger(): Logger;

}
