<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Services\Setting as SettingService;

/**
 * @RoutePrefix("/admin/setting")
 */
class SettingController extends Controller
{

    /**
     * @Route("/site", name="admin.setting.site")
     */
    public function siteAction()
    {
        $section = 'site';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSettings($section, $data);

            $content = [
                'msg' => $this->locale->query('updated_ok'),
            ];

            return $this->jsonSuccess($content);

        } else {

            $site = $settingService->getSettings($section);
            $timezones = $settingService->getTimezones();
            $languages = $settingService->getLanguages();
            $currencies = $settingService->getCurrencies();

            $site['url'] = $site['url'] ?: kg_site_url();

            $this->view->setVar('timezones', $timezones);
            $this->view->setVar('languages', $languages);
            $this->view->setVar('currencies', $currencies);
            $this->view->setVar('site', $site);
        }
    }

    /**
     * @Route("/mail", name="admin.setting.mail")
     */
    public function mailAction()
    {
        $section = 'mail';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSettings($section, $data);

            $content = [
                'msg' => $this->locale->query('updated_ok'),
            ];

            return $this->jsonSuccess($content);

        } else {

            $mail = $settingService->getSettings($section);

            $this->view->setVar('mail', $mail);
        }
    }

    /**
     * @Route("/payment", name="admin.setting.payment")
     */
    public function paymentAction()
    {
        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $section = $this->request->getPost('section', 'string');

            $data = $this->request->getPost();

            $settingService->updateSettings($section, $data);

            $content = [
                'msg' => $this->locale->query('updated_ok'),
            ];

            return $this->jsonSuccess($content);

        } else {

            $paypal = $settingService->getSettings('payment.paypal');
            $stripe = $settingService->getSettings('payment.stripe');

            $this->view->setVar('paypal', $paypal);
            $this->view->setVar('stripe', $stripe);
        }
    }

    /**
     * @Route("/contact", name="admin.setting.contact")
     */
    public function contactAction()
    {
        $section = 'contact';

        $settingService = new SettingService();

        if ($this->request->isPost()) {

            $data = $this->request->getPost();

            $settingService->updateSettings($section, $data);

            $content = [
                'msg' => $this->locale->query('updated_ok'),
            ];

            return $this->jsonSuccess($content);

        } else {

            $contact = $settingService->getSettings($section);

            $this->view->setVar('contact', $contact);
        }
    }

}
