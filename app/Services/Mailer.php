<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/lite-license
 * @link https://www.koogua.net
 */

namespace App\Services;

use App\Plugins\Locale as LocalePlugin;
use Phalcon\Logger\Logger as PhLogger;
use PHPMailer\PHPMailer\PHPMailer;

abstract class Mailer extends Service
{

    /**
     * @var PHPMailer
     */
    protected PHPMailer $mailer;

    /**
     * @var PhLogger
     */
    protected PhLogger $logger;

    public function __construct()
    {
        $this->mailer = $this->getMailer();

        $this->logger = $this->getLogger('mail');
    }

    public function send(string $email, string $subject, string $content, string $file = null): bool
    {
        try {

            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
            $this->mailer->Encoding = PHPMailer::ENCODING_BASE64;

            $this->mailer->Subject = $subject;
            $this->mailer->Body = $content;

            if ($file) {
                $this->mailer->addAttachment($file);
            }

            $result = $this->mailer->send();

        } catch (\Exception $e) {

            $this->logger->error('Send Email Exception: ' . kg_json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function getSubject(string $templateId): string
    {
        $locale = new LocalePlugin();

        $translator = $locale->getTranslator('email');

        $prefix = kg_setting('site', 'title');

        $subject = $translator->query("{$templateId}_subject");

        return sprintf('【%s】%s', $prefix, $subject);
    }

    protected function getContent(string $templateId, array $params = []): string
    {
        $language = kg_setting('site', 'language', 'en');

        $params['site.title'] = kg_setting('site', 'title');
        $params['site.url'] = kg_setting('site', 'url');
        $params['contact.email'] = kg_setting('contact', 'email');

        $path = sprintf('%s/email/%s.html', $language, $templateId);

        $templateFile = locale_path($path);

        if (!file_exists($templateFile)) {
            $path = str_replace($language, 'en', $path);
            $templateFile = locale_path($path);
        }

        $content = file_get_contents($templateFile);

        return kg_ph_replace($content, $params);
    }

    protected function getMailer(): PHPMailer
    {
        $opt = $this->getSettings('mail');

        $mailer = new PHPMailer(true);

        $mailer->isSMTP();

        $mailer->Host = $opt['smtp_host'];
        $mailer->Port = $opt['smtp_port'];
        $mailer->SMTPSecure = $opt['smtp_encryption'];

        if ($opt['smtp_auth_enabled']) {
            $mailer->SMTPAuth = true;
            $mailer->Username = $opt['smtp_username'];
            $mailer->Password = $opt['smtp_password'];
        }

        $mailer->setFrom($opt['smtp_from_email'], $opt['smtp_from_name']);

        return $mailer;
    }

}
