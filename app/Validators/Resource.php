<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Models\Resource as ResourceModel;
use App\Models\Upload as UploadModel;
use App\Repos\Resource as ResourceRepo;

class Resource extends Validator
{

    public function checkResource(int $id): ResourceModel
    {
        $resourceRepo = new ResourceRepo();

        $resource = $resourceRepo->findById($id);

        if (!$resource) {
            throw new BadRequestException('resource.not_found');
        }

        return $resource;
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkChapter(int $id): ChapterModel
    {
        $validator = new Chapter();

        return $validator->checkChapter($id);
    }

    public function checkUpload(int $id): UploadModel
    {
        $validator = new Upload();

        return $validator->checkUpload($id);
    }

}
