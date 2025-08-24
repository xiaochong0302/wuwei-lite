<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\Learning as LearningModel;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Sync\Learning as LearningSyncService;
use App\Validators\Learning as LearningValidator;

class Learning extends LogicService
{

    use ChapterTrait;

    public function handle(int $id): void
    {
        $post = $this->request->getPost();

        $chapter = $this->checkChapterCache($id);

        $user = $this->getLoginUser();

        $validator = new LearningValidator();

        $data = [
            'course_id' => $chapter->course_id,
            'chapter_id' => $chapter->id,
            'user_id' => $user->id,
            'position' => 0,
        ];

        $data['request_id'] = $validator->checkRequestId($post['request_id']);

        if ($chapter->model == ChapterModel::MODEL_VIDEO) {
            $data['position'] = $validator->checkPosition($post['position']);
        }

        $intervalTime = $validator->checkIntervalTime($post['interval_time']);

        $learning = new LearningModel($data);

        $sync = new LearningSyncService();

        $sync->addItem($learning, $intervalTime);
    }

}
