<?php

declare(strict_types=1);
/**
 * @package     downloadmail
 * @filesource  OnManageFormListener.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:52
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @see         http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */

namespace Esit\Downloadmail\Classes\Listener;

use Contao\CoreBundle\Monolog\ContaoContext;
use Esit\Downloadmail\Classes\Events\OnManageFormEvent;
use Esit\Downloadmail\Classes\Services\Helper\StringHelper;
use Esit\Downloadmail\Classes\Services\Wrapper\Config;
use Esit\Downloadmail\Classes\Services\Wrapper\Database;
use Esit\Downloadmail\Classes\Services\Wrapper\Email;
use Esit\Downloadmail\Classes\Services\Wrapper\Environment;
use Esit\Downloadmail\Classes\Services\Wrapper\PageModel;
use Esit\Downloadmail\Classes\Services\Wrapper\System;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnManageFormListener
 * @package Esit\Downloadmail\Classes\Listener
 */
class OnManageFormListener
{


    /**
     * @var Config
     */
    private $config;


    /**
     * @var Database
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
     * @var System
     */
    private $system;


    /**
     * @var StringHelper
     */
    private $stringHelper;


    /**
     * @param Config       $config
     * @param Database     $db
     * @param Environment  $environment
     * @param PageModel    $pageModel
     * @param System       $system
     * @param StringHelper $stringHelper
     */
    public function __construct(
        Config $config,
        Database $db,
        Environment $environment,
        PageModel $pageModel,
        System $system,
        StringHelper $stringHelper
    ) {
        $this->config = $config;
        $this->db = $db;
        $this->environment = $environment;
        $this->pageModel = $pageModel;
        $this->system = $system;
        $this->stringHelper = $stringHelper;
    }


    /**
     * Lädt die Daten des Mailfelds aus tl_form_field.
     * @param OnManageFormEvent        $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadMailField(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $formData = $event->getFormData();
        $postArray = $event->getPostData();
        $dbData = $event->getDbData();

        if (!empty($formData['id'])) {
            $query = "SELECT * FROM tl_form_field WHERE pid = " . $formData['id'] . " AND downloadmailaddress = 1";
            $result = $this->db->execute($query);

            if ($result->numRows > 0) {
                $fieldName = $result->name;

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
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function genFileInfo(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $formData = $event->getFormData();

        if (!empty($formData['mySingleSRC'])) {
            $fileInfos = $this->stringHelper->genFileInfo($formData['mySingleSRC']);
            $event->setDownloadFileInfo($fileInfos);
        }
    }


    /**
     * Erzeugt den Downloadcode.
     * @param OnManageFormEvent        $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function genDownloadCode(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $dbData = $event->getDbData();
        $dlFileInfo = $event->getDownloadFileInfo();
        $hashString = microtime() . uniqid(microtime(), true);

        if (!empty($dbData['email'])) {
            $hashString .= $dbData['email'];
        }

        if (!empty($dlFileInfo['filehash'])) {
            $hashString .= $dlFileInfo['filehash'];
        }

        $dbData['code'] = sha1($hashString);
        $event->setDbData($dbData);
    }


    /**
     * Setzt die Vorgabewerte aus der config.php.
     * @param OnManageFormEvent        $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDefaultValues(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
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
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDataFromSettings(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
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
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDataFromRootpage(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $settings = $event->getSettings();
        $fields = $event->getSettingFields();

        if (!empty($fields)) {
            global $objPage;
            $root = $this->pageModel->findByPk($objPage->rootId);

            if (null !== $root) {
                foreach ($fields as $field) {
                    if (null !== $root->$field && $root->$field !== 'a:1:{i:0;s:0:"";}') {
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
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDataFromForm(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $settings = $event->getSettings();
        $fields = $event->getSettingFields();
        $formData = $event->getFormData();

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
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDataForDb(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $formData = $event->getFormData();
        $settingData = $event->getSettings();
        $dbData = $event->getDbData();
        $dbData['tstamp'] = time();
        $dbData['requesttime'] = time();
        $dbData['jumpto'] = (!empty($settingData['jumptodownload'])) ? $settingData['jumptodownload'] : '';
        $dbData['requestpage'] = $this->environment->get('requestUri');

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
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function filterNotDbFields(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $dbData = $event->getDbData();
        $newData = [];

        foreach ($dbData as $f => $v) {
            if ($this->db->fieldExists($f, 'tl_dm_downloads')) {
                $newData[$f] = $v;
            }
        }

        $event->setDbData($newData);
    }


    /**
     * Speichert die Daten in der Datenbank.
     * @param OnManageFormEvent        $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function saveDataInDb(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $dbData = $event->getDbData();

        if (!empty($dbData)) {
            $this->db->prepare("INSERT INTO tl_dm_downloads %s")->set($dbData)->execute();
        }
    }


    /**
     * Versendet die Mails.
     * @param OnManageFormEvent        $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function sendMails(
        OnManageFormEvent $event,
        string $eventName,
        EventDispatcherInterface $dispatcher
    ): void {
        $settings = $event->getSettings();
        $postData = $event->getPostData();
        $dbData = $event->getDbData();
        $email = new Email();
        $email->subject = $settings['mailsubject'];
        $email->from = $settings['mailfrom'];
        $bcc = unserialize($settings['mailbcc'], [null]);
        $text = $settings['mailtext'];

        if (!empty($dbData['code'])) {
            $link = $this->stringHelper->genLink($settings['jumptodownload'], $dbData['code']);

            if ($this->config->get('usetiny')) {
                $link = "<a href='$link'>$link</a>";
            }

            $text = str_replace('{{download::link}}', $link, $text);
        }

        if (!empty($postData)) {
            foreach ($postData as $strKey => $strValue) {
                $text = str_replace("{{download::$strKey}}", $strValue, $text);
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
        } else {
            $logger = $this->system->getContainer()->get('monolog.logger.contao');
            if (null !== $logger) {
                $context = ['contao' => new ContaoContext(__METHOD__, 'Mailerror')];
                $logger->log(LogLevel::ERROR, 'No mail address found!', $context);
            }
        }
    }
}
