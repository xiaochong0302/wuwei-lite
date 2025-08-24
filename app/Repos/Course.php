<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Chapter as ChapterModel;
use App\Models\ChapterUser as ChapterUserModel;
use App\Models\Course as CourseModel;
use App\Models\CourseFavorite as CourseFavoriteModel;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\CourseRelated as CourseRelatedModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\Package as PackageModel;
use App\Models\Resource as ResourceModel;
use App\Models\Review as ReviewModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Row;
use Phalcon\Paginator\RepositoryInterface;

class Course extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return RepositoryInterface
     */
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(CourseModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            if (is_array($where['id'])) {
                $builder->inWhere('id', $where['id']);
            } else {
                $builder->andWhere('id = :id:', ['id' => $where['id']]);
            }
        }

        if (!empty($where['category_id'])) {
            if (is_array($where['category_id'])) {
                $builder->inWhere('category_id', $where['category_id']);
            } else {
                $builder->andWhere('category_id = :category_id:', ['category_id' => $where['category_id']]);
            }
        }

        if (!empty($where['level'])) {
            if (is_array($where['level'])) {
                $builder->inWhere('level', $where['level']);
            } else {
                $builder->andWhere('level = :level:', ['level' => $where['level']]);
            }
        }

        if (!empty($where['teacher_id'])) {
            $builder->andWhere('teacher_id = :teacher_id:', ['teacher_id' => $where['teacher_id']]);
        }

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['free'])) {
            if ($where['free'] == 1) {
                $builder->andWhere('regular_price = 0');
            } else {
                $builder->andWhere('regular_price > 0');
            }
        }

        if (isset($where['featured'])) {
            $builder->andWhere('featured = :featured:', ['featured' => $where['featured']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if ($sort == 'free') {
            $builder->andWhere('regular_price = 0');
        } elseif ($sort == 'featured') {
            $builder->andWhere('featured = 1');
        } elseif ($sort == 'vip_discount') {
            $builder->andWhere('vip_price < regular_price');
            $builder->andWhere('vip_price > 0');
        } elseif ($sort == 'vip_free') {
            $builder->andWhere('regular_price > 0');
            $builder->andWhere('vip_price = 0');
        }

        $orderBy = match ($sort) {
            'score' => 'score DESC, id DESC',
            'rating' => 'rating DESC, id DESC',
            'popular' => 'user_count DESC, id DESC',
            'oldest' => 'id ASC',
            default => 'id DESC',
        };

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param array $where
     * @param string $sort
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findAll($where = [], $sort = 'latest')
    {
        /**
         * 一个偷懒的实现，适用于中小体量数据
         */
        $paginate = $this->paginate($where, $sort, 1, 10000);

        return $paginate->getItems();
    }

    /**
     * @param int $id
     * @return CourseModel|Row|bool
     */
    public function findById($id)
    {
        return CourseModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param string $title
     * @return CourseModel|Row|bool
     */
    public function findByTitle($title)
    {
        return CourseModel::findFirst([
            'conditions' => 'title = :title:',
            'bind' => ['title' => $title],
            'order' => 'id DESC',
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return CourseModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findChapters($courseId)
    {
        return ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ChapterModel[]
     */
    public function findLessons($courseId)
    {
        return ChapterModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('parent_id > 0')
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ReviewModel[]
     */
    public function findReviews($courseId)
    {
        return ReviewModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|ResourceModel[]
     */
    public function findResources($courseId)
    {
        return ResourceModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|PackageModel[]
     */
    public function findPackages($courseId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('p.*')
            ->addFrom(PackageModel::class, 'p')
            ->join(CoursePackageModel::class, 'p.id = cp.package_id', 'cp')
            ->where('cp.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('p.published = 1')
            ->andWhere('p.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findRelatedCourses($courseId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CourseRelatedModel::class, 'c.id = cr.related_id', 'cr')
            ->where('cr.course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('c.published = 1')
            ->andWhere('c.deleted = 0')
            ->getQuery()->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ResultsetInterface|Resultset|ChapterUserModel[]
     */
    public function findUserLearnings($courseId, $userId)
    {
        return ChapterUserModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->andWhere('deleted = 0')
            ->execute();
    }

    /**
     * @param int $courseId
     * @param int $userId
     * @return ChapterUserModel|Row|bool
     */
    public function findLastChapterUser($courseId, $userId)
    {
        return ChapterUserModel::findFirst([
            'conditions' => 'course_id = ?1 AND user_id = ?2',
            'bind' => [1 => $courseId, 2 => $userId],
            'order' => 'update_time DESC',
        ]);
    }

    public function countPackages($courseId)
    {
        return $this->findPackages($courseId)->count();
    }

    /**
     * @return int
     */
    public function countCourses()
    {
        return (int)CourseModel::count([
            'conditions' => 'published = 1 AND deleted = 0',
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countLessons($courseId)
    {
        return (int)ChapterModel::count([
            'conditions' => 'course_id = :course_id: AND parent_id > 0 AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countResources($courseId)
    {
        return (int)ResourceModel::count([
            'conditions' => 'course_id = :course_id:',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countUsers($courseId)
    {
        return (int)CourseUserModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countReviews($courseId)
    {
        return (int)ReviewModel::count([
            'conditions' => 'course_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => ReviewModel::PUBLISH_APPROVED],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function countFavorites($courseId)
    {
        return (int)CourseFavoriteModel::count([
            'conditions' => 'course_id = :course_id: AND deleted = 0',
            'bind' => ['course_id' => $courseId],
        ]);
    }

    /**
     * @param int $courseId
     * @return int
     */
    public function averageRating($courseId)
    {
        return (int)ReviewModel::average([
            'column' => 'rating',
            'conditions' => 'course_id = ?1 AND published = ?2 AND deleted = 0',
            'bind' => [1 => $courseId, 2 => ReviewModel::PUBLISH_APPROVED],
        ]);
    }

}
