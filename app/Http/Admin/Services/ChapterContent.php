<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Caches\CourseChapterList as CatalogCache;
use App\Library\Utils\Word as WordUtil;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterArticle as ChapterArticleModel;
use App\Models\ChapterVideo as ChapterVideoModel;
use App\Models\Media as MediaModel;
use App\Models\Upload as UploadModel;
use App\Repos\Chapter as ChapterRepo;
use App\Repos\Media as MediaRepo;
use App\Repos\Upload as UploadRepo;
use App\Validators\ChapterArticle as ChapterArticleValidator;
use App\Validators\Media as MediaValidator;

class ChapterContent extends Service
{

    public function formatDuration(int $duration): array
    {
        $result = ['hours' => 0, 'minutes' => 0, 'seconds' => 0];

        if ($duration > 0) {
            $result['hours'] = floor($duration / 3600);
            $result['minutes'] = floor(($duration % 3600) / 60);
            $result['seconds'] = $duration % 60;
        }

        return $result;
    }

    public function getChapterVideo(int $chapterId): ChapterVideoModel
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterVideo($chapterId);
    }

    public function getChapterArticle(int $chapterId): ChapterArticleModel
    {
        $chapterRepo = new ChapterRepo();

        return $chapterRepo->findChapterArticle($chapterId);
    }

    public function getVideoMedia(int $mediaId): MediaModel
    {
        $mediaRepo = new MediaRepo();

        return $mediaRepo->findById($mediaId);
    }

    public function getMediaUpload(int $uploadId): UploadModel
    {
        $uploadRepo = new UploadRepo();

        return $uploadRepo->findById($uploadId);
    }

    public function updateChapterContent(int $chapterId): void
    {
        $chapterRepo = new ChapterRepo();

        $chapter = $chapterRepo->findById($chapterId);

        switch ($chapter->model) {
            case ChapterModel::MODEL_VIDEO:
                $this->updateChapterVideo($chapter);
                break;
            case ChapterModel::MODEL_ARTICLE:
                $this->updateChapterArticle($chapter);
                break;
        }

        $this->rebuildCatalogCache($chapter);
    }

    protected function updateChapterVideo(ChapterModel $chapter): void
    {
        $uploadId = $this->request->getPost('upload_id', 'int', 0);
        $duration = $this->request->getPost('duration');

        $validator = new MediaValidator();

        $upload = $validator->checkUpload($uploadId);

        $chapterRepo = new ChapterRepo();

        $chapterVideo = $chapterRepo->findChapterVideo($chapter->id);

        $mediaRepo = new MediaRepo();

        $media = $mediaRepo->findByUploadId($upload->id);

        if (!$media) {
            $media = new MediaModel();
            $media->upload_id = $upload->id;
            $media->create();
        }

        $fileOrigin = [
            'path' => $upload->path,
            'duration' => 0,
        ];

        if ($duration) {
            $fileOrigin['duration'] = $this->getTotalSeconds($duration);
        }

        $media->file_origin = $fileOrigin;

        $media->update();

        $chapterVideo->media_id = $media->id;

        $chapterVideo->update();

        $attrs = $chapter->attrs;
        $attrs['duration'] = $fileOrigin['duration'];
        $chapter->attrs = $attrs;

        $chapter->update();
    }

    protected function updateChapterArticle(ChapterModel $chapter): void
    {
        $post = $this->request->getPost();

        $chapterRepo = new ChapterRepo();

        $chapterArticle = $chapterRepo->findChapterArticle($chapter->id);

        $validator = new ChapterArticleValidator();

        $content = $validator->checkContent($post['content']);

        $chapterArticle->content = $content;

        $chapterArticle->update();

        $attrs = $chapter->attrs;
        $attrs['word_count'] = WordUtil::getWordCount($content);
        $attrs['duration'] = WordUtil::getWordDuration($content);
        $chapter->attrs = $attrs;

        $chapter->update();
    }

    protected function rebuildCatalogCache(ChapterModel $chapter): void
    {
        $cache = new CatalogCache();

        $cache->rebuild($chapter->course_id);
    }

    protected function getTotalSeconds(array $duration): int
    {
        return $duration['hours'] * 3600 + $duration['minutes'] * 60 + $duration['seconds'];
    }

}
