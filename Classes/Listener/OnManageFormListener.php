<?php

/**
 * @package     downloadmail
 * @since       18.10.2018 - 10:52
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

declare(strict_types=1);

namespace Esit\Downloadmail\Classes\Listener;

use Contao\Database;
use Doctrine\DBAL\Connection;
use Esit\Downloadmail\Classes\Events\OnManageFormEvent;
use Esit\Downloadmail\Classes\Services\Factories\EmailFactory;
use Esit\Downloadmail\Classes\Services\Helper\FileHelper;
use Esit\Downloadmail\Classes\Services\Helper\StringHelper;
use Esit\Downloadmail\Classes\Services\Wrapper\Config;
use Esit\Downloadmail\Classes\Services\Wrapper\Environment;
use Esit\Downloadmail\Classes\Services\Wrapper\PageModel;
use Esit\Downloadmail\Classes\Services\Wrapper\System;

class OnManageFormListener
{


    /**
     * @var Config
     */
    private $config;


    /**
     * @var Connection
     */
    private $db;


    /**
     * @var Environment
     */
    private $environment;


    /**
     * @var PageModel
     */
    private $pageModel;


    /**
     * @var StringHelper
     */
    private $stringHelper;


    /**
     * @var EmailFactory
     */
    private $emailFactory;


    /**
     * @var FileHelper
     */
    private $fileHelper;


    /**
     * @param Config       $config
     * @param Connection   $db
     * @param Environment  $environment
     * @param PageModel    $pageModel
     * @param StringHelper $stringHelper
     * @param EmailFactory $emailFactory
     * @param FileHelper   $fileHelper
     */
    public function __construct(
        Config $config,
        Connection $db,
        Environment $environment,
        PageModel $pageModel,
        StringHelper $stringHelper,
        EmailFactory $emailFactory,
        FileHelper $fileHelper
    ) {
        $this->config       = $config;
        $this->db           = $db;
        $this->environment  = $environment;
        $this->pageModel    = $pageModel;
        $this->stringHelper = $stringHelper;
        $this->emailFactory = $emailFactory;
        $this->fileHelper   = $fileHelper;
    }


    /**
     * Lädt die Daten des Mailfelds aus tl_form_field.
     * @param OnManageFormEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadMailField(OnManageFormEvent $event): void
    {
        $formData   = $event->getFormData();
        $postArray  = $event->getPostData();
        $dbData     = $event->getDbData();

        if (!empty($formData['id'])) {
            $query  = $this->db->createQueryBuilder();
            $result = $query->select('*')
                            ->from('tl_form_field')
                            ->where('pid = ' . $formData['id'])
                            ->andWhere('downloadmailaddress = 1')
                            ->executeQuery();

            $data = $result->fetchAssociative();

            if (!empty($data)) {
                $fieldName = $data['name'];

                if (!empty($postArray[$fieldName])) {
                    $dbData['email'] = $postArray[$fieldName];
                    $event->setDbData($dbData);
                }
            }
        }
    }


    /**
     * Erstellt die Informationen zur Downloaddatei.
     * @param OnManageFormEvent        $event
     */
    public function genFileInfo(OnManageFormEvent $event): void
    {
        $formData = $event->getFormData();

        if (!empty($formData['mySingleSRC'])) {
            $fileInfos = $this->fileHelper->genFileInfo($formData['mySingleSRC']);
            $event->setDownloadFileInfo($fileInfos);
        }
    }


    /**
     * Erzeugt den Downloadcode.
     * @param OnManageFormEvent        $event
     */
    public function genDownloadCode(OnManageFormEvent $event): void
    {
        $dbData     = $event->getDbData();
        $dlFileInfo = $event->getDownloadFileInfo();
        $hashString = \microtime() . \uniqid(\microtime(), true);

        if (!empty($dbData['email'])) {
            $hashString .= $dbData['email'];
        }

        if (!empty($dlFileInfo['filehash'])) {
            $hashString .= $dlFileInfo['filehash'];
        }

        $dbData['code'] = \sha1($hashString);
        $event->setDbData($dbData);
    }


    /**
     * Setzt die Vorgabewerte aus der config.php.
     * @param OnManageFormEvent        $event
     */
    public function loadDefaultValues(OnManageFormEvent $event): void
    {
        $settings = $event->getSettings();
        $fields = $event->getSettingFields();

        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (!empty($GLOBALS['downloadmail'][$field])) {
                    $settings[$field] = $GLOBALS['downloadmail'][$field];
                }
            }

            $event->setSettings($settings);
        }
    }


    /**
     * Lädt die Daten aus den Einstllungen.
     * @param OnManageFormEvent        $event
     */
    public function loadDataFromSettings(OnManageFormEvent $event): void
    {
        $settings = $event->getSettings();
        $fields = $event->getSettingFields();

        if (!empty($fields)) {
            foreach ($fields as $field) {
                $value = $this->config->get($field);
                if (null !== $value && 'a:1:{i:0;s:0:"";}' !== $value) {
                    $settings[$field] = $value;
                }
            }

            $event->setSettings($settings);
        }
    }


    /**
     * Lädt die Daten aus der Rootpage.
     * @param OnManageFormEvent        $event
     */
    public function loadDataFromRootpage(OnManageFormEvent $event): void
    {
        $settings   = $event->getSettings();
        $fields     = $event->getSettingFields();

        if (!empty($fields)) {
            global $objPage;
            $root = $this->pageModel->findByPk($objPage->rootId);

            if (null !== $root) {
                foreach ($fields as $field) {
                    if (!empty($root->$field) && $root->$field !== 'a:1:{i:0;s:0:"";}') {
                        $settings[$field] = $root->$field;
                    }
                }

                $event->setSettings($settings);
            }
        }
    }


    /**
     * Fügt die Daten zusammen.
     * @param OnManageFormEvent        $event
     */
    public function loadDataFromForm(OnManageFormEvent $event): void
    {
        $settings   = $event->getSettings();
        $fields     = $event->getSettingFields();
        $formData   = $event->getFormData();

        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (!empty($formData[$field]) && 'a:1:{i:0;s:0:"";}' !== $formData[$field]) {
                    $settings[$field] = $formData[$field];
                }
            }

            $event->setSettings($settings);
        }
    }


    /**
     * Setzt die Daten, die in tl_dm_doanloads gespeichert werden sollen.
     * @param OnManageFormEvent        $event
     */
    public function setDataForDb(OnManageFormEvent $event): void
    {
        $formData               = $event->getFormData();
        $settingData            = $event->getSettings();
        $dbData                 = $event->getDbData();
        $dbData['tstamp']       = \time();
        $dbData['requesttime']  = \time();
        $dbData['jumpto']       = (!empty($settingData['jumptodownload'])) ? $settingData['jumptodownload'] : '';
        $dbData['requestpage']  = $this->environment->get('requestUri');

        if (!empty($formData['id'])) {
            $dbData['formid'] = $formData['id'];
        }

        if (!empty($formData['mySingleSRC'])) {
            $dbData['singleSRC'] = $formData['mySingleSRC'];
        }

        $event->setDbData($dbData);
    }


    /**
     * Entfernt alle Felder aus $dbData, die nicht in tl_dm_doanloads vorkommen.
     * @param OnManageFormEvent        $event
     */
    public function filterNotDbFields(OnManageFormEvent $event): void
    {
        $dbData = $event->getDbData();
        $newData = [];

        foreach ($dbData as $f => $v) {
            if (Database::getInstance()->fieldExists($f, 'tl_dm_downloads')) {
                $newData[$f] = $v;
            }
        }

        $event->setDbData($newData);
    }


    /**
     * Speichert die Daten in der Datenbank.
     * @param OnManageFormEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function saveDataInDb(OnManageFormEvent $event): void
    {
        $dbData     = $event->getDbData();
        $dbValues   = [];
        $dbFields   = [];

        if (!empty($dbData)) {
            $query = $this->db->createQueryBuilder();

            foreach ($dbData as $k => $v) {
                $dbFields[$k]   = '?';
                $dbValues[]     = $v;
            }

            if (!empty($dbFields) && !empty($dbValues)) {
                $query->insert('tl_dm_downloads')
                      ->values($dbFields)
                      ->setParameters($dbValues)
                      ->executeStatement();
            }
        }
    }


    /**
     * Versendet die Mails.
     * @param OnManageFormEvent $event
     * @return void
     * @throws \Exception
     */
    public function sendMails(OnManageFormEvent $event): void
    {
        $settings       = $event->getSettings();
        $postData       = $event->getPostData();
        $dbData         = $event->getDbData();
        $email          = $this->emailFactory->create();
        $email->subject = $settings['mailsubject'];
        $email->from    = $settings['mailfrom'];
        $bcc            = [];
        $text           = $settings['mailtext'];

        if (!empty($settings['mailbcc'])) {
            $bcc = \unserialize($settings['mailbcc'], [null]);
        }

        if (!empty($dbData['code'])) {
            $link = $this->stringHelper->genLink((int)$settings['jumptodownload'], $dbData['code']);

            if ($this->config->get('usetiny')) {
                $link = "<a href='$link'>$link</a>";
            }

            $text = \str_replace('{{download::link}}', $link, $text);
        }

        if (!empty($postData)) {
            foreach ($postData as $strKey => $strValue) {
                $text = \str_replace("{{download::$strKey}}", $strValue, $text);
            }
        }

        if ($this->config->get('usetiny')) {
            $email->html = $text;
        } else {
            $email->text = $text;
        }

        if (!empty($bcc)) {
            \call_user_func_array([$email, 'sendBcc'], $bcc);
        }

        if (!empty($dbData['email'])) {
            $email->sendTo($dbData['email']);
        }
    }
}
