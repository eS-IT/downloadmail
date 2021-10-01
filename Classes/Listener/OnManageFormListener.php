<?php
/**
 * @package     downloadmail
 * @filesource  OnManageFormListener.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:52
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Listener;

use Contao\Config;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Database;
use Contao\Email;
use Contao\Environment;
use Contao\Input;
use Contao\PageModel;
use Contao\System;
use Esit\Downloadmail\Classes\Events\OnManageFormEvent;
use Esit\Downloadmail\Classes\Helper\StringHelper;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnManageFormListener
 * @package Esit\Downloadmail\Classes\Listener
 */
class OnManageFormListener
{


    /**
     * @var StringHelper
     */
    protected $stringHelper;


    /**
     * @var Database
     */
    protected $db;


    /**
     * OnManageFormListener constructor.
     * @param StringHelper  $sh
     * @param Database|null $db
     */
    public function __construct(StringHelper $sh, Database $db = null)
    {
        $this->stringHelper = $sh;
        $this->db           = $db ?: Database::getInstance();
    }


    /**
     * Lädt die Daten des Mailfelds aus tl_form_field.
     * @param OnManageFormEvent        $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadMailField(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $formData   = $event->getFormData();
        $dbData     = $event->getDbData();

        if (!empty($formData['id'])) {
            $query  = "SELECT * FROM tl_form_field WHERE pid = " . $formData['id'] . " AND downloadmailaddress = 1";
            $result = $this->db->execute($query);

            if ($result->numRows) {
                $fieldName = $result->name;

                if (Input::post($fieldName)) {
                    $dbData['email'] = Input::post($fieldName);
                    $event->setDbData($dbData);
                }
            }
        }
    }


    /**
     * Erstellt die Informationen zur Downloaddatei.
     * @param OnManageFormEvent        $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function genFileInfo(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $formData = $event->getFormData();

        if (!empty($formData['mySingleSRC'])) {
            $fileInfos = $this->stringHelper->genFileInfo($formData['mySingleSRC']);
            $event->setDownloadFileInfo($fileInfos);
        }
    }


    /**
     * Erzeugt den Downloadcode.
     * @param OnManageFormEvent        $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function genDownloadCode(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $dbData         = $event->getDbData();
        $dlFileInfo     = $event->getDownloadFileInfo();
        $hashString     =\microtime() . \uniqid(\microtime(), true);

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
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDefaultValues(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $fields     = $event->getSettingFields();

        if (is_array($fields) && count($fields)) {
            foreach ($fields as $field) {
                if (!empty($GLOBALS['downloadmail'][$field]))
                    $settings[$field] = $GLOBALS['downloadmail'][$field];
            }

            $event->setSettings($settings);
        }
    }


    /**
     * Lädt die Daten aus den Einstllungen.
     * @param OnManageFormEvent        $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDataFromSettings(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $fields     = $event->getSettingFields();

        if (\is_array($fields) && \count($fields)) {
            foreach ($fields as $field) {
                if (Config::get($field) && Config::get($field) !== 'a:1:{i:0;s:0:"";}') {
                    $settings[$field] = Config::get($field);
                }
            }

            $event->setSettings($settings);
        }
    }


    /**
     * Lädt die Daten aus der Rootpage.
     * @param OnManageFormEvent        $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDataFromRootpage(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $fields     = $event->getSettingFields();

        if (is_array($fields) && count($fields)) {
            global $objPage;
            $root = PageModel::findByPk($objPage->rootId);

            if ($root) {
                foreach ($fields as $field) {
                    if ($root->$field && $root->$field !== 'a:1:{i:0;s:0:"";}') {
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
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDataFromForm(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings   = $event->getSettings();
        $fields     = $event->getSettingFields();
        $formData   = $event->getFormData();

        if (is_array($fields) && count($fields)) {
            foreach ($fields as $field) {
                if (isset($formData[$field]) && $formData[$field] && $formData[$field]!== 'a:1:{i:0;s:0:"";}') {
                    $settings[$field] = $formData[$field];
                }
            }

            $event->setSettings($settings);
        }
    }


    /**
     * Setzt die Daten, die in tl_dm_doanloads gespeichert werden sollen.
     * @param OnManageFormEvent        $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDataForDb(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $formData               = $event->getFormData();
        $settingData            = $event->getSettings();
        $dbData                 = $event->getDbData();
        $dbData['tstamp']       = \time();
        $dbData['requesttime']  = \time();
        $dbData['jumpto']       = (!empty($settingData['jumptodownload'])) ? $settingData['jumptodownload'] : '';
        $dbData['requestpage']  = Environment::get('requestUri');

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
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function filterNotDbFields(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $dbData     = $event->getDbData();
        $newData    = [];

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
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function saveDataInDb(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $dbData = $event->getDbData();

        if (is_array($dbData) && count($dbData)) {
            $this->db->prepare("INSERT INTO tl_dm_downloads %s")->set($dbData)->execute();
        }
    }


    /**
     * Versendet die Mails.
     * @param OnManageFormEvent        $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function sendMails(OnManageFormEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $settings       = $event->getSettings();
        $postData       = $event->getPostData();
        $dbData         = $event->getDbData();
        $email          = new Email();
        $email->subject = $settings['mailsubject'];
        $email->from    = $settings['mailfrom'];
        $bcc            = \unserialize($settings['mailbcc'], [null]);
        $text           = $settings['mailtext'];

        if (isset($dbData['code']) && $dbData['code']) {
            $link = $this->stringHelper->genLink($settings['jumptodownload'], $dbData['code']);

            if (Config::get('usetiny')) {
                $link = "<a href='$link'>$link</a>";
            }

            $text = \str_replace('{{download::link}}', $link, $text);
        }

        if (\is_array($postData) && \count($postData)) {
            foreach ($postData as $strKey => $strValue) {
                $text = \str_replace("{{download::$strKey}}", $strValue, $text);
            }
        }

        if (Config::get('usetiny')) {
            $email->html = $text;
        } else {
            $email->text = $text;
        }

        if (is_array($bcc) && count($bcc)) {
            \call_user_func_array(array($email, 'sendBcc'), $bcc);
        }

        if (!empty($dbData['email'])) {
            $email->sendTo($dbData['email']);
        } else {
            $logger = System::getContainer()->get('monolog.logger.contao');
            if (null !== $logger) {
                $context = ['contao' => new ContaoContext(__METHOD__, 'Mailerror')];
                $logger->log(LogLevel::ERROR, 'No mail address found!', $context);
            }
        }
    }
}
