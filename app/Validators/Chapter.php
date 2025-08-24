<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Validators;

use App\Caches\Chapter as ChapterCache;
use App\Caches\MaxChapterId as MaxChapterIdCache;
use App\Exceptions\BadRequest as BadRequestException;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterArticle as ChapterArticleModel;
use App\Models\ChapterVideo as ChapterVideoModel;
use App\Models\Course as CourseModel;
use App\Repos\Chapter as ChapterRepo;

class Chapter extends Validator
{

    public function checkChapterCache(int $id): ChapterModel
    {
        $this->checkId($id);

        $chapterCache = new ChapterCache();

        $chapter = $chapterCache->get($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.not_found');
        }

        return $chapter;
    }

    public function checkChapterVideo(int $id): ChapterVideoModel
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepo();

        $chapterVideo = $chapterRepo->findChapterVideo($id);

        if (!$chapterVideo) {
            throw new BadRequestException('chapter_video.not_found');
        }

        return $chapterVideo;
    }

    public function checkChapterArticle(int $id): ChapterArticleModel
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepo();

        $chapterRead = $chapterRepo->findChapterArticle($id);

        if (!$chapterRead) {
            throw new BadRequestException('chapter_article.not_found');
        }

        return $chapterRead;
    }

    public function checkChapter(int $id): ChapterModel
    {
        $this->checkId($id);

        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.not_found');
        }

        return $chapter;
    }

    public function checkId(int $id): void
    {
        $maxIdCache = new MaxChapterIdCache();

        $maxId = $maxIdCache->get();

        if ($id < 1 || $id > $maxId) {
            throw new BadRequestException('chapter.not_found');
        }
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkParent(int $id): ChapterModel
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($id);

        if (!$chapter) {
            throw new BadRequestException('chapter.parent_not_found');
        }

        return $chapter;
    }

    public function checkTitle(string $title): string
    {
        $value = $this->filter->sanitize($title, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length < 2) {
            throw new BadRequestException('chapter.title_too_short');
        }

        if ($length > 120) {
            throw new BadRequestException('chapter.title_too_long');
        }

        return $value;
    }

    public function checkSummary(string $summary): string
    {
        $value = $this->filter->sanitize($summary, ['trim', 'string']);

        $length = kg_strlen($value);

        if ($length > 255) {
            throw new BadRequestException('chapter.summary_too_long');
        }

        return $value;
    }

    public function checkKeywords($keywords)
    {
        $keywords = $this->filter->sanitize($keywords, ['trim', 'string']);

        $length = kg_strlen($keywords);

        if ($length > 120) {
            throw new BadRequestException('chapter.keyword_too_long');
        }

        return kg_parse_keywords($keywords);
    }

    public function checkModel(int $model): int
    {
        $list = ChapterModel::modelTypes();

        if (!array_key_exists($model, $list)) {
            throw new BadRequestException('chapter.invalid_model');
        }

        return $model;
    }

    public function checkPriority(int $priority): int
    {
        $value = $this->filter->sanitize($priority, ['trim', 'int']);

        if ($value < 1 || $value > 255) {
            throw new BadRequestException('chapter.invalid_priority');
        }

        return $value;
    }

    public function checkPublishStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('chapter.invalid_publish_status');
        }

        return $status;
    }

    public function checkCommentStatus(int $status): int
    {
        if (!in_array($status, [0, 1])) {
            throw new BadRequestException('chapter.invalid_comment_status');
        }

        return $status;
    }

    public function checkPublishAbility(ChapterModel $chapter): void
    {
        $attrs = $chapter->attrs;

        if ($chapter->model == ChapterModel::MODEL_VIDEO) {
            if ($attrs['duration'] == 0) {
                throw new BadRequestException('chapter.video_not_ready');
            }
        } elseif ($chapter->model == ChapterModel::MODEL_ARTICLE) {
            if ($attrs['word_count'] == 0) {
                throw new BadRequestException('chapter.article_not_ready');
            }
        }
    }

    public function checkDeleteAbility(ChapterModel $chapter): void
    {
        $chapterRepo = new ChapterRepo();

        $chapters = $chapterRepo->findAll([
            'parent_id' => $chapter->id,
            'deleted' => 0,
        ]);

        if ($chapters->count() > 0) {
            throw new BadRequestException('chapter.child_existed');
        }
    }

}
