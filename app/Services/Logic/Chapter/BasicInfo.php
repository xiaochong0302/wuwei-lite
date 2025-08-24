<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Chapter;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterVideo as ChapterVideoModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Media as MediaRepo;
use App\Services\Logic\ChapterTrait;
use App\Services\Logic\ContentTrait;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;

class BasicInfo extends LogicService
{

    use CourseTrait;
    use ChapterTrait;
    use ContentTrait;

    public function handle(int $id): array
    {
        $chapter = $this->checkChapter($id);

        $course = $this->checkCourse($chapter->course_id);

        $result = $this->handleBasicInfo($course, $chapter);

        $result['course'] = $this->handleCourseInfo($course);

        return $result;
    }

    public function handleBasicInfo(CourseModel $course, ChapterModel $chapter): array
    {
        $result = [];

        switch ($chapter->model) {
            case ChapterModel::MODEL_VIDEO:
                $result = $this->formatChapterVideo($chapter);
                break;
            case ChapterModel::MODEL_ARTICLE:
                $result = $this->formatChapterArticle($chapter);
                break;
        }

        $result['comment_enabled'] = $this->getCommentStatus($course, $chapter);

        return $result;
    }

    public function handleCourseInfo(CourseModel $course): array
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'slug' => $course->slug,
        ];
    }

    protected function formatChapterVideo(ChapterModel $chapter): array
    {
        $chapterRepo = new ChapterRepo();

        $chapterVideo = $chapterRepo->findChapterVideo($chapter->id);

        $playUrls = $this->getPlayUrls($chapterVideo);

        $settings = $this->getVodSettings($chapterVideo);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'slug' => $chapter->slug,
            'summary' => $chapter->summary,
            'keywords' => $chapter->keywords,
            'model' => $chapter->model,
            'published' => $chapter->published,
            'deleted' => $chapter->deleted,
            'settings' => $settings,
            'play_urls' => $playUrls,
            'human_verify_enabled' => 0,
            'comment_enabled' => $chapter->comment_enabled,
            'comment_count' => $chapter->comment_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
        ];
    }

    protected function formatChapterArticle(ChapterModel $chapter): array
    {
        $chapterRepo = new ChapterRepo();

        $article = $chapterRepo->findChapterArticle($chapter->id);

        $content = $this->handleContent($article->content);

        return [
            'id' => $chapter->id,
            'title' => $chapter->title,
            'summary' => $chapter->summary,
            'keywords' => $chapter->keywords,
            'model' => $chapter->model,
            'content' => $content,
            'settings' => $article->settings,
            'published' => $chapter->published,
            'deleted' => $chapter->deleted,
            'comment_enabled' => $chapter->comment_enabled,
            'comment_count' => $chapter->comment_count,
            'user_count' => $chapter->user_count,
            'like_count' => $chapter->like_count,
            'create_time' => $chapter->create_time,
            'update_time' => $chapter->update_time,
        ];
    }

    protected function getPlayUrls(ChapterVideoModel $chapterVideo): array
    {
        $result = [];

        $mediaRepo = new MediaRepo();

        $media = $mediaRepo->findById($chapterVideo->media_id);

        if (!empty($media->file_origin)) {
            $url = sprintf('%s%s', kg_cos_url(), $media->file_origin['path']);
            $result['sd'] = ['url' => $url];
        }

        return $result;
    }

    protected function getVodSettings(ChapterVideoModel $chapterVideo): array
    {
        /**
         * 重新执行获取后置操作，前面可能有写入操作
         */
        $chapterVideo->afterFetch();

        return $chapterVideo->settings;
    }

    protected function getCommentStatus(CourseModel $course, ChapterModel $chapter): int
    {
        return $course->comment_enabled == 1 ? $chapter->comment_enabled : 0;
    }

}
