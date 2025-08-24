<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Comment;

use App\Models\Comment as CommentModel;
use App\Traits\Client as ClientTrait;
use App\Validators\Comment as CommentValidator;

trait CommentDataTrait
{

    use ClientTrait;

    protected function handlePostData(array $post): array
    {
        $data = [];

        $data['client_type'] = $this->getClientType();
        $data['client_ip'] = $this->getClientIp();

        $validator = new CommentValidator();

        $data['content'] = $validator->checkContent($post['content']);

        return $data;
    }

}
