<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services\Logic\Order;

use App\Models\Course as CourseModel;
use App\Models\KgSale as KgSaleModel;
use App\Models\Order as OrderModel;
use App\Models\Package as PackageModel;
use App\Models\User as UserModel;
use App\Models\Vip as VipModel;
use App\Repos\Package as PackageRepo;
use App\Services\Logic\Service as LogicService;
use App\Traits\Client as ClientTrait;
use App\Validators\Order as OrderValidator;
use App\Validators\UserLimit as UserLimitValidator;

class OrderCreate extends LogicService
{

    /**
     * @var float 订单金额
     */
    protected float $amount = 0.00;

    /**
     * @var string 货币类型
     */
    protected string $currency = 'USD';

    use ClientTrait;

    public function handle(): OrderModel
    {
        $itemId = $this->request->getPost('item_id', ['trim', 'int']);
        $itemType = $this->request->getPost('item_type', ['trim', 'int']);

        $user = $this->getLoginUser();

        $this->checkUserDailyOrderLimit($user);

        $this->currency = kg_setting('site', 'currency', 'USD');

        $orderValidator = new OrderValidator();

        $orderValidator->checkItemType($itemType);

        $order = null;

        if ($itemType == KgSaleModel::ITEM_COURSE) {

            $course = $orderValidator->checkCourse($itemId);

            $orderValidator->checkIfBoughtCourse($user->id, $course->id);

            $calculator = new CoursePayCalculator($course);

            $calculator->handleNormalPay($user);

            $this->amount = $calculator->getPayAmount();

            $orderValidator->checkAmount($this->amount);

            $order = $this->createCourseOrder($course, $user);

        } elseif ($itemType == KgSaleModel::ITEM_PACKAGE) {

            $package = $orderValidator->checkPackage($itemId);

            $orderValidator->checkIfBoughtPackage($user->id, $package->id);

            $calculator = new PackagePayCalculator($package);

            $calculator->handleNormalPay($user);

            $this->amount = $calculator->getPayAmount();

            $orderValidator->checkAmount($this->amount);

            $order = $this->createPackageOrder($package, $user);

        } elseif ($itemType == KgSaleModel::ITEM_VIP) {

            $vip = $orderValidator->checkVip($itemId);

            $calculator = new VipPayCalculator($vip);

            $calculator->handleNormalPay($user);

            $this->amount = $calculator->getPayAmount();

            $orderValidator->checkAmount($this->amount);

            $order = $this->createVipOrder($vip, $user);
        }

        $this->incrUserDailyOrderCount($user);

        return $order;
    }

    protected function createCourseOrder(CourseModel $course, UserModel $user): OrderModel
    {
        $itemInfo = [];

        $itemInfo['course'] = $this->handleCourseInfo($course);

        $subject = $this->locale->query('order_course_x', ['x' => $course->title]);

        $order = new OrderModel();

        $order->owner_id = $user->id;
        $order->item_id = $course->id;
        $order->item_type = KgSaleModel::ITEM_COURSE;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = $subject;
        $order->amount = $this->amount;
        $order->currency = $this->currency;

        $order->create();

        return $order;
    }

    protected function createPackageOrder(PackageModel $package, UserModel $user): OrderModel
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        $itemInfo = [];

        $itemInfo['package'] = $this->handlePackageInfo($package);

        foreach ($courses as $course) {
            $itemInfo['courses'][] = $this->handleCourseInfo($course);
        }

        $subject = $this->locale->query('order_package_x', ['x' => $package->title]);

        $order = new OrderModel();

        $order->owner_id = $user->id;
        $order->item_id = $package->id;
        $order->item_type = KgSaleModel::ITEM_PACKAGE;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = $subject;
        $order->amount = $this->amount;
        $order->currency = $this->currency;

        $order->create();

        return $order;
    }

    protected function createVipOrder(VipModel $vip, UserModel $user): OrderModel
    {
        $itemInfo = [];

        $itemInfo['vip'] = $this->handleVipInfo($vip, $user);

        $subject = $this->locale->query('order_vip_x', ['x' => $vip->expiry]);

        $order = new OrderModel();

        $order->owner_id = $user->id;
        $order->item_id = $vip->id;
        $order->item_type = KgSaleModel::ITEM_VIP;
        $order->item_info = $itemInfo;
        $order->client_type = $this->getClientType();
        $order->client_ip = $this->getClientIp();
        $order->subject = $subject;
        $order->amount = $this->amount;
        $order->currency = $this->currency;

        $order->create();

        return $order;
    }

    protected function handleCourseInfo(CourseModel $course): array
    {
        $studyExpiryTime = strtotime("+{$course->study_expiry} months");
        $refundExpiryTime = strtotime("+{$course->refund_expiry} days");

        $course->cover = CourseModel::getCoverPath($course->cover);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'regular_price' => $course->regular_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'study_expiry_time' => $studyExpiryTime,
            'refund_expiry_time' => $refundExpiryTime,
        ];
    }

    protected function handlePackageInfo(PackageModel $package): array
    {
        $package->cover = PackageModel::getCoverPath($package->cover);

        return [
            'id' => $package->id,
            'title' => $package->title,
            'cover' => $package->cover,
            'regular_price' => $package->regular_price,
            'vip_price' => $package->vip_price,
        ];
    }

    protected function handleVipInfo(VipModel $vip, UserModel $user): array
    {
        $baseTime = $user->vip_expiry_time > time() ? $user->vip_expiry_time : time();
        $expiryTime = strtotime("+{$vip->expiry} months", $baseTime);

        $vip->cover = VipModel::getCoverPath($vip->cover);

        return [
            'id' => $vip->id,
            'cover' => $vip->cover,
            'price' => $vip->price,
            'expiry' => $vip->expiry,
            'expiry_time' => $expiryTime,
        ];
    }

    protected function incrUserDailyOrderCount(UserModel $user): void
    {
        $this->eventsManager->fire('UserDailyCounter:incrOrderCount', $this, $user);
    }

    protected function checkUserDailyOrderLimit(UserModel $user): void
    {
        $validator = new UserLimitValidator();

        $validator->checkDailyOrderLimit($user);
    }

}
