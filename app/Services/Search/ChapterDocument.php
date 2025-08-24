<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Search;

use App\Models\Chapter as ChapterModel;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use Phalcon\Di\Injectable;
use XSDocument;

class ChapterDocument extends Injectable
{

    /**
     * 设置文档
     *
     * @param ChapterModel $chapter
     * @return XSDocument
     */
    public function setDocument(ChapterModel $chapter): XSDocument
    {
        $doc = new XSDocument();

        $data = $this->formatDocument($chapter);

        $doc->setFields($data);

        return $doc;
    }

    /**
     * 格式化文档
     *
     * @param ChapterModel $chapter
     * @return array
     */
    public function formatDocument(ChapterModel $chapter): array
    {
        $course = $this->handleCourse($chapter->course_id);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'slug' => $chapter->slug,
            'summary' => $chapter->summary,
            'keywords' => $chapter->keywords,
            'model' => $chapter->model,
            'free' => $chapter->free,
            'course_id' => $chapter->course_id,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'comment_count' => $chapter->comment_count,
            'course' => $course,
        ];
    }

    protected function handleCourse(int $id): string
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        $course->cover = CourseModel::getCoverPath($course->cover);

        return kg_json_encode([
            'id' => $course->id,
            'title' => $course->title,
            'slug' => $course->slug,
            'cover' => $course->cover,
            'summary' => $course->summary,
        ]);
    }

}
