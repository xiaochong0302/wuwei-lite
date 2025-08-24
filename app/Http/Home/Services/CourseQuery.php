<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Home\Services;

use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;
use App\Validators\CourseQuery as CourseQueryValidator;

class CourseQuery extends Service
{

    /**
     * @var string
     */
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->url->get(['for' => 'home.course.list']);
    }

    public function handleTopCategories()
    {
        $params = $this->getParams();

        if (isset($params['tc'])) {
            unset($params['tc']);
        }

        if (isset($params['sc'])) {
            unset($params['sc']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => $this->locale->query('filter_all'),
            'url' => $this->baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $categoryService = new CategoryService();

        $topCategories = $categoryService->getChildCategories(0);

        foreach ($topCategories as $category) {
            $params['tc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleSubCategories()
    {
        $params = $this->getParams();

        if (empty($params['tc'])) {
            return [];
        }

        $categoryService = new CategoryService();

        $subCategories = $categoryService->getChildCategories($params['tc']);

        if (empty($subCategories)) {
            return [];
        }

        if (isset($params['sc'])) {
            unset($params['sc']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => $this->locale->query('filter_all'),
            'url' => $this->baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        foreach ($subCategories as $category) {
            $params['sc'] = $category['id'];
            $result[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleLevels()
    {
        $params = $this->getParams();

        if (isset($params['level'])) {
            unset($params['level']);
        }

        $defaultItem = [
            'id' => 'all',
            'name' => $this->locale->query('filter_all'),
            'url' => $this->baseUrl . $this->buildParams($params),
        ];

        $result = [];

        $result[] = $defaultItem;

        $levels = CourseModel::levelTypes();

        foreach ($levels as $key => $value) {
            $params['level'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function handleSorts()
    {
        $params = $this->getParams();

        $result = [];

        $sorts = CourseModel::sortTypes();

        foreach ($sorts as $key => $value) {
            $params['sort'] = $key;
            $result[] = [
                'id' => $key,
                'name' => $value,
                'url' => $this->baseUrl . $this->buildParams($params),
            ];
        }

        return $result;
    }

    public function getParams()
    {
        $query = $this->request->getQuery();

        $params = [];

        $validator = new CourseQueryValidator();

        if (isset($query['tc']) && $query['tc'] != 'all') {
            $category = $validator->checkCategory($query['tc']);
            $params['tc'] = $category->id;
        }

        if (isset($query['sc']) && $query['sc'] != 'all') {
            $category = $validator->checkCategory($query['sc']);
            $params['sc'] = $category->id;
        }

        if (isset($query['level']) && $query['level'] != 'all') {
            $params['level'] = $validator->checkLevel($query['level']);
        }

        if (isset($query['sort'])) {
            $params['sort'] = $validator->checkSort($query['sort']);
        }

        return $params;
    }

    protected function buildParams($params)
    {
        return $params ? '?' . http_build_query($params) : '';
    }

}
