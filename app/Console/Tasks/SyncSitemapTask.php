<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Console\Tasks;

use App\Library\Sitemap;
use App\Models\Course as CourseModel;
use App\Models\Chapter as ChapterModel;
use App\Models\Page as PageModel;
use App\Services\Service as AppService;
use Phalcon\Mvc\Model\Resultset;

class SyncSitemapTask extends Task
{

    /**
     * @var string
     */
    protected string $siteUrl;

    /**
     * @var Sitemap
     */
    protected Sitemap $sitemap;

    public function mainAction()
    {
        $this->siteUrl = $this->getSiteUrl();

        $this->sitemap = new Sitemap();

        $filename = public_path('sitemap.xml');

        echo '------ start sync sitemap task ------' . PHP_EOL;

        $this->addIndex();
        $this->addCourses();
        $this->addChapters();
        $this->addPages();
        $this->addOthers();

        $this->sitemap->build($filename);

        echo '------ end sync sitemap task ------' . PHP_EOL;
    }

    protected function getSiteUrl()
    {
        $service = new AppService();

        $settings = $service->getSettings('site');

        return $settings['url'] ?? '';
    }

    protected function addIndex()
    {
        $this->sitemap->addItem($this->siteUrl, 1);
    }

    protected function addCourses()
    {
        /**
         * @var Resultset|CourseModel[] $courses
         */
        $courses = CourseModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('id DESC')
            ->limit(100)
            ->execute();

        if ($courses->count() == 0) return;

        foreach ($courses as $course) {
            $loc = sprintf('%s/course/%s/%s', $this->siteUrl, $course->id, $course->slug);
            $this->sitemap->addItem($loc, 0.8);
        }
    }

    protected function addChapters()
    {
        /**
         * @var Resultset|ChapterModel[] $chapters
         */
        $chapters = ChapterModel::query()
            ->where('parent_id > 0')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('id DESC')
            ->limit(100)
            ->execute();

        if ($chapters->count() == 0) return;

        foreach ($chapters as $chapter) {
            $loc = sprintf('%s/chapter/%s/%s', $this->siteUrl, $chapter->id, $chapter->slug);
            $this->sitemap->addItem($loc, 0.6);
        }
    }

    protected function addPages()
    {
        /**
         * @var Resultset|PageModel[] $pages
         */
        $pages = PageModel::query()
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->execute();

        if ($pages->count() == 0) return;

        foreach ($pages as $page) {
            $loc = sprintf('%s/page/%s/%s', $this->siteUrl, $page->id, $page->slug);
            $this->sitemap->addItem($loc, 0.7);
        }
    }

    protected function addOthers()
    {
        $this->sitemap->addItem("{$this->siteUrl}/course/list", 0.6);
        $this->sitemap->addItem("{$this->siteUrl}/teacher/list", 0.6);
    }

}
