<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice\Email;

use App\Repos\Account as AccountRepo;
use App\Services\Mailer;

class RefundFinish extends Mailer
{

    /**
     * @var string
     */
    protected string $templateId = 'refund_finish';

    public function handle(array $params): bool
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($params['user']['id']);

        if (!$account->email) return false;

        $subject = $this->getSubject($this->templateId);

        $placeholders = [
            'user.name' => $params['user']['name'],
            'refund.subject' => $params['refund']['subject'],
            'refund.amount' => $params['refund']['amount'],
            'refund.currency' => $params['refund']['currency'],
            'refund.sn' => $params['refund']['sn'],
        ];

        $content = $this->getContent($this->templateId, $placeholders);

        return $this->send($account->email, $subject, $content);
    }

}
