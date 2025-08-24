<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Caches;

use App\Models\User as UserModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class IndexTeacherList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 3600;

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getKey($id = null): string
    {
        return 'index-teacher-list';
    }

    public function getContent($id = null): array
    {
        $teachers = $this->findTeachers();

        if ($teachers->count() == 0) {
            return [];
        }

        $result = [];

        $baseUrl = kg_cos_url();

        foreach ($teachers->toArray() as $teacher) {

            $teacher['avatar'] = $baseUrl . $teacher['avatar'];

            $result[] = [
                'id' => $teacher['id'],
                'name' => $teacher['name'],
                'title' => $teacher['title'],
                'avatar' => $teacher['avatar'],
                'about' => $teacher['about'],
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|UserModel[]
     */
    protected function findTeachers(int $limit = 8)
    {
        return UserModel::query()
            ->where('edu_role = :edu_role:', ['edu_role' => UserModel::EDU_ROLE_TEACHER])
            ->andWhere('deleted = 0')
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

}
