<?php
/**
 * @package     downloadmail
 * @filesource  OnManageDownloadListener.php
 * @version     2.0.0
 * @since       18.10.2018 - 10:52
 * @author      Patrick Froch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2018
 * @license     CC-BY-SA-4.0
 */
namespace Esit\Downloadmail\Classes\Listener;

use Contao\Config;
use Contao\Controller;
use Contao\Database;
use Contao\Environment;
use Contao\File;
use Contao\FilesModel;
use Contao\Input;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\System;
use Esit\Downloadmail\Classes\Events\OnManageDownloadEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OnManageDownloadListener
 * @package Esit\Downloadmail\Classes\Listener
 */
class OnManageDownloadListener
{


    /**
     * @var Database
     */
    protected $db;


    /**
     * OnManageDownloadListener constructor.
     * @param Database $db
     */
    public function __construct(Database $db = null)
    {
        $this->db = $db ?: Database::getInstance();
    }


    /**
     * Lädt die Daten des Download aus der Datenbank.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDownloadFromDb(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $formLang       = $event->getFeFormLang();
        $template       = $event->getTamplate();
        $downloadKey    = $event->getDownloadKey();

        if ($event->getDownloadKey()) {
            $query  = "SELECT * FROM tl_dm_downloads WHERE code = '$downloadKey'";
            $result = $this->db->execute($query);

            if ($result->numRows) {
                $event->setDlFromDb($result->fetchAssoc());
            } else {
                $template->strError = $formLang['downloaderror'];
                $event->stopPropagation();
            }
        } else {
            $template->strError = $formLang['keyerror'];
            $event->stopPropagation();
        }
    }


    /**
     * Lädt die Daten zu der angeforderten Datei.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadFileData(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $formLang   = $event->getFeFormLang();
        $template   = $event->getTamplate();
        $dlData     = $event->getDlFromDb();

        if (is_array($dlData) && isset($dlData['singleSRC']) && $dlData['singleSRC']) {
            $fileData = FilesModel::findByPk($dlData['singleSRC']);
            $event->setFileData($fileData);
        } else {
            $template->strError = $formLang['downloaderror'];
            $event->stopPropagation();
        }
    }


    /**
     * Lädt die Daten des Formulars.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadFormData(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $dlData = $event->getDlFromDb();

        if (isset($dlData['formid']) && $dlData['formid']) {
            $query  = "SELECT * FROM tl_form WHERE id = " . $dlData['formid'];
            $result = $this->db->execute($query);

            if ($result->numRows) {
                $event->setFormData($result->fetchAssoc());
            }
        }
    }


    /**
     * Lädt die Zeit, die ein Download gültig ist.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function loadDownloadTime(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        global $objPage;
        $objRoot    = PageModel::findByPk($objPage->rootId);
        $formData   = $event->getFormData();

        if (isset($formData['downloadtime'])) {
            $event->setDownloadtime($formData['downloadtime']);
        } elseif ($objRoot && $objRoot->downloadtime > 0) {
            $event->setDownloadtime($objRoot->downloadtime);
        } elseif (Config::get('downloadtime')) {
            $event->setDownloadtime(Config::get('downloadtime'));
        } else {
            $event->setDownloadtime($GLOBALS['downloadmail']['downloadtime']);
        }
    }


    /**
     * Erzeugt den Link, um den Download erneut anzufordern.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function getRequestLink(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $dlData = $event->getDlFromDb();
        $event->setRequestLink($dlData['requestpage']);
    }


    /**
     * Prüft, ob die Downloadanfrage in der Downloadfrist liegt.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function checkDownloadTime(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $formLang       = $event->getFeFormLang();
        $template       = $event->getTamplate();
        $dlData         = $event->getDlFromDb();
        $downloadTime   = $event->getDownloadtime();
        $requestLink    = $event->getRequestLink();

        if (is_array($dlData) && isset($dlData['requesttime']) && $dlData['requesttime']) {
            $intDownloadPeriodEnd   = $dlData['requesttime'];
            $intDownloadPeriodEnd  += ($downloadTime * $GLOBALS['downloadmail']['timemodifikator']);

            if (time() > $intDownloadPeriodEnd) {
                $template->strError = sprintf($formLang['downloadtolate'], $requestLink);
                $event->stopPropagation();
            }
        } else {
                $template->strError = sprintf($formLang['notimeerr'], $requestLink);
                $event->stopPropagation();
        }
    }


    /**
     * Lädt die Zeit bis zum automatischen Start des Downloads.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function getRequestTime(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $moduleId   = $event->getModulId();
        $objModul   = ModuleModel::findByPk($moduleId);

        if ($objModul && $objModul->redirecttime > 0) {
            $event->setRequestTime($objModul->redirecttime);
        } elseif (Config::get('redirecttime') > 0) {
            $event->setRequestTime(Config::get('redirecttime'));
        } else {
            $event->setRequestTime($GLOBALS['downloadmail']['requestTime']);
        }
    }


    /**
     * Verarbeitet die Download-Anfrage.
     * @param OnManageDownloadEvent    $event
     * @param                          $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function handleDownload(OnManageDownloadEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $downloadKey    = $event->getDownloadKey();
        $formLang       = $event->getFeFormLang();
        $template       = $event->getTamplate();
        $dlData         = $event->getDlFromDb();
        $fileModel      = $event->getFileData();
        $requestTime    = $event->getRequestTime();

        if ($fileModel) {
            if (Input::get('download') === "true") {    // Infput::get() gibt einen String zurück!
                $dlData['downloadcount']++;
                $ip                     = System::anonymizeIp(Environment::get('remoteAddr'));
                $downloads              = deserialize($dlData['downloaddata'], true);
                $downloads[]            = ['time'=> time(), 'code' => $downloadKey, 'ip'=> $ip];
                $dlData['downloaddata'] = serialize($downloads);
                $dlDataId               = $dlData['id'];

                unset($dlData['id']);

                $this->db->prepare("UPDATE tl_dm_downloads %s WHERE id = $dlDataId")->set($dlData)->execute();

                Controller::sendFileToBrowser($fileModel->path);
            } else {
                $template->strLink    = Environment::get('requestUri') . '&download=true';
                $template->strLabel   = $formLang['downloadstart'];
                $template->strMessage = sprintf($formLang['downloadmessage'], $requestTime);
                $template->intTimer   = $requestTime;
            }
        } else {
            $template->strError = $formLang['fileerror'];
            $event->stopPropagation();
        }
    }
}