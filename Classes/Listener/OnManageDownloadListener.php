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

use Doctrine\DBAL\Connection;
use Esit\Downloadmail\Classes\Events\OnManageDownloadEvent;
use Esit\Downloadmail\Classes\Services\Wrapper\Config;
use Esit\Downloadmail\Classes\Services\Wrapper\Controller;
use Esit\Downloadmail\Classes\Services\Wrapper\Environment;
use Esit\Downloadmail\Classes\Services\Wrapper\FilesModel;
use Esit\Downloadmail\Classes\Services\Wrapper\Input;
use Esit\Downloadmail\Classes\Services\Wrapper\ModuleModel;
use Esit\Downloadmail\Classes\Services\Wrapper\PageModel;
use Esit\Downloadmail\Classes\Services\Wrapper\StringUtil;
use Esit\Downloadmail\Classes\Services\Wrapper\System;

class OnManageDownloadListener
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var Connection
     */
    private $db;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var FilesModel
     */
    private $filesModel;

    /**
     * @var Input
     */
    private $input;

    /**
     * @var ModuleModel
     */
    private $moduleModel;

    /**
     * @var PageModel
     */
    private $pageModel;

    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * @var System
     */
    private $system;

    /**
     * @param Config      $config
     * @param Controller  $controller
     * @param Connection  $database
     * @param Environment $environment
     * @param FilesModel  $filesModel
     * @param Input       $input
     * @param ModuleModel $moduleModel
     * @param PageModel   $pageModel
     * @param StringUtil  $stringUtil
     * @param System      $system
     */
    public function __construct(
        Config $config,
        Controller $controller,
        Connection $database,
        Environment $environment,
        FilesModel $filesModel,
        Input $input,
        ModuleModel $moduleModel,
        PageModel $pageModel,
        StringUtil $stringUtil,
        System $system
    ) {
        $this->config = $config;
        $this->controller = $controller;
        $this->db = $database;
        $this->environment = $environment;
        $this->filesModel = $filesModel;
        $this->input = $input;
        $this->moduleModel = $moduleModel;
        $this->pageModel = $pageModel;
        $this->stringUtil = $stringUtil;
        $this->system = $system;
    }


    /**
     * Lädt die Daten des Downloads aus der Datenbank.
     * @param OnManageDownloadEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadDownloadFromDb(OnManageDownloadEvent $event,): void {
        $formLang = $event->getFeFormLang();
        $template = $event->getTamplate();
        $downloadKey = $event->getDownloadKey();

        if ('' !== $event->getDownloadKey()) {
            $query  = $this->db->createQueryBuilder();
            $result = $query->select('*')->from('tl_dm_downloads')->where("code = '$downloadKey'")->execute();
            $data   = $result->fetchAssociative();

            if (false !== $data) {
                $event->setDlFromDb($data);
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
     */
    public function loadFileData(OnManageDownloadEvent $event): void {
        $dlData = $event->getDlFromDb();
        $singleSrc = $this->stringUtil->binToUuid($dlData['singleSRC']);

        if ('00000000-0000-0000-0000-000000000000' !== $singleSrc) {
            $fileData = $this->filesModel->findByPk($singleSrc);

            if (null !== $fileData) {
                $event->setFileData($fileData);
            }
        }
    }


    /**
     * Lädt die Daten des Formulars.
     * @param OnManageDownloadEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadFormData(OnManageDownloadEvent $event): void {
        $dlData = $event->getDlFromDb();

        if (!empty($dlData['formid'])) {
            $query  = $this->db->createQueryBuilder();
            $result = $query->select('*')->from('tl_form')->where('id = ' . $dlData['formid'])->execute();
            $data   = $result->fetchAssociative();

            if (false !== $data) {
                $event->setFormData($data);
            }
        }
    }

    /**
     * Lädt die Zeit, die ein Download gültig ist.
     * @param OnManageDownloadEvent    $event
     */
    public function loadDownloadTime(OnManageDownloadEvent $event): void {
        global $objPage;
        $objRoot = $this->pageModel->findByPk($objPage->rootId);
        $formData = $event->getFormData();

        if (!empty($formData['downloadtime'])) {
            $event->setDownloadtime($formData['downloadtime']);
        } elseif (null !== $objRoot && $objRoot->downloadtime > 0) {
            $event->setDownloadtime($objRoot->downloadtime);
        } elseif ($this->config->get('downloadtime')) {
            $event->setDownloadtime($this->config->get('downloadtime'));
        } else {
            $event->setDownloadtime($GLOBALS['downloadmail']['downloadtime']);
        }
    }

    /**
     * Erzeugt den Link, um den Download erneut anzufordern.
     * @param OnManageDownloadEvent    $event
     */
    public function getRequestLink(OnManageDownloadEvent $event): void {
        $dlData = $event->getDlFromDb();

        if (!empty($dlData['requestpage'])) {
            $event->setRequestLink($dlData['requestpage']);
        }
    }

    /**
     * Prüft, ob die Downloadanfrage in der Downloadfrist liegt.
     * @param OnManageDownloadEvent    $event
     */
    public function checkDownloadTime(OnManageDownloadEvent $event): void {
        $formLang = $event->getFeFormLang();
        $template = $event->getTamplate();
        $dlData = $event->getDlFromDb();
        $downloadTime = $event->getDownloadtime();
        $requestLink = $event->getRequestLink();

        if (!empty($dlData['requesttime'])) {
            $intDownloadPeriodEnd = $dlData['requesttime'];
            $intDownloadPeriodEnd += ($downloadTime * $GLOBALS['downloadmail']['timemodifikator']);

            if (\time() > $intDownloadPeriodEnd) {
                $template->strError = \sprintf($formLang['downloadtolate'], $requestLink);
                $event->stopPropagation();
            }
        } else {
            $template->strError = \sprintf($formLang['notimeerr'], $requestLink);
            $event->stopPropagation();
        }
    }

    /**
     * Lädt die Zeit bis zum automatischen Start des Downloads.
     * @param OnManageDownloadEvent    $event
     */
    public function getRequestTime(OnManageDownloadEvent $event): void {
        $moduleId = $event->getModulId();
        $objModul = $this->moduleModel->findByPk($moduleId);

        if (null !== $objModul) {
            $event->setModul($objModul);

            if ($objModul->redirecttime > 0) {
                $event->setRequestTime($objModul->redirecttime);
            }
        } elseif ($this->config->get('redirecttime') > 0) {
            $event->setRequestTime($this->config->get('redirecttime'));
        } else {
            $event->setRequestTime($GLOBALS['downloadmail']['requestTime']);
        }
    }


    /**
     * Verarbeitet die Download-Anfrage.
     * @param OnManageDownloadEvent $event
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function handleDownload(OnManageDownloadEvent $event): void {
        $downloadKey = $event->getDownloadKey();
        $formLang = $event->getFeFormLang();
        $template = $event->getTamplate();
        $dlData = $event->getDlFromDb();
        $fileModel = $event->getFileData();
        $requestTime = $event->getRequestTime();

        if (null !== $fileModel) {
            if ("true" === $this->input->get('download')) {    // Infput::get() gibt einen String zurück!
                $dlData['downloadcount'] = (!empty($dlData['downloadcount'])) ? $dlData['downloadcount'] + 1 : 1;
                $ip = $this->system->anonymizeIp($this->environment->get('remoteAddr'));

                if (!empty($dlData['downloaddata'])) {
                    $downloads = \unserialize($dlData['downloaddata'], [null]);
                }

                $downloads[] = ['time' => \time(), 'code' => $downloadKey, 'ip' => $ip];
                $dlData['downloaddata'] = \serialize($downloads);
                $dlDataId = $dlData['id'];

                unset($dlData['id']);
                $dbValues = [];

                foreach ($dlData as $k => $v) {
                    $dbValues[] = $v;
                }

                $query = $this->db->createQueryBuilder();
                $query->update('tl_dm_downloads');

                foreach ($dlData as $k => $v) {
                    $query->set($k, '?');
                }

                $query->setParameters($dbValues)
                      ->where("id = $dlDataId")
                      ->execute();

                $this->controller->sendFileToBrowser($fileModel->path);
            } else {
                $template->strLink = $this->environment->get('requestUri') . '&download=true';
                $template->strLabel = $formLang['downloadstart'];
                $template->strMessage = \sprintf($formLang['downloadmessage'], $requestTime);
                $template->intTimer = $requestTime;
            }
        } else {
            $template->strError = $formLang['fileerror'];
            $event->stopPropagation();
        }
    }
}
