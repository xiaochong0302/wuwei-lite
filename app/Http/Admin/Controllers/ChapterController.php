<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Chapter as ChapterService;
use App\Http\Admin\Services\ChapterContent as ChapterContentService;
use App\Http\Admin\Services\Course as CourseService;
use App\Models\Chapter as ChapterModel;
use App\Models\Media as MediaModel;
use App\Models\Upload as UploadModel;

/**
 * @RoutePrefix("/admin/chapter")
 */
class ChapterController extends Controller
{

    /**
     * @Get("/{id:[0-9]+}/lessons", name="admin.chapter.lessons")
     */
    public function lessonsAction($id)
    {
        $courseService = new CourseService();
        $chapterService = new ChapterService();

        $chapter = $chapterService->getChapter($id);
        $lessons = $chapterService->getLessons($chapter->id);
        $course = $courseService->getCourse($chapter->course_id);

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('lessons', $lessons);
        $this->view->setVar('course', $course);
    }

    /**
     * @Get("/add", name="admin.chapter.add")
     */
    public function addAction()
    {
        $courseId = $this->request->getQuery('course_id', 'int');
        $parentId = $this->request->getQuery('parent_id', 'int');
        $type = $this->request->getQuery('type', 'string', 'module');

        $courseService = new CourseService();

        $course = $courseService->getCourse($courseId);
        $modules = $courseService->getModules($courseId);

        $chapterService = new ChapterService();

        $modelTypes = $chapterService->getModelTypes();

        $this->view->pick('chapter/add_module');

        if ($type == 'lesson') {
            $this->view->pick('chapter/add_lesson');
        }

        $this->view->setVar('course', $course);
        $this->view->setVar('model_types', $modelTypes);
        $this->view->setVar('parent_id', $parentId);
        $this->view->setVar('modules', $modules);
    }

    /**
     * @Post("/create", name="admin.chapter.create")
     */
    public function createAction()
    {
        $chapterService = new ChapterService();

        $chapter = $chapterService->createChapter();

        if ($chapter->parent_id > 0) {
            $location = $this->url->get([
                'for' => 'admin.chapter.lessons',
                'id' => $chapter->parent_id,
            ]);
        } else {
            $location = $this->url->get([
                'for' => 'admin.course.chapters',
                'id' => $chapter->course_id,
            ]);
        }

        $msg = $this->locale->query('created_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.chapter.edit")
     */
    public function editAction($id)
    {
        $contentService = new ChapterContentService();
        $chapterService = new ChapterService();
        $courseService = new CourseService();

        $chapter = $chapterService->getChapter($id);
        $course = $courseService->getCourse($chapter->course_id);

        $this->view->pick('chapter/edit_module');

        if ($chapter->parent_id > 0) {

            $this->view->pick('chapter/edit_lesson');

            switch ($chapter->model) {
                case ChapterModel::MODEL_VIDEO:
                    $chapterVideo = $contentService->getChapterVideo($chapter->id);
                    $media = new mediaModel();
                    if ($chapterVideo->media_id > 0) {
                        $media = $contentService->getVideoMedia($chapterVideo->media_id);
                    }
                    $upload = new UploadModel();
                    if ($media->id > 0) {
                        $upload = $contentService->getMediaUpload($media->upload_id);
                    }
                    $duration = $media->file_origin['duration'] ?? 0;
                    $duration = $contentService->formatDuration($duration);
                    $this->view->setVar('media', $media);
                    $this->view->setVar('upload', $upload);
                    $this->view->setVar('cos_url', kg_cos_url());
                    $this->view->setVar('duration', $duration);
                    break;
                case ChapterModel::MODEL_ARTICLE:
                    $chapterArticle = $contentService->getChapterArticle($chapter->id);
                    $this->view->setVar('article', $chapterArticle);
                    break;
            }
        }

        $this->view->setVar('chapter', $chapter);
        $this->view->setVar('course', $course);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.chapter.update")
     */
    public function updateAction($id)
    {
        $chapterService = new ChapterService();

        $chapter = $chapterService->updateChapter($id);

        if ($chapter->parent_id > 0) {
            $location = $this->url->get([
                'for' => 'admin.chapter.lessons',
                'id' => $chapter->parent_id,
            ]);
        } else {
            $location = $this->url->get([
                'for' => 'admin.course.modules',
                'id' => $chapter->course_id,
            ]);
        }

        $msg = $this->locale->query('updated_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.chapter.delete")
     */
    public function deleteAction($id)
    {
        $chapterService = new ChapterService();

        $chapterService->deleteChapter($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.chapter.restore")
     */
    public function restoreAction($id)
    {
        $chapterService = new ChapterService();

        $chapterService->restoreChapter($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/content", name="admin.chapter.content")
     */
    public function contentAction($id)
    {
        $contentService = new ChapterContentService();

        $contentService->updateChapterContent($id);

        $chapterService = new ChapterService();

        $chapter = $chapterService->getChapter($id);

        $location = $this->url->get([
            'for' => 'admin.chapter.lessons',
            'id' => $chapter->parent_id,
        ]);

        $msg = $this->locale->query('updated_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

}
