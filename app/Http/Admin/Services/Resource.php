<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Models\Course as CourseModel;
use App\Models\Resource as ResourceModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Resource as ResourceRepo;
use App\Validators\Resource as ResourceValidator;
use App\Validators\Upload as UploadValidator;

class Resource extends Service
{

    public function createResource(): ResourceModel
    {
        $courseId = $this->request->getPost('course_id');
        $uploadId = $this->request->getPost('upload_id');

        $validator = new ResourceValidator();

        $course = $validator->checkCourse($courseId);
        $upload = $validator->checkUpload($uploadId);

        $resourceRepo = new ResourceRepo();

        $resources = $resourceRepo->findByCourseId($course->id);

        if ($resources->count() > 0) {
            foreach ($resources as $resource) {
                if ($resource->upload_id == $upload->id) {
                    return $resource;
                }
            }
        }

        $resource = new ResourceModel();

        $resource->course_id = $course->id;
        $resource->upload_id = $upload->id;

        $resource->create();

        $this->recountCourseResources($course);

        return $resource;
    }

    public function updateResource(int $id): ResourceModel
    {
        $resource = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new UploadValidator();

        $upload = $validator->checkUpload($resource->upload_id);

        $data = [];

        if (isset($post['name'])) {
            $data['name'] = $validator->checkName($post['name']);
        }

        $upload->assign($data);

        $upload->update();

        $resource->update();
    }

    public function deleteResource(int $id): ResourceModel
    {
        $resource = $this->findOrFail($id);

        $validator = new ResourceValidator();

        $course = $validator->checkCourse($resource->course_id);

        $resource->delete();

        $this->recountCourseResources($course);
    }

    protected function findOrFail(int $id): ResourceModel
    {
        $validator = new ResourceValidator();

        return $validator->checkResource($id);
    }

    protected function recountCourseResources(CourseModel $course): void
    {
        $courseRepo = new CourseRepo();

        $resourceCount = $courseRepo->countResources($course->id);

        $course->resource_count = $resourceCount;

        $course->update();
    }

}
