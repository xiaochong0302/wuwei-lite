<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Caches\CoursePackageList as CoursePackageListCache;
use App\Caches\Package as PackageCache;
use App\Caches\PackageCourseList as PackageCourseListCache;
use App\Library\Paginator\Query as PagerQuery;
use App\Models\CoursePackage as CoursePackageModel;
use App\Models\Package as PackageModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CoursePackage as CoursePackageRepo;
use App\Repos\Package as PackageRepo;
use App\Validators\Package as PackageValidator;
use Phalcon\Paginator\RepositoryInterface;

class Package extends Service
{

    public function getXmCourses(int $id): array
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($id);

        $courseIds = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $courseIds[] = $course->id;
            }
        }

        $courseRepo = new CourseRepo();

        $items = $courseRepo->findAll([
            'free' => 0,
            'published' => 1,
            'deleted' => 0,
        ]);

        if ($items->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            $result[] = [
                'name' => sprintf('%s - %s (%0.2f)', $item->id, $item->title, $item->regular_price),
                'value' => $item->id,
                'selected' => in_array($item->id, $courseIds),
            ];
        }

        return $result;
    }

    public function getPackages(): RepositoryInterface
    {
        $pagerQuery = new PagerQuery();

        $params = $pagerQuery->getParams();

        $params['deleted'] = $params['deleted'] ?? 0;

        $sort = $pagerQuery->getSort();
        $page = $pagerQuery->getPage();
        $limit = $pagerQuery->getLimit();

        $pageRepo = new PackageRepo();

        return $pageRepo->paginate($params, $sort, $page, $limit);
    }

    public function getPackage(int $id): PackageModel
    {
        return $this->findOrFail($id);
    }

    public function createPackage(): PackageModel
    {
        $post = $this->request->getPost();

        $validator = new PackageValidator();

        $data = [];

        $data['title'] = $validator->checkTitle($post['title']);

        $package = new PackageModel();

        $package->assign($data);

        $package->create();

        $this->rebuildPackageCache($package->id);

        return $package;
    }

    public function updatePackage(int $id): PackageModel
    {
        $package = $this->findOrFail($id);

        $post = $this->request->getPost();

        $validator = new PackageValidator();

        $data = [];

        if (isset($post['title'])) {
            $data['title'] = $validator->checkTitle($post['title']);
        }

        if (isset($post['cover'])) {
            $data['cover'] = $validator->checkCover($post['cover']);
        }

        if (isset($post['summary'])) {
            $data['summary'] = $validator->checkSummary($post['summary']);
        }

        if (isset($post['regular_price'])) {
            $data['regular_price'] = $validator->checkregularPrice($post['regular_price']);
        }

        if (isset($post['vip_price'])) {
            $data['vip_price'] = $validator->checkVipPrice($post['vip_price']);
        }

        if (isset($post['published'])) {
            $data['published'] = $validator->checkPublishStatus($post['published']);
        }

        if (isset($post['xm_course_ids'])) {
            $this->saveCourses($package, $post['xm_course_ids']);
        }

        $package->assign($data);

        $package->update();

        $this->handlePackagedCourses($package->id);
        $this->recountPackageCourses($package->id);
        $this->rebuildPackageCache($package->id);

        return $package;
    }

    public function deletePackage(int $id): PackageModel
    {
        $package = $this->findOrFail($id);

        $package->deleted = 1;

        $package->update();

        $this->handlePackagedCourses($package->id);
        $this->rebuildPackageCache($package->id);

        return $package;
    }

    public function restorePackage(int $id): PackageModel
    {
        $package = $this->findOrFail($id);

        $package->deleted = 0;

        $package->update();

        $this->handlePackagedCourses($package->id);
        $this->rebuildPackageCache($package->id);

        return $package;
    }

    protected function findOrFail(int $id): PackageModel
    {
        $validator = new PackageValidator();

        return $validator->checkPackage($id);
    }

    protected function saveCourses(PackageModel $package, string $courseIds): void
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        $originCourseIds = [];

        if ($courses->count() > 0) {
            foreach ($courses as $course) {
                $originCourseIds[] = $course->id;
            }
        }

        $newCourseIds = $courseIds ? explode(',', $courseIds) : [];
        $addedCourseIds = array_diff($newCourseIds, $originCourseIds);

        if ($addedCourseIds) {
            foreach ($addedCourseIds as $courseId) {
                $coursePackage = new CoursePackageModel();
                $coursePackage->course_id = $courseId;
                $coursePackage->package_id = $package->id;
                $coursePackage->create();
                $this->recountCoursePackages($courseId);
                $this->rebuildCoursePackageCache($courseId);
            }
        }

        $deletedCourseIds = array_diff($originCourseIds, $newCourseIds);

        if ($deletedCourseIds) {
            $coursePackageRepo = new CoursePackageRepo();
            foreach ($deletedCourseIds as $courseId) {
                $coursePackage = $coursePackageRepo->findCoursePackage($courseId, $package->id);
                $coursePackage->delete();
                $this->recountCoursePackages($courseId);
                $this->rebuildCoursePackageCache($courseId);
            }
        }
    }

    protected function handlePackagedCourses(int $packageId): void
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($packageId);

        if ($courses->count() == 0) return;

        foreach ($courses as $course) {
            $this->rebuildCoursePackageCache($course->id);
            $this->recountCoursePackages($course->id);
        }
    }

    protected function recountPackageCourses(int $packageId): void
    {
        $packageRepo = new PackageRepo();

        $package = $packageRepo->findById($packageId);

        $courseCount = $packageRepo->countCourses($package->id);

        $package->course_count = $courseCount;

        $package->update();
    }

    protected function recountCoursePackages(int $courseId): void
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($courseId);

        $packageCount = $courseRepo->countPackages($courseId);

        $course->package_count = $packageCount;

        $course->update();
    }

    protected function rebuildPackageCache(int $packageId): void
    {
        $cache = new PackageCache();

        $cache->rebuild($packageId);

        $cache = new PackageCourseListCache();

        $cache->rebuild($packageId);
    }

    protected function rebuildCoursePackageCache(int $courseId): void
    {
        $cache = new CoursePackageListCache();

        $cache->rebuild($courseId);
    }

}
