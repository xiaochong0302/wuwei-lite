<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Notice\Email;

use App\Repos\Account as AccountRepo;
use App\Services\Mailer;

class ReviewRemind extends Mailer
{

    /**
     * @var string
     */
    protected string $templateId = 'review_remind';

    public function handle(array $params): bool
    {
        $accountRepo = new AccountRepo();

        $account = $accountRepo->findById($params['user']['id']);

        if (!$account->email) return true;

        $subject = $this->getSubject($this->templateId);

        $placeholders = [
            'user.name' => $params['user']['name'],
            'course.title' => $params['course']['title'],
            'my.progress' => $params['course_user']['progress'],
            'my.duration' => $params['course_user']['duration'],
            'my.courses_url' => $this->getMyCoursesUrl(),
        ];

        $content = $this->getContent($this->templateId, $placeholders);

        return $this->send($account->email, $subject, $content);
    }

    protected function getMyCoursesUrl(): string
    {
        return sprintf('%s/uc/courses', kg_setting('site', 'url'));
    }

}
