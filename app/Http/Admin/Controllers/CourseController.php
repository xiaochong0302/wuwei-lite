<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Course as CourseService;
use App\Http\Admin\Services\CourseLearning as CourseLearningService;
use App\Http\Admin\Services\CourseUser as CourseUserService;

/**
 * @RoutePrefix("/admin/course")
 */
class CourseController extends Controller
{

    /**
     * @Get("/search", name="admin.course.search")
     */
    public function searchAction()
    {
        $courseService = new CourseService();

        $categoryOptions = $courseService->getCategoryOptions();
        $teacherOptions = $courseService->getTeacherOptions();
        $levelTypes = $courseService->getLevelTypes();

        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('teacher_options', $teacherOptions);
        $this->view->setVar('level_types', $levelTypes);
    }

    /**
     * @Get("/list", name="admin.course.list")
     */
    public function listAction()
    {
        $courseService = new CourseService();

        $pager = $courseService->getCourses();

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/add", name="admin.course.add")
     */
    public function addAction()
    {

    }

    /**
     * @Post("/create", name="admin.course.create")
     */
    public function createAction()
    {
        $courseService = new CourseService();

        $course = $courseService->createCourse();

        $location = $this->url->get([
            'for' => 'admin.course.edit',
            'id' => $course->id,
        ]);

        $msg = $this->locale->query('created_ok');

        $content = [
            'location' => $location,
            'msg' => $msg,
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/edit", name="admin.course.edit")
     */
    public function editAction($id)
    {
        $courseService = new CourseService();

        $course = $courseService->getCourse($id);
        $xmCourses = $courseService->getXmCourses($id);
        $levelTypes = $courseService->getLevelTypes();
        $teacherOptions = $courseService->getTeacherOptions();
        $categoryOptions = $courseService->getCategoryOptions();
        $studyExpiryOptions = $courseService->getStudyExpiryOptions();
        $refundExpiryOptions = $courseService->getRefundExpiryOptions();

        $this->view->setVar('course', $course);
        $this->view->setVar('xm_courses', $xmCourses);
        $this->view->setVar('level_types', $levelTypes);
        $this->view->setVar('teacher_options', $teacherOptions);
        $this->view->setVar('category_options', $categoryOptions);
        $this->view->setVar('study_expiry_options', $studyExpiryOptions);
        $this->view->setVar('refund_expiry_options', $refundExpiryOptions);
    }

    /**
     * @Post("/{id:[0-9]+}/update", name="admin.course.update")
     */
    public function updateAction($id)
    {
        $courseService = new CourseService();

        $courseService->updateCourse($id);

        $content = [
            'msg' => $this->locale->query('updated_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/delete", name="admin.course.delete")
     */
    public function deleteAction($id)
    {
        $courseService = new CourseService();

        $courseService->deleteCourse($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('deleted_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/restore", name="admin.course.restore")
     */
    public function restoreAction($id)
    {
        $courseService = new CourseService();

        $courseService->restoreCourse($id);

        $content = [
            'location' => $this->request->getHTTPReferer(),
            'msg' => $this->locale->query('restored_ok'),
        ];

        return $this->jsonSuccess($content);
    }

    /**
     * @Get("/{id:[0-9]+}/modules", name="admin.course.modules")
     */
    public function modulesAction($id)
    {
        $courseService = new CourseService();

        $course = $courseService->getCourse($id);
        $modules = $courseService->getModules($id);

        $this->view->setVar('course', $course);
        $this->view->setVar('modules', $modules);
    }

    /**
     * @Get("/{id:[0-9]+}/learnings", name="admin.course.learnings")
     */
    public function learningsAction($id)
    {
        $service = new CourseLearningService();

        $pager = $service->getLearnings($id);

        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/users", name="admin.course.users")
     */
    public function usersAction($id)
    {
        $service = new CourseService();
        $course = $service->getCourse($id);

        $service = new CourseUserService();
        $pager = $service->getUsers($id);

        $this->view->setVar('course', $course);
        $this->view->setVar('pager', $pager);
    }

    /**
     * @Get("/{id:[0-9]+}/user/search", name="admin.course.search_user")
     */
    public function searchUserAction($id)
    {
        $service = new CourseService();
        $course = $service->getCourse($id);

        $service = new CourseUserService();
        $sourceTypes = $service->getSourceTypes();

        $this->view->pick('course/search_user');
        $this->view->setVar('source_types', $sourceTypes);
        $this->view->setVar('course', $course);
    }

}
