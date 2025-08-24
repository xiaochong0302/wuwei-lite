<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Models\Course as CourseModel;
use App\Services\Search\CourseDocument;
use App\Services\Search\CourseSearcher;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseIndexTask extends Task
{

    /**
     * 搜索测试
     *
     * @command: php console.php course_index search {query}
     */
    public function searchAction(string $query): void
    {
        if (strlen($query) == 0) {
            exit('please enter a query word' . PHP_EOL);
        }

        $handler = new CourseSearcher();

        $result = $handler->search($query);

        var_export($result);
    }

    /**
     * 清空索引
     *
     * @command: php console.php course_index clean
     */
    public function cleanAction(): void
    {
        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start clean course index ------' . PHP_EOL;

        $index->clean();

        echo '------ end clean course index ------' . PHP_EOL;
    }

    /**
     * 重建索引
     *
     * @command: php console.php course_index rebuild
     */
    public function rebuildAction(): void
    {
        $courses = $this->findCourses();

        if ($courses->count() == 0) return;

        $handler = new CourseSearcher();

        $doc = new CourseDocument();

        $index = $handler->getXS()->getIndex();

        echo '------ start rebuild course index ------' . PHP_EOL;

        $index->beginRebuild();

        foreach ($courses as $course) {
            $document = $doc->setDocument($course);
            $index->add($document);
        }

        $index->endRebuild();

        echo '------ end rebuild course index ------' . PHP_EOL;
    }

    /**
     * 刷新索引缓存
     *
     * @command: php console.php course_index flush_index
     */
    public function flushIndexAction(): void
    {
        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush course index ------' . PHP_EOL;

        $index->flushIndex();

        echo '------ end flush course index ------' . PHP_EOL;
    }

    /**
     * 刷新搜索日志
     *
     * @command: php console.php course_index flush_logging
     */
    public function flushLoggingAction(): void
    {
        $handler = new CourseSearcher();

        $index = $handler->getXS()->getIndex();

        echo '------ start flush course logging ------' . PHP_EOL;

        $index->flushLogging();

        echo '------ end flush course logging ------' . PHP_EOL;
    }

    /**
     * 查找课程
     *
     * @return ResultsetInterface|CourseModel[]
     */
    protected function findCourses()
    {
        return CourseModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->execute();
    }

}
