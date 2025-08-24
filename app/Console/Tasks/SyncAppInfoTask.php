<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\AppInfo;
use App\Library\Utils\Lock as LockUtil;
use GuzzleHttp\Client as HttpClient;

class SyncAppInfoTask extends Task
{

    const API_BASE_URL = 'https://www.koogua.net/api';

    public function mainAction()
    {
        $taskLockKey = $this->getTaskLockKey();

        $taskLockId = LockUtil::addLock($taskLockKey);

        if (!$taskLockId) return;

        echo '------ start sync app info ------' . PHP_EOL;

        $site = $this->getSettings('site');

        $serverHost = parse_url($site['url'], PHP_URL_HOST);

        $serverIp = gethostbyname($serverHost);

        $appInfo = new AppInfo();

        $params = [
            'server_host' => $serverHost,
            'server_ip' => $serverIp,
            'app_name' => $appInfo->get('name'),
            'app_alias' => $appInfo->get('alias'),
            'app_version' => $appInfo->get('version'),
            'app_link' => $appInfo->get('link'),
        ];

        $client = new HttpClient();

        $url = sprintf('%s/instance/collect', self::API_BASE_URL);

        $client->request('POST', $url, ['form_params' => $params]);

        echo '------ end of sync app info ------' . PHP_EOL;

        LockUtil::releaseLock($taskLockKey, $taskLockId);
    }

}
