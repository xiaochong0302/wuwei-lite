<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Models\Chapter as ChapterModel;
use App\Models\ChapterArticle as ChapterArticleModel;
use App\Models\ChapterLike as ChapterLikeModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\ChapterVideo as ChapterVideoModel;
use App\Models\Comment as CommentModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;

class Chapter extends Repository
{

    /**
     * @param array $where
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findAll($where = [])
    {
        $query = ChapterModel::query();

        $query->where('1 = 1');

        if (isset($where['parent_id'])) {
            $query->andWhere('parent_id = :parent_id:', ['parent_id' => $where['parent_id']]);
        }

        if (isset($where['course_id'])) {
            $query->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (isset($where['model'])) {
            $query->andWhere('model = :model:', ['model' => $where['model']]);
        }

        if (isset($where['published'])) {
            $query->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $query->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        return $query->execute();
    }

    /**
     * @param int $id
     * @return ChapterModel|Row|null
     */
    public function findById($id)
    {
        return ChapterModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return ChapterModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $id
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findLessons($id)
    {
        return ChapterModel::query()
            ->where('parent_id = :parent_id:', ['parent_id' => $id])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $chapterId
     * @return ChapterVideoModel|Row|null
     */
    public function findChapterVideo($chapterId)
    {
        return ChapterVideoModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return ChapterArticleModel|Row|null
     */
    public function findChapterArticle($chapterId)
    {
        return ChapterArticleModel::findFirst([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function maxChapterPriority($courseId)
    {
        return (int)ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'course_id = :course_id: AND parent_id = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return int
     */
    public function maxLessonPriority($chapterId)
    {
        return (int)ChapterModel::maximum([
            'column' => 'priority',
            'conditions' => 'parent_id = :parent_id:',
            'bind' => ['parent_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return int
     */
    public function countLessons($chapterId)
    {
        return (int)ChapterModel::count([
            'conditions' => 'parent_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return int
     */
    public function countUsers($chapterId)
    {
        return (int)ChapterUserModel::count([
            'conditions' => 'chapter_id = :chapter_id:',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return int
     */
    public function countLikes($chapterId)
    {
        return (int)ChapterLikeModel::count([
            'conditions' => 'chapter_id = :chapter_id: AND deleted = 0',
            'bind' => ['chapter_id' => $chapterId],
        ]);
    }

    /**
     * @param int $chapterId
     * @return int
     */
    public function countComments($chapterId)
    {
        return (int)CommentModel::count([
            'conditions' => 'chapter_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $chapterId, 2 => CommentModel::PUBLISH_APPROVED],
        ]);
    }

}
